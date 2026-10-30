<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TransaksiKeluar;
use App\Models\TransaksiKeluarItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class TransaksiController extends BaseController
{
	/**
	 * Tampilkan daftar transaksi keluar (Riwayat Penjualan)
	 */
	public function keluar(Request $request)
	{
		// Hanya owner yang bisa akses riwayat penjualan
		if (auth()->user()->role !== 'owner') {
			abort(403, 'Akses ditolak. Hanya owner yang bisa melihat riwayat penjualan.');
		}

		$query = TransaksiKeluar::where('user_id', auth()->user()->id)
			->with(['items.product'])
			->orderBy('created_at', 'desc');

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
			->appends($request->only('search', 'per_page'));

		return view('transaksi.keluar.index', compact('transaksi'));
	}

	public function exportKeluarExcel(Request $request)
	{
		$query = TransaksiKeluar::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc');

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
	 * Form tambah transaksi keluar (POS Kasir)
	 */
	public function createKeluar()
	{
		// Shift dihapus, tidak perlu validasi shift aktif
		$products = Product::where('user_id', auth()->user()->id)
			->where('is_active', true)
			->orderBy('nama_produk')
			->get();

		return view('pos.cashier', compact('products'));
	}

	/**
	 * Simpan transaksi keluar
	 */
	public function storeKeluar(Request $request)
	{
		try {
			// Validate basic transaction data
			$validated = $request->validate([
				'customer_name' => 'nullable|string|max:100',
				'tanggal_transaksi' => 'required|date',
				'metode_pembayaran' => 'required|in:cash,non-cash',
				'nominal_bayar' => 'nullable|integer',
				'kembalian' => 'nullable|integer|min:0',
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
				// 1. Create Transaksi Keluar header
				$transaksiKeluar = TransaksiKeluar::create([
					'user_id' => auth()->user()->id,
					'invoice_no' => TransaksiKeluar::generateNumber(),
					'customer_name' => $validated['customer_name'] ?? null,
					'tanggal_transaksi' => $validated['tanggal_transaksi'],
					'status' => 'complete',
					'metode_pembayaran' => $validated['metode_pembayaran'],
					'nominal_bayar' => $validated['nominal_bayar'] ?? 0,
					'kembalian' => $validated['kembalian'] ?? 0,
					'catatan' => $validated['catatan'] ?? null,
					// shift_id dihapus karena fitur shift dihapus
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
		// Table profiles sudah dihapus, gunakan data dari user
		$storeName = 'Aksara';
		return view('transaksi.keluar.nota', compact('transaksiKeluar', 'storeName', 'user'));
	}
}
