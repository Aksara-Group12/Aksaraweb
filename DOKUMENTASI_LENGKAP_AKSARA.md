# DOKUMENTASI LENGKAP AKSARA
Sistem Manajemen Usaha UMKM Terpadu

---

## RINGKASAN SISTEM

Aksara adalah sistem manajemen usaha UMKM berbasis web yang mencakupu:
- **POS/Kasir**: Transaksi penjualan instan dengan dukungan pembayaran cash dan tempo
- **Inventory**: Manajemen produk, stok, dan stock adjustment (opname)
- **Finance**: Pencatatan biaya operasional (expenses), jurnal, dan laporan keuangan
- **CRM**: Manajemen pelanggan dan pencatatan piutang (Invoice/Account Receivable)
- **Pembelian**: Transaksi masuk dari supplier dengan update stok otomatis
- **Shift Kasir**: Rekonsiliasi kas dengan modal awal/akhir dan selisih kas
- **SaaS**: Sistem berbasis subscription dengan paket untuk owner

**Teknologi**: Laravel 11, Blade, MySQL, Tailwind CSS

---

## ARSITEKTUR & INVENTARIS MODUL

### Modul yang Tersedia (Berdasarkan Kode):

1. **Authentication & User Management**
   - Login/Register (Owner)
   - Login Admin (Karyawan)
   - Profile (Profil usaha)
   - Admin Management (Kelola karyawan untuk Owner)

2. **Subscription & Billing**
   - Paket (Manajemen paket langganan - Superadmin)
   - Subscribe (Pembelian paket - Owner)
   - Bank (Rekening bank - Superadmin)
   - Manajemen User (Kelola user & approval subscription - Superadmin)

3. **Produk & Inventory**
   - Produk (CRUD produk)
   - Produk Stok (Monitoring stok)
   - Log Produk (Riwayat perubahan stok)
   - Stock Adjustment (Opname stok fisik)

4. **Transaksi**
   - Transaksi Masuk (Pembelian dari supplier)
   - Transaksi Keluar (Penjualan/History sales)
   - POS Kasir (Antarmuka penjualan instan)
   - Log Transaksi (Riwayat semua transaksi)

5. **Keuangan**
   - Expenses (Biaya operasional)
   - Jurnal Finance (Buku besar akuntansi)
   - Invoice / AR (Tagihan piutang & pencatatan pembayaran)
   - Laporan Keuangan (Ringkasan keuangan)
   - Laba Rugi (Profit & Loss)

6. **Operasional**
   - Shift Kasir (Buka/tutup shift, rekonsiliasi kas)
   - Pelanggan (Master data pelanggan)
   - Supplier (Master data supplier)

7. **Landing Page**
   - Halaman publik dengan informasi fitur dan paket

---

## DAFTAR ROLE PENGGUNA

### 1. Owner
**Tujuan**: Pemilik usaha, mengelola seluruh bisnis
**Hak Akses**:
- Semua menu utama (Produk, Transaksi, Keuangan, Shift, dll)
- Kelola Admin/Karyawan
- Kelola profil usaha
- Kelola subscription
- Melihat semua laporan

**Pembatasan**: Fitur diblokir jika subscription expired (kecuali profil dan subscribe)

### 2. Admin / Karyawan
**Tujuan**: Staf kasir, karyawan usaha
**Hak Akses**:
- POS Kasir (jika subscription aktif)
- Transaksi Keluar (jika subscription aktif)
- Riwayat transaksi (tergantung session admin_id)

**Pembatasan**: Tidak dapat mengubah konfigurasi sistem, melihat laporan keuangan lengkap, atau mengelola subscription

### 3. Superadmin
**Tujuan**: Admin platform Aksara (bukan owner usaha)
**Hak Akses**:
- Dashboard Superadmin
- Manajemen User (semua user di platform)
- Manajemen Paket (subscription paket)
- Manajemen Bank (rekening platform)
- Approval subscription

**Pembatasan**: Tidak dapat mengakses data owner/usaha individual

### 4. Pelanggan (Tidak memiliki akses sistem)
**Tujuan**: Konsumen usaha
**Hak Akses**: Tidak ada (hanya tercatat dalam data)

---

## DAFTAR SELURUH HALAMAN & ROUTE

### 1. Authentication

#### 1.1 Login Owner
- **Route**: `/login`
- **Controller**: `AuthController@showLoginForm`, `AuthController@login`
- **View**: `resources/views/auth/login.blade.php`
- **Role**: Guest (belum login)
- **Tujuan**: Login untuk owner usaha
- **Field**: Email, Password
- **Validasi**: Kredensial valid, email verified
- **Output**: Redirect ke dashboard (jika subscription aktif) atau modal subscription

#### 1.2 Register Owner
- **Route**: `/register`
- **Controller**: `AuthController@showRegisterForm`, `AuthController@register`
- **View**: `resources/views/auth/register.blade.php`
- **Role**: Guest
- **Tujuan**: Pendaftaran owner baru
- **Field**: Name, Email, Password, Password Confirmation
- **Validasi**: Email unik, password minimal 8 karakter
- **Output**: Redirect ke login atau halaman verifikasi email

#### 1.3 Login Admin
- **Route**: `/admin/login`
- **Controller**: `AdminAuthController@showLoginForm`, `AdminAuthController@login`
- **View**: `resources/views/auth/admin-login.blade.php`
- **Role**: Guest
- **Tujuan**: Login untuk karyawan/admin usaha
- **Field**: Email, Password
- **Output**: Redirect ke dashboard (sesuai role)

---

### 2. Dashboard

#### 2.1 Dashboard Owner
- **Route**: `/dashboard`
- **Controller**: `DashboardController@index`
- **View**: `resources/views/dashboard.blade.php`
- **Role**: Owner
- **Tujuan**: Ringkasan KPI bisnis
- **Fitur**:
  - Period select (pilih periode laporan)
  - Custom date range (tanggal mulai & akhir)
  - Statistik cards (omset, produk, piutang, laba bersih)
  - Grafik penjualan
  - Recent transactions
- **Proses Sistem**: Mengambil data dari TransaksiKeluar, Product, Invoice berdasarkan periode
- **Output**: Tampilan ringkasan dalam kartu dan grafik

#### 2.2 Dashboard Superadmin
- **Route**: `/dashboard-superadmin`
- **Controller**: `DashboardSuperadminController@index`
- **View**: `resources/views/dashboardsuperadmin.blade.php`
- **Role**: Superadmin
- **Tujuan**: Ringkasan platform (total user, subscription, dll)

---

### 3. Manajemen Produk

#### 3.1 Index Produk
- **Route**: `/produk`
- **Controller**: `ProdukController@index`
- **View**: `resources/views/produk/index.blade.php`
- **Role**: Owner
- **Tujuan**: Daftar semua produk
- **Fitur**:
  - Search (cari kode/nama/brand)
  - Filter per page (10/20/50)
  - Export Excel/PDF
  - Bulk delete
  - Tombol Tambah Produk
- **Field Filter**: Search text, Per page dropdown
- **Proses Sistem**: Query produk dengan filter, pagination
- **Output**: Tabel produk dengan SKU, nama, kategori, satuan, harga, stok, status

