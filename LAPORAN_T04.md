# LAPORAN T-04: Hapus Menu Tambah Transaksi Keluar
Tanggal: 29 Oktober 2026

---

## TUJUAN
Hapus link menu dan arahkan route `/transaksi/keluar/create` ke POS. Semua penjualan baru lewat POS.

---

## FILE YANG DIUBAH:
1. `routes/web.php`

---

## PERUBAHAN YANG DILAKUKAN:

### 1. Routes (`web.php`)
- **Lama:** `Route::get('/keluar/create', [TransaksiController::class, 'createKeluar'])->name('transaksi.keluar.create');`
- **Baru:** Dikomentari (disabled) route
- Route POS (`/transaksi/pos`) tetap ada dan mengarah ke controller yang sama

---

## SELESAI BILA:
- Tidak ada lagi route aktif untuk `/transaksi/keluar/create`
- Semua penjualan baru hanya lewat `/transaksi/pos`

---

## CATATAN:
- Controller `createKeluar` tetap ada (dipakai oleh POS)
- View `transaksi/keluar/create.blade.php` masih ada tapi tidak bisa diakses lewat route
- Tidak perlu hapus view karena masih bisa dipakai sebagai referensi
