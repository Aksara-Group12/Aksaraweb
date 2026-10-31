# 📊 Analisis Proyek: Aksara Developer (Aksara POS)

Dokumen ini memuat analisis komprehensif mengenai arsitektur sistem, tumpukan teknologi (*tech stack*), modul fungsional, dan pembagian peran pengguna (*user roles*) pada aplikasi **Aksara Developer (Aksara POS)**.

---

## 1. Tentang Proyek (Project Overview)

**Aksara Developer / Aksara POS** adalah aplikasi web berbasis *Software-as-a-Service (SaaS)* yang dirancang sebagai sistem Point of Sale (POS) dan Enterprise Resource Planning (ERP) mini untuk Usaha Mikro, Kecil, dan Menengah (UMKM), toko retail, serta komoditas khusus (seperti toko logam mulia/perhiasan dan perdagangan umum).

### Masalah yang Diselesaikan:
- **Operasional Kasir & Shift**: Mencegah kebocoran kas dengan pencatatan shift kasir, modal awal kasir, dan penutupan shift kasir (*closing shift*).
- **Arus Kas & Piutang (Tempo)**: Penjualan dapat dilakukan secara tunai (*cash*) maupun bertahap/kredit (*tempo*), lengkap dengan pencatatan invoice dan pembayaran piutang bertahap.
- **Otomasi Persediaan & HPP**: Stok barang berkurang otomatis saat penjualan dan bertambah saat pembelian. Sistem menghitung Harga Pokok Penjualan (HPP) dan margin keuntungan bersih secara seketika (*real-time*).
- **Model Bisnis SaaS Berlangganan**: Menyediakan mekanisme multi-tier lisensi toko (Trial, Standar, Premium, Enterprise) dengan alur verifikasi bukti pembayaran manual oleh Superadmin.

---

## 2. Tech Stack (Tumpukan Teknologi)

Aplikasi ini dibangun menggunakan arsitektur monolitik modern berbasis ekosistem PHP dan Laravel terbaru:

### A. Backend & Core Engine
| Komponen | Teknologi | Keterangan |
|---|---|---|
| **Bahasa Pemrograman** | **PHP ^8.3** | Memanfaatkan fitur modern PHP 8.3 (type safety, readonly properties, performance optimization). |
| **Framework Utama** | **Laravel 13.x** | Menggunakan arsitektur MVC (Model-View-Controller), routing terstruktur, dan middleware otentikasi. |
| **Database & ORM** | **MySQL / MariaDB** via **Eloquent ORM** | Relasi antar tabel kompleks, migration versioning, dan database transaction support. |
| **Ekspor PDF** | **barryvdh/laravel-dompdf (^3.1)** | Digunakan untuk mencetak nota transaksi, laporan laba rugi, log produk, dan laporan pengeluaran. |
| **Debugging & Tooling** | **Laravel Tinker, Pail, Pint** | Tooling bawaan Laravel untuk inspeksi data, tailing log real-time, dan linting standar kode PSR-12. |

### B. Frontend & User Interface
| Komponen | Teknologi | Keterangan |
|---|---|---|
| **Template Engine** | **Laravel Blade** | Templating dinamis yang terpisah rapi menjadi layout (`layouts/horizon.blade.php`), komponen sidebar, header, dan modul tampilan. |
| **CSS Framework** | **Tailwind CSS v4.0** | Utility-first CSS untuk responsive grid, spacing, dan layout konsol. |
| **Desain & Tipografi** | **Editorial Luxury Theme** | Mengombinasikan font serif **Newsreader** (heading editorial berkelas) dan sans-serif **Plus Jakarta Sans** (teks isi modern). |
| **Skema Warna Utama** | *Warm Cream* (`#FAF8F5`), *Deep Navy* (`#0F223D`), *Warm Gold* (`#C5A059`), dan *Shadcn Slate*. |
| **Icon Library** | **Lucide Icons (`lucide@latest`)** | Ikon modern, konsisten, dan ringan berstandar Shadcn UI. |
| **Bundler & Build Tool** | **Vite 8.x + Laravel Vite Plugin** | Hot Module Replacement (HMR) dan kompilasi aset cepat. |
| **JavaScript Client** | **Vanilla JS (ES6+) & Axios** | Interaktivitas modal pop-up, kalkulasi form, cascading dropdown API, dan AJAX request. |

---

## 3. Pembagian Peran Pengguna (User Roles & Permissions)

Sistem ini menerapkan mekanisme hak akses berlapis (*Role-Based Access Control*) yang membagi pengguna ke dalam 3 tingkatan peran:

```
                  ┌───────────────────────────────┐
                  │          SUPERADMIN           │
                  │   (Platform & Subscription)   │
                  └──────────────┬────────────────┘
                                 │ Mengelola Akun & Lisensi
                                 ▼
                  ┌───────────────────────────────┐
                  │             OWNER             │
                  │   (Pemilik Toko / Merchant)   │
                  └──────────────┬────────────────┘
                                 │ Menugaskan Pegawai
                                 ▼
                  ┌───────────────────────────────┐
                  │         ADMIN / KASIR         │
                  │     (Staff Operasional POS)   │
                  └───────────────────────────────┘
```

### 1. Superadmin (Administrator Platform)
Pengelola tertinggi sistem SaaS Aksara Developer. Bertanggung jawab atas kesehatan platform, lisensi, dan arus kas langganan.
- **Dashboard Global**: Memantau KPI platform (Total Pengguna, User Berlangganan Aktif, Pendapatan Subscription, Laba Bersih Sistem, dan 5 Pengguna Transaksi Terbesar).
- **Manajemen User (Merchant)**: Mengaktifkan, mengedit, menonaktifkan, atau menghapus akun pemilik toko (*owner*).
- **Verifikasi Pembayaran Langganan**: Memeriksa bukti transfer struk pembayaran paket dari merchant dan menyetujui (*approve*) atau menolak (*reject*) permohonan langganan.
- **Manajemen Paket Subscribe**: Menentukan paket langganan (Trial 7 hari, Standar, Premium, Enterprise), harga per bulan, diskon promo, dan fitur paket.
- **Rekening Bank Sistem**: Mengelola nomor rekening bank resmi platform tujuan transfer pembayaran merchant.

