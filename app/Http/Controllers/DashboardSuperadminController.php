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
}
