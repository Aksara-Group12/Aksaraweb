<?php

namespace App\Http\Controllers\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RbacController extends Controller
{
	public function index()
	{
		$roles = Role::latest()->paginate(20);
		return view('data-master.rbac.index', compact('roles'));
	}

	public function create()
	{
		return view('data-master.rbac.create');
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255|unique:roles',
			'display_name' => 'required|string|max:255',
			'permissions' => 'nullable|array',
		]);

		$validated['permissions'] = $validated['permissions'] ?? [];
		$validated['is_active'] = true;

		Role::create($validated);

		return redirect()->route('data-master.rbac.index')->with('success', 'Role berhasil ditambahkan');
	}

	public function edit(Role $role)
	{
		return view('data-master.rbac.edit', compact('role'));
	}

	public function update(Request $request, Role $role)
	{
		$validated = $request->validate([
			'display_name' => 'required|string|max:255',
			'permissions' => 'nullable|array',
			'is_active' => 'required|boolean',
		]);

		$validated['permissions'] = $validated['permissions'] ?? [];

		$role->update($validated);

		return redirect()->route('data-master.rbac.index')->with('success', 'Role berhasil diperbarui');
	}

	public function destroy(Role $role)
	{
		$role->delete();
		return redirect()->route('data-master.rbac.index')->with('success', 'Role berhasil dihapus');
	}
}