#### 3.2 Tambah Produk
- **Route**: `/produk/create`
- **Controller**: `ProdukController@create`, `ProdukController@store`
- **View**: `resources/views/produk/create.blade.php`
- **Role**: Owner
- **Tujuan**: Menambah produk baru
- **Field**:
  - Nama Produk (wajib)
  - Kode Produk / SKU (opsional, auto-generate jika kosong)
  - Kategori (opsional)
  - Satuan (wajib, default: pcs)
  - Harga Beli / HPP (opsional)
  - Harga Jual (wajib)
  - Stok Awal (opsional)
  - Stok Minimum (opsional)
  - Status (Active/Inactive)
  - Deskripsi (opsional)
- **Validasi**: Nama dan harga jual wajib
- **Proses Sistem**: Generate SKU otomatis jika kosong, simpan ke tabel `products`
- **Output**: Redirect ke index produk dengan pesan sukses

#### 3.3 Edit Produk
- **Route**: `/produk/{product}/edit`
- **Controller**: `ProdukController@edit`, `ProdukController@update`
- **View**: `resources/views/produk/edit.blade.php`
- **Role**: Owner
- **Tujuan**: Mengubah data produk
- **Field**: Sama dengan tambah, pre-filled dengan data existing
- **Proses Sistem**: Update tabel `products`
- **Output**: Redirect ke index produk

#### 3.4 Hapus Produk
- **Route**: `/produk/{product}` (DELETE)
- **Controller**: `ProdukController@destroy`
- **Role**: Owner
- **Tujuan**: Menghapus produk
- **Proses Sistem**: Soft/hard delete dari tabel `products`
- **Output**: Redirect ke index produk dengan pesan sukses

#### 3.5 Log Produk
- **Route**: `/produk/log`
- **Controller**: `ProdukController@log`
- **View**: `resources/views/produk/log.blade.php`
- **Role**: Owner
- **Tujuan**: Riwayat perubahan stok produk
- **Fitur**: Export Excel/PDF
- **Proses Sistem**: Query log perubahan stok dari transaksi
- **Output**: Tabel log dengan timestamp, tipe perubahan, qty sebelum/sesudah

---

### 4. Produk Stok

#### 4.1 Index Stok
- **Route**: `/produk-stok`
- **Controller**: `ProdukStokController@index`
- **View**: `resources/views/produk-stok/index.blade.php`
- **Role**: Owner
- **Tujuan**: Monitoring stok dengan status (Habis/Menipis/Aman)
- **Proses Sistem**: Query semua produk, hitung status berdasarkan stok vs min_stock
- **Output**: Tabel dengan badge status stok

#### 4.2 Detail Stok
- **Route**: `/produk-stok/{id}`
- **Controller**: `ProdukStokController@show`
- **View**: `resources/views/produk-stok/show.blade.php`
- **Role**: Owner
- **Tujuan**: Detail stok per produk
- **Output**: Info detail produk dan stok

---

### 5. Stock Adjustment (Opname)

#### 5.1 Index Stock Adjustment
- **Route**: `/stock-adjustment`
- **Controller**: `StockAdjustmentController@index`
- **View**: `resources/views/stock-adjustment/index.blade.php`
- **Role**: Owner
- **Tujuan**: Daftar semua stock adjustment
- **Output**: Tabel adjustment dengan tanggal, item yang disesuaikan

#### 5.2 Tambah Stock Adjustment
- **Route**: `/stock-adjustment/create`
- **Controller**: `StockAdjustmentController@create`, `StockAdjustmentController@store`
- **View**: `resources/views/stock-adjustment/create.blade.php`
- **Role**: Owner
- **Tujuan:** Penyesuaian stok fisik vs sistem
- **Field**:
  - Tanggal (wajib)
  - Keterangan (opsional)
  - Items (dinamis):
    - Produk (wajib)
    - Stok Sistem (readonly, dari products.stock)
    - Stok Fisik (wajib)
    - Selisih (auto-calculate: stok_fisik - stok_sistem)
    - Alasan (dropdown: Rusak, Kadaluarsa, Hilang, Selisih Hitung, Lainnya)
- **Validasi**: Stok fisik wajib diisi
- **Proses Sistem**:
  - Hitung selisih per item
  - Hitung total_nilai_selisih = selisih × harga_beli
  - Simpan ke `stock_adjustments` dan `stock_adjustment_items`
  - Update `products.stock` ke stok fisik
- **Output**: Redirect ke index adjustment dengan ringkasan nilai selisih

---

### 6. Transaksi Masuk (Pembelian)

#### 6.1 Index Transaksi Masuk
- **Route**: `/transaksi/masuk`
- **Controller**: `TransaksiController@masuk`
- **View**: `resources/views/transaksi/masuk/index.blade.php`
- **Role**: Owner, Admin (tergantung session admin_id)
- **Tujuan**: Daftar pembelian dari supplier
- **Fitur**: Search, filter admin, filter per page, export Excel/PDF, bulk delete
- **Proses Sistem**: Query transaksi_masuk dengan filter, pagination
- **Output**: Tabel transaksi dengan nomor, tanggal, supplier, total, status

#### 6.2 Tambah Transaksi Masuk
- **Route**: `/transaksi/masuk/create`
- **Controller**: `TransaksiController@createMasuk`, `TransaksiController@storeMasuk`
- **View**: `resources/views/transaksi/masuk/create.blade.php`
- **Role**: Owner, Admin
- **Tujuan**: Mencatat pembelian barang dari supplier
- **Field**:
  - Tanggal (wajib)
  - Supplier (dropdown dari master supplier)
  - Lokasi (opsional)
  - Metode Pembayaran (dropdown: Cash/Non-Cash)
  - Items (dinamis):
    - Produk (dropdown dari master produk)
    - Qty (wajib)
    - Harga Beli (wajib)
    - Diskon (opsional)
- **Validasi**: Tanggal, supplier, dan item wajib
- **Proses Sistem**:
  - Simpan ke `transaksi_masuk` dan `transaksi_masuk_items`
  - Update `products.stock` += qty untuk setiap item
  - Tambah log produk stok masuk
- **Output**: Redirect ke index transaksi masuk

#### 6.3 Nota Transaksi Masuk
- **Route**: `/transaksi/masuk/{transaksiMasuk}/nota`
- **Controller**: `TransaksiController@printMasuk`
- **Role**: Owner, Admin
- **Tujuan**: Cetak nota pembelian
- **Output**: Tampilan nota untuk print

#### 6.4 Audit Log Transaksi Masuk
- **Route**: (Tersedia di model, view belum pasti)
- **Tujuan**: Riwayat perubahan transaksi
- **Status**: Model `TransaksiMasukAuditLog` tersedia, UI belum diverifikasi

---

### 7. Transaksi Keluar (Penjualan)

#### 7.1 Index Transaksi Keluar
- **Route**: `/transaksi/keluar`
- **Controller**: `TransaksiController@keluar`
- **View**: `resources/views/transaksi/keluar/index.blade.php`
- **Role**: Owner, Admin (tergantung session admin_id)
- **Tujuan**: Riwayat penjualan (history sales)
- **Fitur**: Search, filter admin, filter per page, export Excel/PDF, bulk delete, void
- **Field Filter**: Search invoice/pelanggan/kota, admin dropdown, per page
- **Proses Sistem**: Query transaksi_keluar dengan filter, pagination
- **Output**: Tabel transaksi dengan invoice_no, tanggal, pelanggan, total, status, void status

