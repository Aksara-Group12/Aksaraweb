# LAPORAN T-10: Tipe Produk dan Opsi Lacak Stok
Tanggal: 29 Oktober 2026

---

## T-10: Tipe Produk dan Opsi Lacak Stok
- **Migration:** `2026_10_28_210118_add_product_type_and_track_stock_to_products_table.php` ✅ DIBUAT dan DI-MIGRATE
- **Kolom tambahan:**
  - `tipe` enum('Barang', 'Menu', 'Jasa') default 'Barang'
  - `lacak_stok` boolean default true
- **Model Product:** ✅ FILLABLE diperbarui (tambah tipe, lacak_stok)
- **Status:** ✅ DATABASE SELESAI
- **Perlu dilakukan:**
  - Update form produk (create.blade.php dan edit.blade.php) untuk menambahkan:
    - Dropdown tipe (Barang/Menu/Jasa)
    - Checkbox lacak_stok
    - Petunjuk harga beli: "Isi jika sistem perlu menghitung modal otomatis. Kosongkan bila belanja bahan atau upah dicatat di Expenses."
    - Sembunyikan field stok jika lacak_stok = false
  - Update controller ProdukController validation dan store logic
  - Update POS untuk tidak mengurangi stok jika lacak_stok = false

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
- T-10: ⚠ PARCIAL (migration & model selesai, form & controller belum)

---

## T-11 s/d T-29: BELUM DIPERIKSA
- T-11: Validasi stok di POS (prasyarat T-10)
- T-12: Laba Rugi memakai HPP snapshot (prasyarat T-08, T-10)
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
