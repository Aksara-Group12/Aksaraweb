# 🚀 Catatan Perjalanan & Riwayat Perubahan Aksara Web
**Tanggal:** 01 November 2026  
**Penulis / Kontributor:** Faraysz & Tim Pengembang Aksara  
**Proyek:** Aksara Developer — Sistem Kasir, Stok, Pelanggan, & Keuangan UMKM  

---

## 📌 Ringkasan Eksekutif
Hari ini, **1 November 2026**, merupakan salah satu tonggak (*milestone*) pengembangan paling masif untuk Aksara Web. Fokus pengembangan hari ini mencakup penguatan fungsionalitas inti kasir (POS), fleksibilitas paket langganan, penyelarasan identitas visual antarmuka (*Geist Sans typography*), perbaikan stabilitas otentikasi, penambahan fitur keamanan UX (*toggle password*), hingga penyempurnaan estetika *Landing Page* dengan animasi ultra-ringan kelas dunia ala **Framer Motion & ReactBits**.

---

## 🛠️ Rincian Perubahan & Peningkatan Sistem

### 1. Fitur POS Kasir & Laporan Finansial
- **Fitur Baru Close Order (Tutup Kasir / Pergantian Shift):**
  - Menambahkan menu dan fungsionalitas **Close Order** pada POS Kasir dan navigasi sidebar Laporan.
  - Memfasilitasi rekapitulasi sesi kasir: perhitungan modal awal kas kecil, total penjualan tunai & non-tunai, pembatalan/void, serta pencocokan fisik uang laci vs sistem.
  - Penyediaan fitur **Export Laporan Close Order ke format Excel / CSV**.
- **Refinement Ringkasan Keuangan:**
  - Membersihkan metrik yang tidak diperlukan (HPP/COGS dan Piutang Belum Tertagih) dari ringkasan cepat agar laporan laba rugi lebih sederhana, fokus, dan mudah dibaca oleh pemilik UMKM.

---

### 2. Restrukturisasi & Pembaruan Sistem Langganan (*Subscription*)
- **Evolusi Model Paket Berlangganan:**
  - Mengubah skema paket bertingkat (Tier 1/2/3 yang membatasi modul) menjadi paket fleksibel berbasis rentang waktu (*Time-Based Subscription*): **Aksara Daily (1 Hari)**, **Aksara 3 Days (3 Hari)**, **Aksara Weekly (7 Hari)**, dan **Aksara Monthly (30 Hari)**.
  - Seluruh pengguna kini mendapatkan **akses 100% penuh tanpa pembatasan fitur**.
- **Perbaikan Perhitungan Sisa Masa Aktif:**
  - Memperbaiki bug tampilan desimal panjang pada sisa hari aktif (sebelumnya muncul angka desimal pecahan seperti `5.4096640295833 hari`).
  - Mengimplementasikan kalkulasi pembulatan ke atas yang rapi (`ceil`), dengan batas minimum `0 hari` agar tidak muncul nilai negatif.
- **Penyederhanaan Arsitektur Basis Data:**
  - Menghapus ketergantungan tabel legacy `profiles` pada `ProfileController` dan views terkait untuk struktur data yang lebih ramping.

---

### 3. Standardisasi Identitas Visual: Migrasi ke Geist Sans
- **Penyelarasan Tipografi Admin Panel:**
  - Mengganti seluruh penggunaan font *Newsreader Serif* di area panel operasional admin toko dan superadmin menjadi **Geist Sans**.
  - Font Geist Sans diaplikasikan secara konsisten pada:
    - Judul Header & Subjudul Navbar (`layouts/horizon.blade.php`, `component/header.blade.php`)
    - Halaman Dashboard Toko & Superadmin (`dashboardsuperadmin.blade.php`, `dashboard.blade.php`)
    - Seluruh modul Data Master (Pengguna, Role, Kategori Produk, Kategori Pengeluaran, RBAC)
    - Halaman Laporan Keuangan & Close Order
    - Halaman Edit Profil Toko & Paket Berlangganan
  - *Newsreader* tetap dipertahankan secara khusus di Landing Page untuk mempertahankan nuansa editorial editorial-modern yang berkelas.

---

### 4. Redesain Tata Letak (*Layout Grid*) Dashboard Toko
- **Modernisasi Kartu KPI Utama:**
  - 4 Kartu KPI Keuangan (Total Pendapatan, Estimasi Profit, Total Pengeluaran, dan Stok Menipis) diatur ulang menjadi grid **2x2** yang simetris, lega, dan nyaman di mata dengan gaya warna pastel lembut.
- **Roda Penjualan Per Jam (*Hourly Sales Chart*):**
  - Mengubah grafik aktivitas penjualan per jam menjadi **Full Width (lebar penuh)** sehingga visual tren transaksi kasir terlihat sangat detail sepanjang hari.
- **Penyeimbangan Komponen Bawah:**
  - Memindahkan card *Metode Pembayaran* ke baris bawah dan menyandingkannya secara proporsional dengan card *Pengeluaran Per Kategori* dan *Pelanggan Teratas (Top 10)*.
