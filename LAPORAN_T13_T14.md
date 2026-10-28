# LAPORAN T-13 & T-14
Tanggal: 29 Oktober 2026

---

## T-13: Pembatasan Menu Per Paket (SELESAI)
- **File:** `app/Models/Subscribe.php`
- **Perubahan:** Tambah method `getPackageType($userId)` - return 1, 2, or 3
- **File:** `resources/views/component/sidebar.blade.php`
- **Perubahan:**
  - Tambah `$paketType` di php block atas
  - `Manajemen Stok` - hanya tampil jika `$paketType >= 2`
  - `Stock Adjustment` - hanya tampil jika `$paketType >= 2`
  - `Log Produk` - hanya tampil jika `$paketType >= 2`
  - `Data Pelanggan` - hanya tampil jika `$paketType >= 3`
  - `Data Supplier` - hanya tampil jika `$paketType >= 3`
  - `Transaksi Masuk` - hanya tampil jika `$paketType >= 2`
- **Status:** ✅ SELESAI (sidebar)
- **Catatan:** Opsi "Bayar Nanti" di POS belum ada di form, jadi tidak perlu dihide

---

## T-14: Gabungkan Ringkasan Keuangan dan Laba Rugi
- **File:** `routes/web.php`
- **Perubahan:** Route `/laba-rugi` redirect ke `ringkasanKeuangan`
- **Status:** ✅ SELESAI (route redirect)
- **Perlu dilakukan:**
  - Ubah label sidebar "Ringkasan Keuangan" menjadi "Laporan Keuangan"
  - Update view laporan keuangan untuk menyatukan semua angka (revenue, HPP, laba kotor, beban per kategori, laba bersih, margin)
  - Hapus menu lama jika sudah digabungkan

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

---

## T-15 s/d T-29: BELUM DIPERIKSA
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
