<?php

namespace App\Http\Controllers\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;

class KategoriPengeluaranController extends Controller
{
	public function index()
	{
		$kategoris = KategoriPengeluaran::where('user_id', auth()->id())
			->active()
			->latest()
			->paginate(20);
		return view('data-master.kategori-pengeluaran.index', compact('kategoris'));
	}

	public function create()
	{
		return view('data-master.kategori-pengeluaran.create');
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
		]);

		$validated['user_id'] = auth()->id();
		$validated['is_active'] = true;

		KategoriPengeluaran::create($validated);

		return redirect()->route('data-master.kategori-pengeluaran.index')->with('success', 'Kategori Pengeluaran berhasil ditambahkan');
	}

	public function edit(KategoriPengeluaran $kategori)
	{
		return view('data-master.kategori-pengeluaran.edit', compact('kategori'));
	}

	public function update(Request $request, KategoriPengeluaran $kategori)
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'is_active' => 'required|boolean',
		]);

		$kategori->update($validated);

		return redirect()->route('data-master.kategori-pengeluaran.index')->with('success', 'Kategori Pengeluaran berhasil diperbarui');
	}

	public function destroy(KategoriPengeluaran $kategori)
	{
		$kategori->delete();
		return redirect()->route('data-master.kategori-pengeluaran.index')->with('success', 'Kategori Pengeluaran berhasil dihapus');
	}
}