- **Optimalisasi Ruang Header:**
  - Keterangan ringkasan toko (*free text*) dipindahkan ke subtitle header navbar, mengeliminasi ruang kosong yang tidak perlu.

---

### 5. Stabilitas Otentikasi & Arsitektur Login
- **Resolusi Masalah HTTP 500 Login:**
  - Menuntaskan *fatal error* `ErrorException: include(.../Admin.php): Failed to open stream` saat mengakses `/login`.
  - Merestrukturisasi `AuthController.php` dengan menghapus ketergantungan pada model usang `Admin.php` dan menyatukan flow otentikasi login admin/superadmin/owner ke flow pengguna tunggal yang aman dan teruji.

---

### 6. Peningkatan Keamanan & UX: Password Visibility Toggle
- **Fitur Lihat/Sembunyikan Kata Sandi (Icon Mata):**
  - Menambahkan tombol interaktif icon mata (*Lucide Icons* `eye` / `eye-off`) di sisi kanan input password.
  - Diterapkan pada:
    1. Modal Tambah Pengguna Baru (`data-master/pengguna/index.blade.php`)
    2. Modal Edit Pengguna
    3. Halaman Edit Profil Toko & Ganti Kata Sandi (`profile/index.blade.php`)
  - Menyematkan modul *Universal Password Toggle Auto-Initializer* pada layout utama `horizon.blade.php` agar setiap input bertipe password di masa depan langsung memiliki fungsionalitas ini secara otomatis.

---

### 7. Modernisasi Landing Page: Animasi Elegan ala Framer Motion & ReactBits
- **100% Ringan & High-Performance:**
  - Diimplementasikan murni menggunakan **GPU-Accelerated CSS** dan **Native Intersection Observer API** tanpa beban pustaka JS eksternal berat (bebas lag, mulus 60–120 FPS).
- **Efek Visual yang Diterapkan:**
  - **Framer Motion Blur-Fade Stagger:** Elemen Hero (Badge, Editorial Headline, Deskripsi, Tombol CTA) muncul bertahap dengan kurva pegas `cubic-bezier(0.16, 1, 0.3, 1)` dari kondisi blur halus ke tajam.
  - **ReactBits Spotlight Card Hover:** Seluruh kartu (Modul Kasir, Solusi Industri, Alur Kerja, Paket Harga, dan FAQ) memiliki sorotan kilau cahaya (*spotlight radial glow*) yang mengikuti pergerakan kursor mouse pengguna secara dinamis.
  - **Ambient Aura Pulse:** Cahaya latar gradien halus di balik headline hero yang berdenyut tenang.
  - **Floating Shimmer Badge:** Tagline hero melayang lembut secara periodik dengan efek kilau emas berjalan.
  - **Spring Scroll-Reveal:** Tiap bagian halaman muncul secara mulus saat digulir (*scroll-triggered*) dan otomatis berhenti diobservasi untuk efisiensi memori browser.

---

## 📂 Ringkasan File yang Dimodifikasi Hari Ini

| Kategori | File Terkait | Jenis Perubahan |
| :--- | :--- | :--- |
| **Auth & Security** | `app/Http/Controllers/AuthController.php` | Perbaikan login admin, pembersihan model usang |
| **Landing Page** | `resources/views/landingpage/index.blade.php` | Animasi Framer Motion/ReactBits, Spotlight cards |
| **Layouts & Core** | `resources/views/layouts/horizon.blade.php` | Migrasi Geist Sans, Universal Password Toggle injector |
| **Dashboard** | `resources/views/dashboard.blade.php` | Grid 2x2 KPI, Hourly Chart full width, card balancing |
| **Superadmin** | `resources/views/dashboardsuperadmin.blade.php` | Geist Sans typography, UI refinement |
| **Subscription** | `resources/views/subscribe/content.blade.php` | Redesain paket durasi, fix desimal sisa hari |
| **Subscription Model**| `app/Models/Subscribe.php` | Perbaikan logika sisa hari aktif |
| **Data Master** | `resources/views/data-master/pengguna/index.blade.php` | Password toggle, modal styling, Geist Sans |
| **Profil Toko** | `resources/views/profile/index.blade.php` | Password toggle, pembersihan form |
| **Laporan** | `resources/views/laporan/close-order.blade.php` | Penambahan fitur & view close order POS |
| **Laporan** | `resources/views/laporan/ringkasan-keuangan.blade.php` | Pembersihan COGS/AR, typography |
| **Dokumentasi** | `docs/panduan/PANDUAN_OPTIMASI_KECEPATAN_LOADING.md` | Panduan performa & optimasi |
| **Dokumentasi** | `docs/laporan/LAPORAN_RESOLUSI_KONFLIK_MERGE_BRANCH.md` | Dokumentasi resolusi konflik branch Git |

---

## 🎯 Status Akhir & Rekomendasi Selanjutnya
- **Status Sistem:** Stabil, bebas error build/syntax, seluruh view cache berhasil dikompilasi secara bersih.
- **Rekomendasi Tahap Berikutnya:**
  1. Pengujian transaksi end-to-end pada printer thermal Bluetooth dengan struk kasir Close Order.
  2. Evaluasi feedback pengguna terkait responsivitas tampilan di layar tablet / smartphone.

