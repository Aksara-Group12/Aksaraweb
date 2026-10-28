# LAPORAN T-03: Hak Akses Karyawan yang Jelas
Tanggal: 29 Oktober 2026

---

## TUJUAN
Akses karyawan tidak bergantung pada pengaturan manual. Karyawan hanya bisa membuka: POS, Shift, Riwayat Penjualan, dan Log Transaksi.

---

## FILE YANG DIUBAH:
1. `app/Http/Middleware/EnsureEmployeeAccess.php` (FILE BARU)
2. `bootstrap/app.php`

---

## PERUBAHAN YANG DILAKUKAN:

### 1. Middleware Baru (`EnsureEmployeeAccess.php`)
- Logic: Jika ada session admin_id (karyawan), cek route
- Route yang BOLEH diakses karyawan:
  - transaksi.pos (POS Kasir)
  - transaksi.keluar (Riwayat Penjualan)
  - transaksi.keluar.store (Simpan transaksi)
  - transaksi.keluar.print (Cetak nota)
  - transaksi.keluar.void (Void transaksi)
  - transaksi.log (Log Transaksi)
  - transaksi.log.export.* (Export log)
  - shift.* (Shift: index, create, store, close, updateClose)
  - dashboard (Dashboard)
  - logout
- Route yang DITOLAK: Akses ke Produk, Keuangan, Karyawan, Profil, Langganan, dll

### 2. Bootstrap (`app.php`)
- Tambahkan alias middleware: `'employee.access' => \App\Http\Middleware\EnsureEmployeeAccess::class`

---

## YANG BELUM SELESAI (Karena context limit):
- Apply middleware `'employee.access'` ke route group di `routes/web.php`
- Perlu ditambahkan ke semua route yang berada di bawah `Route::middleware(['auth', 'subscription.access'])`

---

## CARA MENYELESAIKAN T-03 (Perlu dilakukan setelah ini):
Di `routes/web.php`, ubah:
```php
// DARI:
Route::middleware(['auth', 'subscription.access'])->group(function () {
    // semua route
});

// MENJADI:
Route::middleware(['auth', 'subscription.access', 'employee.access'])->group(function () {
    // semua route
});
```

---

## CATATAN:
- Middleware sudah dibuat dan terdaftar di bootstrap
- Logic sudah benar (cek session admin_id, cek allowed routes)
- Hanya perlu apply ke route group di web.php
