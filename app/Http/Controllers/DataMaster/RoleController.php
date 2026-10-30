<?php

namespace App\Http\Controllers\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
	public function index()
	{
		$roles = Role::latest()->paginate(20);
		return view('data-master.role.index', compact('roles'));
	}

	public function create()
	{
		return view('data-master.role.create');
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255|unique:roles',
			'display_name' => 'required|string|max:255',
		]);

		$validated['permissions'] = [];
		$validated['is_active'] = true;

		Role::create($validated);

		return redirect()->route('data-master.role.index')->with('success', 'Role berhasil ditambahkan');
	}

	public function edit(Role $role)
	{
		return view('data-master.role.edit', compact('role'));
	}

	public function update(Request $request, Role $role)
	{
		$validated = $request->validate([
			'display_name' => 'required|string|max:255',
			'is_active' => 'required|boolean',
		]);

		$role->update($validated);

		return redirect()->route('data-master.role.index')->with('success', 'Role berhasil diperbarui');
	}

	public function destroy(Role $role)
	{
		$role->delete();
		return redirect()->route('data-master.role.index')->with('success', 'Role berhasil dihapus');
	}
}
