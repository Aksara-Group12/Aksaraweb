# 🚀 Catatan Perjalanan & Riwayat Perubahan Aksara Web
**Tanggal:** 03 November 2026  
**Penulis / Kontributor:** Faraysz & Tim Pengembang Aksara  
**Proyek:** Aksara Developer — Sistem Kasir, Stok, Pelanggan, & Keuangan UMKM  

---

## 📌 Ringkasan Eksekutif
Pengembangan pada tanggal **03 November 2026** berfokus pada standardisasi visual, modernisasi sistem filter interaktif di seluruh dashboard dan modul laporan utama, harmonisasi tipografi form control menggunakan **Inter**, penyelarasan halaman autentikasi, serta pembentukan pedoman eksekusi agen pengembangan (*agent coding rules*). 

Semua pembaruan ini menghadirkan antarmuka yang jauh lebih profesional, responsif, dan konsisten di setiap lini modul Aksara Web.

---

## 🛠️ Rincian Perubahan & Peningkatan Sistem

### 1. Modernisasi Sistem Filter Interaktif Bergaya FeriPresence
Sebelumnya, sistem filter pada dashboard dan laporan menggunakan dropdown kaku sederhana dengan tombol hitam standar. Hari ini telah dirombak total mengadopsi desain panel filter interaktif modern (*collapsible*):

- **Dashboard Superadmin (`dashboardsuperadmin.blade.php` & `DashboardSuperadminController.php`):**
  - Mengimplementasikan **Action Bar Header** dengan tombol filter biru solid (`btn-filter-trigger`) yang dilengkapi ikon Lucide `filter` dan animasi *active pulse dot* saat filter sedang aktif.
  - Menghadirkan panel collapsible interaktif dengan dukungan filter multi-dimensi:
    - **Status Langganan:** Filter *Semua Status*, *Berlangganan Aktif*, *Uji Coba (Trial)*, dan *Kedaluwarsa*.
    - **Paket Langganan:** Filter *Semua Paket* serta pilihan dinamis paket aktif dari database.
    - **Periode Waktu:** Shortcut cepat (*Bulan Ini*, *Hari Ini*, *Minggu Ini*, *Tahun Ini*, *Semua Data*, *Rentang Khusus*).
    - **Rentang Tanggal:** Input *Tanggal Awal* dan *Tanggal Akhir* dengan kalender interaktif dan auto-sync.
    - **Tombol Aksi:** Tombol *Reset* (mengembalikan filter ke default) dan tombol *Cari*.
  - Controller diperbarui untuk mendukung filtering komprehensif pada metrik total pendapatan, merchant aktif, volume transaksi kasir, dan grafik tren sistem.

- **Dashboard Owner Toko (`dashboard.blade.php` & `DashboardController.php`):**
  - Menggantikan dropdown lama dan tombol hitam "Terapkan" dengan sistem tombol filter collapsible yang sama.
  - Membantu pemilik toko menyaring data performa penjualan kasir, total pendapatan, estimasi profit, pengeluaran operasional, grafik roda penjualan per jam, dan stok menipis berdasarkan rentang waktu yang diinginkan.
  - Backend controller dilengkapi variabel `$isFilterActive`, `$startDate`, `$endDate`, dan `$period`.

- **Laporan Ringkasan Keuangan (`laporan/ringkasan-keuangan.blade.php` & `LaporanController.php`):**
  - Menerapkan tombol filter collapsible yang disandingkan secara presisi dengan tombol **Export Tracking Produk**.
  - Sinkronisasi instan terhadap indikator utama (*Core Metrics*): *Revenue*, *Gross Profit*, *Operating Expenses*, dan *Net Profit*, serta riwayat transaksi penjualan dan pengeluaran operasional.

- **Laporan Produk Terlaris (`laporan/produk-terlaris.blade.php` & `LaporanController.php`):**
  - Mengganti filter lama dengan collapsible filter panel seragam.
  - Pemilik toko dapat dengan mudah melihat peringkat produk paling laris berdasarkan rentang tanggal tertentu untuk analisis stok dan strategi penjualan.

---

### 2. Standardisasi Tipografi: Form Controls & Dropdown Menggunakan Font Inter
Berdasarkan arahan desain agar komponen form dan dropdown memiliki keterbacaan (*readability*) maksimal dan tidak memakai Geist Sans maupun serif:

