# LAPORAN T-26: Aturan Transaksi Masuk
Tanggal: 29 Oktober 2026

---

## T-26: Aturan Transaksi Masuk
- **File:** `app/Http/Controllers/TransaksiController.php`
- **Perubahan:**
  - Tambah filter `->where('tipe', 'Barang')` pada query produk
  - Tambah filter `->where('lacak_stok', true)` pada query produk
- **Status:** ✅ SELESAI (controller)
- **Perlu dilakukan:**
  - Update view form Transaksi Masuk untuk menampilkan petunjuk: "Untuk pembelian barang yang dijual kembali. Belanja bahan olahan dicatat di Expenses."
  - Tambah opsi "Perbarui harga beli produk dengan harga ini" di form

---

## STATUS FINAL T-00 s/d T-26:
- T-00 s/d T-17: ✅ SELESAI LENGKAP
- T-18: ⚠ Controller selesai, view belum
- T-20: ⚠ Migration dan model selesai, form belum
- T-26: ⚠ Controller selesai, view petunjuk belum

---

## T-19, T-21, T-27 s/d T-29: BELUM DIPERIKSA
- T-19: Halaman laporan produk terlaris
- T-21: Pencatatan otomatis ke Jurnal Finance
- T-27: Laporan Keuangan menampilkan piutang belum tertagih
- T-28: Pindah paket tanpa kehilangan data
- T-29: Peringatan modal terhitung dua kali
