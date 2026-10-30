<?php

namespace App\Http\Controllers\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriProdukController extends Controller
{
	public function index()
	{
		$kategoris = Kategori::where('user_id', auth()->id())
			->where('tipe', 'produk')
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

		$validated['tipe'] = 'produk';
		$validated['user_id'] = auth()->id();
		$validated['is_active'] = true;

		Kategori::create($validated);

		return redirect()->route('data-master.kategori-produk.index')->with('success', 'Kategori berhasil ditambahkan');
	}

	public function edit(Kategori $kategori)
	{
		return view('data-master.kategori-produk.edit', compact('kategori'));
	}

	public function update(Request $request, Kategori $kategori)
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'is_active' => 'required|boolean',
		]);

		$kategori->update($validated);

		return redirect()->route('data-master.kategori-produk.index')->with('success', 'Kategori berhasil diperbarui');
	}

	public function destroy(Kategori $kategori)
	{
		$kategori->delete();
		return redirect()->route('data-master.kategori-produk.index')->with('success', 'Kategori berhasil dihapus');
	}
}
