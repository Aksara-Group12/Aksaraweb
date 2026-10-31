<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardSuperadminController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\ProdukStokController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ManajemenUserController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockAdjustmentController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ShiftController;

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
    Route::get('/dashboard-superadmin/export-pdf', [DashboardSuperadminController::class, 'exportPdf'])
        ->name('dashboard-superadmin.export-pdf');
    Route::get('/dashboard-superadmin/export-excel', [DashboardSuperadminController::class, 'exportExcel'])
        ->name('dashboard-superadmin.export-excel');

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

    Route::get('/expenses', [ExpensesController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/export/excel', [ExpensesController::class, 'exportExcel'])->name('expenses.export.excel');
    Route::get('/expenses/export/pdf', [ExpensesController::class, 'exportPdf'])->name('expenses.export.pdf');
    Route::get('/expenses/create', [ExpensesController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpensesController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}/edit', [ExpensesController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{expense}', [ExpensesController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpensesController::class, 'destroy'])->name('expenses.destroy');
    Route::post('/expenses/{expense}/destroy', [ExpensesController::class, 'destroy'])->name('expenses.destroy.post');
    Route::delete('/expenses/bulk-destroy', [ExpensesController::class, 'bulkDestroy'])->name('expenses.bulk-destroy');
    Route::post('/expenses/bulk-destroy', [ExpensesController::class, 'bulkDestroy'])->name('expenses.bulk-destroy.post');
    Route::post('/expenses/hapus-massal', [ExpensesController::class, 'bulkDestroy'])->name('expenses.bulk-delete.post');

    Route::prefix('produk-stok')->name('produkstok.')->group(function () {
        Route::get('/', [ProdukStokController::class, 'index'])->name('index');
        Route::get('/{id}', [ProdukStokController::class, 'show'])->name('show');
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

    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/', [PelangganController::class, 'index'])->name('index');
        Route::get('/create', [PelangganController::class, 'create'])->name('create');
        Route::post('/', [PelangganController::class, 'store'])->name('store');
        Route::get('/{pelanggan}/edit', [PelangganController::class, 'edit'])->name('edit');
        Route::patch('/{pelanggan}', [PelangganController::class, 'update'])->name('update');
        Route::delete('/{pelanggan}', [PelangganController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('supplier')->name('supplier.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::get('/create', [SupplierController::class, 'create'])->name('create');
        Route::post('/', [SupplierController::class, 'store'])->name('store');
        Route::get('/{supplier}/edit', [SupplierController::class, 'edit'])->name('edit');
        Route::patch('/{supplier}', [SupplierController::class, 'update'])->name('update');
        Route::delete('/{supplier}', [SupplierController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('stock-adjustment')->name('stock-adjustment.')->group(function () {
        Route::get('/', [StockAdjustmentController::class, 'index'])->name('index');
        Route::get('/create', [StockAdjustmentController::class, 'create'])->name('create');
        Route::post('/', [StockAdjustmentController::class, 'store'])->name('store');
    });

    Route::prefix('journal')->name('journal.')->group(function () {
        Route::get('/', [JournalController::class, 'index'])->name('index');
    });

    Route::prefix('invoice')->name('invoice.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/create-manual', [InvoiceController::class, 'createManual'])->name('createManual');
        Route::post('/manual', [InvoiceController::class, 'storeManual'])->name('storeManual');
        Route::post('/record-payment/{id}', [InvoiceController::class, 'recordPayment'])->name('recordPayment');
    });

    Route::prefix('shift')->name('shift.')->group(function () {
        Route::get('/', [ShiftController::class, 'index'])->name('index');
        Route::get('/create', [ShiftController::class, 'create'])->name('create');
        Route::post('/', [ShiftController::class, 'store'])->name('store');
        Route::get('/close/{id}', [ShiftController::class, 'close'])->name('close');
        Route::post('/close/{id}', [ShiftController::class, 'updateClose'])->name('updateClose');
    });

});

// Admin Management Routes - tanpa middleware subscription.access (owner bisa akses walaupun subscription expired)
Route::get('/admin/verify-email/{id}', [AdminController::class, 'verifyEmail'])
    ->name('admin.verify-email');

Route::middleware(['auth'])->group(function () {
    Route::resource('profile', ProfileController::class)->only([
        'index', 'create', 'store', 'edit', 'update',
    ]);

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/create', [AdminController::class, 'create'])->name('create');
        Route::post('/', [AdminController::class, 'store'])->name('store');
        Route::post('/bulk-destroy', [AdminController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/destroy', [AdminController::class, 'destroy'])->name('destroy.post');
        Route::post('/{id}/restore', [AdminController::class, 'restore'])->name('restore');
    });
});

// Protected application routes
Route::middleware(['auth', 'subscription.access', 'employee.access'])->group(function () {
    Route::prefix('transaksi')->group(function () {
        Route::get('/masuk', [TransaksiController::class, 'masuk'])->name('transaksi.masuk');
        Route::get('/masuk/export/excel', [TransaksiController::class, 'exportMasukExcel'])->name('transaksi.masuk.export.excel');
        Route::get('/masuk/export/pdf', [TransaksiController::class, 'exportMasukPdf'])->name('transaksi.masuk.export.pdf');
        Route::get('/masuk/create', [TransaksiController::class, 'createMasuk'])->name('transaksi.masuk.create');
        Route::post('/masuk', [TransaksiController::class, 'storeMasuk'])->name('transaksi.masuk.store');
        Route::get('/masuk/{transaksiMasuk}/nota', [TransaksiController::class, 'printMasuk'])->name('transaksi.masuk.print');
        Route::delete('/masuk/{transaksiMasuk}', [TransaksiController::class, 'destroyMasuk'])->name('transaksi.masuk.destroy');
        Route::post('/masuk/{transaksiMasuk}/destroy', [TransaksiController::class, 'destroyMasuk'])->name('transaksi.masuk.destroy.post');
        Route::delete('/masuk/bulk-destroy', [TransaksiController::class, 'bulkDestroyMasuk'])->name('transaksi.masuk.bulk-destroy');
        Route::post('/masuk/bulk-destroy', [TransaksiController::class, 'bulkDestroyMasuk'])->name('transaksi.masuk.bulk-destroy.post');
        Route::post('/masuk/hapus-massal', [TransaksiController::class, 'bulkDestroyMasuk'])->name('transaksi.masuk.bulk-delete.post');

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

        Route::get('/laporan', [TransaksiController::class, 'laporan'])->name('transaksi.laporan');
        // Route::get('/laba-rugi', [LaporanController::class, 'labaRugi'])->name('laporan.laba-rugi'); // Digabung ke laporan.keuangan
        Route::get('/laba-rugi', [LaporanController::class, 'ringkasanKeuangan'])->name('laporan.laba-rugi'); // Redirect

        Route::get('/log', [TransaksiController::class, 'logTransaksi'])->name('transaksi.log');
        Route::get('/log/export/excel', [TransaksiController::class, 'logTransaksiExportExcel'])->name('transaksi.log.export.excel');
        Route::get('/log/export/pdf', [TransaksiController::class, 'logTransaksiExportPdf'])->name('transaksi.log.export.pdf');

        // API endpoints untuk cascading dropdown (disembunyikan - legacy logam mulia)
        // Route::get('/api/brands/{jenisLogam}', [TransaksiController::class, 'getBrands']);
        // Route::get('/api/weights/{jenisLogam}/{brand}', [TransaksiController::class, 'getWeights']);
        // Route::get('/api/products/{jenisLogam}/{brand}/{berat}', [TransaksiController::class, 'getProducts']);
        // Route::get('/api/products-by-jenis/{jenisLogam}', [TransaksiController::class, 'getProductsByJenisLogam']);
        // Route::get('/api/all-products', [TransaksiController::class, 'getAllProducts']);
        // Route::get('/api/serial-numbers/{productId}', [TransaksiController::class, 'getSerialNumbers']);
    });

    // Subscribe routes (hanya untuk owner)
    Route::prefix('subscribe')->name('subscribe.')->middleware('auth')->group(function () {
        Route::get('/package', [SubscribeController::class, 'package'])->name('package');
        Route::post('/store', [SubscribeController::class, 'store'])->name('store');
        Route::get('/status', [SubscribeController::class, 'getStatus'])->name('status');
        Route::get('/banks', [SubscribeController::class, 'getBanks'])->name('banks');
    });
});

