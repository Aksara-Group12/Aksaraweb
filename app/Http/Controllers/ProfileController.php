<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Paket;
use App\Models\Subscribe;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
	/**
	 * Tampilkan halaman utama profil & form edit profil yang terpadu
	 */
	public function index()
	{
		$user = auth()->user();
		$profile = Profile::where('user_id', $user->id)->first();

		if (!$profile) {
			$profile = Profile::create([
				'user_id' => $user->id,
				'store_name' => $user->role === 'superadmin' ? 'Aksara Developer' : ($user->name . ' Store'),
				'owner_name' => $user->name,
				'email' => $user->email,
			]);
		}

		$packages = [];
		$activeSubscription = null;
		$banks = [];

		if ($user->role === 'owner') {
			$packages = Paket::where('is_active', true)->get();
			$activeSubscription = Subscribe::getActiveSubscription($user->id);
			$banks = Bank::all();
		}

		return view('profile.index', compact('profile', 'user', 'packages', 'activeSubscription', 'banks'));
	}

	public function create()
	{
		return redirect()->route('profile.index');
	}

	public function store(Request $request)
	{
		return redirect()->route('profile.index');
	}

	public function edit(Profile $profile)
	{
		return redirect()->route('profile.index');
	}

	/**
	 * Simpan pembaruan profil & data akun pengguna
	 */
	public function update(Request $request, Profile $profile)
	{
		$user = auth()->user();

		if ((int) $profile->user_id !== (int) $user->id) {
			abort(403, 'Anda tidak memiliki hak akses untuk mengubah profil ini.');
		}

		$request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
			'password' => ['nullable', 'string', 'min:8', 'confirmed'],
			'store_name' => ['required', 'string', 'max:255'],
			'phone' => ['nullable', 'string', 'max:50'],
			'address' => ['nullable', 'string'],
			'city' => ['nullable', 'string', 'max:255'],
			'postal_code' => ['nullable', 'string', 'max:20'],
			'logo' => ['nullable', 'file', 'image', 'mimes:png,jpg,jpeg', 'max:5120'],
		], [
			'name.required' => 'Nama lengkap wajib diisi.',
			'email.required' => 'Alamat email wajib diisi.',
			'email.unique' => 'Alamat email sudah digunakan oleh akun lain.',
			'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
			'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
			'store_name.required' => $user->role === 'superadmin' ? 'Nama Sistem / Brand wajib diisi.' : 'Nama Toko wajib diisi.',
			'logo.image' => 'Logo harus berupa berkas gambar.',
			'logo.max' => 'Ukuran logo maksimal adalah 5MB.',
		]);

		// 1. Update data User
		$userData = [
			'name' => $request->name,
			'email' => $request->email,
		];

		if ($request->filled('password')) {
			$userData['password'] = Hash::make($request->password);
		}

		$user->update($userData);

		// 2. Handle upload logo
		$profileData = [
			'store_name' => $request->store_name,
			'owner_name' => $request->name,
			'email' => $request->email,
			'phone' => $request->phone,
			'address' => $request->address,
			'city' => $request->city,
			'postal_code' => $request->postal_code,
		];

		if ($request->hasFile('logo')) {
			try {
				if ($profile->logo_path && Storage::disk('public')->exists($profile->logo_path)) {
					Storage::disk('public')->delete($profile->logo_path);
				}

				$extension = $request->file('logo')->getClientOriginalExtension();
				$fileName = 'logo_' . $user->id . '_' . time() . '.' . $extension;
				$request->file('logo')->storeAs('logos', $fileName, 'public');
				$profileData['logo_path'] = 'logos/' . $fileName;
			} catch (\Exception $e) {
				return back()
					->withInput()
					->withErrors(['logo' => 'Gagal mengunggah logo: ' . $e->getMessage()]);
			}
		}

		$profile->update($profileData);

		return redirect()
			->route('profile.index')
			->with('success', 'Profil dan akun berhasil diperbarui.');
	}
}
