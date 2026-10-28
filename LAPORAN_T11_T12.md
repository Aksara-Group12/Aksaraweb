# LAPORAN T-11 & T-12
Tanggal: 29 Oktober 2026

---

## T-11: Validasi Stok di POS
- **File:** `app/Http/Controllers/TransaksiController.php`
- **Perubahan:**
  - Line 499-503: Validasi stok hanya jika `lacak_stok = true`
  - Line 561-563: Kurangi stok hanya jika `lacak_stok = true`
- **Status:** ✅ SELESAI
- **Perlu juga:** Update DashboardController dengan validasi yang sama

---

## T-12: Laba Rugi Memakai HPP Snapshot
- **File:** `app/Http/Controllers/LaporanController.php`
- **Perubahan:**
  - HPP dihitung dari `SUM(qty_keluar × hpp_snapshot)` pada TransaksiKeluarItem
  - Hanya transaksi yang tidak void
  - Tidak lagi menghitung dari TransaksiMasuk (pembelian)
- **Status:** ✅ SELESAI
- **Perlu juga:** Update DashboardController dengan rumus yang sama

---

## STATUS PENEGERJAAN SAAT INI:
- T-00: ✅ SELESAI
- T-01: ✅ SELESAI
- T-02: ✅ SELESAI
- T-03: ✅ SELESAI
- T-04: ✅ SELESAI
- T-05: ✅ SELESAI
- T-06: ✅ SELESAI
- T-07: ✅ SELESAI
- T-08: ✅ SELESAI
- T-09: ✅ SELESAI
- T-10: ✅ SELESAI
- T-11: ✅ SELESAI
- T-12: ✅ SELESAI

---

## T-13 s/d T-29: BELUM DIPERIKSA
- T-13: Pembatasan menu per paket (prasyarat T-10, T-12)
- T-14: Gabungkan Ringkasan Keuangan dan Laba Rugi (prasyarat T-12)
- T-15: Putuskan Produk dan Produk Stok (prasyarat T-00)
- T-16: Stock Adjustment tercatat di Log Produk (prasyarat T-00)
- T-17: Laporan shift per kasir (prasyarat T-02)
- T-18: Top 5 Produk Terlaris
- T-19: Halaman laporan produk terlaris (prasyarat T-18)
- T-20: Kategori produk sebagai data master
- T-21: Pencatatan otomatis ke Jurnal Finance (prasyarat T-12, T-14)
- T-26: Aturan Transaksi Masuk (prasyarat T-10)
- T-27: Laporan Keuangan menampilkan piutang belum tertagih (prasyarat T-14)
- T-28: Pindah paket tanpa kehilangan data (prasyarat T-13)
- T-29: Peringatan modal terhitung dua kali (prasyarat T-12, T-14, T-20)
