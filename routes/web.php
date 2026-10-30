<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardSuperadminController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\LandingPageController;

// TEMP: Clear cache (hapus setelah berhasil)
Route::get('/clear-cache', function () {
    // Clear config
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    
    // Force delete bootstrap cache
    $files = glob(base_path('bootstrap/cache/*'));
    foreach ($files as $file) {
        if (is_file($file)) {
            @unlink($file);
        }
    }
    
    return 'Cache cleared! All bootstrap files deleted.';
})->name('clear.cache');

Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// TEMP: Debug user lookup (hapus setelah selesai)
Route::get('/debug-user', function (\Illuminate\Http\Request $request) {
    $email = (string) $request->query('email');
    $user = $email !== '' ? \App\Models\User::where('email', $email)->first() : null;

    return response()->json([
        'db' => config('database.connections.mysql.database'),
        'email' => $email,
        'exists' => (bool) $user,
        'verified_at' => $user?->email_verified_at,
    ]);
})->name('debug.user');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/email/verify', [AuthController::class, 'showVerifyPrompt'])->name('verification.notice');
});

// Email verification route
Route::get('/email/verify/{user}/{token}', [AuthController::class, 'verifyEmail'])->name('verify-email');
Route::post('/email/resend', [AuthController::class, 'resendVerification'])->name('verification.resend');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Subscription details API - tanpa middleware subscription.access (superadmin only)
Route::middleware(['auth'])->group(function () {
    Route::get('/manajemen-user/{user_id}/subscription-details', [ManajemenUserController::class, 'getSubscriptionDetails'])
        ->name('manajemen-user.subscription-details');
});

