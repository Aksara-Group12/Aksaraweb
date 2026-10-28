# LAPORAN T-05, T-06, T-07
Tanggal: 29 Oktober 2026

---

## T-05: Ganti Nama Menu Transaksi Keluar menjadi Riwayat Penjualan
- **File:** `resources/views/component/sidebar.blade.php`
- **Perubahan:** Label "Keluar" → "Riwayat Penjualan" (line 196)
- **Status:** ✅ SELESAI

---

## T-06: Sembunyikan Jurnal Finance dan Invoice Manual
- **File:** `resources/views/component/sidebar.blade.php`
- **Perubahan:** Lines 223-241 (menu Jurnal Finance dan Invoice / AR) dihapus dengan sed command
- **Status:** ✅ SELESAI (dihapus dari sidebar)
- **Route dan controller:** TIDAK dihapus, masih tersedia untuk kemudahan munculkan kembali jika perlu

---

## T-07: Bersihkan Peninggalan Logam Mulia
- **Status:** ⚠ BELUM DIPERIKSA (context habis)
- **Perlu dilakukan di sesi berikutnya**

---

## STATUS PENEGERJAAN SAAT INI:
- T-00: ✅ SELESAI
- T-01: ✅ SELESAI
- T-02: ✅ SELESAI
- T-03: ✅ SELESAI
- T-04: ✅ SELESAI
- T-05: ✅ SELESAI
- T-06: ✅ SELESAI
- T-07: ⚠ BELUM DIPERIKSA

---

## T-08 s/d T-29: BELUM DIPERIKSA
- T-08: Void tidak ikut laporan
- T-09: Cegah void ganda
- T-10: Tipe produk dan opsi lacak stok
- T-11: Validasi stok di POS
- T-12: Laba Rugi memakai HPP snapshot
- T-13: Pembatasan menu per paket
- T-14: Gabungkan Ringkasan Keuangan dan Laba Rugi
- T-15: Putuskan Produk dan Produk Stok
- T-16: Stock Adjustment tercatat di Log Produk
- T-17: Laporan shift per kasir
- T-18: Top 5 Produk Terlaris
- T-19: Halaman laporan produk terlaris
- T-20: Kategori produk sebagai data master
- T-21: Pencatatan otomatis ke Jurnal Finance
- T-26: Aturan Transaksi Masuk
- T-27: Laporan Keuangan menampilkan piutang belum tertagih
- T-28: Pindah paket tanpa kehilangan data
- T-29: Peringatan modal terhitung dua kali
