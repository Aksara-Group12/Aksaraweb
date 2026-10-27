<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\TransaksiKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $query = Shift::where('user_id', auth()->user()->id)
            ->with('admin');

        if ($search = $request->input('search')) {
            $query->where('shift_number', 'like', "%{$search}%");
        }

        $perPage = (int) $request->input('per_page', 20);
        if ($perPage <= 0) {
            $perPage = 20;
        }

        $shifts = $query
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->only('search', 'per_page'));

        return view('shift.index', compact('shifts'));
    }

    public function create()
    {
        // Cek apakah ada shift yang masih open
        $activeShift = Shift::where('user_id', auth()->user()->id)
            ->where('status', 'open')
            ->first();

        if ($activeShift) {
            return redirect()->route('shift.index')
                ->with('error', 'Anda masih memiliki shift yang aktif. Silakan tutup shift terlebih dahulu.');
        }

        return view('shift.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'modal_awal' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, &$shiftRef) {
            $shift = Shift::create([
                'user_id' => auth()->user()->id,
                'admin_id' => session('admin_id'),
                'shift_number' => Shift::generateNumber(),
                'tanggal_shift' => today(),
                'modal_awal' => $validated['modal_awal'],
                'status' => 'open',
                'waktu_buka' => now(),
                'catatan' => $validated['catatan'] ?? null,
            ]);

            // Set session active_shift_id
            session(['active_shift_id' => $shift->id, 'modal_awal' => $shift->modal_awal]);

            $shiftRef = $shift;
        });

        return redirect()->route('shift.index')->with('success', 'Shift berhasil dibuka');
    }

    public function close($id)
    {
        $shift = Shift::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->where('status', 'open')
            ->firstOrFail();

        // Hitung total penjualan shift ini
        $totalPenjualan = TransaksiKeluar::where('shift_id', $shift->id)
            ->where('status', 'complete')
            ->sum('total_harga');

        // Hitung total penerimaan (cash payment)
        $totalPenerimaan = TransaksiKeluar::where('shift_id', $shift->id)
            ->where('status', 'complete')
            ->where('metode_pembayaran', 'cash')
            ->sum('nominal_bayar');

        // Modal akhir = modal awal + total penerimaan
        $modalAkhir = $shift->modal_awal + $totalPenerimaan;

        // Selisih kas = modal akhir - (modal awal + total penjualan)
        $selisihKas = $modalAkhir - ($shift->modal_awal + $totalPenjualan);

        return view('shift.close', compact('shift', 'totalPenjualan', 'totalPenerimaan', 'modalAkhir', 'selisihKas'));
    }

    public function updateClose(Request $request, $id)
    {
        $validated = $request->validate([
            'modal_akhir' => 'required|numeric|min:0',
            'catatan' => 'nullable|string',
        ]);

        $shift = Shift::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->where('status', 'open')
            ->firstOrFail();

        DB::transaction(function () use ($shift, $validated) {
            // Hitung total penjualan shift ini
            $totalPenjualan = TransaksiKeluar::where('shift_id', $shift->id)
                ->where('status', 'complete')
                ->sum('total_harga');

            // Hitung total penerimaan (cash payment)
            $totalPenerimaan = TransaksiKeluar::where('shift_id', $shift->id)
                ->where('status', 'complete')
                ->where('metode_pembayaran', 'cash')
                ->sum('nominal_bayar');

            // Update shift
            $shift->update([
                'modal_akhir' => $validated['modal_akhir'],
                'total_penjualan' => $totalPenjualan,
                'total_penerimaan' => $totalPenerimaan,
                'selisih_kas' => $validated['modal_akhir'] - ($shift->modal_awal + $totalPenerimaan),
                'status' => 'closed',
                'waktu_tutup' => now(),
                'catatan' => $validated['catatan'] ?? $shift->catatan,
            ]);

            // Clear session active_shift_id
            session()->forget(['active_shift_id', 'modal_awal']);
        });

        return redirect()->route('shift.index')->with('success', 'Shift berhasil ditutup');
    }
}