### 2. Owner (Pemilik Toko / Merchant UMKM)
Pemilik bisnis atau toko yang berlangganan pada sistem. Memiliki kendali penuh atas manajemen toko dan data finansial usahanya.
- **Dashboard Bisnis Toko**: Ringkasan omset penjualan harian/bulanan, total transaksi, stok kritis, dan grafik keuangan.
- **Operasional Kasir (POS)**: Buka shift, transaksi kasir keluar (penjualan tunai/tempo), cetak nota struk, dan *void* transaksi.
- **Manajemen Inventori & Produk**: Tambah/edit produk, barcode/nomor seri produk (*serial number*), stok opname (*stock adjustment*), dan log mutasi stok barang.
- **Pengadaan & Supplier**: Transaksi masuk barang dari supplier, pencatatan biaya tambahan pengiriman, dan cetak nota barang masuk.
- **Manajemen Pelanggan & Invoice**: Database pelanggan tetap, pembuatan invoice manual/tempo, dan pencatatan pembayaran piutang pelanggan.
- **Keuangan & Akuntansi Ringkas**: Pencatatan beban pengeluaran operasional (*expenses*), jurnal umum otomatis (*general journal*), dan laporan laba-rugi terperinci (Penjualan, HPP, Margin Kotor, Biaya, dan Laba Bersih).
- **Manajemen Admin / Pegawai Kasir**: Membuat akun pegawai kasir toko dan mengontrol status aktif/nonaktif pegawai.
- **Profil Toko & Langganan**: Mengatur identitas bisnis, logo toko untuk nota, serta perpanjangan paket langganan.

### 3. Admin / Kasir (Staff Operasional Toko)
Pegawai atau kasir yang ditugaskan oleh Owner toko untuk menjalankan operasional kasir harian di meja kasir.
- **Login Pegawai**: Melalui portal login kasir toko.
- **Shift Kasir**: Membuka shift dengan menginput modal kas awal (*opening cash*), mencatat penjualan shift berlangsung, dan menutup shift (*closing cash*).
- **Transaksi Penjualan (POS)**: Memindai produk, memilih pelanggan, memproses pembayaran tunai maupun tempo, dan mencetak nota struk kasir pelanggan.
- **Transaksi Masuk**: Membantu penerimaan stok barang masuk dari supplier jika diberi wewenang.
- **Batasan Akses**: Tidak dapat mengubah paket langganan toko, tidak dapat melihat pendapatan bersih rahasia owner di luar izin, dan tidak memiliki akses ke pengaturan Superadmin.

---

## 4. Peta Modul & Alur Bisnis Utama

### A. Alur Transaksi Kasir & Akuntansi Otomatis
```
[Buka Shift Kasir] ──> [Catat Penjualan (Cash/Tempo)] ──> [Stok Berkurang Otomatis]
                                │                                 │
                                ├── Jika Tempo ──> [Buku Piutang & Invoice Terbit]
                                │
                                └── Transaksi Complete ──> [Jurnal Akuntansi Terbentuk]
                                                                  │
                                                                  ▼
                                                      [Laporan Laba Rugi Real-Time]
                                                      (Penjualan - HPP - Beban = Laba Bersih)
```

### B. Alur Berlangganan SaaS (Subscription Lifecycle)
1. User baru mendaftar melalui halaman `/register` dan mendapatkan paket **Trial 7 hari**.
2. Jika masa aktif habis, middleware `EnsureSubscriptionActive` mengarahkan ke halaman pilihan paket.
3. Owner memilih paket, melakukan transfer ke Rekening Bank resmi, dan mengunggah foto bukti transfer.
4. Permohonan masuk ke antrean Superadmin di menu **Manajemen User**.
5. Superadmin memvalidasi keaslian mutasi rekening dan menekan tombol **Setujui / Approve**.
6. Masa aktif paket merchant bertambah secara otomatis sesuai durasi bulan yang dibeli, dan merchant dapat kembali bertransaksi secara penuh.

---

## 5. Ringkasan Direktori Utama Proyek

```
Aksaraweb/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Controller untuk modul POS, Superadmin, Owner, Laporan, dll.
│   │   └── Middleware/           # Guard langganan (EnsureSubscriptionActive) & role redirection.
│   └── Models/                   # 24 Model Eloquent (User, Admin, Product, Transaksi, Subscribe, dll).
├── database/
│   └── migrations/               # Skema tabel database (users, admins, products, subscribes, banks, dll).
├── docs/
│   └── analisis_proyek.md        # Dokumen analisis sistem ini.
├── resources/
│   ├── views/
│   │   ├── auth/                 # Halaman login, register, dan verifikasi email.
│   │   ├── component/            # Sidebar, header, dan modal dialog bersama.
│   │   ├── layouts/              # Master layout (horizon.blade.php).
│   │   ├── superadmin/           # Konsol Superadmin (Manajemen User, Paket, Bank).
│   │   ├── profile/              # Pengaturan profil terpadu & ganti password.
│   │   └── dashboardsuperadmin.blade.php  # Dashboard metrik platform.
└── routes/
    └── web.php                   # Pemetaan seluruh rute web dan API cascading dropdown.
```

---

*Dokumen ini dibuat otomatis sebagai referensi teknis dan fungsional pengembangan sistem Aksara Developer.*

