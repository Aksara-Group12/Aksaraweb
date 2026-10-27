<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TransaksiKeluar;
use App\Models\TransaksiMasuk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class DashboardController extends BaseController
{
	/**
	 * Tampilkan halaman dashboard dengan ringkasan data berdasarkan periode.
	 */
	public function index(Request $request)
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

		$today = Carbon::today();
		$last7Days = collect();

		// Penjualan (transaksi keluar complete) untuk periode terpilih
		$totalRevenue = (float) TransaksiKeluar::where('user_id', auth()->user()->id)
			->whereBetween('tanggal_transaksi', [$start, $end])
			->where('status', 'complete')
			->sum('total_harga');
		$todayRevenue = (float) TransaksiKeluar::where('user_id', auth()->user()->id)
			->whereDate('tanggal_transaksi', $today)
			->where('status', 'complete')
			->sum('total_harga');

		// Transaksi dalam periode (filter by user_id)
		$totalSalesTransactions = (int) TransaksiKeluar::where('user_id', auth()->user()->id)
			->whereBetween('tanggal_transaksi', [$start, $end])->count();
		$totalPurchaseTransactions = (int) TransaksiMasuk::where('user_id', auth()->user()->id)
			->whereBetween('tanggal_transaksi', [$start, $end])->count();
		$totalTransactions = $totalSalesTransactions + $totalPurchaseTransactions;
		$todayTransactions = (int) TransaksiKeluar::where('user_id', auth()->user()->id)
			->whereDate('tanggal_transaksi', $today)->count();

		// Produk & stok (stok dihitung dari relasi transaksi per produk) - filter by user_id
		$totalProducts = (int) Product::where('user_id', auth()->user()->id)->count();
		$totalStockUnits = (int) Product::where('user_id', auth()->user()->id)->get()->sum(function (Product $product) {
			return (int) $product->stok; // accessor stok di model Product
		});

		// Ringkasan penjualan 7 hari terakhir relatif terhadap akhir periode - filter by user_id
		$chartEnd = $end->copy();
		for ($i = 6; $i >= 0; $i--) {
			$date = $chartEnd->copy()->subDays($i);
			$amount = (float) TransaksiKeluar::where('user_id', auth()->user()->id)
				->whereDate('tanggal_transaksi', $date)
				->where('status', 'complete')
				->sum('total_harga');

			$last7Days->push([
				'label' => $date->format('d M'),
				'amount' => $amount,
			]);
		}

		$maxRevenue7Days = max($last7Days->pluck('amount')->all() ?: [0]);
		$maxRevenue7Days = $maxRevenue7Days > 0 ? $maxRevenue7Days : 1;

		$revenueChartLabels = $last7Days->pluck('label')->all();
		$revenueChartValues = $last7Days->pluck('amount')->all();
		$revenueChartHeights = $last7Days->map(function ($day) use ($maxRevenue7Days) {
			return $day['amount'] > 0
				? round(($day['amount'] / $maxRevenue7Days) * 100)
				: 0;
		})->all();

		// Top customer berdasarkan total belanja di periode - filter by user_id
		$topCustomers = TransaksiKeluar::where('user_id', auth()->user()->id)
			->whereBetween('tanggal_transaksi', [$start, $end])
			->whereNotNull('customer_name')
			->where('customer_name', '<>', '')
			->selectRaw('customer_name, customer_contact, lokasi, SUM(total_harga) as total_spent, COUNT(*) as total_transactions')
			->groupBy('customer_name', 'customer_contact', 'lokasi')
			->orderByDesc('total_spent')
			->limit(3)
			->get();

		return view('dashboard', [
			'period' => $period,
			'startDate' => $start->toDateString(),
			'endDate' => $end->toDateString(),
			'totalRevenue' => $totalRevenue,
			'todayRevenue' => $todayRevenue,
			'totalSalesTransactions' => $totalSalesTransactions,
			'totalTransactions' => $totalTransactions,
			'todayTransactions' => $todayTransactions,
			'totalProducts' => $totalProducts,
			'totalStockUnits' => $totalStockUnits,
			'revenueChartLabels' => $revenueChartLabels,
			'revenueChartValues' => $revenueChartValues,
			'revenueChartHeights' => $revenueChartHeights,
			'topCustomers' => $topCustomers,
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

