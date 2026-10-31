<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSerialNumber;
use App\Models\TransaksiMasukItem;
use App\Models\TransaksiKeluarItem;
use App\Models\KategoriProduk;
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

		$kategoriProduk = KategoriProduk::where('tipe', 'produk')->get();

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
			'nama_produk' => 'required|string|max:255',
			'code' => 'nullable|string|max:50|unique:products,code,' . $product->id,
			'sku' => 'nullable|string|max:50',
			'unit' => 'required|string|max:50',
			'tipe' => 'required|in:Barang,Menu,Jasa',
			'lacak_stok' => 'nullable',
			'harga_beli' => 'nullable|numeric|min:0',
			'harga_jual' => 'required|numeric|min:0',
			'stock' => 'nullable|integer|min:0',
			'min_stock' => 'nullable|integer|min:0',
			'kategori' => 'nullable|string',
			'kategori_produk_id' => 'nullable|exists:kategori_produk,id',
			'lokasi' => 'nullable|string',
			'description' => 'nullable|string',
			'barcode' => 'nullable|string|max:50',
			'is_active' => 'required',
			'brand' => 'nullable|string|max:100',
		], [
			'nama_produk.required' => 'Nama produk wajib diisi.',
			'unit.required' => 'Satuan (unit) produk wajib diisi.',
			'tipe.required' => 'Tipe produk wajib dipilih (Barang, Menu, atau Jasa).',
			'harga_jual.required' => 'Harga jual produk wajib diisi.',
			'code.unique' => 'Kode produk/SKU ini sudah digunakan.',
		]);

		// Handle lacak_stok checkbox (flexible - user choice)
		$validated['lacak_stok'] = isset($validated['lacak_stok']) ? true : false;

		// Handle is_active (convert string to boolean)
		$validated['is_active'] = $validated['is_active'] == '1' ? true : false;

		// Fallback kategori dari master jika dipilih
		if (!empty($validated['kategori_produk_id'])) {
			$kat = KategoriProduk::find($validated['kategori_produk_id']);
			if ($kat && empty($validated['kategori'])) {
				$validated['kategori'] = $kat->nama_kategori;
			}
		}

		// Nilai aman untuk kolom database legacy
		if (empty($validated['brand'])) {
			$validated['brand'] = $product->brand ?? 'Umum';
		}
		if (!isset($validated['harga_beli']) || $validated['harga_beli'] === null) {
			$validated['harga_beli'] = $product->harga_beli ?? 0.00;
		}

		$product->update($validated);

		return redirect()
			->route('produk.index')
			->with('success', 'Produk berhasil diperbarui.');
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
		$kategoriProduk = KategoriProduk::where('tipe', 'produk')->get();

		return view('produk.create', compact('kategoriProduk'));
	}

	/**
	 * Simpan produk baru
	 */
	public function store(Request $request)
	{
		try {
			$validated = $request->validate([
				'nama_produk' => 'required|string|max:255',
				'code' => 'nullable|string|max:50|unique:products,code',
				'sku' => 'nullable|string|max:50',
				'unit' => 'required|string|max:50',
				'tipe' => 'required|in:Barang,Menu,Jasa',
				'lacak_stok' => 'nullable',
				'harga_beli' => 'nullable|numeric|min:0',
				'harga_jual' => 'required|numeric|min:0',
				'stock' => 'nullable|integer|min:0',
				'min_stock' => 'nullable|integer|min:0',
				'kategori' => 'nullable|string',
				'kategori_produk_id' => 'nullable|exists:kategori_produk,id',
				'lokasi' => 'nullable|string',
				'description' => 'nullable|string',
				'barcode' => 'nullable|string|max:50',
				'is_active' => 'required',
				'brand' => 'nullable|string|max:100',
			], [
				'nama_produk.required' => 'Nama produk wajib diisi.',
				'unit.required' => 'Satuan (unit) produk wajib diisi.',
				'tipe.required' => 'Tipe produk wajib dipilih (Barang, Menu, atau Jasa).',
				'harga_jual.required' => 'Harga jual produk wajib diisi.',
				'harga_jual.numeric' => 'Harga jual harus berupa angka.',
				'code.unique' => 'Kode produk/SKU ini sudah digunakan.',
			]);

			// Generate code jika tidak ada
			if (empty($validated['code'])) {
				$validated['code'] = Product::generateCode();
			}

			$validated['user_id'] = auth()->user()->id;

			// Handle lacak_stok checkbox (flexible - user choice)
			$validated['lacak_stok'] = isset($validated['lacak_stok']) ? true : false;

			// Handle is_active (convert string to boolean)
			$validated['is_active'] = $validated['is_active'] == '1' ? true : false;

			// Fallback kategori dari master jika dipilih
			if (!empty($validated['kategori_produk_id'])) {
				$kat = KategoriProduk::find($validated['kategori_produk_id']);
				if ($kat && empty($validated['kategori'])) {
					$validated['kategori'] = $kat->nama_kategori;
				}
			}

			// Pastikan field legacy dengan NOT NULL di database MySQL terisi default aman
			$validated['jenis_logam'] = 'emas';
			$validated['brand'] = !empty($validated['brand']) ? $validated['brand'] : 'Umum';
			$validated['berat_standar'] = 1.00;
			$validated['harga_beli'] = isset($validated['harga_beli']) && $validated['harga_beli'] !== null ? $validated['harga_beli'] : 0.00;
			$validated['stock'] = isset($validated['stock']) && $validated['stock'] !== null ? (int) $validated['stock'] : 0;
			$validated['min_stock'] = isset($validated['min_stock']) && $validated['min_stock'] !== null ? (int) $validated['min_stock'] : 0;
			$validated['cost_price'] = $validated['harga_beli'];
			$validated['stock_minimum'] = $validated['min_stock'];

			// Membuat produk
			$product = Product::create($validated);

			return redirect()->route('produk.index')
				->with('success', 'Produk "' . $product->nama_produk . '" berhasil ditambahkan');

		} catch (\Illuminate\Validation\ValidationException $e) {
			return back()
				->withErrors($e->validator)
				->withInput();
		} catch (\Exception $e) {
			return back()
				->with('error', 'Gagal menyimpan produk: ' . $e->getMessage())
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
					'qty' => $item->qty, // Gunakan qty langsung, bukan count(nomor_seri)
					'total_harga' => $item->total_harga,
					'status' => $item->transaksi->status,
					'id' => 'masuk-' . $item->id,
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
					'qty' => $item->qty, // Gunakan qty langsung, bukan count(nomor_seri)
					'total_harga' => $item->total_harga,
					'status' => $item->transaksi->status,
					'id' => 'keluar-' . $item->id,
				];
			});

		// Merge collections
		$allHistories = collect(array_merge($masukItems->all(), $keluarItems->all()));

		// Apply filters
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