#### 7.2 POS Kasir
- **Route**: `/transaksi/pos`
- **Controller**: `TransaksiController@createKeluar` (redirect ke POS view)
- **View**: `resources/views/pos/cashier.blade.php`
- **Role**: Owner, Admin
- **Tujuan**: Antarmuka penjualan instan kasir
- **Validasi**: Wajib buka shift sebelum akses POS (redirect ke shift.create jika belum ada shift aktif)
- **Field**:
  - Pelanggan (dropdown dari master pelanggan atau "Pelanggan Umum")
  - Katalog produk (klik untuk tambah ke keranjang)
  - Keranjang (qty, diskon per item)
  - Metode Pembayaran (dropdown: Cash/Non-Cash)
  - Checkbox "Bayar Nanti / Invoice" (untuk pembayaran tempo)
  - Jumlah Bayar (jika cash)
- **Proses Sistem**:
  - Capture HPP snapshot dari products.harga_beli ke transaksi_keluar_items.hpp_snapshot
  - Link ke shift_id aktif
  - Jika checkbox "Bayar Nanti" = true → set is_invoice = true
  - Simpan ke `transaksi_keluar` dan `transaksi_keluar_items`
  - Update `products.stock` -= qty untuk setiap item
  - Jika is_invoice = true → buat invoice di `invoices` (tabel invoice & invoice_items)
  - Update shift.total_penjualan dan shift.total_penerimaan
- **Output**: Redirect ke halaman struk/nota atau invoice

#### 7.3 Tambah Transaksi Keluar (Form Manual)
- **Route**: `/transaksi/keluar/create`
- **Controller**: `TransaksiController@createKeluar`
- **View**: Mengarah ke POS
- **Role**: Owner, Admin
- **Tujuan**: Sama dengan POS (tidak ada form manual terpisah)

#### 7.4 Edit Transaksi Keluar
- **Route**: `/transaksi/keluar/{transaksiKeluar}/edit`
- **Controller**: `TransaksiController@editKeluar`, `TransaksiController@updateKeluar`
- **View**: `resources/views/transaksi/keluar/edit.blade.php`
- **Role**: Owner, Admin
- **Tujuan**: Mengubah transaksi penjualan
- **Proses Sistem**: Update tabel transaksi_keluar dan items, hitung ulang total
- **Output**: Redirect ke index transaksi keluar

#### 7.5 Void Transaksi
- **Route**: `/transaksi/keluar/{id}/void`
- **Controller**: `TransaksiController@voidTransaksi`
- **Role**: Owner, Admin
- **Tujuan**: Membatalkan transaksi dan mengembalikan stok
- **Field**: Alasan void (wajib)
- **Validasi**: Alasan wajib, transaksi belum di-void
- **Proses Sistem**:
  - Set is_void = true, void_reason, voided_at
  - Kembalikan stok ke products (stock += qty dari items)
  - Tidak menghapus data, hanya menandai void
- **Output**: Redirect ke index transaksi keluar dengan status void

#### 7.6 Nota Transaksi Keluar
- **Route**: `/transaksi/keluar/{transaksiKeluar}/nota`
- **Controller**: `TransaksiController@printKeluar`
- **Role**: Owner, Admin
- **Tujuan**: Cetak nota penjualan
- **Output**: Tampilan nota untuk print

---

### 8. Expenses (Biaya Operasional)

#### 8.1 Index Expenses
- **Route**: `/expenses`
- **Controller**: `ExpensesController@index`
- **View**: `resources/views/transaksi/expenses/index.blade.php`
- **Role**: Owner
- **Tujuan**: Daftar biaya operasional
- **Fitur**: Search, filter periode, export Excel/PDF, bulk delete
- **Proses Sistem**: Query expenses dengan filter
- **Output**: Tabel expenses dengan tanggal, kategori, nominal, metode, keterangan

#### 8.2 Tambah Expenses
- **Route**: `/expenses/create`
- **Controller**: `ExpensesController@create`, `ExpensesController@store`
- **View**: `resources/views/transaksi/expenses/create.blade.php`
- **Role**: Owner
- **Tujuan**: Mencatat biaya operasional
- **Field**:
  - Tanggal (wajib)
  - Kategori (wajib)
  - Nominal (wajib, positif)
  - Metode Pembayaran (dropdown: Cash/Non-Cash)
  - Reference No (opsional)
  - Keterangan (opsional)
- **Validasi**: Tanggal, kategori, nominal wajib, nominal harus positif
- **Proses Sistem**: Simpan ke tabel `expenses`
- **Output**: Redirect ke index expenses

#### 8.3 Edit Expenses
- **Route**: `/expenses/{expense}/edit`
- **Controller**: `ExpensesController@edit`, `ExpensesController@update`
- **Role**: Owner
- **Tujuan**: Mengubah biaya operasional
- **Proses Sistem**: Update tabel `expenses`
- **Output**: Redirect ke index expenses

#### 8.4 Hapus Expenses
- **Route**: `/expenses/{expense}`
- **Controller**: `ExpensesController@destroy`
- **Role**: Owner
- **Tujuan**: Menghapus biaya operasional
- **Proses Sistem**: Delete dari tabel `expenses`
- **Output**: Redirect ke index expenses

---

### 9. Pelanggan (CRM)

#### 9.1 Index Pelanggan
- **Route**: `/pelanggan`
- **Controller**: `PelangganController@index`
- **View**: `resources/views/pelanggan/index.blade.php`
- **Role**: Owner
- **Tujuan**: Daftar pelanggan
- **Proses Sistem**: Query pelanggan
- **Output**: Tabel pelanggan dengan nama, kontak, email, alamat

#### 9.2 Tambah Pelanggan
- **Route**: `/pelanggan/create`
- **Controller**: `PelangganController@create`, `PelangganController@store`
- **View**: `resources/views/pelanggan/create.blade.php`
- **Role**: Owner
- **Tujuan**: Menambah pelanggan baru
- **Field**:
  - Nama (wajib)
  - Kontak (opsional)
  - Email (opsional)
  - Alamat (opsional)
  - Catatan (opsional)
- **Validasi**: Nama wajib
- **Proses Sistem**: Simpan ke tabel `pelanggan`
- **Output**: Redirect ke index pelanggan

#### 9.3 Edit Pelanggan
- **Route**: `/pelanggan/{pelanggan}/edit`
- **Controller**: `PelangganController@edit`, `PelangganController@update`
- **Role**: Owner
- **Tujuan**: Mengubah data pelanggan
- **Proses Sistem**: Update tabel `pelanggan`
- **Output**: Redirect ke index pelanggan

#### 9.4 Hapus Pelanggan
- **Route**: `/pelanggan/{pelanggan}`
- **Controller**: `PelangganController@destroy`
- **Role**: Owner
- **Tujuan**: Menghapus pelanggan
- **Proses Sistem**: Delete dari tabel `pelanggan`
- **Output**: Redirect ke index pelanggan

---

### 10. Supplier

