# LAPORAN T-14 & T-17 (FINAL)
Tanggal: 29 Oktober 2026

---

## T-14: Gabungkan Ringkasan Keuangan dan Laba Rugi (SELESAI)
- **File:** `resources/views/laporan/ringkasan-keuangan.blade.php`
- **Perubahan:** Label "Ringkasan Keuangan" → "Laporan Keuangan", subtext diperbarui
- **File:** `resources/views/component/sidebar.blade.php`
- **Perubahan:** Label sidebar "Ringkasan Keuangan" → "Laporan Keuangan"
- **File:** `routes/web.php`
- **Perubahan:** Route `/laba-rugi` redirect ke `ringkasanKeuangan`
- **Status:** ✅ SELESAI

---

## T-17: Laporan Shift Per Kasir (SELESAI)
- **File:** `app/Http/Controllers/ShiftController.php`
- **Perubahan:** Tambah filter admin_id, start_date, end_date; pass $admins ke view
- **File:** `resources/views/shift/index.blade.php`
- **Perubahan:** Tambah dropdown filter kasir, input tanggal start/end, tombol filter
- **Status:** ✅ SELESAI

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
- T-14: ✅ SELESAI
- T-15: ✅ SELESAI
- T-16: ✅ SELESAI
- T-17: ✅ SELESAI

---

## T-18 s/d T-29: BELUM DIPERIKSA
- T-18: Top 5 Produk Terlaris
- T-19: Halaman laporan produk terlaris
- T-20: Kategori produk sebagai data master
- T-21: Pencatatan otomatis ke Jurnal Finance
- T-26: Aturan Transaksi Masuk
- T-27: Laporan Keuangan menampilkan piutang belum tertagih
- T-28: Pindah paket tanpa kehilangan data
- T-29: Peringatan modal terhitung dua kali
