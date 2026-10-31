<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmployeeAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika bukan employee (tidak ada session admin_id), biarkan lewat
        if (!session()->has('admin_id')) {
            return $next($request);
        }

        // Route yang boleh diakses karyawan
        $allowedRoutes = [
            'transaksi.pos',
            'transaksi.keluar',
            'transaksi.keluar.store',
            'transaksi.keluar.print',
            'transaksi.keluar.void',
            'transaksi.log',
            'transaksi.log.export.excel',
            'transaksi.log.export.pdf',
            'shift.index',
            'shift.create',
            'shift.store',
            'shift.close',
            'shift.updateClose',
            'dashboard',
            'logout',
        ];

        $routeName = $request->route()?->getName();

        if ($routeName && in_array($routeName, $allowedRoutes, true)) {
            return $next($request);
        }

        // Route yang tidak boleh diakses karyawan
        $pesan = 'Akses ditolak. Karyawan hanya dapat mengakses POS, Shift, dan Riwayat Transaksi.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $pesan,
            ], 403);
        }

        return redirect()->route('transaksi.pos')->with('employee_access_denied', $pesan);
    }
}