#### 10.1 Index Supplier
- **Route**: `/supplier`
- **Controller**: `SupplierController@index`
- **View**: `resources/views/supplier/index.blade.php`
- **Role**: Owner
- **Tujuan**: Daftar supplier
- **Proses Sistem**: Query supplier
- **Output**: Tabel supplier dengan nama, kontak, email, alamat

#### 10.2 Tambah Supplier
- **Route**: `/supplier/create`
- **Controller**: `SupplierController@create`, `SupplierController@store`
- **View**: `resources/views/supplier/create.blade.php`
- **Role**: Owner
- **Tujuan**: Menambah supplier baru
- **Field**:
  - Nama (wajib)
  - Kontak (opsional)
  - Email (opsional)
  - Alamat (opsional)
  - Catatan (opsional)
- **Validasi**: Nama wajib
- **Proses Sistem**: Simpan ke tabel `supplier`
- **Output**: Redirect ke index supplier

#### 10.3 Edit Supplier
- **Route**: `/supplier/{supplier}/edit`
- **Controller**: `SupplierController@edit`, `SupplierController@update`
- **Role**: Owner
- **Tujuan**: Mengubah data supplier
- **Proses Sistem**: Update tabel `supplier`
- **Output**: Redirect ke index supplier

#### 10.4 Hapus Supplier
- **Route**: `/supplier/{supplier}`
- **Controller**: `SupplierController@destroy`
- **Role**: Owner
- **Tujuan**: Menghapus supplier
- **Proses Sistem**: Delete dari tabel `supplier`
- **Output**: Redirect ke index supplier

---

### 11. Invoice / AR (Piutang)

#### 11.1 Index Invoice
- **Route**: `/invoice`
- **Controller**: `InvoiceController@index`
- **View**: `resources/views/invoice/index.blade.php`
- **Role**: Owner
- **Tujuan**: Daftar tagihan piutang dari transaksi tempo
- **Fitur**: Catat pembayaran (cicilan/pelunasan)
- **Proses Sistem**:
  - Query invoice yang terhubung ke transaksi_keluar (sales_transaction_id)
  - Tampilkan sisa_tagihan
  - Record payment mengurangi sisa_tagihan
  - Update status: unpaid → partial → paid
- **Output**: Tabel invoice dengan nomor, pelanggan, total, sisa, status

#### 11.2 Catat Pembayaran
- **Route**: `/invoice/record-payment/{id}`
- **Controller**: `InvoiceController@recordPayment`
- **Role**: Owner
- **Field**: Nominal bayar (wajib, positif)
- **Proses Sistem**:
  - Kurangi sisa_tagihan -= nominal bayar
  - Update status berdasarkan sisa_tagihan
- **Output**: Redirect ke index invoice dengan sisa dan status baru

#### 11.3 Create Manual Invoice
- **Route**: `/invoice/create-manual`, `/invoice/manual`
- **Controller**: `InvoiceController@createManual`, `InvoiceController@storeManual`
- **Role**: Owner
- **Tujuan**: Membuat invoice manual (tanpa transaksi terkait)
- **Status**: Fitur tersedia untuk kasus khusus, bukan flow utama

---

### 12. Shift Kasir

#### 12.1 Index Shift
- **Route**: `/shift`
- **Controller**: `ShiftController@index`
- **View**: `resources/views/shift/index.blade.php`
- **Role**: Owner, Admin
- **Tujuan**: Daftar shift kasir
- **Output**: Tabel shift dengan nomor, tanggal, modal awal/akhir, status

#### 12.2 Buka Shift
- **Route**: `/shift/create`
- **Controller**: `ShiftController@create`, `ShiftController@store`
- **View**: `resources/views/shift/create.blade.php`
- **Role**: Owner, Admin
- **Tujuan**: Membuka shift kasir baru
- **Field**:
  - Modal Awal (wajib, nominal positif)
  - Catatan (opsional)
- **Validasi**: Modal awal wajib
- **Proses Sistem**:
  - Generate shift_number (SHIFT-YYYYMMDD-XXX)
  - Set status = 'open'
  - Set waktu_buka = now()
  - Simpan ke tabel `shifts`
- **Output**: Redirect ke index shift

#### 12.3 Tutup Shift
- **Route**: `/shift/close/{id}`, `/shift/updateClose/{id}`
- **Controller**: `ShiftController@close`, `ShiftController@updateClose`
- **View**: `resources/views/shift/close.blade.php`
- **Role**: Owner, Admin
- **Tujuan**: Menutup shift dan rekonsiliasi kas
- **Field**:
  - Modal Akhir (Fisik) (wajib)
  - Catatan (opsional)
- **Validasi**: Modal akhir wajib
- **Proses Sistem**:
  - Hitung total_penjualan dari transaksi_keluar di shift ini
  - Hitung total_penerimaan (cash payment)
  - Hitung selisih_kas = modal_akhir - (modal_awal + total_penerimaan)
  - Set status = 'closed'
  - Set waktu_tutup = now()
- **Output**: Redirect ke index shift dengan rekonsiliasi

---

### 13. Jurnal Finance

#### 13.1 Index Jurnal
- **Route**: `/journal`
- **Controller**: `JournalController@index`
- **View**: `resources/views/journal/index.blade.php`
- **Role**: Owner
- **Tujuan**: Buku besar akuntansi (debit/kredit)
- **Proses Sistem**: Query tabel `journals` dan `journal_items`
- **Output**: Tabel jurnal dengan nomor, tanggal, akun, debit, kredit
- **Status**: UI tersedia, auto-posting dari transaksi belum diverifikasi

---

### 14. Laporan

#### 14.1 Laporan Keuangan
- **Route**: `/laporan/keuangan`
- **Controller**: `LaporanController@ringkasanKeuangan`
- **View**: `resources/views/laporan/ringkasan-keuangan.blade.php`
- **Role**: Owner
- **Tujuan**: Ringkasan keuangan
- **Proses Sistem**: Agregasi data dari transaksi dan expenses
- **Output**: Tabel ringkasan dengan kategori dan nominal

#### 14.2 Laba Rugi
- **Route**: `/laporan/laba-rugi`
- **Controller**: `LaporanController@labaRugi`
- **View**: `resources/views/laporan/laba-rugi.blade.php`
- **Role**: Owner
- **Tujuan**: Laporan Profit & Loss
- **Field**: Start Date, End Date
- **Proses Sistem**:
  - Revenue = total dari transaksi_keluar complete
  - HPP = total dari transaksi_masuk (per periode)
  - Gross Profit = Revenue - HPP
  - Net Profit = Gross Profit - Total Expenses
  - Breakdown expenses per kategori
- **Output**: Tabel laporan dengan revenue, HPP, gross profit, expenses, net profit, margin

#### 14.3 Log Transaksi
- **Route**: `/transaksi/log`
- **Controller**: `TransaksiController@logTransaksi`
- **View**: `resources/views/transaksi/log.blade.php`
- **Role**: Owner, Admin
- **Tujuan**: Riwayat semua transaksi (masuk & keluar)
- **Fitur**: Export Excel/PDF
- **Proses Sistem**: Union transaksi_masuk dan transaksi_keluar
- **Output**: Tabel gabungan dengan filter tanggal

