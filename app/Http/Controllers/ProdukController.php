<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSerialNumber;
use App\Models\TransaksiKeluarItem;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class ProdukController extends BaseController
{
	/**
	 * Tampilkan daftar produk
	 */
	public function index(Request $request)
	{
		$query = Product::where('user_id', auth()->user()->id);

		if ($search = $request->input('search')) {
			$query->where(function ($q) use ($search) {
				$q->where('nama_produk', 'like', "%{$search}%")
					->orWhere('code', 'like', "%{$search}%")
					->orWhere('sku', 'like', "%{$search}%")
					->orWhere('brand', 'like', "%{$search}%")
					->orWhere('kategori', 'like', "%{$search}%");
			});
		}

		$perPage = (int) $request->input('per_page', 20);
		if ($perPage <= 0) {
			$perPage = 20;
		}

		$products = $query
			->orderBy('created_at', 'desc')
			->paginate($perPage)
			->appends($request->only('search', 'per_page'));

		return view('produk.index', compact('products'));
	}

	/**
	 * Form edit produk
	 */
	public function edit(Product $product)
	{
		// Pastikan hanya edit produk miliknya
		if ((int) $product->user_id !== (int) auth()->user()->id) {
			abort(403, 'Anda tidak memiliki akses ke produk ini');
		}

		$kategoriProduk = Kategori::where('user_id', auth()->id())
			->where('tipe', 'produk')
			->where('is_active', true)
			->get();

		return view('produk.edit', compact('product', 'kategoriProduk'));
	}

	/**
	 * Update data produk
	 */
	public function update(Request $request, Product $product)
	{
		// Pastikan hanya edit produk miliknya
		if ((int) $product->user_id !== (int) auth()->user()->id) {
			abort(403, 'Anda tidak memiliki akses ke produk ini');
		}

		$validated = $request->validate([
			'harga_jual' => 'required|numeric|min:0',
			'stock' => 'required|integer|min:0',
		]);

		// Tambah stok dan update harga
		$product->stock += $validated['stock'];
		$product->harga_jual = $validated['harga_jual'];
		$product->save();

		return redirect()
			->route('produk.index')
			->with('success', 'Produk ' . $product->nama_produk . ' berhasil diperbarui. Stok sekarang: ' . $product->stock);
	}

	/**
	 * Hapus produk (soft delete atau non-aktifkan)
	 */
	public function destroy(Product $product)
	{
		// Pastikan hanya hapus produk miliknya
		if ((int) $product->user_id !== (int) auth()->user()->id) {
			abort(403, 'Anda tidak memiliki akses ke produk ini');
		}

		// Cek apakah produk sudah digunakan dalam transaksi
		$hasTransactions = $product->transaksiMasukItems()->exists() || $product->transaksiKeluarItems()->exists();

		if ($hasTransactions) {
			// Jika sudah ada transaksi, hanya non-aktifkan
			$product->update(['is_active' => false]);
			return redirect()
				->route('produk.index')
				->with('success', 'Produk berhasil dinonaktifkan. Data tetap tersimpan untuk riwayat transaksi.');
		} else {
			// Jika belum ada transaksi, soft delete
			$product->delete();
			return redirect()
				->route('produk.index')
				->with('success', 'Produk berhasil dihapus.');
		}
	}

	public function bulkDestroy(Request $request)
	{
		$ids = $request->input('ids', []);

		if (empty($ids) || !is_array($ids)) {
			return redirect()->route('produk.index')->with('error', 'Tidak ada produk yang dipilih.');
		}

		Product::where('user_id', auth()->user()->id)->whereIn('id', $ids)->delete();

		return redirect()->route('produk.index')->with('success', 'Berhasil menghapus ' . count($ids) . ' produk.');
	}

	/**
	 * Form tambah produk
	 */
	public function create()
	{
		$kategoriProduk = Kategori::where('user_id', auth()->id())
			->where('tipe', 'produk')
			->where('is_active', true)
			->get();

		return view('produk.create', compact('kategoriProduk'));
	}

	/**
	 * Simpan produk baru
	 */
	public function store(Request $request)
	{
		try {
			$validated = $request->validate([
				'kategori_produk_id' => 'required|exists:kategoris,id',
				'harga_jual' => 'required|numeric|min:0',
				'stock' => 'required|integer|min:0',
			]);

			$kategori = Kategori::find($validated['kategori_produk_id']);

			// Cek apakah sudah ada produk dengan kategori yang sama
			$existingProduct = Product::where('user_id', auth()->user()->id)
				->where('kategori', $kategori->name)
				->first();

			if ($existingProduct) {
				// Jika produk sudah ada, tambahkan stoknya saja
				$existingProduct->stock += $validated['stock'];
				$existingProduct->harga_jual = $validated['harga_jual']; // Update harga jika berubah
				$existingProduct->save();

				return redirect()->route('produk.index')
					->with('success', 'Stok produk ' . $existingProduct->nama_produk . ' berhasil ditambahkan. Stok sekarang: ' . $existingProduct->stock);
			} else {
				// Jika belum ada, buat produk baru
				// Hapus kategori_produk_id dari array sebelum create (tidak ada di fillable)
				unset($validated['kategori_produk_id']);

				// Default values untuk field lain
				$validated['code'] = Product::generateCode();
				$validated['user_id'] = auth()->user()->id();
				$validated['unit'] = 'pcs';
				$validated['tipe'] = 'Barang';
				$validated['lacak_stok'] = true;
				$validated['is_active'] = true;
				$validated['harga_beli'] = 0;
				$validated['min_stock'] = 0;
				$validated['nama_produk'] = $kategori->name;
				$validated['kategori'] = $kategori->name; // Simpan nama kategori ke field kategori

				// Default values untuk field legacy (dari database DESCRIBE)
				$validated['jenis_logam'] = null; // Bisa null di database
				$validated['brand'] = null; // Bisa null di database
				$validated['berat_standar'] = null; // Bisa null di database
				$validated['stock_minimum'] = 0; // Sudah ada default 0 di database

				// Membuat produk baru
				$product = Product::create($validated);

				return redirect()->route('produk.index')
					->with('success', 'Produk ' . $product->nama_produk . ' berhasil ditambahkan');
			}

		} catch (\Exception $e) {
			return back()
				->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
				->withInput();
		}
	}

	/**
	 * Tampilkan riwayat produk masuk dan keluar
	 */
	public function log(Request $request)
	{
		$tipe = $request->input('tipe');
		$search = $request->input('search');
		$startDate = $request->input('start_date');
		$endDate = $request->input('end_date');
		$perPage = (int) $request->input('per_page', 20);

		if ($perPage <= 0) {
			$perPage = 20;
		}

		// Get keluar items
		$keluarItems = TransaksiKeluarItem::with('transaksi', 'product')
			->where('user_id', auth()->user()->id)
			->whereHas('transaksi')
			->get()
			->map(function ($item) {
				return (object)[
					'tipe' => 'keluar',
					'tanggal_transaksi' => $item->transaksi->tanggal_transaksi,
					'referensi_no' => $item->transaksi->invoice_no,
					'nama_produk' => $item->product->nama_produk,
					'sku' => $item->product->sku,
					'supplier_name' => null,
					'customer_name' => $item->transaksi->customer_name,
					'qty' => $item->qty, // Gunakan qty langsung, bukan count(nomor_seri)
					'total_harga' => $item->total_harga,
					'status' => $item->transaksi->status,
					'id' => 'keluar-' . $item->id,
				];
			});

		// Merge collections (only keluar items since masuk was removed)
		$allHistories = $keluarItems;

		// Apply filters (tipe filter removed since only keluar is available)
		/*
		if ($tipe) {
			$allHistories = $allHistories->filter(function ($item) use ($tipe) {
				return $item->tipe === $tipe;
			});
		}
		*/

		if ($search) {
			$search = strtolower($search);
			$allHistories = $allHistories->filter(function ($item) use ($search) {
				return strpos(strtolower($item->nama_produk), $search) !== false ||
					   strpos(strtolower($item->referensi_no), $search) !== false ||
					   strpos(strtolower($item->sku ?? ''), $search) !== false ||
					   strpos(strtolower($item->supplier_name ?? ''), $search) !== false ||
					   strpos(strtolower($item->customer_name ?? ''), $search) !== false;
			});
		}

		if ($startDate) {
			$allHistories = $allHistories->filter(function ($item) use ($startDate) {
				return $item->tanggal_transaksi >= $startDate;
			});
		}

		if ($endDate) {
			$allHistories = $allHistories->filter(function ($item) use ($endDate) {
				return $item->tanggal_transaksi <= $endDate;
			});
		}

		// Sort by date descending
		$allHistories = $allHistories->sortByDesc('tanggal_transaksi');

		// Paginate manually
		$total = $allHistories->count();
		$page = $request->input('page', 1);
		$skip = ($page - 1) * $perPage;
		$items = $allHistories->slice($skip, $perPage)->values();

		$histories = new \Illuminate\Pagination\LengthAwarePaginator(
			$items,
			$total,
			$perPage,
			$page,
			[
				'path' => route('produk.log'),
				'query' => $request->only('tipe', 'search', 'start_date', 'end_date', 'per_page'),
			]
		);

		return view('produk.log', compact('histories'));
	}

	/**
	 * Export riwayat produk ke Excel
	 */
	public function logExportExcel(Request $request)
	{
		$tipe = $request->input('tipe');
		$search = $request->input('search');
		$startDate = $request->input('start_date');
		$endDate = $request->input('end_date');

		// Get masuk items
		$masukItems = TransaksiMasukItem::with('transaksi', 'product')
			->where('user_id', auth()->user()->id)
			->whereHas('transaksi')
			->get()
			->map(function ($item) {
				return (object)[
					'tipe' => 'masuk',
					'tanggal_transaksi' => $item->transaksi->tanggal_transaksi,
					'referensi_no' => $item->transaksi->reference_no,
					'nama_produk' => $item->product->nama_produk,
					'sku' => $item->product->sku,
					'supplier_name' => $item->transaksi->supplier_name,
					'customer_name' => null,
					'qty' => $item->qty, // Gunakan qty langsung
					'total_harga' => $item->total_harga,
					'status' => $item->transaksi->status,
				];
			});

		// Get keluar items
		$keluarItems = TransaksiKeluarItem::with('transaksi', 'product')
			->where('user_id', auth()->user()->id)
			->whereHas('transaksi')
			->get()
			->map(function ($item) {
				return (object)[
					'tipe' => 'keluar',
					'tanggal_transaksi' => $item->transaksi->tanggal_transaksi,
					'referensi_no' => $item->transaksi->invoice_no,
					'nama_produk' => $item->product->nama_produk,
					'sku' => $item->product->sku,
					'supplier_name' => null,
					'customer_name' => $item->transaksi->customer_name,
					'qty' => $item->qty, // Gunakan qty langsung
					'total_harga' => $item->total_harga,
					'status' => $item->transaksi->status,
				];
			});

		// Merge and filter for Excel export
		$allHistories = collect(array_merge($masukItems->all(), $keluarItems->all()));

		if ($tipe) {
			$allHistories = $allHistories->filter(function ($item) use ($tipe) {
				return $item->tipe === $tipe;
			});
		}

		if ($search) {
			$search = strtolower($search);
			$allHistories = $allHistories->filter(function ($item) use ($search) {
				return strpos(strtolower($item->nama_produk), $search) !== false ||
					   strpos(strtolower($item->referensi_no), $search) !== false ||
					   strpos(strtolower($item->sku ?? ''), $search) !== false;
			});
		}

		if ($startDate) {
			$allHistories = $allHistories->filter(function ($item) use ($startDate) {
				return $item->tanggal_transaksi >= $startDate;
			});
		}

		if ($endDate) {
			$allHistories = $allHistories->filter(function ($item) use ($endDate) {
				return $item->tanggal_transaksi <= $endDate;
			});
		}

		$allHistories = $allHistories->sortByDesc('tanggal_transaksi');

		// Generate CSV
		$stream = fopen('php://memory', 'r+');
		fputs($stream, chr(0xEF) . chr(0xBB) . chr(0xBF));
		fputcsv($stream, ['Tanggal', 'Tipe', 'No. Referensi', 'Nama Produk', 'Supplier/Customer', 'Qty', 'Total Harga', 'Status'], ';');

		foreach ($allHistories as $history) {
			$statusLabel = $history->status === 'complete'
				? 'Selesai'
				: ($history->status === 'pending' ? 'Pending' : 'Dibatalkan');
			fputcsv($stream, [
				Carbon::parse($history->tanggal_transaksi)->format('d/m/Y'),
				strtoupper($history->tipe),
				$history->referensi_no,
				$history->nama_produk,
				$history->supplier_name ?? $history->customer_name ?? '-',
				$history->qty,
				$history->total_harga,
				$statusLabel,
			], ';');
		}

		rewind($stream);
		$csv = stream_get_contents($stream);
		fclose($stream);

		$fileName = 'riwayat_produk_' . now()->format('Ymd_His') . '.csv';
		return response($csv, 200, [
			'Content-Type' => 'text/csv; charset=UTF-8',
			'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
		]);
	}

	/**
	 * Export riwayat produk ke PDF
	 */
	public function logExportPdf(Request $request)
	{
		$tipe = $request->input('tipe');
		$search = $request->input('search');
		$startDate = $request->input('start_date');
		$endDate = $request->input('end_date');

		// Get masuk items
		$masukItems = TransaksiMasukItem::with('transaksi', 'product')
			->where('user_id', auth()->user()->id)
			->whereHas('transaksi')
			->get()
			->map(function ($item) {
				return (object)[
					'tipe' => 'masuk',
					'tanggal_transaksi' => $item->transaksi->tanggal_transaksi,
					'referensi_no' => $item->transaksi->reference_no,
					'nama_produk' => $item->product->nama_produk,
					'sku' => $item->product->sku,
					'supplier_name' => $item->transaksi->supplier_name,
					'customer_name' => null,
					'qty' => $item->qty, // Gunakan qty langsung
					'total_harga' => $item->total_harga,
					'status' => $item->transaksi->status,
				];
			});

		// Get keluar items
		$keluarItems = TransaksiKeluarItem::with('transaksi', 'product')
			->where('user_id', auth()->user()->id)
			->whereHas('transaksi')
			->get()
			->map(function ($item) {
				return (object)[
					'tipe' => 'keluar',
					'tanggal_transaksi' => $item->transaksi->tanggal_transaksi,
					'referensi_no' => $item->transaksi->invoice_no,
					'nama_produk' => $item->product->nama_produk,
					'sku' => $item->product->sku,
					'supplier_name' => null,
					'customer_name' => $item->transaksi->customer_name,
					'qty' => $item->qty, // Gunakan qty langsung
					'total_harga' => $item->total_harga,
					'status' => $item->transaksi->status,
				];
			});

		// Merge and filter
		$allHistories = collect(array_merge($masukItems->all(), $keluarItems->all()));

		if ($tipe) {
			$allHistories = $allHistories->filter(function ($item) use ($tipe) {
				return $item->tipe === $tipe;
			});
		}

		if ($search) {
			$search = strtolower($search);
			$allHistories = $allHistories->filter(function ($item) use ($search) {
				return strpos(strtolower($item->nama_produk), $search) !== false ||
					   strpos(strtolower($item->referensi_no), $search) !== false ||
					   strpos(strtolower($item->sku ?? ''), $search) !== false;
			});
		}

		if ($startDate) {
			$allHistories = $allHistories->filter(function ($item) use ($startDate) {
				return $item->tanggal_transaksi >= $startDate;
			});
		}

		if ($endDate) {
			$allHistories = $allHistories->filter(function ($item) use ($endDate) {
				return $item->tanggal_transaksi <= $endDate;
			});
		}

		$allHistories = $allHistories->sortByDesc('tanggal_transaksi');

		$pdf = Pdf::loadView('produk.log-pdf', [
			'histories' => $allHistories,
		]);

		$fileName = 'riwayat_produk_' . now()->format('Ymd_His') . '.pdf';
		return $pdf->download($fileName);
	}
}
