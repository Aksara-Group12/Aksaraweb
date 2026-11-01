<?php

namespace App\Http\Controllers;

use App\Models\CloseOrderShift;
use App\Models\TransaksiKeluar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CloseOrderExport;

class CloseOrderController extends Controller
{
	/**
	 * Buka shift baru
	 */
	public function openShift(Request $request)
	{
		// Cek apakah ada shift yang masih open
		$existingOpenShift = CloseOrderShift::where('user_id', auth()->id())
			->where('status', 'open')
			->first();

		if ($existingOpenShift) {
			return back()->with('error', 'Shift masih terbuka. Silakan close order terlebih dahulu.');
		}

		$shift = CloseOrderShift::create([
			'user_id' => auth()->id(),
			'operator_name' => auth()->user()->name,
			'opened_at' => now(),
			'status' => 'open',
		]);

		return back()->with('success', 'Shift berhasil dibuka.');
	}

	/**
	 * Tutup shift
	 */
	public function closeShift(Request $request)
	{
		$shift = CloseOrderShift::where('user_id', auth()->id())
			->where('status', 'open')
			->first();

		if (!$shift) {
			return back()->with('error', 'Tidak ada shift yang terbuka.');
		}

		// Hitung total pendapatan dan transaksi selama shift
		$totalRevenue = TransaksiKeluar::where('user_id', auth()->id())
			->whereBetween('tanggal_transaksi', [$shift->opened_at, now()])
			->where('status', 'complete')
			->sum('total_harga');

		$totalTransactions = TransaksiKeluar::where('user_id', auth()->id())
			->whereBetween('tanggal_transaksi', [$shift->opened_at, now()])
			->where('status', 'complete')
			->count();

		$shift->update([
			'closed_at' => now(),
			'total_revenue' => $totalRevenue,
			'total_transactions' => $totalTransactions,
			'status' => 'closed',
		]);

		return back()->with('success', 'Shift berhasil ditutup. Total pendapatan: Rp ' . number_format($totalRevenue, 0, ',', '.'));
	}

	/**
	 * Cek status shift saat ini
	 */
	public function getCurrentShiftStatus()
	{
		$shift = CloseOrderShift::where('user_id', auth()->id())
			->where('status', 'open')
			->first();

		return response()->json([
			'status' => $shift ? 'open' : 'closed',
			'shift' => $shift,
		]);
	}

	/**
	 * Index laporan close order
	 */
	public function index(Request $request)
	{
		$shifts = CloseOrderShift::where('user_id', auth()->id())
			->orderByDesc('opened_at')
			->paginate(20);

		return view('laporan.close-order', compact('shifts'));
	}

	/**
	 * Export Excel
	 */
	public function exportExcel(Request $request)
	{
		$startDate = $request->input('start_date');
		$endDate = $request->input('end_date');

		return Excel::download(new CloseOrderExport($startDate, $endDate), 'laporan-close-order.xlsx');
	}
}