---

### 15. Admin Management (Kelola Karyawan)

#### 15.1 Index Admin
- **Route**: `/admin`
- **Controller**: `AdminController@index`
-View**: `resources/views/admin/index.blade.php`
- **Role**: Owner
- **Tujuan**: Kelola karyawan/admin usaha
- **Field**: Name, Email, Password, Role
- **Proses Sistem**: Simpan ke tabel `admins`
- **Output**: Redirect ke index admin

#### 15.2 Profile (Profil Usaha)
- **Route**: `/profile/*`
- **Controller**: `ProfileController`
- **View**: `resources/views/profile/create.blade.php`, dll
- **Role**: Owner
- **Tujuan**: Mengatur profil usaha (nama toko, logo, dll)
- **Field**: Store name, logo path, dll
- **Proses Sistem**: Simpan ke tabel `profiles`
- **Output**: Redirect ke profile atau dashboard

---

### 16. Subscription (SaaS)

#### 16.1 Paket Langganan
- **Route**: `/paket`
- **Controller**: `PaketController`
- **Role**: Superadmin
- **Tujuan**: Kelola paket subscription
- **Field**: Nama paket, deskripsi, harga, durasi, harga diskon
- **Proses Sistem**: Simpan ke tabel `paket`

#### 16.2 Pembelian Paket
- **Route**: `/subscribe/package`, `/subscribe/store`
- **Controller**: `SubscribeController`
- **Role**: Owner
- **Tujuan**: Beli paket subscription
- **Proses Sistem**: Simpan ke tabel `subscriptions` dengan status pending
- **Output**: Redirect ke pembayaran upload bukti

#### 16.3 Status Subscription
- **Route**: `/subscribe/status`
- **Controller**: `SubscribeController@getStatus`
- **Role**: Owner
- **Tujuan**: Cek status subscription aktif
- **Proses Sistem**: Query subscription dengan end_date >= today dan status approved
- **Output**: JSON response status subscription

---

### 17. Landing Page

#### 17.1 Halaman Utama
- **Route**: `/`
- **Controller**: `LandingPageController@index`
- **View**: `resources/views/landingpage/index.blade.php`
- **Role**: Guest
- **Tujuan**: Halaman publik promosi
- **Fitur**: Info fitur, paket, Coba Gratis, Login, Register

---

## MATRIKS HAK AKSES

| Fitur/Halaman | Owner | Admin/Karyawan | Superadmin | Catatan |
|--------------|-------|----------------|------------|--------|
| Dashboard | ✓ | ✗ | ✗ | Hanya Owner punya dashboard bisnis |
| Produk (CRUD) | ✓ | ✗ | ✗ | Hanya Owner |
| Produk Stok | ✓ | ✗ | ✗ | Hanya Owner |
| Log Produk | ✓ | ✗ | ✗ | Hanya Owner |
| Stock Adjustment | ✓ | ✗ | ✗ | Hanya Owner (admin_id tidak ada = owner only) |
| Transaksi Masuk | ✓ | ✓ (tergantung session) | ✗ | Admin akses tergantung admin_id di session |
| Transaksi Keluar | ✓ | ✓ (tergantung session) | ✗ | Admin akses tergantung admin_id di session |
| POS Kasir | ✓ | ✓ (tergantung session) | ✗ | Admin akses tergantung admin_id di session |
| Expenses | ✓ | ✗ | ✗ | Hanya Owner |
| Invoice / AR | ✓ | ✗ | ✗ | Hanya Owner |
| Jurnal Finance | ✓ | ✗ | ✗ | Hanya Owner |
| Laba Rugi | ✓ | ✗ | ✗ | Hanya Owner |
| Log Transaksi | ✓ | ✓ (tergantung session) | ✗ | Admin akses tergantung admin_id di session |
| Shift Kasir | ✓ | ✓ (tergantung session) | ✗ | Admin akses tergantung admin_id di session |
| Pelanggan | ✓ | ✗ | ✗ | Hanya Owner |
| Supplier | ✓ | ✗ | ✗ | Hanya Owner |
| Admin Management | ✓ | ✗ | ✗ | Hanya Owner |
| Profile | ✓ | ✗ | ✗ | Hanya Owner |
| Subscribe | ✓ | ✗ | ✗ | Hanya Owner |
| Dashboard Superadmin | ✗ | ✗ | ✓ | Hanya Superadmin |
| Manajemen User | ✗ | ✗ | ✓ | Hanya Superadmin |
| Paket | ✗ | ✗ | ✓ | Hanya Superadmin |
| Bank | ✗ | ✗ | ✓ | Hanya Superadmin |

**Catatan Pembatasan Akses**:
- Fitur Owner diblokir oleh middleware `EnsureSubscriptionActive` jika subscription expired
- Admin/Karyawan akses tergantung `session('admin_id')` - tidak ada auto-login khusus role
- Tidak ada auto-switching role berdasarkan login endpoint (manual set session admin_id)

---

## ALUR KERJA OWNER (DARI AWAL HINGGA AKHIR)

### Langkah 1: Register & Setup Profil
1. Buka `/register`
2. Isi Name, Email, Password
3. Login → `/dashboard`
4. Buka `/profile/create`
5. Isi Store Name, upload Logo
6. Simpan → profil usaha tercatat

### Langkah 2: Subscription
1. Buka `/subscribe/package`
2. Pilih paket (Basic, Standard, Premium, dll)
3. Upload bukti pembayaran
4. Tunggu approval dari Superadmin
5. Status subscription berubah ke "active"

### Langkah 3: Kelola Admin/Karyawan (Opsional)
1. Buka `/admin`
2. Klik "Tambah Admin"
3. Isi Name, Email, Password, Role
4. Simpan → admin dapat login

### Langkah 4: Setup Master Data
1. **Supplier**: `/supplier/create` → tambah supplier
2. **Pelanggan**: `/pelanggan/create` → tambah pelanggan
3. **Produk**: `/produk/create` → tambah produk:
   - Nama, SKU (auto), Kategori, Satuan, Harga Beli, Harga Jual, Stok Awal, Min Stok
4. **Stok Awal**: Bisa diisi di form produk atau via Stock Adjustment

### Langkah 5: Pembelian Barang (Opsional)
1. Buka `/transaksi/masuk/create`
2. Pilih Supplier, Tanggal, Metode Pembayaran
3. Tambah items: Produk, Qty, Harga Beli, Diskon
4. Simpan → stok produk bertambah otomatis

### Langkah 6: Buka Shift Kasir
1. Buka `/shift/create`
2. Input Modal Awal (misal: Rp 500.000)
3. Simpan → shift aktif, set session shift_id

### Langkah 7: Penjualan via POS
1. Buka `/transaksi/pos`
2. Sistem cek shift aktif → jika tidak, redirect ke `/shift/create`
3. Pilih Produk dari katalog → tambah ke keranjang
4. Sesuaikan Qty dan Diskon
5. Pilih Pelanggan (opsional) atau "Pelanggan Umum"
6. Pilih Metode Pembayaran: Cash atau Non-Cash
7. Jika tempo → centang "Bayar Nanti / Invoice"
8. Input Jumlah Bayar (jika cash)
9. Klik "Proses Transaksi"
10. **Sistem**:
    - Capture HPP snapshot dari produk
    - Simpan transaksi dengan shift_id
    - Kurangi stok produk
    - Jika tempo → buat invoice di tabel `invoices`
    - Update shift total penjualan
