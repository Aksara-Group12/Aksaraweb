# LAPORAN T-17: Laporan Shift Per Kasir
Tanggal: 29 Oktober 2026

---

## T-17: Laporan Shift Per Kasir
- **File:** `app/Http/Controllers/ShiftController.php`
- **Perubahan:**
  - Tambah filter berdasarkan admin_id (kasir)
  - Tambah filter berdasarkan tanggal (start_date, end_date)
  - Tambah parameter `$admins` ke view untuk dropdown filter kasir
- **Status:** ✅ SELESAI (controller)
- **Perlu dilakukan:** Update view shift.index untuk menampilkan dropdown filter kasir dan tanggal

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
- T-13: ✅ SELESAI
- T-14: ⚠ PARCIAL (route redirect selesai, view belum)
- T-15: ✅ SELESAI
- T-16: ✅ SELESAI
- T-17: ⚠ PARCIAL (controller selesai, view belum)

---

## T-18 s/d T-29: BELUM DIPERIKSA
- T-18: Top 5 Produk Terlaris
- T-19: Halaman laporan produk terlaris (prasyarat T-18)
- T-20: Kategori produk sebagai data master
- T-21: Pencatatan otomatis ke Jurnal Finance (prasyarat T-12, T-14)
- T-26: Aturan Transaksi Masuk (prasyarat T-10)
- T-27: Laporan Keuangan menampilkan piutang belum tertagih (prasyarat T-14)
- T-28: Pindah paket tanpa kehilangan data (prasyarat T-13)
- T-29: Peringatan modal terhitung dua kali (prasyarat T-12, T-14, T-20)