- **Pengaturan Khusus pada Komponen Filter:**
  - Seluruh elemen filter dropdown (`.filter-select`), daftar item dropdown (`.filter-select option`, `optgroup`), input tanggal (`.filter-input-date`), label form (`.filter-field-label`), serta tombol filter (`.btn-filter-reset`, `.btn-filter-submit`) secara tegas diatur menggunakan:
    ```css
    font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
    ```
- **Penguatan Global pada Layout Utama (`layouts/horizon.blade.php`):**
  - Menambahkan aturan global untuk seluruh elemen form control di dashboard admin, owner, dan kasir:
    ```css
    input, select, select *, select option, optgroup, textarea, button, label, .form-control, .form-select {
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
    }
    ```
  - Menjamin bahwa saat dropdown diklik dan daftar pilihan terbuka di browser (Chromium, Firefox, Safari), font yang dirender adalah **Inter** yang bersih dan presisi.

---

### 3. Penyelarasan Halaman Autentikasi (Login & Register)
- **Eliminasi Serif pada Panel Masuk Akun:**
  - Menghapus penggunaan font *Newsreader* serif pada halaman:
    - [`resources/views/auth/login.blade.php`](file:///d:/Projects/Aksara-Developer/Aksaraweb/resources/views/auth/login.blade.php) (Login Merchant/Owner/Kasir)
    - [`resources/views/auth/admin-login.blade.php`](file:///d:/Projects/Aksara-Developer/Aksaraweb/resources/views/auth/admin-login.blade.php) (Login Khusus Superadmin)
    - [`resources/views/auth/register.blade.php`](file:///d:/Projects/Aksara-Developer/Aksaraweb/resources/views/auth/register.blade.php) (Pendaftaran Akun Toko Baru)
    - [`resources/views/auth/verify-email.blade.php`](file:///d:/Projects/Aksara-Developer/Aksaraweb/resources/views/auth/verify-email.blade.php) (Verifikasi Email)
  - Judul halaman auth kini menggunakan **Geist Sans** (selaras dengan dashboard), sementara label input, teks bantuan, dan tombol menggunakan **Inter**.
- **Fitur Toggle Password (Ikon Mata):**
  - Seluruh input kata sandi pada formulir autentikasi dan modal tambah pengguna telah terintegrasi dengan tombol ikon mata (Lucide `eye` / `eye-off`) untuk memudahkan pengguna melihat dan memeriksa input kata sandi secara aman.

---

### 4. Penyusunan Pedoman & Tata Kelola AI Agent (`rules.md`)
- **Panduan Eksekusi Agen (`rules.md`):**
  - Membuat berkas panduan khusus di root proyek untuk memandu sistem kerja agent coding agar terarah dan terhindar dari konflik kode (*merge conflict*).
  - Standarisasi alur kerja wajib: melakukan `git status` dan pengecekan sinkronisasi repositori sebelum memulai tugas, menjaga integritas kode yang ada, serta mematuhi preferensi desain dan bahasa Indonesia.

---

## 📁 Berkas-Berkas yang Dimodifikasi / Ditambahkan
1. `app/Http/Controllers/DashboardController.php` — Penambahan variabel filter status & rentang tanggal dashboard owner.
2. `app/Http/Controllers/DashboardSuperadminController.php` — Penyesuaian filter logic superadmin.
3. `app/Http/Controllers/LaporanController.php` — Penambahan logika filter aktif pada Ringkasan Keuangan dan Produk Terlaris.
4. `resources/views/layouts/horizon.blade.php` — Penguatan font Inter pada global select, option, input, label, dan form controls.
5. `resources/views/dashboard.blade.php` — Redesain filter collapsible dashboard owner & styling Inter.
6. `resources/views/dashboardsuperadmin.blade.php` — Penerapan collapsible filter superadmin & styling Inter.
7. `resources/views/laporan/ringkasan-keuangan.blade.php` — Penerapan collapsible filter laporan keuangan & export tracking.
8. `resources/views/laporan/produk-terlaris.blade.php` — Penerapan collapsible filter laporan produk terlaris.
9. `resources/views/auth/login.blade.php` — Penyelarasan font judul ke Geist Sans & font input ke Inter.
10. `resources/views/auth/admin-login.blade.php` — Penyelarasan font judul ke Geist Sans & font input ke Inter.
11. `resources/views/auth/register.blade.php` — Penyelarasan font judul ke Geist Sans & font input ke Inter.
12. `resources/views/auth/verify-email.blade.php` — Penyelarasan font judul ke Geist Sans.
13. `docs/perubahan_03_11_2026.md` — Dokumentasi resmi riwayat perubahan hari ini.