11. Output: Struk/nota transaksi

### Langkah 8: Tutup Shift
1. Buka `/shift`
2. Klik "Tutup" pada shift aktif
3. Input Modal Akhir (fisik)
4. Sistem hitung: Total Penjualan, Total Penerimaan, Selisih Kas
5. Simpan → shift ditutup

### Langkah 9: Pencatatan Biaya Operasional
1. Buka `/expenses/create`
2. Isi Tanggal, Kategori, Nominal, Metode, Keterangan
3. Simpan → tercatat di tabel `expenses`

### Langkah 10: Laporan & Monitoring
1. **Dashboard**: `/dashboard` → lihat ringkasan omset, stok, piutang, laba bersih
2. **Laba Rugi**: `/laporan/laba-rugi` → filter periode → lihat revenue, HPP, expenses, net profit
3. **Invoice/AR**: `/invoice` → lihat piutang, catat pembayaran
4. **Stock Adjustment**: `/stock-adjustment/create` → jika ada selisih stok fisik

---

## ALUR KASIR & KARYAWAN

### Login sebagai Admin/Karyawan
1. Buka `/admin/login`
2. Input Email & Password
3. Login → redirect ke dashboard

### Buka Shift (Wajib)
1. Buka `/shift/create`
2. Input Modal Awal
3. Simpan → shift aktif

### Transaksi Penjualan via POS
1. Buka `/transaksi/pos`
2. Sistem cek shift aktif → jika tidak, redirect ke `/shift/create`
3. Pilih Produk → tambah ke keranjang
4. Input Qty, Diskon
5. Pilih Pelanggan
6. Pilih Metode Pembayaran
7. Jika tempo → centang "Bayar Nanti"
8. Input Jumlah Bayar (jika cash)
9. Proses Transaksi
10. Output: Struk, stok berkurang, shift terupdate

### Tutup Shift
1. Buka `/shift`
2. Tutup shift aktif
3. Input Modal Akhir (fisik)
4. Simpan → rekonsiliasi kas

**Batas Akses Kasir/Karyawan**:
- Tidak dapat mengubah produk, harga, konfigurasi sistem
- Tidak dapat melihat laporan keuangan lengkap
- Akses transaksi tergantung session admin_id (tidak ada auto-role switching)
- Tidak dapat mengelola subscription atau profile usaha

---

## SIMULASI USAHA BAKSO

### Setup Produk
1. **Bakso Urat**: `/produk/create`
   - Nama: "Bakso Urat Spesial"
   - SKU: Auto (misal: PRD-20261029-001)
   - Kategori: "Makanan Berkuah"
   - Satuan: "bowl"
   - Harga Beli: Rp 15.000
   - Harga Jual: Rp 25.000
   - Stok Awal: 50
   - Min Stok: 10

2. **Mi Rebus**: `/produk/create`
   - Nama: "Mi Rebus"
   - Satuan: "porsi"
   - Harga Beli: Rp 5.000
   - Harga Jual: Rp 12.000
   - Stok: 100

3. **Es Teh**: `/produk/create`
   - Nama: "Es Teh Manis"
   - Satuan: "gelas"
   - Harga Beli: Rp 1.000
   - Harga Jual: Rp 3.000
   - Stok: 200

### Pembelian Bahan (Opsional)
1. `/transaksi/masuk/create`
2. Supplier: "Distributor Daging"
3. Item: "Daging Sapi"
4. Qty: 10 kg
5. Harga Beli: Rp 120.000/kg
6. Total: Rp 1.200.000
7. Simpan → stok produk bertambah jika dipisahkan ke produk

**Catatan**: Sistem TIDAK mendukung resep atau konversi bahan baku ke produk jadi. Stok produk adalah stok jadi, bukan stok bahan baku.

### Penjualan via POS
1. Buka shift → Modal Awal: Rp 500.000
2. POS → Pilih:
   - Bakso Urat (2 bowl) = Rp 50.000
   - Mi Rebus (1 porsi) = Rp 12.000
   - Es Teh (1 gelas) = Rp 3.000
3. Total: Rp 65.000
4. Metode: Cash
5. Input Bayar: Rp 65.000
6. Proses → stok berkurang:
   - Bakso Urat: 50 → 48
   - Mi Rebus: 100 → 99
   - Es Teh: 200 → 199
7. Shift terupdate: total penjualan bertambah Rp 65.000

### Biaya Operasional
1. `/expenses/create`
2. Kategori: "Listrik"
3. Nominal: Rp 500.000
4. Simpan → tercatat di expenses

### Laporan Laba Rugi
1. `/laporan/laba-rugi`
2. Filter periode (misal: bulan ini)
3. **Revenue**: Total penjualan dari POS
4. **HPP**: Total dari transaksi masuk (jika ada pembelian dicatat)
5. **Gross Profit**: Revenue - HPP
6. **Net Profit**: Gross Profit - Total Expenses

**Keterbatasan untuk Usaha Bakso**:
- Tidak ada resep/komposisi bahan baku
- Tidak ada konversi stok bahan ke produk jadi
- Stok produk tidak otomatis sama dengan stok bahan
- Tidak ada fitur meja, nomor pesanan, status pembuatan pesanan
- Tidak ada split bill
- Tidak ada dukungan khusus restoran (meja, kitchen display)

---

## SIMULASI KAFE

### Setup Menu
1. **Kopi Susu**: `/produk/create`
   - Satuan: "cup"
   - Harga Beli: Rp 8.000
   - Harga Jual: Rp 18.000
   - Stok: 100

2. **Kopi Latte**: `/produk/create`
   - Satuan: "cup"
   - Harga Beli: Rp 10.000
   - Harga Jual: Rp 25.000
   - Stok: 80

3. **Roti Bakar**: `/produk/create`
   - Satuan: "pcs"
   - Harga Beli: Rp 5.000
   - Harga Jual: Rp 12.000
   - Stok: 50

### Penjualan via POS
1. POS → Kopi Susu (1 cup) + Roti Bakar (1 pcs) = Rp 30.000
2. Metode: Cash
3. Input Bayar: Rp 30.000
4. Proses → stok berkurang
5. Output: Struk

**Keterbatasan untuk Kafe**:
- Tidak ada varian produk (size small/medium/large)
- Tidak ada topping atau modifier
- Tidak ada fitur meja, nomor pesanan
- Tidak ada status pembuatan pesanan (preparing, ready, served)
- Tidak ada split bill
- Satuan produk terbatas (tidak bisa jual "Kopi Latte + Topping Tambahan" dalam satu item)

---

## SIMULASI JASA BABY SPA

### Setup Produk (Jasa sebagai Produk Non-Fisik)
1. **Pijat Bayi**: `/produk/create`
   - Nama: "Pijat Bayi Full Body"
   - Satuan: "sesi"
   - Harga Beli: Rp 50.000 (upah terapis)
   - Harga Jual: Rp 100.000
   - Stok: 10
   - Min Stok: 2

