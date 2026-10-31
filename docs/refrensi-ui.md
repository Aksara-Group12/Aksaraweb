Tata Letak POS (Point of Sale)
+-----------------------------------------------------------------------------------------------+
| TOP HEADER: Status Shift (Jayjo - Kasir 1) | Modal Awal: Rp 100.000 | [ Tutup Shift ] | Jam   |
+------------------------------------------------------+----------------------------------------+
| AREA KIRI: KATALOG PRODUK (60% - 65% Lebar Layar)    | AREA KANAN: KERANJANG KASIR (Sticky)   |
|                                                      |                                        |
| [🔍 Cari Produk / Scan Barcode...] [ Kategori: All ▾] | Pelanggan: [ Pelanggan Umum      ▾]   |
|                                                      |                                        |
| +--------------------------------------------------+ | DAFTAR ITEM (Scrollable):              |
| | [Basreng]     [Kopi Susu]   [Keripik Pedas]      | | 1. Basreng Krispi                      |
| | Rp 3.000      Rp 15.000     Rp 10.000            | |    Rp 3.000 x [- 2 +] = Rp 6.000  [🗑] |
| | Stok: 100     Stok: 24      Stok: 5              | | 2. Kopi Susu                           |
| |                                                  | |    Rp 15.000 x [- 1 +] = Rp 15.000 [🗑] |
| | [Teh Botol]   [Roti Bakar]  [Mie Goreng]         | |----------------------------------------|
| | Rp 5.000      Rp 12.000     Rp 18.000            | | Subtotal                      Rp 21.000 |
| | Stok: 40      Stok: 12      Stok: 8              | | Diskon (Voucher)                   Rp 0 |
| +--------------------------------------------------+ | Pajak / PB1 (Opsional)             Rp 0 |
|                                                      |========================================|
|                                                      | TOTAL BAYAR                 Rp 21.000  |
|                                                      |----------------------------------------|
|                                                      | Metode: [💵 Tunai] [💳 QRIS] [🏦 Trf]  |
|                                                      | Uang Diterima: [Rp 50.000]             |
|                                                      | KEMBALIAN:     Rp 29.000 (Hijau Besar) |
|                                                      |----------------------------------------|
|                                                      | [       BAYAR SEKARANG (ENTER)       ] |
+------------------------------------------------------+----------------------------------------+





Tata Letak Manajemen User & Karyawan
+-----------------------------------------------------------------------------------------------+
| HEADER: Manajemen Pengguna & Karyawan (Total: 4 User)              [+ Tambah Pengguna Baru]    |
+-----------------------------------------------------------------------------------------------+
| FILTER BAR: [🔍 Cari nama, email...]  [Role: Semua Role ▾]  [Status: Aktif ▾]  [Hapus Massal] |
+-----------------------------------------------------------------------------------------------+
| [ ] PENGGUNA                  ROLE            OUTLET/CABANG     STATUS         AKSI           |
|-----------------------------------------------------------------------------------------------|
| [ ] (FJ) Faraysz              Owner           Pusat             ● Aktif        [ ✏️ ] [ 🔒 ]   |
|     faraysz@gmail.com                                                                         |
|                                                                                               |
| [ ] (AT) Atma Jaya            Admin Toko      Cabang 1          ● Aktif        [ ✏️ ] [ 🗑️ ]   |
|     atma@gmail.com                                                                            |
|                                                                                               |
| [ ] (JJ) Jayjo                Kasir           Cabang 1          ● Aktif        [ ✏️ ] [ 🗑️ ]   |
|     jayjo@pos.com                                                                             |
|                                                                                               |
| [ ] (BU) Budi Santoso         Kasir           Cabang 2          ○ Nonaktif     [ ✏️ ] [ 🔄 ]   |
|     budi@pos.com                                                                              |
+-----------------------------------------------------------------------------------------------+





Tata Letak Dashboard yang Paling User-Friendly
+-------------------------------------------------------------------------------------------------+
| TOP HEADER (Navbar): Ringkasan Penjualan & Stok  |  [Hai, Faraysz ▾]                           |
+-------------------------------------------------------------------------------------------------+
| [📅 Bulan Ini ▾] [Custom: dd/mm/yyyy s/d dd/mm/yyyy]                [📥 Export Laporan] [⚙️]    |
+-------------------------------------------------------------------------------------------------+
| ZONA 1: CORE METRICS (4 Kartu Ringkas)                                                          |
| +--------------------+ +--------------------+ +--------------------+ +--------------------+    |
| | TOTAL PENDAPATAN   | | TRANSAKSI HARI INI | | NILAI RATA-RATA    | | TOTAL PRODUK AKTIF |    |
| | Rp 12.850.000      | | 48 Transaksi       | | Rp 267.700 /trx    | | 120 SKU            |    |
| | ↗ +12.5% vs bln lalu| | 5 Pending          | | ↗ +4.2% stabil     | | ● 3 Stok Menipis   |    |
| +--------------------+ +--------------------+ +--------------------+ +--------------------+    |
+-------------------------------------------------------------------------------------------------+
| ZONA 2: ANALITIK UTAMA (Rasio 65% : 35%)                                                        |
| +---------------------------------------------------+ +-----------------------------------------+ |
| | TREN PENJUALAN (Grafik Garis Area Halus)          | | KATEGORI TERLARIS (Progress Bar / Donut)| |
| | [ 7 Hari ] [ 30 Hari ] [ 1 Tahun ]                | | 1. Makanan Ringan  (45%) [======    ]   | |
| |      /\                                           | | 2. Minuman Dingin  (30%) [====      ]   | |
| |  _.-'  \       _.-                                | | 3. Sembako         (15%) [==        ]   | |
| |         \__.-'                                    | | 4. Lainnya         (10%) [=         ]   | |
| +---------------------------------------------------+ +-----------------------------------------+ |
+-------------------------------------------------------------------------------------------------+
| ZONA 3: DATA REAL-TIME & PERINGATAN STOK (Split 50% : 50%)                                      |
| +---------------------------------------------------+ +-----------------------------------------+ |
| | 5 PENJUALAN TERAKHIR           [Lihat Semua →]    | | PERINGATAN INVENTARIS / STOK MENIPIS    | |
| | • INV-0012 | Basreng Krispi (2)  | Rp 6.000       | | ⚠️ Kopi Susu Aren      | Sisa: 2 pcs    | |
| | • INV-0011 | Kopi Susu (1)       | Rp 15.000      | | ⚠️ Keripik Pedas       | Sisa: 4 pcs    | |
| | • INV-0010 | Pelanggan Umum      | Rp 45.000      | | ✅ Semua stok lainnya dalam kondisi aman | |
| +---------------------------------------------------+ +-----------------------------------------+ |
+-------------------------------------------------------------------------------------------------+