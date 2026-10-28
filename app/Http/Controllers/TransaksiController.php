<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiMasukItem;
use App\Models\ProductSerialNumber;
use App\Models\TransaksiKeluar;
use App\Models\TransaksiKeluarItem;
use App\Models\TransaksiBiayaTambahan;
use App\Models\Expenses;
use App\Models\TransaksiMasukAuditLog;
use App\Models\Admin;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class TransaksiController extends BaseController
{
	/**
	 * === TRANSAKSI MASUK ===
	 */

	/**
	 * Tampilkan daftar transaksi masuk
	 */
	public function masuk(Request $request)
	{
		$query = TransaksiMasuk::where('user_id', auth()->user()->id)->with('items.product')->orderBy('created_at', 'desc');

		if ($search = $request->input('search')) {
			$query->where(function ($q) use ($search) {
				$q->where('reference_no', 'like', "%{$search}%")
					->orWhere('supplier_name', 'like', "%{$search}%")
					->orWhere('lokasi', 'like', "%{$search}%");
			});
		}

		$perPage = (int) $request->input('per_page', 20);
		if ($perPage <= 0) {
			$perPage = 20;
		}

		$transaksi = $query
			->paginate($perPage)
			->appends($request->only('search', 'per_page'));

		return view('transaksi.masuk.index', compact('transaksi'));
	}

	public function exportMasukExcel(Request $request)
	{
		$query = TransaksiMasuk::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc');

		if ($search = $request->input('search')) {
			$query->where(function ($q) use ($search) {
				$q->where('reference_no', 'like', "%{$search}%")
					->orWhere('supplier_name', 'like', "%{$search}%")
					->orWhere('lokasi', 'like', "%{$search}%");
			});
		}

		$rows = $query->get();

		$stream = fopen('php://memory', 'r+');
		fputs($stream, chr(0xEF) . chr(0xBB) . chr(0xBF));
		fputcsv($stream, ['Tanggal', 'No. Referensi', 'Supplier', 'Kota/Kabupaten', 'Total Harga', 'Status'], ';');

		foreach ($rows as $t) {
			$statusLabel = $t->status === 'complete'
				? 'Selesai'
				: ($t->status === 'pending' ? 'Pending' : ($t->status === 'reopened' ? 'Reopened' : 'Dibatalkan'));
			fputcsv($stream, [
				$t->created_at ? $t->created_at->format('d/m/Y') : '',
				$t->reference_no,
				$t->supplier_name,
				$t->lokasi ?? '-',
				$t->total_harga,
				$statusLabel,
			], ';');
		}

		rewind($stream);
		$csv = stream_get_contents($stream);
		fclose($stream);

		$fileName = 'transaksi_masuk_' . now()->format('Ymd_His') . '.csv';
		return response($csv, 200, [
			'Content-Type' => 'text/csv; charset=UTF-8',
			'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
		]);
	}

	public function exportMasukPdf(Request $request)
	{
		$query = TransaksiMasuk::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc');

		if ($search = $request->input('search')) {
			$query->where(function ($q) use ($search) {
				$q->where('reference_no', 'like', "%{$search}%")
					->orWhere('supplier_name', 'like', "%{$search}%")
					->orWhere('lokasi', 'like', "%{$search}%");
			});
		}

		$rows = $query->get();

		$pdf = Pdf::loadView('transaksi.masuk.export-pdf', [
			'transaksi' => $rows,
		]);

		$fileName = 'transaksi_masuk_' . now()->format('Ymd_His') . '.pdf';
		return $pdf->download($fileName);
	}

	/**
	 * Form tambah transaksi masuk
	 */
	public function createMasuk()
	{
		$products = Product::where('user_id', auth()->user()->id)
			->where('is_active', true)
			->orderBy('nama_produk')
			->get();
		
		return view('transaksi.masuk.create', compact('products'));
	}

	/**
	 * Simpan transaksi masuk
	 */
	public function storeMasuk(Request $request)
	{
		try {
			// Validate basic transaction data
			$validated = $request->validate([
				'supplier_name' => 'nullable|string|max:100',
				'supplier_contact' => 'nullable|string|max:100',
				'lokasi' => 'nullable|string|max:100',
				'po_invoice' => 'nullable|string',
				'tanggal_transaksi' => 'required|date',
				'metode_pembayaran' => 'required|in:cash,non-cash',
				'nominal_bayar' => 'nullable|numeric',
				'kembalian' => 'nullable|numeric|min:0',
				'catatan' => 'nullable|string',
				'items' => 'required|json',
			]);

			// Parse and validate items
			$itemsData = json_decode($validated['items'], true);
			if (!is_array($itemsData) || count($itemsData) === 0) {
				return back()
					->with('error', 'Minimal harus ada 1 item transaksi')
					->withInput();
			}

			// Validate setiap item detail
			foreach ($itemsData as $index => $item) {
				$itemNum = $index + 1;
				
				// Validate product_id
				if (empty($item['product_id'])) {
					return back()
						->with('error', "Item #$itemNum: Produk harus dipilih")
						->withInput();
				}

				// Validate qty
				if (empty($item['qty']) || floatval($item['qty']) <= 0) {
					return back()
						->with('error', "Item #$itemNum: Jumlah harus diisi dan lebih dari 0")
						->withInput();
				}

				// Validate harga_beli
				if (empty($item['harga_beli']) || floatval($item['harga_beli']) <= 0) {
					return back()
						->with('error', "Item #$itemNum: Harga beli harus diisi dan lebih dari 0")
						->withInput();
				}

				// Validate product ownership
				$product = Product::where('id', $item['product_id'])
					->where('user_id', auth()->user()->id)
					->first();
				
				if (!$product) {
					return back()
						->with('error', "Item #$itemNum: Produk tidak ditemukan atau bukan milik Anda")
						->withInput();
				}
			}

			DB::transaction(function () use ($request, $validated, $itemsData, &$transaksiMasukRef) {
				// 1. Create Transaksi Masuk header
				$transaksiMasuk = TransaksiMasuk::create([
					'user_id' => auth()->user()->id,
					'reference_no' => TransaksiMasuk::generateNumber(),
					'supplier_name' => $validated['supplier_name'],
					'supplier_contact' => $validated['supplier_contact'],
					'lokasi' => $validated['lokasi'] ?? null,
					'po_invoice' => $validated['po_invoice'] ?? null,
					'tanggal_transaksi' => $validated['tanggal_transaksi'],
					'status' => 'complete',
					'metode_pembayaran' => $validated['metode_pembayaran'],
					'nominal_bayar' => $validated['nominal_bayar'] ?? 0,
					'kembalian' => $validated['kembalian'] ?? 0,
					'catatan' => $validated['catatan'] ?? null,
				]);

				\Log::info("StoreMasuk - Created transaksi header with ID: {$transaksiMasuk->id}");
				$transaksiMasukRef = $transaksiMasuk;

				$totalHarga = 0;

				// 2. Process setiap item
				foreach ($itemsData ?? [] as $index => $itemData) {
					$itemNum = $index + 1;
					\Log::info("StoreMasuk Item #$itemNum - Processing: " . json_encode($itemData));

					// Get product from master products
					$product = Product::where('id', $itemData['product_id'])
						->where('user_id', auth()->user()->id)
						->where('tipe', 'Barang') // Hanya Barang yang bisa dibeli
						->where('lacak_stok', true) // Hanya yang lacak stok
						->first();

					if (!$product) {
						throw new \Exception("Item #$itemNum: Produk tidak ditemukan");
					}

					$qty = floatval($itemData['qty'] ?? 0);
					$hargaBeli = floatval($itemData['harga_beli'] ?? 0);
					$diskon = floatval($itemData['diskon'] ?? 0);
					
					// Hitung subtotal: qty * harga_beli - diskon
					$itemTotal = TransaksiMasukItem::calculateTotal($qty, $hargaBeli, $diskon);

					// 3. Create transaction item record
					$masukItem = TransaksiMasukItem::create([
						'user_id' => auth()->user()->id,
						'transaksi_masuk_id' => $transaksiMasuk->id,
						'product_id' => $product->id,
						'qty' => $qty,
						'harga_beli' => $hargaBeli,
						'diskon' => $diskon,
						'total_harga' => $itemTotal,
						'deskripsi' => $itemData['deskripsi'] ?? null,
						// Field legacy (opsional, bisa diisi null)
						'berat' => null,
						'harga_gram' => null,
						'kadar' => null,
						'nomor_seri' => null,
					]);

					\Log::info("StoreMasuk Item #$itemNum - TransaksiMasukItem created with ID: {$masukItem->id}, Product ID: {$product->id}, Qty: {$qty}, Harga Beli: {$hargaBeli}");

					// 4. Update stok produk
					$product->stock += $qty;
					$product->save();

					\Log::info("StoreMasuk Item #$itemNum - Product stock updated: {$product->stock}");

					// 5. Calculate total for transaction
					$totalHarga += $itemTotal;
				}

				// Update total transaksi
				$transaksiMasuk->update(['total_harga' => $totalHarga]);
			});

			// Setelah simpan, arahkan ke halaman nota/print transaksi masuk
			return redirect()->route('transaksi.masuk.print', $transaksiMasukRef->id);

		} catch (\Illuminate\Validation\ValidationException $e) {
			return back()
				->withErrors($e->errors())
				->withInput();
		} catch (\Exception $e) {
			\Log::error('StoreMasuk Error: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
			return back()
				->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
				->withInput();
		}
	}

	/**
	 * === TRANSAKSI KELUAR ===
	 */

	/**
	 * Tampilkan daftar transaksi keluar
	 */
	public function keluar(Request $request)
	{
		// Hanya owner yang bisa akses riwayat penjualan
		if (auth()->user()->role !== 'owner') {
			abort(403, 'Akses ditolak. Hanya owner yang bisa melihat riwayat penjualan.');
		}

		$query = TransaksiKeluar::where('user_id', auth()->user()->id)
			->with(['items.product', 'admin'])
			->orderBy('created_at', 'desc');

		if (session()->has('admin_id')) {
			$query->where('admin_id', session('admin_id'));
		}

		$admins = null;
		if (!session()->has('admin_id')) {
			$admins = Admin::where('user_id', auth()->id())
				->orderBy('name')
				->get();

			if ($request->filled('admin_id')) {
				$adminId = $request->input('admin_id');
				if ($adminId === 'owner') {
					$query->whereNull('admin_id');
				} else {
					$query->where('admin_id', $adminId);
				}
			}
		}

		if ($search = $request->input('search')) {
			$query->where(function ($q) use ($search) {
				$q->where('invoice_no', 'like', "%{$search}%")
					->orWhere('customer_name', 'like', "%{$search}%")
					->orWhere('lokasi', 'like', "%{$search}%");
			});
		}

		$perPage = (int) $request->input('per_page', 20);
		if ($perPage <= 0) {
			$perPage = 20;
		}

		$transaksi = $query
			->paginate($perPage)
			->appends($request->only('search', 'per_page', 'admin_id'));

		return view('transaksi.keluar.index', compact('transaksi', 'admins'));
	}

	public function exportKeluarExcel(Request $request)
	{
		$query = TransaksiKeluar::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc');

		if (session()->has('admin_id')) {
			$query->where('admin_id', session('admin_id'));
		} elseif ($request->filled('admin_id')) {
			$adminId = $request->input('admin_id');
			if ($adminId === 'owner') {
				$query->whereNull('admin_id');
			} else {
				$query->where('admin_id', $adminId);
			}
		}

		if ($search = $request->input('search')) {
			$query->where(function ($q) use ($search) {
				$q->where('invoice_no', 'like', "%{$search}%")
					->orWhere('customer_name', 'like', "%{$search}%")
					->orWhere('lokasi', 'like', "%{$search}%");
			});
		}

		$rows = $query->get();

		$stream = fopen('php://memory', 'r+');
		fputs($stream, chr(0xEF) . chr(0xBB) . chr(0xBF));
		fputcsv($stream, ['Tanggal', 'No. Invoice', 'Pelanggan', 'Kota/Kabupaten', 'Total', 'Status'], ';');

		foreach ($rows as $t) {
			$status = $t->status ?? 'complete';
			$label = ucfirst($status);
			fputcsv($stream, [
				($t->tanggal_transaksi ?: $t->created_at)?->format('d/m/Y'),
				$t->invoice_no,
				$t->customer_name,
				$t->lokasi ?? '-',
				$t->total_harga ?? 0,
				$label,
			], ';');
		}

		rewind($stream);
		$csv = stream_get_contents($stream);
		fclose($stream);

		$fileName = 'transaksi_keluar_' . now()->format('Ymd_His') . '.csv';
		return response($csv, 200, [
			'Content-Type' => 'text/csv; charset=UTF-8',
			'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
		]);
	}

	public function exportKeluarPdf(Request $request)
	{
		$query = TransaksiKeluar::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc');

		if (session()->has('admin_id')) {
			$query->where('admin_id', session('admin_id'));
		} elseif ($request->filled('admin_id')) {
			$adminId = $request->input('admin_id');
			if ($adminId === 'owner') {
				$query->whereNull('admin_id');
			} else {
				$query->where('admin_id', $adminId);
			}
		}

		if ($search = $request->input('search')) {
			$query->where(function ($q) use ($search) {
				$q->where('invoice_no', 'like', "%{$search}%")
					->orWhere('customer_name', 'like', "%{$search}%")
					->orWhere('lokasi', 'like', "%{$search}%");
			});
		}

		$rows = $query->get();

		$pdf = Pdf::loadView('transaksi.keluar.export-pdf', [
			'transaksi' => $rows,
		]);

		$fileName = 'transaksi_keluar_' . now()->format('Ymd_His') . '.pdf';
		return $pdf->download($fileName);
	}

	/**
	 * Form tambah transaksi keluar
	 */
	public function createKeluar()
	{
		// Validasi shift aktif
		if (!session('active_shift_id')) {
			return redirect()->route('shift.create')
				->with('error', 'Silakan buka shift terlebih dahulu sebelum melakukan transaksi.');
		}

		$products = Product::where('user_id', auth()->user()->id)
			->where('is_active', true)
			->where('stock', '>', 0)
			->orderBy('nama_produk')
			->get();

		$pelanggan = \App\Models\Pelanggan::where('user_id', auth()->user()->id)
			->orderBy('nama')
			->get();

		return view('pos.cashier', compact('products', 'pelanggan'));
	}

	/**
	 * Simpan transaksi keluar
	 */
	public function storeKeluar(Request $request)
	{
		try {
			// Validate basic transaction data
			$validated = $request->validate([
				'customer_name' => 'required|string|max:100',
				'customer_contact' => 'nullable|string|max:100',
				'tanggal_transaksi' => 'required|date',
				'metode_pembayaran' => 'required|in:cash,non-cash',
				'lokasi' => 'nullable|string|max:100',
				'nominal_bayar' => 'nullable|numeric',
				'kembalian' => 'nullable|numeric|min:0',
				'catatan' => 'nullable|string',
				'items' => 'required|json',
			]);

			// Parse and validate items
			$itemsData = json_decode($validated['items'], true);
			if (!is_array($itemsData) || count($itemsData) === 0) {
				return back()
					->with('error', 'Minimal harus ada 1 item transaksi')
					->withInput();
			}

			// Validate setiap item
			foreach ($itemsData as $index => $item) {
				$itemNum = $index + 1;
				
				if (empty($item['product_id'])) {
					return back()->with('error', "Item #$itemNum: Produk harus dipilih")->withInput();
				}

				if (empty($item['qty_keluar']) || floatval($item['qty_keluar']) <= 0) {
					return back()->with('error', "Item #$itemNum: Jumlah harus diisi dan lebih dari 0")->withInput();
				}

				// Validate product ownership and stock
				$product = Product::where('id', $item['product_id'])
					->where('user_id', auth()->user()->id)
					->first();
				
				if (!$product) {
					return back()->with('error', "Item #$itemNum: Produk tidak ditemukan atau bukan milik Anda")->withInput();
				}

				$qty = floatval($item['qty_keluar']);

				// Validasi stok hanya untuk produk dengan lacak_stok = true
				if ($product->lacak_stok && $qty > $product->stock) {
					return back()->with('error', "Item #$itemNum: Stok tidak cukup. Stok tersedia: {$product->stock}")->withInput();
				}
			}

			DB::transaction(function () use ($request, $validated, $itemsData, &$transaksiKeluarRef) {
				// Tentukan apakah ini invoice (bayar nanti) atau cash
				$isInvoice = $request->has('is_invoice') || $validated['metode_pembayaran'] === 'non-cash';

				// 1. Create Transaksi Keluar header
				$transaksiKeluar = TransaksiKeluar::create([
					'admin_id' => session('admin_id'),
					'user_id' => auth()->user()->id,
					'invoice_no' => TransaksiKeluar::generateNumber(),
					'customer_name' => $validated['customer_name'],
					'customer_contact' => $validated['customer_contact'] ?? null,
					'tanggal_transaksi' => $validated['tanggal_transaksi'],
					'status' => 'complete',
					'lokasi' => $validated['lokasi'] ?? null,
					'metode_pembayaran' => $validated['metode_pembayaran'],
					'nominal_bayar' => $validated['nominal_bayar'] ?? 0,
					'kembalian' => $validated['kembalian'] ?? 0,
					'catatan' => $validated['catatan'] ?? null,
					'shift_id' => session('active_shift_id'),
					'is_invoice' => $isInvoice,
				]);

				$totalHarga = 0;

				// 2. Process setiap item
				foreach ($itemsData ?? [] as $itemData) {
					$product = Product::where('id', $itemData['product_id'])
						->where('user_id', auth()->user()->id)
						->firstOrFail();

					$qty = floatval($itemData['qty_keluar']);
					$hargaJual = floatval($itemData['harga_jual'] ?? $product->harga_jual ?? 0);
					$diskon = floatval($itemData['diskon'] ?? 0);

					// Capture HPP snapshot dari product (gunakan harga_beli atau 0 jika null)
					$hppSnapshot = floatval($product->harga_beli ?? 0);

					// Hitung subtotal
					$itemTotal = TransaksiKeluarItem::calculateTotal($qty, $hargaJual, $diskon);

					// Create transaction item record
					TransaksiKeluarItem::create([
						'user_id' => auth()->user()->id,
						'transaksi_keluar_id' => $transaksiKeluar->id,
						'product_id' => $product->id,
						'qty_keluar' => $qty,
						'harga_jual' => $hargaJual,
						'diskon' => $diskon,
						'total_harga' => $itemTotal,
						'biaya_tambahan' => 0,
						'nomor_seri' => null, // Legacy - tidak digunakan untuk produk umum
						'hpp_snapshot' => $hppSnapshot,
					]);

					// Update stok produk hanya jika lacak_stok = true
					if ($product->lacak_stok) {
						$product->stock -= $qty;
						$product->save();
					}

					$totalHarga += $itemTotal;
				}

				// Update total transaksi
				$transaksiKeluar->update(['total_harga' => $totalHarga]);
				$transaksiKeluarRef = $transaksiKeluar;
			});

			// Setelah simpan, arahkan ke halaman nota/print transaksi keluar
			return redirect()->route('transaksi.keluar.print', $transaksiKeluarRef->id);

		} catch (\Illuminate\Validation\ValidationException $e) {
			return back()
				->withErrors($e->errors())
				->withInput();
		} catch (\Exception $e) {
			\Log::error('StoreKeluar Error: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
			return back()
				->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
				->withInput();
		}
	}

	/**
	 * Void/Batalkan transaksi keluar
	 */
	public function voidTransaksi(Request $request, $id)
	{
		$validated = $request->validate([
			'void_reason' => 'required|string|max:255',
		]);

		$transaksi = TransaksiKeluar::where('id', $id)
			->where('user_id', auth()->user()->id)
			->firstOrFail();

		if ($transaksi->is_void) {
			return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.');
		}

		DB::transaction(function () use ($transaksi, $validated) {
			// Kembalikan stok ke produk
			foreach ($transaksi->items as $item) {
				$product = Product::where('id', $item->product_id)
					->where('user_id', auth()->user()->id)
					->first();

				if ($product) {
					$product->stock += $item->qty_keluar;
					$product->save();
				}
			}

			// Update status transaksi menjadi void
			$transaksi->update([
				'is_void' => true,
				'void_reason' => $validated['void_reason'],
				'voided_at' => now(),
			]);
		});

		return redirect()->route('transaksi.keluar')->with('success', 'Transaksi berhasil dibatalkan. Stok telah dikembalikan.');
	}

	/**
	 * Hapus transaksi masuk beserta item-itemnya dan produk
	 */
	public function destroyMasuk(TransaksiMasuk $transaksiMasuk)
	{
		try {
			// Get all product IDs associated with this transaksi masuk
			$productIds = TransaksiMasukItem::where('transaksi_masuk_id', $transaksiMasuk->id)
				->pluck('product_id')
				->unique()
				->toArray();

			// Delete product serial numbers for these products
			if (!empty($productIds)) {
				ProductSerialNumber::whereIn('product_id', $productIds)->delete();
				// Delete the products themselves
				Product::whereIn('id', $productIds)->delete();
			}

			// Delete the transaksi masuk (will cascade delete items and biaya_tambahan)
			$transaksiMasuk->delete();

			return redirect()->route('transaksi.masuk')
				->with('success', 'Transaksi masuk beserta produk berhasil dihapus');
		} catch (\Exception $e) {
			\Log::error('DestroyMasuk Error: ' . $e->getMessage());
			return back()->with('error', 'Gagal menghapus transaksi masuk: ' . $e->getMessage());
		}
	}

	public function bulkDestroyMasuk(Request $request)
	{
		$ids = $request->input('ids', []);
		if (is_string($ids)) {
			$decoded = json_decode($ids, true);
			if (json_last_error() === JSON_ERROR_NONE) {
				$ids = $decoded;
			} else {
				$ids = array_filter(array_map('trim', explode(',', $ids)));
			}
		}
		if (!is_array($ids)) {
			$ids = [];
		}

		if (empty($ids) || !is_array($ids)) {
			return redirect()->route('transaksi.masuk')->with('error', 'Tidak ada transaksi yang dipilih.');
		}

		TransaksiMasuk::whereIn('id', $ids)->each(function ($transaksi) {
			$transaksi->delete();
		});

		return redirect()->route('transaksi.masuk')->with('success', 'Berhasil menghapus ' . count($ids) . ' transaksi masuk.');
	}

	/**
	 * Form edit transaksi keluar
	 */
	public function editKeluar(TransaksiKeluar $transaksiKeluar)
	{
		return view('transaksi.keluar.edit', compact('transaksiKeluar'));
	}

	/**
	 * Update header transaksi keluar (tanpa ubah item)
	 */
	public function updateKeluar(Request $request, TransaksiKeluar $transaksiKeluar)
	{
		try {
			$validated = $request->validate([
				'customer_name' => 'required|string|max:100',
				'customer_contact' => 'nullable|string|max:100',
				'tanggal_transaksi' => 'required|date',
				'status' => 'required|in:pending,complete,cancel',
				// gunakan key "lokasi" (lowercase) agar konsisten dengan form dan kolom database
				'lokasi' => 'nullable|string|max:100',
				'metode_pembayaran' => 'required|in:cash,non-cash',
				'nominal_bayar' => 'nullable|numeric',
				'catatan' => 'nullable|string',
			]);

			$transaksiKeluar->customer_name = $validated['customer_name'];
			$transaksiKeluar->customer_contact = $validated['customer_contact'] ?? null;
			$transaksiKeluar->tanggal_transaksi = $validated['tanggal_transaksi'];
			$transaksiKeluar->status = $validated['status'];
			$transaksiKeluar->lokasi = $validated['lokasi'] ?? null;
			$transaksiKeluar->metode_pembayaran = $validated['metode_pembayaran'];
			$transaksiKeluar->nominal_bayar = $validated['nominal_bayar'] ?? 0;
			$transaksiKeluar->catatan = $validated['catatan'] ?? null;
			$transaksiKeluar->save();

			return redirect()->route('transaksi.keluar')
				->with('success', 'Transaksi keluar berhasil diperbarui');
		} catch (\Illuminate\Validation\ValidationException $e) {
			return back()
				->withErrors($e->errors())
				->withInput();
		} catch (\Exception $e) {
			\Log::error('UpdateKeluar Error: ' . $e->getMessage());
			return back()->with('error', 'Gagal memperbarui transaksi keluar');
		}
	}

	/**
	 * Tampilkan nota/print transaksi masuk
	 */
	public function printMasuk(TransaksiMasuk $transaksiMasuk)
	{
		$transaksiMasuk->load('items.product');
		$user = auth()->user();
		$profile = \App\Models\Profile::where('user_id', $user->id)->first();
		return view('transaksi.masuk.nota', compact('transaksiMasuk', 'profile', 'user'));
	}

	/**
	 * Hapus transaksi keluar beserta item-itemnya
	 */
	public function destroyKeluar(TransaksiKeluar $transaksiKeluar)
	{
		try {
			$transaksiKeluar->delete();
			return redirect()->route('transaksi.keluar')
				->with('success', 'Transaksi keluar berhasil dihapus');
		} catch (\Exception $e) {
			\Log::error('DestroyKeluar Error: ' . $e->getMessage());
			return back()->with('error', 'Gagal menghapus transaksi keluar');
		}
	}

	public function bulkDestroyKeluar(Request $request)
	{
		$ids = $request->input('ids', []);
		if (is_string($ids)) {
			$decoded = json_decode($ids, true);
			if (json_last_error() === JSON_ERROR_NONE) {
				$ids = $decoded;
			} else {
				$ids = array_filter(array_map('trim', explode(',', $ids)));
			}
		}
		if (!is_array($ids)) {
			$ids = [];
		}

		if (empty($ids) || !is_array($ids)) {
			return redirect()->route('transaksi.keluar')->with('error', 'Tidak ada transaksi yang dipilih.');
		}

		TransaksiKeluar::whereIn('id', $ids)->each(function ($transaksi) {
			$transaksi->delete();
		});

		return redirect()->route('transaksi.keluar')->with('success', 'Berhasil menghapus ' . count($ids) . ' transaksi keluar.');
	}

	/**
	 * Tampilkan nota/print transaksi keluar
	 */
	public function printKeluar(TransaksiKeluar $transaksiKeluar)
	{
		$transaksiKeluar->load('items.product', 'biayaTambahan');
		$user = auth()->user();
		$profile = \App\Models\Profile::where('user_id', $user->id)->first();
		return view('transaksi.keluar.nota', compact('transaksiKeluar', 'profile', 'user'));
	}

	/**
	 * === API ENDPOINTS (untuk cascading dropdown) ===
	 */

	/**
	 * Get brands by jenis_logam
	 */
	public function getBrands($jenisLogam)
	{
		$brands = Product::where('user_id', auth()->user()->id)
			->where('jenis_logam', $jenisLogam)
			->distinct()
			->pluck('brand');

		return response()->json($brands);
	}

	/**
	 * Get weights by jenis_logam dan brand
	 */
	public function getWeights($jenisLogam, $brand)
	{
		$weights = Product::where('user_id', auth()->user()->id)
			->where('jenis_logam', $jenisLogam)
			->where('brand', $brand)
			->distinct()
			->pluck('berat_standar');

		return response()->json($weights);
	}

	/**
	 * Get products by jenis_logam, brand, dan berat - with available stock only for outgoing transaction
	 */
	public function getProducts($jenisLogam, $brand, $berat)
	{
		$products = Product::where('user_id', auth()->user()->id)
			->where('jenis_logam', $jenisLogam)
			->where('brand', $brand)
			->where('berat_standar', $berat)
			->get()
			->filter(function ($product) {
				return $product->calculateStock() > 0;
			})
			->values();

		return response()->json($products->map(function ($product) {
			return [
				'id' => $product->id,
				'nama_produk' => $product->nama_produk,
				'stok' => $product->calculateStock(),
				'harga_beli' => $product->harga_beli,
				'harga_jual' => $product->harga_jual,
				'berat_standar' => $product->berat_standar,
			];
		}));
	}

	/**
	 * Get available serial numbers by product_id
	 */
	public function getSerialNumbers($productId)
	{
		$serials = ProductSerialNumber::where('product_id', $productId)
			->where('status', 'available')
			->pluck('nomor_seri');

		return response()->json($serials);
	}

	/**
	 * Get all products with available stock (untuk transaksi keluar)
	 */
	public function getAllProducts()
	{
		$products = Product::where('user_id', auth()->user()->id)
			->get()
			->filter(function ($product) {
				return $product->calculateStock() > 0;
			})
			->values();

		return response()->json($products->map(function ($product) {
			return [
				'id' => $product->id,
				'nama_produk' => $product->nama_produk,
				'stok' => $product->calculateStock(),
				'harga_beli' => $product->harga_beli,
				'harga_jual' => $product->harga_jual,
				'berat_standar' => $product->berat_standar,
				'jenis_logam' => $product->jenis_logam,
				'brand' => $product->brand,
			];
		}));
	}

	/**
	 * Get products by jenis_logam with available stock
	 */
	public function getProductsByJenisLogam($jenisLogam)
	{
		$products = Product::where('user_id', auth()->user()->id)
			->where('jenis_logam', $jenisLogam)
			->get()
			->filter(function ($product) {
				return $product->calculateStock() > 0;
			})
			->values();

		// Ambil semua nomor seri available untuk product_id yang dimaksud dalam satu query
		$serialsByProduct = ProductSerialNumber::available()
			->whereIn('product_id', $products->pluck('id'))
			->get()
			->groupBy('product_id');

		return response()->json($products->map(function ($product) use ($serialsByProduct) {
			$serials = $serialsByProduct->get($product->id, collect())->pluck('nomor_seri')->values();

			return [
				'id' => $product->id,
				'nama_produk' => $product->nama_produk,
				'stok' => $product->calculateStock(),
				'harga_beli' => $product->harga_beli,
				'harga_jual' => $product->harga_jual,
				'berat_standar' => $product->berat_standar,
				'brand' => $product->brand,
				'serial_numbers' => $serials,
			];
		}));
	}

	/**
	 * === LAPORAN ===
	 */

	public function laporan()
	{
		$transaksiMasuk = TransaksiMasuk::where('user_id', auth()->user()->id)->count();
		$transaksiKeluar = TransaksiKeluar::where('user_id', auth()->user()->id)->count();

		return view('transaksi.laporan.index', compact('transaksiMasuk', 'transaksiKeluar'));
	}

	/**
	 * === LOG RIWAYAT TRANSAKSI ===
	 */

	public function logTransaksi(Request $request)
	{
		$tipe = $request->input('tipe');
		$search = $request->input('search');
		$startDate = $request->input('start_date');
		$endDate = $request->input('end_date');
		$perPage = (int) $request->input('per_page', 20);

		if ($perPage <= 0) {
			$perPage = 20;
		}

		// Get transaksi masuk
		$masukHistories = TransaksiMasuk::where('user_id', auth()->user()->id)->get()->map(function ($item) {
			return (object)[
				'tipe' => 'masuk',
				'tanggal' => $item->tanggal_transaksi,
				'referensi_no' => $item->reference_no,
				'supplier_name' => $item->supplier_name,
				'customer_name' => null,
				'keterangan' => $item->supplier_name,
				'kategori' => null,
				'lokasi' => $item->lokasi,
				'nominal' => $item->total_harga,
				'status' => $item->status,
				'id' => 'masuk-' . $item->id,
			];
		});

		// Get transaksi keluar
		$keluarHistories = TransaksiKeluar::where('user_id', auth()->user()->id)->get()->map(function ($item) {
			return (object)[
				'tipe' => 'keluar',
				'tanggal' => $item->tanggal_transaksi,
				'referensi_no' => $item->invoice_no,
				'supplier_name' => null,
				'customer_name' => $item->customer_name,
				'keterangan' => $item->customer_name,
				'kategori' => null,
				'lokasi' => $item->lokasi,
				'nominal' => $item->total_harga,
				'status' => $item->status,
				'id' => 'keluar-' . $item->id,
			];
		});

		// Get expenses
		$expensesHistories = Expenses::where('user_id', auth()->user()->id)->get()->map(function ($item) {
			return (object)[
				'tipe' => 'expenses',
				'tanggal' => $item->tanggal,
				'referensi_no' => $item->reference_no,
				'supplier_name' => null,
				'customer_name' => null,
				'keterangan' => $item->keterangan,
				'kategori' => $item->kategori,
				'lokasi' => null,
				'nominal' => $item->nominal,
				'status' => 'complete',
				'id' => 'expenses-' . $item->id,
			];
		});

		// Merge all
		$allHistories = collect(array_merge(
			$masukHistories->all(),
			$keluarHistories->all(),
			$expensesHistories->all()
		));

		// Apply filters
		if ($tipe) {
			$allHistories = $allHistories->filter(function ($item) use ($tipe) {
				return $item->tipe === $tipe;
			});
		}

		if ($search) {
			$search = strtolower($search);
			$allHistories = $allHistories->filter(function ($item) use ($search) {
				return strpos(strtolower($item->referensi_no ?? ''), $search) !== false ||
					   strpos(strtolower($item->supplier_name ?? ''), $search) !== false ||
					   strpos(strtolower($item->customer_name ?? ''), $search) !== false ||
					   strpos(strtolower($item->keterangan ?? ''), $search) !== false;
			});
		}

		if ($startDate) {
			$allHistories = $allHistories->filter(function ($item) use ($startDate) {
				return $item->tanggal >= $startDate;
			});
		}

		if ($endDate) {
			$allHistories = $allHistories->filter(function ($item) use ($endDate) {
				return $item->tanggal <= $endDate;
			});
		}

		// Sort by date descending
		$allHistories = $allHistories->sortByDesc('tanggal');

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
				'path' => route('transaksi.log'),
				'query' => $request->only('tipe', 'search', 'start_date', 'end_date', 'per_page'),
			]
		);

		return view('transaksi.log', compact('histories'));
	}

	/**
	 * Export log transaksi ke Excel
	 */
	public function logTransaksiExportExcel(Request $request)
	{
		$tipe = $request->input('tipe');
		$search = $request->input('search');
		$startDate = $request->input('start_date');
		$endDate = $request->input('end_date');

		// Get transaksi masuk
		$masukHistories = TransaksiMasuk::where('user_id', auth()->user()->id)->get()->map(function ($item) {
			return (object)[
				'tipe' => 'masuk',
				'tanggal' => $item->tanggal_transaksi,
				'referensi_no' => $item->reference_no,
				'supplier_name' => $item->supplier_name,
				'customer_name' => null,
				'keterangan' => $item->supplier_name,
				'kategori' => null,
				'lokasi' => $item->lokasi,
				'nominal' => $item->total_harga,
				'status' => $item->status,
			];
		});

		// Get transaksi keluar
		$keluarHistories = TransaksiKeluar::where('user_id', auth()->user()->id)->get()->map(function ($item) {
			return (object)[
				'tipe' => 'keluar',
				'tanggal' => $item->tanggal_transaksi,
				'referensi_no' => $item->invoice_no,
				'supplier_name' => null,
				'customer_name' => $item->customer_name,
				'keterangan' => $item->customer_name,
				'kategori' => null,
				'lokasi' => $item->lokasi,
				'nominal' => $item->total_harga,
				'status' => $item->status,
			];
		});

		// Get expenses
		$expensesHistories = Expenses::where('user_id', auth()->user()->id)->get()->map(function ($item) {
			return (object)[
				'tipe' => 'expenses',
				'tanggal' => $item->tanggal,
				'referensi_no' => $item->reference_no,
				'supplier_name' => null,
				'customer_name' => null,
				'keterangan' => $item->keterangan,
				'kategori' => $item->kategori,
				'lokasi' => null,
				'nominal' => $item->nominal,
				'status' => 'complete',
			];
		});

		// Merge and filter
		$allHistories = collect(array_merge(
			$masukHistories->all(),
			$keluarHistories->all(),
			$expensesHistories->all()
		));

		if ($tipe) {
			$allHistories = $allHistories->filter(function ($item) use ($tipe) {
				return $item->tipe === $tipe;
			});
		}

		if ($search) {
			$search = strtolower($search);
			$allHistories = $allHistories->filter(function ($item) use ($search) {
				return strpos(strtolower($item->referensi_no ?? ''), $search) !== false ||
					   strpos(strtolower($item->supplier_name ?? ''), $search) !== false ||
					   strpos(strtolower($item->customer_name ?? ''), $search) !== false;
			});
		}

		if ($startDate) {
			$allHistories = $allHistories->filter(function ($item) use ($startDate) {
				return $item->tanggal >= $startDate;
			});
		}

		if ($endDate) {
			$allHistories = $allHistories->filter(function ($item) use ($endDate) {
				return $item->tanggal <= $endDate;
			});
		}

		$allHistories = $allHistories->sortByDesc('tanggal');

		// Generate CSV
		$stream = fopen('php://memory', 'r+');
		fputs($stream, chr(0xEF) . chr(0xBB) . chr(0xBF));
		fputcsv($stream, ['Tanggal', 'Tipe', 'No. Referensi', 'Keterangan', 'Lokasi/Kategori', 'Nominal', 'Status'], ';');

		foreach ($allHistories as $history) {
			$statusLabel = $history->status === 'complete'
				? 'Selesai'
				: ($history->status === 'pending' ? 'Pending' : ($history->status === 'reopened' ? 'Reopened' : 'Dibatalkan'));
			fputcsv($stream, [
				Carbon::parse($history->tanggal)->format('d/m/Y'),
				strtoupper($history->tipe),
				$history->referensi_no,
				$history->keterangan,
				$history->lokasi ?? $history->kategori ?? '-',
				$history->nominal,
				$statusLabel,
			], ';');
		}

		rewind($stream);
		$csv = stream_get_contents($stream);
		fclose($stream);

		$fileName = 'riwayat_transaksi_' . now()->format('Ymd_His') . '.csv';
		return response($csv, 200, [
			'Content-Type' => 'text/csv; charset=UTF-8',
			'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
		]);
	}

	/**
	 * Export log transaksi ke PDF
	 */
	public function logTransaksiExportPdf(Request $request)
	{
		$tipe = $request->input('tipe');
		$search = $request->input('search');
		$startDate = $request->input('start_date');
		$endDate = $request->input('end_date');

		// Get transaksi masuk
		$masukHistories = TransaksiMasuk::where('user_id', auth()->user()->id)->get()->map(function ($item) {
			return (object)[
				'tipe' => 'masuk',
				'tanggal' => $item->tanggal_transaksi,
				'referensi_no' => $item->reference_no,
				'supplier_name' => $item->supplier_name,
				'customer_name' => null,
				'keterangan' => $item->supplier_name,
				'kategori' => null,
				'lokasi' => $item->lokasi,
				'nominal' => $item->total_harga,
				'status' => $item->status,
			];
		});

		// Get transaksi keluar
		$keluarHistories = TransaksiKeluar::where('user_id', auth()->user()->id)->get()->map(function ($item) {
			return (object)[
				'tipe' => 'keluar',
				'tanggal' => $item->tanggal_transaksi,
				'referensi_no' => $item->invoice_no,
				'supplier_name' => null,
				'customer_name' => $item->customer_name,
				'keterangan' => $item->customer_name,
				'kategori' => null,
				'lokasi' => $item->lokasi,
				'nominal' => $item->total_harga,
				'status' => $item->status,
			];
		});

		// Get expenses
		$expensesHistories = Expenses::where('user_id', auth()->user()->id)->get()->map(function ($item) {
			return (object)[
				'tipe' => 'expenses',
				'tanggal' => $item->tanggal,
				'referensi_no' => $item->reference_no,
				'supplier_name' => null,
				'customer_name' => null,
				'keterangan' => $item->keterangan,
				'kategori' => $item->kategori,
				'lokasi' => null,
				'nominal' => $item->nominal,
				'status' => 'complete',
			];
		});

		// Merge and filter
		$allHistories = collect(array_merge(
			$masukHistories->all(),
			$keluarHistories->all(),
			$expensesHistories->all()
		));

		if ($tipe) {
			$allHistories = $allHistories->filter(function ($item) use ($tipe) {
				return $item->tipe === $tipe;
			});
		}

		if ($search) {
			$search = strtolower($search);
			$allHistories = $allHistories->filter(function ($item) use ($search) {
				return strpos(strtolower($item->referensi_no ?? ''), $search) !== false ||
					   strpos(strtolower($item->supplier_name ?? ''), $search) !== false ||
					   strpos(strtolower($item->customer_name ?? ''), $search) !== false;
			});
		}

		if ($startDate) {
			$allHistories = $allHistories->filter(function ($item) use ($startDate) {
				return $item->tanggal >= $startDate;
			});
		}

		if ($endDate) {
			$allHistories = $allHistories->filter(function ($item) use ($endDate) {
				return $item->tanggal <= $endDate;
			});
		}

		$allHistories = $allHistories->sortByDesc('tanggal');

		$pdf = Pdf::loadView('transaksi.log-pdf', [
			'histories' => $allHistories,
		]);

		$fileName = 'riwayat_transaksi_' . now()->format('Ymd_His') . '.pdf';
		return $pdf->download($fileName);
	}
}