2. **Baby Spa Regular**: `/produk/create`
   - Nama: "Baby Spa Regular 30 menit"
   - Satuan: "sesi"
   - Harga Beli: Rp 30.000
   - Harga Jual: Rp 60.000
   - Stok: 20

### Penjualan via POS
1. POS → Pijat Bayi Full Body (1 sesi) = Rp 100.000
2. Pilih Pelanggan: "Ibu Ani"
3. Metode: Non-Cash (Tempo/Invoice)
4. Centang "Bayar Nanti / Invoice"
5. Proses →
   - Transaksi disimpan
   - Invoice dibuat otomatis di `invoices`
   - Stok jasa berkurang (10 → 9)
6. Output: Invoice dengan sisa tagihan Rp 100.000

### Catat Pembayaran
1. `/invoice` → daftar piutang muncul
2. Klik "Bayar" → input nominal Rp 50.000
3. Proses → sisa tagihan Rp 50.000, status: partial
4. Bayar lagi Rp 50.000 → sisa Rp 0, status: paid

**Keterbatasan untuk Baby Spa**:
- Tidak ada fitur booking atau jadwal
- Tidak ada kalender
- Tidak ada pencatatan riwayat kunjungan per pelanggan
- Tidak ada fitur DP/pelunasan khusus untuk jasa
- Jasa dicatat sebagai produk non-fisik dengan stok, bukan sebagai jasa dengan slot waktu
- Tidak ada rekam layanan terapis spesifik per kunjungan

---

## DIAGRAM ALIRAN DATA

```mermaid
graph TD
    Produk -->|Pembelian|Transaksi Masuk Items
    Supplier -->|Pembelian|Transaksi Masuk
    Transaksi Masuk -->|Stok+|Products
    Products -->|POS|Transaksi Keluar Items
    Products -->|HPP Snapshot|Transaksi Keluar Items
    User(Owner) -->|Shift|Shift
    Shift -->|POS|Transaksi Keluar
    User(Owner) -->|Admin|Admin
    Admin -->|Transaksi Keluar|Shift
    Pelanggan -->|Invoice|Invoice
    Transaksi Keluar -->|Invoice|sales_transaction_id
    Transaksi Keluar -->|Stok-|Products
    Transaksi Keluar -->|Shift|shift_id
    Transaksi Keluar -->|HPP Snapshot|Transaksi Keluar Items.hpp_snapshot
    Transaksi Keluar -->|Void|is_void=true
    Void -->|Stok+|Products
    Expenses -->|Laba Rugi|expenses
    Transaksi Masuk -->|Laba Rugi|hpp
    Transaksi Keluar -->|Laba Rugi|revenue
    Stock Adjustment -->|Stok|Products
    Stock Adjustment -->|Nilai Kerugian|total_nilai_selisih
```

**Hubungan yang Sudah Diterapkan:**
- ✓ Transaksi Masuk → Update stok produk
- ✓ POS/Transaksi Keluar → Update stok produk
- ✓ Transaksi Keluar → Capture HPP snapshot
- ✓ Transaksi Keluar → Link ke Shift
- ✓ Transaksi Keluar dengan is_invoice = true → Auto-create Invoice
- ✓ Void Transaksi → Kembalikan stok
- ✓ Stock Adjustment → Update stok dan hitung nilai kerugian
- ✓ Transaksi Masuk/Keluar → Input untuk Laba Rugi (HPP dan Revenue)
- ✓ Expenses → Input untuk Laba Rugi (beban operasional)

**Hubungan yang Diterapkan Sebagian:**
- ⚠️ Invoice → Auto-create dari Transaksi Keluar, tapi manual invoice tersedia untuk kasus khusus
- ⚠️ Jurnal Finance → UI tersedia, auto-posting dari transaksi belum diverifikasi
- ⚠️ PPN/Tax → Belum diimplementasikan

**Hubungan yang Belum Ada:**
- ✗ Produk → Komposisi bahan baku (resep)
- ✗ Transaksi Masuk → HPP per item (HPP saat ini dihitung agregat)
- ✗ Produk → Varian/topping/modifier
- ✗ Pelanggan → Riwayat kunjungan
- ✗ Transaksi Keluar → Split bill
- ✗ POS → Nomor meja, status pesanan

---

## DASHBOARD & LAPORAN

### Dashboard Owner
**Indikator:**
- **Omset Penjualan**: Total dari Transaksi Keluar (periode terpilih)
- **Total Produk/SKU**: Jumlah produk di tabel products
- **Piutang/Invoice**: Total sisa_tagihan dari invoices
- **Laba Bersih**: Net Profit dari Laba Rugi (Revenue - HPP - Expenses)

**Sumber Data:** TransaksiKeluar, Product, Invoice, Expenses
**Periode:** User-selectable (default: hari ini/bulan ini)
**Auto-update:** Ya (real-time saat query)
**Role:** Owner

### Laba Rugi
**Indikator:**
- **Revenue**: Total `total_harga` dari Transaksi Keluar (status complete, periode terpilih)
- **HPP**: Total dari Transaksi Masuk (periode terpilih)
- **Gross Profit**: Revenue - HPP
- **Beban Operasional**: Total nominal dari Expenses (periode terpilih)
- **Net Profit**: Gross Profit - Beban Operasional
- **Margin:** Net Profit / Revenue (jika revenue > 0)

**Sumber Data:** TransaksiKeluar, TransaksiMasuk, Expenses
**Periode:** Start Date & End Date (user input)
**Rumus Perhitungan:** Terverifikasi di `LaporanController@labaRugi`
- Revenue = `TransaksiKeluar::where('status', 'complete')->whereBetween('tanggal_transaksi', $startDate, $endDate)->sum('total_harga')`
- HPP = `TransaksiMasuk::whereBetween('tanggal_transaksi', $startDate, $endDate)->sum('total_harga')`
- Gross Profit = Revenue - HPP
- Net Profit = Gross Profit - Total Expenses

**Include Void/Belum Lunas?**
- ✅ Void: transaksi dengan `is_void = true` biasanya di-exclude dari revenue (tidak diverifikasi di kode)
- ⚠️ Belum Lunas: Invoice dengan sisa_tagihan > 0 termasuk dalam total invoice, tapi status pendapatan belum diverifikasi

---

## FITUR YANG BELUM TERSEDIA ATAU BELUM TERINTEGRASI

### Fitur Hanya UI / Placeholder:
- ⚠️ Jurnal Finance → UI tersedia, auto-posting dari transaksi belum diverifikasi
- ⚠️ API endpoints cascading dropdown (brand, weight, serial number) → Dibuat untuk logam mulia, belum relevan untuk UMKM umum

### Fitur Belum Tersedia:
- ✗ Barcode scanner (UI belum ada)
- ✗ Loyalty/CRM campaigns
- ✗ Multi-warehouse/cabang
- ✗ Stock transfer antar lokasi
- ✗ PPN/Pajak
- ✗ Split bill
- ✗ Nomor meja
- ✗ Status pesanan (preparing, ready, served)
- ✗ Booking / Jadwal
- ✗ Riwayat kunjungan pelanggan
- ✗ Komposisi bahan baku / resep
- ✗ Varian produk (size, topping, modifier)
- ✗ Konversi satuan
- ✗ Automatic posting ke Jurnal Finance
- ✓ Stock Adjustment → Tersedia dengan hitung nilai kerugian

