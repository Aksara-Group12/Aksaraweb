<?php

namespace App\Http\Controllers;

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

		// Gunakan data user langsung (table profiles sudah dihapus)
		$storeName = 'Aksara';
		$logoPath = null;

		$packages = [];
		$activeSubscription = null;
		$banks = [];

		if ($user->role === 'owner') {
			$packages = Paket::where('is_active', true)->get();
			$activeSubscription = Subscribe::getActiveSubscription($user->id);
			$banks = Bank::all();
		}

		return view('profile.index', compact('user', 'storeName', 'logoPath', 'packages', 'activeSubscription', 'banks'));
	}

	public function create()
	{
		return redirect()->route('profile.index');
	}

	public function store(Request $request)
	{
		return redirect()->route('profile.index');
	}

	public function edit()
	{
		return redirect()->route('profile.index');
	}

	/**
	 * Simpan pembaruan profil & data akun pengguna
	 */
	public function update(Request $request)
	{
		$user = auth()->user();

		$request->validate([
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
			'password' => ['nullable', 'string', 'min:8', 'confirmed'],
		], [
			'name.required' => 'Nama lengkap wajib diisi.',
			'email.required' => 'Alamat email wajib diisi.',
			'email.unique' => 'Alamat email sudah digunakan oleh akun lain.',
			'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
			'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
		]);

		// Update data User
		$userData = [
			'name' => $request->name,
			'email' => $request->email,
		];

		if ($request->filled('password')) {
			$userData['password'] = Hash::make($request->password);
		}

		$user->update($userData);

		return redirect()
			->route('profile.index')
			->with('success', 'Profil dan akun berhasil diperbarui.');
	}
}
