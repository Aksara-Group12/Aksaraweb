<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Subscribe;
use App\Models\User;
use App\Models\Admin;
use App\Mail\VerifyEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
	public function showLoginForm()
	{
		return view('auth.login');
	}

	public function login(Request $request)
	{
		$credentials = $request->validate([
			'email' => ['required', 'email'],
			'password' => ['required'],
		]);

		$remember = $request->boolean('remember');
		$user = User::where('email', $credentials['email'])->first();

		if ($user) {
			// Check if email verified (superadmin skip, owner auto-verify for testing lokal)
			if ($user->role !== 'superadmin' && !$user->hasVerifiedEmail()) {
				// Disabled untuk testing lokal - auto-verify jika belum diverifikasi
				$user->markEmailAsVerified();
				// return back()
				// 	->with('login_warning', 'Email Anda belum terverifikasi. Silakan cek email untuk link verifikasi.')
				// 	->withErrors([
				// 		'email' => 'Email belum terverifikasi. Cek email Anda untuk link verifikasi.',
				// 	])
				// 	->onlyInput('email');
			}

			if (Auth::attempt($credentials, $remember)) {
				$request->session()->regenerate();

				if ($user->role === 'superadmin') {
					$request->session()->forget('url.intended');
					return redirect()->route('dashboard-superadmin');
				}

				return redirect()->intended(route('dashboard'));
			}

			return back()
				->withErrors([
					'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
				])
				->onlyInput('email');
		}

		// Fallback to admin login if user email not found
		$admin = Admin::where('email', $credentials['email'])->first();
		if (!$admin) {
			return back()
				->with('login_warning', 'Akun belum terdaftar. Silakan daftar terlebih dahulu.')
				->withErrors([
					'email' => 'Akun belum terdaftar. Silakan daftar terlebih dahulu.',
				])
				->onlyInput('email');
		}

		if ($admin->status !== 'active') {
			return back()
				->withErrors([
					'email' => 'Admin nonaktif. Hubungi owner untuk mengaktifkan akun.',
				])
				->onlyInput('email');
		}

		if (!$admin->hasVerifiedEmail()) {
			return back()
				->withErrors([
					'email' => 'Email admin belum diverifikasi. Silakan cek email verifikasi.',
				])
				->onlyInput('email');
		}

		if (!Hash::check($credentials['password'], $admin->password)) {
			return back()
				->withErrors([
					'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
				])
				->onlyInput('email');
		}

		$owner = User::find($admin->user_id);
		if (!$owner) {
			return back()
				->withErrors([
					'email' => 'Owner tidak ditemukan untuk admin ini.',
				])
				->onlyInput('email');
		}

		Auth::login($owner, $remember);
		$request->session()->regenerate();

		$request->session()->put([
			'admin_id' => $admin->id,
			'admin_email' => $admin->email,
			'admin_name' => $admin->name,
		]);

		return redirect()->intended(route('dashboard'));
	}

	public function showRegisterForm()
	{
		return view('auth.register');
	}

	public function register(Request $request)
	{
		$data = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
			'password' => ['required', 'string', 'min:8', 'confirmed'],
		]);

		$user = User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => Hash::make($data['password']),
		]);

		// Superadmin auto-verified, user biasa kirim email verifikasi
		if ($user->role === 'superadmin') {
			$user->markEmailAsVerified();
		} else {
			// Send verification email (dengan error handling)
			try {
				Mail::to($user->email)->send(new VerifyEmail($user));
			} catch (\Exception $e) {
				\Log::error('Email verification gagal: ' . $e->getMessage());
				// Tetap lanjut - registrasi tetap berhasil
			}
		}

		if ($user->role !== 'superadmin') {
			$trialPackage = Paket::firstOrCreate([
				'nama_paket' => 'Trial',
			], [
				'deskripsi' => 'Trial 7 hari',
				'harga' => 0,
				'durasi_bulan' => 0,
				'is_active' => false,
			]);

			$startDate = Carbon::now()->startOfDay();
			$durationDays = 7;
			$endDate = $startDate->copy()->addDays($durationDays);

			Subscribe::create([
				'user_id' => $user->id,
				'paket_id' => $trialPackage->id,
				'paket_name' => $trialPackage->nama_paket,
				'price' => 0,
				'duration_days' => $durationDays,
				'start_date' => $startDate,
				'end_date' => $endDate,
				'status' => 'active',
				'approval_status' => 'approved',
				'approved_at' => now(),
				'approved_by' => null,
				'admin_notes' => 'Auto trial 7 hari',
				'is_trial' => true,
			]);
		}

		return redirect()->route('login')
			->with('success', 'Pendaftaran berhasil! Silakan cek email Anda untuk verifikasi akun sebelum login.');
	}

	public function logout(Request $request)
	{
		Auth::logout();
		$request->session()->forget(['admin_id', 'admin_email', 'admin_name']);

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return redirect('/');
	}

	public function showVerifyPrompt()
	{
		return view('auth.verify-email');
	}

	public function verifyEmail(Request $request, User $user)
	{
		// Validate token
		$token = $request->route('token') ?? $request->input('token');
		$email = $request->input('email', $user->email);

		if ($user->email !== $email) {
			return redirect()->route('login')->with('error', 'Link verifikasi tidak valid.');
		}

		if (!hash_equals((string) $token, sha1($user->getEmailForVerification()))) {
			return redirect()->route('login')->with('error', 'Link verifikasi tidak valid atau sudah kadaluarsa.');
		}

		if ($user->hasVerifiedEmail()) {
			return redirect()->route('login')->with('success', 'Email sudah terverifikasi sebelumnya. Silakan login.');
		}

		$user->markEmailAsVerified();

		return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Sekarang Anda bisa login.');
	}

	public function resendVerification(Request $request)
	{
		$request->validate([
			'email' => ['required', 'email', 'exists:users,email'],
		]);

		$user = User::where('email', $request->email)->first();

		if ($user->hasVerifiedEmail()) {
			return back()->with('info', 'Email Anda sudah terverifikasi. Silakan login.');
		}

		Mail::to($user->email)->send(new VerifyEmail($user));

		return back()->with('success', 'Link verifikasi telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
	}
}

