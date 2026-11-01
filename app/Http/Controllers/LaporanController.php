<?php

namespace App\Http\Controllers;

use App\Models\TransaksiKeluar;
use App\Models\Pengeluaran;
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
			->where('status', 'complete')
			->where('is_void', false);

		$totalRevenue = (float) $salesQuery->sum('total_harga');
		$totalSalesCount = (int) $salesQuery->count();

		// Gross Profit (tanpa HPP/COGS)
		$grossProfit = $totalRevenue;
		$grossMargin = 100.0; // Gross margin 100% karena tidak ada HPP

		// Operating expenses dari tabel expenses
		$expensesQuery = Pengeluaran::where('user_id', auth()->user()->id)
			->whereBetween('tanggal', [$start, $end]);
		$totalOperatingExpenses = (float) $expensesQuery->sum('nominal');

		$netProfit = $grossProfit - $totalOperatingExpenses;
		$netMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0.0;

		// Cashflow (Cash Flow Method)
		// Cash In: Total Revenue dari penjualan complete
		$cashIn = $totalRevenue;

		// Cash Out: Operating Expenses saja (tanpa HPP)
		$cashOut = $totalOperatingExpenses;

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

	public function produkTerlaris(Request $request)
	{
		$period = $request->input('period', 'month');
		$startDate = $request->input('start_date');
		$endDate = $request->input('end_date');

		if ($period !== 'custom') {
			[$startDate, $endDate] = $this->resolvePeriodRange($period);
		} else {
			if (empty($startDate) || empty($endDate)) {
				[$startDate, $endDate] = $this->resolvePeriodRange('month');
				$period = 'month';
			}
		}

		$start = Carbon::parse($startDate)->startOfDay();
		$end = Carbon::parse($endDate)->endOfDay();

		// Query produk terlaris
		$products = TransaksiKeluarItem::join('transaksi_keluar', 'transaksi_keluar_items.transaksi_keluar_id', '=', 'transaksi_keluar.id')
			->join('products', 'transaksi_keluar_items.product_id', '=', 'products.id')
			->where('transaksi_keluar.user_id', auth()->user()->id)
			->whereBetween('transaksi_keluar.tanggal_transaksi', [$start, $end])
			->where('transaksi_keluar.status', 'complete')
			->where('transaksi_keluar.is_void', false)
			->selectRaw('products.nama_produk, products.tipe, SUM(transaksi_keluar_items.qty_keluar) as total_qty, SUM(transaksi_keluar_items.total_harga) as total_revenue')
			->groupBy('products.id', 'products.nama_produk', 'products.tipe')
			->orderByDesc('total_qty')
			->get();

		return view('laporan.produk-terlaris', [
			'period' => $period,
			'startDate' => $start->toDateString(),
			'endDate' => $end->toDateString(),
			'products' => $products,
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

		// TransaksiMasuk dihapus, fitur tracking produk tidak tersedia
		// Return empty data
		$data[] = [];

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

		// Gross Profit (tanpa HPP/COGS)
		$grossProfit = $totalRevenue;
		$grossMargin = 100.0; // Gross margin 100% karena tidak ada HPP

		// Beban Operasional
		$totalOperatingExpenses = (float) Pengeluaran::where('user_id', auth()->user()->id)
			->whereBetween('tanggal', [$start, $end])
			->sum('nominal');

		$netProfit = $grossProfit - $totalOperatingExpenses;
		$netMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0.0;

		// Breakdown expenses by category
		$expensesByCategory = Pengeluaran::where('user_id', auth()->user()->id)
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
			'grossProfit' => $grossProfit,
			'grossMargin' => $grossMargin,
			'totalOperatingExpenses' => $totalOperatingExpenses,
			'netProfit' => $netProfit,
			'netMargin' => $netMargin,
			'expensesByCategory' => $expensesByCategory,
		]);
	}
}
