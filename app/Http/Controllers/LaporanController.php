<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMasuk;
use App\Models\TransaksiKeluar;
use App\Models\Expenses;
use App\Models\TransaksiMasukItem;
use App\Models\TransaksiKeluarItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends BaseController
{
	public function ringkasanKeuangan(Request $request)
	{
		if (session()->has('admin_id')) {
			abort(403, 'Akses ditolak.');
		}

		$period = $request->input('period', 'month');
		$startDate = $request->input('start_date');
		$endDate = $request->input('end_date');

		// Jika bukan custom, atau custom tapi tanggal tidak lengkap, gunakan range bawaan
		if ($period !== 'custom') {
			[$startDate, $endDate] = $this->resolvePeriodRange($period);
		} else {
			if (empty($startDate) || empty($endDate)) {
				[$startDate, $endDate] = $this->resolvePeriodRange('month');
				$period = 'month';
			}
		}

		// Normalisasi ke Carbon
		$start = Carbon::parse($startDate)->startOfDay();
		$end = Carbon::parse($endDate)->endOfDay();

		// Transaksi keluar (penjualan)
		$salesQuery = TransaksiKeluar::where('user_id', auth()->user()->id)
			->whereBetween('tanggal_transaksi', [$start, $end])
			->where('status', 'complete');

		$totalRevenue = (float) $salesQuery->sum('total_harga');
		$totalSalesCount = (int) $salesQuery->count();

		// HPP / COGS: pendekatan sederhana dari total pembelian complete di periode
		$purchasesQuery = TransaksiMasuk::where('user_id', auth()->user()->id)
			->whereBetween('tanggal_transaksi', [$start, $end])
			->where('status', 'complete');
		$totalHPP = (float) $purchasesQuery->sum('total_harga');

		$grossProfit = $totalRevenue - $totalHPP;
		$grossMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0.0;

		// Operating expenses dari tabel expenses
		$expensesQuery = Expenses::where('user_id', auth()->user()->id)
			->whereBetween('tanggal', [$start, $end]);
		$totalOperatingExpenses = (float) $expensesQuery->sum('nominal');

		$netProfit = $grossProfit - $totalOperatingExpenses;
		$netMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0.0;

		// Cashflow (Cash Flow Method)
		// Cash In: Total Revenue dari penjualan complete
		$cashIn = $totalRevenue;

		// Cash Out: HPP + Operating Expenses
		// HPP adalah cost of goods sold (dari total pembelian)
		// Operating Expenses dari tabel expenses
		$cashOut = $totalHPP + $totalOperatingExpenses;

		$freeCashFlow = $cashIn - $cashOut;

		// Breakdown metode pembayaran dari penjualan
		$paymentBreakdown = TransaksiKeluar::whereBetween('tanggal_transaksi', [$start, $end])
			->where('status', 'complete')
			->selectRaw('metode_pembayaran, SUM(total_harga) as total')
			->groupBy('metode_pembayaran')
			->pluck('total', 'metode_pembayaran')
			->toArray();

		// Expenses by type (kategori)
		$expensesByType = (clone $expensesQuery)
			->selectRaw('kategori, SUM(nominal) as total')
			->groupBy('kategori')
			->pluck('total', 'kategori')
			->toArray();

		$recentExpenses = (clone $expensesQuery)
			->orderByDesc('tanggal')
			->limit(5)
			->get();

		return view('laporan.ringkasan-keuangan', [
			'period' => $period,
			'startDate' => $start->toDateString(),
			'endDate' => $end->toDateString(),
			'totalRevenue' => $totalRevenue,
			'totalSalesCount' => $totalSalesCount,
			'totalHPP' => $totalHPP,
			'grossProfit' => $grossProfit,
			'grossMargin' => $grossMargin,
			'totalOperatingExpenses' => $totalOperatingExpenses,
			'netProfit' => $netProfit,
			'netMargin' => $netMargin,
			'cashIn' => $cashIn,
			'cashOut' => $cashOut,
			'freeCashFlow' => $freeCashFlow,

			'paymentBreakdown' => $paymentBreakdown,
			'expensesByType' => $expensesByType,
			'expenses' => $recentExpenses,
		]);
	}

	protected function resolvePeriodRange(string $period): array
	{
		$today = Carbon::today();

		return match ($period) {
			'today' => [$today, $today],
			'week' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
			'year' => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
			'all' => ['2000-01-01', Carbon::now()],
			default => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
		};
	}

	/**
	 * Export tracking produk dari pembelian hingga penjualan
	 * 
	 * @param Request $request
	 * Params: start_date, end_date, pemasok (opsional), status (all/sold/unsold)
	 * 
	 * Kolom Excel/CSV:
	 * A: Tanggal Beli
	 * B: Pemasok
	 * C: Kontak Pemasok
	 * D: Kota/Kabupaten
	 * E: Gramasi (berat produk)
	 * F: Jenis Logam (emas/perak)
	 * G: Gramasi/Seri (format: berat/serial_number)
	 * H: Nama Customer (kosong jika belum terjual)
	 * I: Kontak Customer (kosong jika belum terjual)
	 * J: Kota/Kabupaten (kosong jika belum terjual)
	 * K: Tanggal Terjual (kosong jika belum terjual)
	 */
	public function exportTrackingProduk(Request $request)
	{
		$startDate = $request->input('start_date', Carbon::now()->startOfMonth());
		$endDate = $request->input('end_date', Carbon::now()->endOfMonth());
		$pemasok = $request->input('pemasok'); // opsional, filter by supplier
		$status = $request->input('status', 'all'); // all, sold, unsold

		$startDate = Carbon::parse($startDate)->startOfDay();
		$endDate = Carbon::parse($endDate)->endOfDay();

		$data = [];
		
		// Header row
		$data[] = [
			'Tanggal Beli',
			'Pemasok',
			'Kontak Pemasok',
			'Kota/Kabupaten',
			'Gramasi',
			'Jenis Logam',
			'Gramasi/Seri',
			'Nama Customer',
			'Kontak Customer',
			'Kota/Kabupaten',
			'Tanggal Terjual'
		];

		// Preload semua transaksi keluar items + mapping nomor_seri ke item untuk pencarian yang lebih cepat dan stabil
		$keluarItems = TransaksiKeluarItem::with('transaksi')
			->whereHas('transaksi', function ($query) {
				$query->where('user_id', auth()->user()->id);
			})
			->get();
		$serialToKeluarItem = [];
		foreach ($keluarItems as $item) {
			$serialsKeluar = is_array($item->nomor_seri)
				? $item->nomor_seri
				: json_decode($item->nomor_seri ?? '[]', true) ?? [];

			foreach ($serialsKeluar as $seriKeluar) {
				$norm = strtolower(trim($seriKeluar));
				if ($norm === '') continue;
				if (!isset($serialToKeluarItem[$norm])) {
					$serialToKeluarItem[$norm] = $item;
				}
			}
		}

		// Query semua transaksi masuk items dengan filter tanggal
		$masukItems = TransaksiMasukItem::with('transaksi', 'product')
			->whereHas('transaksi', function ($query) use ($startDate, $endDate, $pemasok) {
				$query->whereBetween('tanggal_transaksi', [$startDate, $endDate])
					->where('status', 'complete')
					->where('user_id', auth()->user()->id);
				if ($pemasok) {
					$query->where('supplier_name', 'like', '%' . $pemasok . '%');
				}
			})
			->orderBy('created_at', 'asc')
			->get();

		foreach ($masukItems as $masukItem) {
			// Dapatkan nomor_seri array dari item pembelian
			$serials = is_array($masukItem->nomor_seri) ? $masukItem->nomor_seri : json_decode($masukItem->nomor_seri ?? '[]', true) ?? [];
			
			$transaksiMasuk = $masukItem->transaksi;
			$product = $masukItem->product;

			// Untuk setiap serial number dalam item, buat satu baris di excel
			foreach ($serials as $serial) {
				$normSerial = strtolower(trim($serial));
				$keluarItem = $serialToKeluarItem[$normSerial] ?? null;

				$tanggalJualText = null;
				$namaCustomer = null;
				$kontakCustomer = null;
				$kotaKabupatenJual = null;

				if ($keluarItem && $keluarItem->transaksi) {
					$transaksiKeluar = $keluarItem->transaksi;
					$tanggalJualText = $transaksiKeluar->tanggal_transaksi->format('m/d/y');
					$namaCustomer = $transaksiKeluar->customer_name;
					$kontakCustomer = $transaksiKeluar->customer_contact;
					$kotaKabupatenJual = $transaksiKeluar->lokasi;
				}

				// Filter berdasarkan status
				$isSold = !is_null($namaCustomer);
				if ($status === 'sold' && !$isSold) {
					continue; // Skip jika hanya filter sold dan belum terjual
				}
				if ($status === 'unsold' && $isSold) {
					continue; // Skip jika hanya filter unsold dan sudah terjual
				}

				// Pastikan nomor telepon diekspor sebagai teks agar nol di depan tidak hilang di Excel
				$supplierContact = $transaksiMasuk->supplier_contact;
				if (!is_null($supplierContact) && $supplierContact !== '') {
					$supplierContact = "'" . $supplierContact;
				}

				$customerContact = $kontakCustomer;
				if (!is_null($customerContact) && $customerContact !== '') {
					$customerContact = "'" . $customerContact;
				}

				// Build row
				$row = [
					$transaksiMasuk->tanggal_transaksi->format('m/d/y'),     // A: Tanggal Beli
					$transaksiMasuk->supplier_name,                         // B: Pemasok
					$supplierContact,                                       // C: Kontak Pemasok
					$transaksiMasuk->lokasi,                               // D: Kota/Kabupaten
					$masukItem->berat,                                      // E: Gramasi
					$product?->jenis_logam,                                 // F: Jenis Logam
					$masukItem->berat . '/' . $serial,                      // G: Gramasi/Seri
					$namaCustomer,                                          // H: Nama Customer
					$customerContact,                                       // I: Kontak Customer
					$kotaKabupatenJual,                                     // J: Kota/Kabupaten
					$tanggalJualText,                                       // K: Tanggal Terjual
				];

				$data[] = $row;
			}
		}

		// Generate CSV filename
		$fileName = 'tracking-produk_' . $startDate->format('Ymd') . '_' . $endDate->format('Ymd') . '.csv';

		// Create streamed response for CSV download
		$stream = fopen('php://memory', 'r+');

		// Write BOM for UTF-8 (agar Excel bisa baca karakter Indonesia)
		fputs($stream, chr(0xEF) . chr(0xBB) . chr(0xBF));

		// Write CSV rows dengan delimiter ; supaya Excel lokal (ID) terbaca kolomnya
		foreach ($data as $row) {
			fputcsv($stream, $row, ';');
		}

		rewind($stream);
		$csv = stream_get_contents($stream);
		fclose($stream);

		return response($csv, 200, [
			'Content-Type' => 'text/csv; charset=UTF-8',
			'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
		]);
	}

	public function labaRugi(Request $request)
	{
		if (session()->has('admin_id')) {
			abort(403, 'Akses ditolak.');
		}

		$startDate = $request->input('start_date', Carbon::now()->startOfMonth());
		$endDate = $request->input('end_date', Carbon::now()->endOfMonth());

		$start = Carbon::parse($startDate)->startOfDay();
		$end = Carbon::parse($endDate)->endOfDay();

		// Pendapatan Penjualan
		$salesQuery = TransaksiKeluar::where('user_id', auth()->user()->id)
			->whereBetween('tanggal_transaksi', [$start, $end])
			->where('status', 'complete');

		$totalRevenue = (float) $salesQuery->sum('total_harga');
		$totalSalesCount = (int) $salesQuery->count();

		// HPP (COGS) - dari Transaksi Masuk
		$totalHPP = (float) TransaksiMasuk::where('user_id', auth()->user()->id)
			->whereBetween('tanggal_transaksi', [$start, $end])
			->where('status', 'complete')
			->sum('total_harga');

		$grossProfit = $totalRevenue - $totalHPP;
		$grossMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0.0;

		// Beban Operasional
		$totalOperatingExpenses = (float) Expenses::where('user_id', auth()->user()->id)
			->whereBetween('tanggal', [$start, $end])
			->sum('nominal');

		$netProfit = $grossProfit - $totalOperatingExpenses;
		$netMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0.0;

		// Breakdown expenses by category
		$expensesByCategory = Expenses::where('user_id', auth()->user()->id)
			->whereBetween('tanggal', [$start, $end])
			->selectRaw('kategori, SUM(nominal) as total')
			->groupBy('kategori')
			->pluck('total', 'kategori')
			->toArray();

		return view('laporan.laba-rugi', [
			'startDate' => $start->toDateString(),
			'endDate' => $end->toDateString(),
			'totalRevenue' => $totalRevenue,
			'totalSalesCount' => $totalSalesCount,
			'totalHPP' => $totalHPP,
			'grossProfit' => $grossProfit,
			'grossMargin' => $grossMargin,
			'totalOperatingExpenses' => $totalOperatingExpenses,
			'netProfit' => $netProfit,
			'netMargin' => $netMargin,
			'expensesByCategory' => $expensesByCategory,
		]);
	}
}
