<?php

namespace App\Http\Controllers\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
	public function index()
	{
		$users = User::where('user_id', auth()->user()->id)->latest()->paginate(20);
		return view('data-master.pengguna.index', compact('users'));
	}

	public function create()
	{
		return view('data-master.pengguna.create');
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email',
			'password' => 'required|string|min:6',
			'role' => 'required|in:owner,admin,kasir',
		]);

		$validated['password'] = bcrypt($validated['password']);
		$validated['user_id'] = auth()->user()->id;
		$validated['is_active'] = true;

		User::create($validated);

		return redirect()->route('data-master.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan');
	}

	public function edit(User $pengguna)
	{
		return view('data-master.pengguna.edit', compact('pengguna'));
	}

	public function update(Request $request, User $pengguna)
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:users,email,' . $pengguna->id,
			'role' => 'required|in:owner,admin,kasir',
			'is_active' => 'required|boolean',
		]);

		if ($request->filled('password')) {
			$validated['password'] = bcrypt($request->password);
		}

		$pengguna->update($validated);

		return redirect()->route('data-master.pengguna.index')->with('success', 'Pengguna berhasil diperbarui');
	}

	public function destroy(User $pengguna)
	{
		$pengguna->delete();
		return redirect()->route('data-master.pengguna.index')->with('success', 'Pengguna berhasil dihapus');
	}
}