// Protected application routes
Route::middleware(['auth', 'subscription.access', 'employee.access'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard-superadmin', [DashboardSuperadminController::class, 'index'])
        ->name('dashboard-superadmin');

    Route::get('/produk', [ProdukController::class, 'index'])
        ->name('produk.index');
    Route::get('/produk/create', [ProdukController::class, 'create'])
        ->name('produk.create');
    Route::post('/produk', [ProdukController::class, 'store'])
        ->name('produk.store');
    Route::get('/produk/{product}/edit', [ProdukController::class, 'edit'])
        ->name('produk.edit');
    Route::put('/produk/{product}', [ProdukController::class, 'update'])
        ->name('produk.update');
    Route::delete('/produk/{product}', [ProdukController::class, 'destroy'])
        ->name('produk.destroy');
    Route::post('/produk/{product}/destroy', [ProdukController::class, 'destroy'])
        ->name('produk.destroy.post');
    Route::delete('/produk/bulk-destroy', [ProdukController::class, 'bulkDestroy'])
        ->name('produk.bulk-destroy');
    Route::post('/produk/bulk-destroy', [ProdukController::class, 'bulkDestroy'])
        ->name('produk.bulk-destroy.post');
    Route::get('/produk/log', [ProdukController::class, 'log'])
        ->name('produk.log');
    Route::get('/produk/log/export/excel', [ProdukController::class, 'logExportExcel'])
        ->name('produk.log.export.excel');
    Route::get('/produk/log/export/pdf', [ProdukController::class, 'logExportPdf'])
        ->name('produk.log.export.pdf');

    Route::get('/laporan/keuangan', [LaporanController::class, 'ringkasanKeuangan'])
		->name('laporan.keuangan');

    Route::get('/laporan/produk-terlaris', [LaporanController::class, 'produkTerlaris'])
		->name('laporan.produk-terlaris');

    Route::get('/laporan/tracking-produk/export', [LaporanController::class, 'exportTrackingProduk'])
		->name('laporan.tracking-produk.export');

    Route::prefix('pengeluaran')->name('pengeluaran.')->group(function () {
        Route::get('/', [PengeluaranController::class, 'index'])->name('index');
        Route::get('/export/excel', [PengeluaranController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [PengeluaranController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/create', [PengeluaranController::class, 'create'])->name('create');
        Route::post('/', [PengeluaranController::class, 'store'])->name('store');
        Route::get('/{pengeluaran}/edit', [PengeluaranController::class, 'edit'])->name('edit');
        Route::put('/{pengeluaran}', [PengeluaranController::class, 'update'])->name('update');
        Route::delete('/{pengeluaran}', [PengeluaranController::class, 'destroy'])->name('destroy');
        Route::post('/{pengeluaran}/destroy', [PengeluaranController::class, 'destroy'])->name('destroy.post');
        Route::delete('/bulk-destroy', [PengeluaranController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::post('/bulk-destroy', [PengeluaranController::class, 'bulkDestroy'])->name('bulk-destroy.post');
        Route::post('/hapus-massal', [PengeluaranController::class, 'bulkDestroy'])->name('bulk-delete.post');
    });

    Route::prefix('manajemen-user')->name('manajemen-user.')->group(function () {
        Route::get('/', [ManajemenUserController::class, 'index'])->name('index');
        Route::post('/bulk-destroy', [ManajemenUserController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::post('/subscription/{subscription}/approve', [ManajemenUserController::class, 'approveSubscription'])->name('subscription.approve');
        Route::post('/subscription/{subscription}/reject', [ManajemenUserController::class, 'rejectSubscription'])->name('subscription.reject');
        Route::get('/subscription/{subscription}/edit', [ManajemenUserController::class, 'editSubscription'])->name('subscription.edit');
        Route::put('/subscription/{subscription}', [ManajemenUserController::class, 'updateSubscription'])->name('subscription.update');
        
        // User CRUD routes
        Route::get('/{user}/edit', [ManajemenUserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [ManajemenUserController::class, 'update'])->name('update');
        Route::delete('/{user}', [ManajemenUserController::class, 'destroy'])->name('destroy');
    });

    // Download bukti transaksi - di luar prefix untuk menghindari conflict
    Route::get('/subscription-bukti/{filename}', [ManajemenUserController::class, 'downloadBukti'])
        ->where('filename', '.+')
        ->name('manajemen-user.subscription.download');

    Route::prefix('paket')->name('paket.')->group(function () {
        Route::get('/', [PaketController::class, 'index'])->name('index');
        Route::get('/create', [PaketController::class, 'create'])->name('create');
        Route::post('/', [PaketController::class, 'store'])->name('store');
        Route::get('/{paket}/edit', [PaketController::class, 'edit'])->name('edit');
        Route::put('/{paket}', [PaketController::class, 'update'])->name('update');
        Route::delete('/{paket}', [PaketController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('bank')->name('bank.')->group(function () {
        Route::get('/', [BankController::class, 'index'])->name('index');
        Route::get('/create', [BankController::class, 'create'])->name('create');
        Route::post('/', [BankController::class, 'store'])->name('store');
        Route::get('/{bank}/edit', [BankController::class, 'edit'])->name('edit');
        Route::put('/{bank}', [BankController::class, 'update'])->name('update');
        Route::delete('/{bank}', [BankController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-destroy', [BankController::class, 'bulkDestroy'])->name('bulk-destroy');
    });



});

Route::middleware(['auth'])->group(function () {
    Route::resource('profile', ProfileController::class)->only([
        'index', 'create', 'store', 'edit', 'update',
    ]);
});

// Protected application routes
Route::middleware(['auth', 'subscription.access', 'employee.access'])->group(function () {
    Route::prefix('transaksi')->group(function () {
        Route::get('/keluar', [TransaksiController::class, 'keluar'])->name('transaksi.keluar');
        Route::get('/keluar/export/excel', [TransaksiController::class, 'exportKeluarExcel'])->name('transaksi.keluar.export.excel');
        Route::get('/keluar/export/pdf', [TransaksiController::class, 'exportKeluarPdf'])->name('transaksi.keluar.export.pdf');
        // Route::get('/keluar/create', [TransaksiController::class, 'createKeluar'])->name('transaksi.keluar.create'); // Dihapus: semua penjualan lewat POS
        Route::post('/keluar', [TransaksiController::class, 'storeKeluar'])->name('transaksi.keluar.store');
        Route::get('/pos', [TransaksiController::class, 'createKeluar'])->name('transaksi.pos');
	Route::get('/keluar/{transaksiKeluar}/nota', [TransaksiController::class, 'printKeluar'])->name('transaksi.keluar.print');
	Route::get('/keluar/{transaksiKeluar}/edit', [TransaksiController::class, 'editKeluar'])->name('transaksi.keluar.edit');
	Route::put('/keluar/{transaksiKeluar}', [TransaksiController::class, 'updateKeluar'])->name('transaksi.keluar.update');
	Route::delete('/keluar/{transaksiKeluar}', [TransaksiController::class, 'destroyKeluar'])->name('transaksi.keluar.destroy');
    Route::post('/keluar/{transaksiKeluar}/destroy', [TransaksiController::class, 'destroyKeluar'])->name('transaksi.keluar.destroy.post');
    Route::delete('/keluar/bulk-destroy', [TransaksiController::class, 'bulkDestroyKeluar'])->name('transaksi.keluar.bulk-destroy');
    Route::post('/keluar/bulk-destroy', [TransaksiController::class, 'bulkDestroyKeluar'])->name('transaksi.keluar.bulk-destroy.post');
    Route::post('/keluar/hapus-massal', [TransaksiController::class, 'bulkDestroyKeluar'])->name('transaksi.keluar.bulk-delete.post');
    Route::post('/keluar/{id}/void', [TransaksiController::class, 'voidTransaksi'])->name('transaksi.keluar.void');
    });

    // Subscribe routes (hanya untuk owner)
    Route::prefix('subscribe')->name('subscribe.')->middleware('auth')->group(function () {
        Route::get('/package', [SubscribeController::class, 'package'])->name('package');
        Route::post('/store', [SubscribeController::class, 'store'])->name('store');
        Route::get('/status', [SubscribeController::class, 'getStatus'])->name('status');
        Route::get('/banks', [SubscribeController::class, 'getBanks'])->name('banks');
    });
});

