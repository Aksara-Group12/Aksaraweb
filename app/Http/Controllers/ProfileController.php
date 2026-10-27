<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Paket;
use App\Models\Subscribe;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
	public function index()
	{
		$profile = Profile::where('user_id', auth()->user()->id)->first();

		// Data untuk subscription (hanya jika user adalah owner)
		$packages = [];
		$activeSubscription = null;
		$banks = [];

		if (auth()->user()->role === 'owner') {
			$packages = Paket::where('is_active', true)->get();
			$activeSubscription = Subscribe::getActiveSubscription(auth()->id());
			$banks = Bank::all();
		}

		return view('profile.index', compact('profile', 'packages', 'activeSubscription', 'banks'));
	}

	public function create()
	{
		$profile = Profile::where('user_id', auth()->user()->id)->first();
		if ($profile) {
			return redirect()->route('profile.edit', $profile);
		}

		return view('profile.create', ['profile' => null]);
	}

	public function store(Request $request)
	{
		\Log::info('Profile Store Request', [
			'has_logo' => $request->hasFile('logo'),
			'files' => $request->files->keys(),
			'post_data' => $request->except(['logo']),
		]);

		$data = $this->validateData($request);
		$data['user_id'] = auth()->user()->id;

		// Handle file upload
		if ($request->hasFile('logo')) {
			try {
				$fileName = 'logo_' . auth()->user()->id . '_' . time() . '.png';
				$request->file('logo')->storeAs('logos', $fileName, 'public');
				$data['logo_path'] = 'logos/' . $fileName;
				\Log::info('Logo uploaded successfully', ['file' => $fileName]);
			} catch (\Exception $e) {
				\Log::error('Logo upload failed', ['error' => $e->getMessage()]);
				return back()->withErrors(['logo' => 'Gagal upload logo: ' . $e->getMessage()]);
			}
		}

		Profile::create($data);

		return redirect()
			->route('profile.index')
			->with('success', 'Profil toko berhasil disimpan.');
	}

	public function edit(Profile $profile)
	{
		// Pastikan hanya edit profil miliknya sendiri - gunakan type casting untuk safety
		if ((int) $profile->user_id !== (int) auth()->user()->id) {
			abort(403, 'Anda tidak memiliki akses ke profil ini');
		}

		return view('profile.create', compact('profile'));
	}

	public function update(Request $request, Profile $profile)
	{
		// Pastikan hanya update profil miliknya sendiri - gunakan type casting untuk safety
		if ((int) $profile->user_id !== (int) auth()->user()->id) {
			abort(403, 'Anda tidak memiliki akses ke profil ini');
		}

		\Log::info('Profile Update Request', [
			'has_logo' => $request->hasFile('logo'),
			'files' => $request->files->keys(),
			'post_data' => $request->except(['logo']),
		]);

		$data = $this->validateData($request);

		// Handle file upload
		if ($request->hasFile('logo')) {
			try {
				// Hapus logo lama jika ada
				if ($profile->logo_path && Storage::disk('public')->exists($profile->logo_path)) {
					Storage::disk('public')->delete($profile->logo_path);
					\Log::info('Old logo deleted', ['path' => $profile->logo_path]);
				}
				
				$fileName = 'logo_' . auth()->user()->id . '_' . time() . '.png';
				$request->file('logo')->storeAs('logos', $fileName, 'public');
				$data['logo_path'] = 'logos/' . $fileName;
				\Log::info('Logo uploaded successfully', ['file' => $fileName]);
			} catch (\Exception $e) {
				\Log::error('Logo upload failed', ['error' => $e->getMessage()]);
				return back()->withErrors(['logo' => 'Gagal upload logo: ' . $e->getMessage()]);
			}
		}

		$profile->update($data);

		return redirect()
			->route('profile.index')
			->with('success', 'Profil toko berhasil diperbarui.');
	}

	protected function validateData(Request $request): array
	{
		$rules = [
			'store_name' => ['required', 'string', 'max:255'],
			'owner_name' => ['nullable', 'string', 'max:255'],
			'phone' => ['nullable', 'string', 'max:50'],
			'email' => ['nullable', 'email', 'max:255'],
			'address' => ['nullable', 'string'],
			'city' => ['nullable', 'string', 'max:255'],
			'postal_code' => ['nullable', 'string', 'max:20'],
		];

		// Logo validation - very lenient
		if ($request->hasFile('logo')) {
			$rules['logo'] = ['required', 'file', 'max:5120']; // Max 5MB
		}

		return $request->validate($rules);
	}
}

