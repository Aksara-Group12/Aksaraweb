<?php

namespace App\Http\Controllers\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduk;
use Illuminate\Http\Request;

class KategoriProdukController extends Controller
{
	public function index()
	{
		$kategoris = KategoriProduk::where('user_id', auth()->id())
			->active()
			->latest()
			->paginate(20);
		return view('data-master.kategori-produk.index', compact('kategoris'));
	}

	public function create()
	{
		return view('data-master.kategori-produk.create');
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
		]);

		$validated['user_id'] = auth()->id();
		$validated['is_active'] = true;

		KategoriProduk::create($validated);

		return redirect()->route('data-master.kategori-produk.index')->with('success', 'Kategori Produk berhasil ditambahkan');
	}

	public function edit(KategoriProduk $kategori)
	{
		return view('data-master.kategori-produk.edit', compact('kategori'));
	}

	public function update(Request $request, KategoriProduk $kategori)
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'is_active' => 'required|boolean',
		]);

		$kategori->update($validated);

		return redirect()->route('data-master.kategori-produk.index')->with('success', 'Kategori Produk berhasil diperbarui');
	}

	public function destroy(KategoriProduk $kategori)
	{
		$kategori->delete();
		return redirect()->route('data-master.kategori-produk.index')->with('success', 'Kategori Produk berhasil dihapus');
	}
}