### Fitur Terintegrasi Sebagian:
- ⚠️ Invoice → Auto-create dari Transaksi Keluar saat `is_invoice = true`, tapi manual invoice tersedia untuk kasus khusus
- ⚠️ HPP → Dihitung agregat dari Transaksi Masuk per periode, bukan per item per transaksi

---

## RISIKO & KONSEKUENSI DATA

### Potensi Penghitungan Ganda:
- ⚠️ Jika satu transaksi masuk dicatat, lalu stok produknya dijual, HPP bisa dihitung dua kali (saat pembelian dan saat Laba Rugi)
- ⚠️ Jika void transaksi hanya menandai is_void=true tanpa membatasi akses laporan transaksi void, revenue dan laba rugi bisa tidak akurat

### Potensi Data Tidak Konsisten:
- ⚠️ Admin akses tergantung session admin_id yang diset manual - bisa ada kesalahan human
- ⚠️ Tidak ada validasi untuk memastikan stok fisik = stok sistem setelah stock adjustment
- ⚠️ Tidak ada validasi untuk memastikan transaksi void tidak di-void ulang

### Keterbatasan Data Isolasi:
- ✅ User isolation via `user_id` sudah ada di model Product, TransaksiKeluar, dll
- ⚠is Business ID isolation: Field `business_id` ada di Product tapi belum ada implementasi multi-business owner

---

## REKOMENDASI PRIORITAS

### Prioritas Kritis (Keamanan & Konsistensi):
1. Tambah validasi untuk mencegah void transaksi yang sudah dibayar
2. Tambah validasi untuk mencegah stock adjustment ketika stok fisik kurang dari transaksi aktif
3. Perbaiki HPP per item (bukan agregat) untuk akurasi laba rugi
4. Implementasi validasi admin_id yang lebih robust (auto-set berdasarkan login admin, bukan manual session)

### Prioritas Tinggi (Alur Operasional):
1. Tambah auto-posting ke Jurnal Finance dari transaksi
2. Implementasi PPN/pajak (optional tapi penting untuk compliance)
3. Tambah validasi stok sebelum transaksi (jika stok <= 0, block transaksi)
4. Tambah konfirmasi sebelum void transaksi (tampilkan dampak ke keuangan)

### Prioritas Menengah (Laporan & UX):
1. Tambah filter "exclude void" di Laba Rugi
2. Tambah visualisasi pending vs paid di Dashboard
3. Tambah export semua laporan ke PDF/Excel yang lebih lengkap
4. Perbaiki search dan filter yang lebih intuitif

### Prioritas Khususus (Vertical UMKM):
1. Untuk Restoran: resep, komposisi bahan, konversi satuan
2. Untuk Kafe: varian produk, topping, meja, nomor pesanan
3. Untuk Jasa: booking, jadwal, riwayat kunjungan

---

## CATATAN PENTING

1. **Data Legacy Logam Mulia**: Sistem masih memiliki field legacy (jenis_logam, berat, kadar, purity, cost_price, dll) untuk kompatibilitas, tapi tidak digunakan dalam fitur UMKM umum.

2. **Session Admin ID**: Akses admin/karyawan tidak otomatis berdasarkan login `/admin/login`. Owner harus set session admin_id secara manual atau melalui fitur yang belum ada.

3. **HPP Calculation**: Saat ini HPP dihitung agregat dari total Transaksi Masuk per periode, bukan per item per transaksi. Ini bisa kurang akurat jika ada pencampuran produk.

4. **Invoice Logic**: Invoice utama di-generate otomatis dari Transaksi Keluar dengan `is_invoice = true`. Invoice manual tersedia untuk kasus khusus tapi bukan flow utama.

5. **Subscription Middleware**: Pembatasan akses fitur owner saat subscription expired menggunakan middleware `EnsureSubscriptionActive`. Beberapa route dikecualikan dari pembatasan (profile, subscribe, logout).

6. **Stok Management**: Stok produk disimpan langsung di `products.stock` sebagai single point of truth. Tidak ada tabel inventory terpisah atau batch/lot tracking.

7. **Void Transaction**: Void hanya menandai `is_void = true` dan mengembalikan stok. Tidak ada pembatasan waktu (bisa void kapan saja).

8. **File Gambar Logo**: File gambar logo masih menggunakan nama lama "IdoTech x GolDay.png". Perlu diganti dengan file logo Aksara yang baru jika ada.

---

## FILE & DATABASE YANG PENTING

### Model Utama:
- `User` - user authentication
- `Profile` - profil usaha
- `Product` - master produk
- `TransaksiMasuk` & `TransaksiMasukItem` - pembelian
- `TransaksiKeluar` & `TransaksiKeluarItem` - penjualan
- `Invoice` & `InvoiceItem` - piutang
- `Shift` - shift kasir
- `StockAdjustment` & `StockAdjustmentItem` - opname
- `Expenses` - biaya operasional
- `Journal` & `JournalItem` - jurnal akuntansi
- `Pelanggan` - master pelanggan
- `Supplier` - master supplier
- `Admin` - karyawan/admin usaha
- `Subscribe` - subscription
- `Paket` - paket subscription
- `Bank` - rekening platform (Superadmin)

### Migrations Penting:
- `2026_10_27_110037_add_invoice_fields_to_transactions` - menambah shift_id, is_invoice, void fields
- `2026_10_27_111210_create_shifts_table` - membuat tabel shift
- `2026_10_27_111906_add_void_fields_to_transaksi_keluar` - menambah void fields
- `2026_10_27_112740_add_total_nilai_selisih_to_stock_adjustment_items` - menambah nilai kerugian ke stock adjustment

---

## STATUS IMPLEMENTASI

### ✓ Sudah Berfungsi & Terintegrasi:
- POS Kasir dengan HPP snapshot
- Shift Kasir dengan rekonsiliasi kas
- Void Transaksi dengan pengembalian stok
- Stock Adjustment dengan nilai kerugian
- Invoice/AR dari transaksi tempo
- Master Pelanggan & Supplier
- Expenses & Laba Rugi basic
- Subscription middleware & pembatasan akses
- Export Excel/PDF untuk transaksi dan laporan

### ⚠️ Berfungsi Sebagian:
- Jurnal Finance (UI ada, auto-posting belum diverifikasi)
- HPP calculation (agregat, bukan per item)
- Data isolation via user_id sudah ada, tapi business_id belum diimplementasi

### ✗ Belum Tersedia:
- Barcode scanner
- Loyalty/CRM campaigns
- Multi-warehouse/cabang
- Stock transfer
- PPN/pajak
- Split bill
- Nomor meja & status pesanan
- Booking/jadwal
- Riwayat kunjungan pelanggan
- Resep/komposisi bahan baku
- Varian produk (topping, size)
- Konversi satuan

---

**Dokumentasi ini dibuat berdasarkan pemeriksaan kode sumber project pada tanggal 29 Oktober 2026. Semua penjelasan berdasarkan implementasi yang benar-benar ada, tanpa mengarang fitur yang tidak dapat diverifikasi.**
