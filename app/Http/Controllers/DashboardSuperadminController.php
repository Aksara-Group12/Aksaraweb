<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TransaksiMasuk;
use App\Models\TransaksiKeluar;
use App\Models\Subscribe;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardSuperadminController extends BaseController
{
	public function index(Request $request)
	{
		// Guard: hanya superadmin
		if (auth()->user()?->role !== 'superadmin') {
			abort(403, 'Anda tidak memiliki akses ke dashboard superadmin');
		}

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

		// ===== STATISTIK USER =====
		$totalUsers = (int) User::count();
		$totalActiveUsers = (int) User::where('role', '!=', 'superadmin')->count();
		$todayNewUsers = (int) User::whereDate('created_at', Carbon::today())->count();

		// ===== STATISTIK SUBSCRIPTION =====
		// Total user yang berlangganan (is_trial = 0)
		$totalSubscribedUsers = (int) Subscribe::where('is_trial', 0)
			->distinct('user_id')
			->count('user_id');

		// Pendapatan dari subscription (is_trial = 0) dalam periode
		$subscriptionRevenue = (float) Subscribe::whereBetween('start_date', [$start, $end])
			->where('is_trial', 0)
			->sum('price');

		// Pendapatan subscription hari ini
		$todaySubscriptionRevenue = (float) Subscribe::whereDate('start_date', Carbon::today())
			->where('is_trial', 0)
			->sum('price');

		// ===== STATISTIK KEUANGAN =====
		// Total uang masuk (pembelian produk - transaksi masuk)
		$totalMasuk = (float) TransaksiMasuk::whereBetween('tanggal_transaksi', [$start, $end])
			->where('status', 'complete')
			->sum('total_harga');

		// Total uang keluar (penjualan produk - transaksi keluar)
		$totalKeluar = (float) TransaksiKeluar::whereBetween('tanggal_transaksi', [$start, $end])
			->where('status', 'complete')
			->sum('total_harga');

		$todayMasuk = (float) TransaksiMasuk::whereDate('tanggal_transaksi', Carbon::today())
			->where('status', 'complete')
			->sum('total_harga');

		$todayKeluar = (float) TransaksiKeluar::whereDate('tanggal_transaksi', Carbon::today())
			->where('status', 'complete')
			->sum('total_harga');

		// Net (profit/loss)
		$netIncome = $totalKeluar - $totalMasuk;
		$todayNetIncome = $todayKeluar - $todayMasuk;

		// ===== CHART 7 HARI TERAKHIR =====
		$chartEnd = $end->copy();
		$last7Days = collect();

		for ($i = 6; $i >= 0; $i--) {
			$date = $chartEnd->copy()->subDays($i);
			$keluar = (float) TransaksiKeluar::whereDate('tanggal_transaksi', $date)
				->where('status', 'complete')
				->sum('total_harga');
			$masuk = (float) TransaksiMasuk::whereDate('tanggal_transaksi', $date)
				->where('status', 'complete')
				->sum('total_harga');

			$last7Days->push([
				'label' => $date->format('d M'),
				'keluar' => $keluar,
				'masuk' => $masuk,
				'net' => $keluar - $masuk,
			]);
		}

		$maxRevenue = max($last7Days->pluck('net')->all() ?: [0]);
		$maxRevenue = $maxRevenue > 0 ? $maxRevenue : 1;

		$chartsLabels = $last7Days->pluck('label')->all();
		$chartsKeluar = $last7Days->pluck('keluar')->all();
		$chartsMasuk = $last7Days->pluck('masuk')->all();

		// ===== TOP USERS (PALING BANYAK TRANSAKSI) =====
		$topUsers = User::where('role', '!=', 'superadmin')
			->selectRaw('users.id, users.name, users.email, COUNT(DISTINCT tk.id) as total_transaksi_keluar, SUM(tk.total_harga) as total_revenue')
			->leftJoin('transaksi_keluar as tk', 'users.id', '=', 'tk.user_id')
			->whereBetween('tk.tanggal_transaksi', [$start, $end])
			->where('tk.status', 'complete')
			->groupBy('users.id', 'users.name', 'users.email')
			->orderByDesc('total_revenue')
			->limit(5)
			->get();

		return view('dashboardsuperadmin', [
			'period' => $period,
			'startDate' => $start->toDateString(),
			'endDate' => $end->toDateString(),
			'totalUsers' => $totalUsers,
			'totalActiveUsers' => $totalActiveUsers,
			'todayNewUsers' => $todayNewUsers,
			'totalSubscribedUsers' => $totalSubscribedUsers,
			'subscriptionRevenue' => $subscriptionRevenue,
			'todaySubscriptionRevenue' => $todaySubscriptionRevenue,
			'totalMasuk' => $totalMasuk,
			'totalKeluar' => $totalKeluar,
			'netIncome' => $netIncome,
			'todayMasuk' => $todayMasuk,
			'todayKeluar' => $todayKeluar,
			'todayNetIncome' => $todayNetIncome,
			'chartsLabels' => $chartsLabels,
			'chartsKeluar' => $chartsKeluar,
			'chartsMasuk' => $chartsMasuk,
			'topUsers' => $topUsers,
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
	 * Export laporan pemesanan sistem dari awal hingga akhir ke PDF
	 */
	public function exportPdf(Request $request)
	{
		if (auth()->user()?->role !== 'superadmin') {
			abort(403, 'Anda tidak memiliki hak akses untuk mengunduh laporan ini.');
		}

		$query = Subscribe::with(['user.profile', 'bank', 'paket'])
			->orderBy('created_at', 'asc'); // Riwayat dari awal hingga akhir

		$periodTitle = 'Semua Riwayat (Dari Awal Hingga Akhir)';

		if ($request->input('scope') === 'period' && $request->filled('start_date') && $request->filled('end_date')) {
			$start = Carbon::parse($request->input('start_date'))->startOfDay();
			$end = Carbon::parse($request->input('end_date'))->endOfDay();
			$query->whereBetween('created_at', [$start, $end]);
			$periodTitle = $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y');
		}

		$subscriptions = $query->get();

		$totalSubscriptions = $subscriptions->count();
		$totalRevenue = (float) $subscriptions->where('is_trial', false)->sum('price');
		$totalActive = $subscriptions->where('status', 'active')->count();
		$totalPending = $subscriptions->where('approval_status', 'pending')->count();

		$transaksiKeluar = TransaksiKeluar::with(['admin', 'items'])
			->where('status', 'complete')
			->orderBy('created_at', 'asc')
			->get();

		$totalPenjualanKasir = (float) $transaksiKeluar->sum('total_harga');

		$pdf = Pdf::loadView('dashboardsuperadmin-pdf', [
			'subscriptions' => $subscriptions,
			'totalSubscriptions' => $totalSubscriptions,
			'totalRevenue' => $totalRevenue,
			'totalActive' => $totalActive,
			'totalPending' => $totalPending,
			'transaksiKeluar' => $transaksiKeluar,
			'totalPenjualanKasir' => $totalPenjualanKasir,
			'periodTitle' => $periodTitle,
			'documentTitle' => 'Laporan Pemesanan Sistem Aksara Developer',
		]);

		$pdf->setPaper('a4', 'landscape');
		$fileName = 'laporan_pemesanan_aksara_' . now()->format('Ymd_His') . '.pdf';

		return $pdf->download($fileName);
	}

	/**
	 * Export laporan pemesanan sistem dari awal hingga akhir ke Excel / CSV
	 */
	public function exportExcel(Request $request)
	{
		if (auth()->user()?->role !== 'superadmin') {
			abort(403, 'Anda tidak memiliki hak akses untuk mengunduh laporan ini.');
		}

		$query = Subscribe::with(['user.profile', 'bank', 'paket'])
			->orderBy('created_at', 'asc');

		if ($request->input('scope') === 'period' && $request->filled('start_date') && $request->filled('end_date')) {
			$start = Carbon::parse($request->input('start_date'))->startOfDay();
			$end = Carbon::parse($request->input('end_date'))->endOfDay();
			$query->whereBetween('created_at', [$start, $end]);
		}

		$subscriptions = $query->get();
		$fileName = 'laporan_pemesanan_aksara_' . now()->format('Ymd_His') . '.csv';

		$headers = [
			'Content-Type' => 'text/csv; charset=UTF-8',
			'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
			'Pragma' => 'no-cache',
			'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
			'Expires' => '0',
		];

		return response()->stream(function () use ($subscriptions) {
			$handle = fopen('php://output', 'w');
			fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8 for Excel

			fputcsv($handle, [
				'No',
				'ID Pesanan',
				'Tanggal Pesan',
				'Nama Pemesan',
				'Email Pemesan',
				'Nama Toko',
				'Paket Dipesan',
				'Tipe Paket',
				'Durasi (Hari)',
				'Bank Tujuan',
				'Harga / Biaya (IDR)',
				'Status Langganan',
				'Status Approval',
			]);

			foreach ($subscriptions as $idx => $sub) {
				fputcsv($handle, [
					$idx + 1,
					'#SUB-' . str_pad($sub->id, 5, '0', STR_PAD_LEFT),
					$sub->created_at ? $sub->created_at->format('Y-m-d H:i:s') : '-',
					$sub->user->name ?? '-',
					$sub->user->email ?? '-',
					$sub->user?->profile?->store_name ?? '-',
					$sub->paket_name ?? ($sub->paket->nama_paket ?? '-'),
					$sub->is_trial ? 'Trial' : 'Berbayar',
					$sub->duration_days ?? 0,
					$sub->bank->nama_bank ?? '-',
					$sub->price ?? 0,
					ucfirst($sub->status ?? '-'),
					ucfirst($sub->approval_status ?? '-'),
				]);
			}

			fclose($handle);
		}, 200, $headers);
	}
}
