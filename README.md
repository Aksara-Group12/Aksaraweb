<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<div align="center">

# 💼 Aksara - Sistem Kasir & Bisnis UMKM

**Sistem kasir, stok, CRM & keuangan terpadu untuk transformasi digital UMKM Indonesia**

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com/)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com/)
[![License](https://img.shields.io/badge/License-Proprietary-4CAF50?style=for-the-badge)](LICENSE)

</div>

---

## 📖 Tentang Aksara

**Aksara** adalah sistem manajemen bisnis terpadu yang dirancang khusus untuk **UMKM Indonesia** (Retail, F&B, dan Jasa). Kami membantu transformasi dari pencatatan manual menuju tata kelola bisnis berbasis digital yang transparan dan efisien.

### 🎯 Visi
Transformasi UMKM Indonesia dari pencatatan manual menuju tata kelola bisnis berbasis digital yang transparan dan efisien.

### 🚀 Misi
- **Kasir POS Serbaguna**: Transaksi cepat tunai/non-tunai/tempo dengan pencatatan akurat dan cetak nota instan.
- **Kontrol Stok & Opname Presisi**: Alert stok menipis, tracking pergerakan stok, dan penyesuaian selisih stok fisik vs sistem.

---

## ✨ Fitur Utama

### 🛒 **1. Kasir POS (Point of Sale)**
- Transaksi cepat dengan antarmuka yang user-friendly dan responsif.
- Mendukung berbagai metode pembayaran (tunai, transfer/non-tunai, dan piutang/tempo).
- Cetak nota/struk belanja instan.
- Manajemen kasir dan pergantian shift kasir.

### 📦 **2. Manajemen Stok & Inventory**
- Pelacakan pergerakan stok barang secara *real-time*.
- Sistem alert dan peringatan otomatis jika stok produk menipis.
- Fitur Stock Opname untuk penyesuaian stok fisik dengan data sistem.
- Riwayat transaksi barang masuk dan keluar yang detail dan transparan.

### 🤝 **3. CRM (Customer Relationship Management)**
- Basis data master pelanggan dan supplier terpusat.
- Riwayat riil transaksi belanja per pelanggan.
- Manajemen pencatatan dan jatuh tempo piutang/hutang bisnis.

### 📊 **4. Laporan Keuangan Otomatis**
- Pencatatan jurnal dan pembukuan otomatis dari setiap transaksi penjualan & pengeluaran.
- Laporan laba/rugi otomatis.
- Arus kas (*cash flow*) harian, bulanan, maupun tahunan.
- Pembuatan dan pengelolaan invoice penjualan resmi.

### 📈 **5. Dashboard & Analytics**
- Statistik performa penjualan dan pendapatan *real-time*.
- Pemantauan produk terlaris (*best seller*) dan perputaran barang.
- Panel manajemen subscription dan paket langganan SaaS.

---

## 🎯 Siapa yang Cocok Menggunakan Aksara?

| Sektor | Contoh Bidang Usaha |
|---|---|
| 🏪 **Retail** | Toko kelontong, minimarket, toko pakaian/butik, toko elektronik, optik |
| 🍽️ **F&B** | Restoran, coffee shop/kafe, warung makan, bakery, usaha katering |
| 💇 **Jasa** | Salon, barbershop, bengkel kendaraan, laundry, klinik kesehatan |

---

## 🛠️ Tech Stack

- **Backend:** [Laravel 11.x](https://laravel.com) (PHP 8.2+)
- **Frontend:** Blade Templating, [Tailwind CSS](https://tailwindcss.com), [Vite](https://vitejs.dev)
- **Database:** [MySQL](https://www.mysql.com/) / MariaDB
- **Autentikasi & Otorisasi:** Multi-role (Superadmin, Owner, Admin)

---

## 🚀 Panduan Instalasi

### Prasyarat Sistem
Pastikan perangkat Anda sudah terinstal:
- **PHP** >= 8.2 (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `curl`)
- **Composer** (Package Manager PHP)
- **Node.js** >= 18.x & **NPM**
- **MySQL** / MariaDB Server

---

### Langkah Instalasi

#### 1. Clone Repository
```bash
git clone https://github.com/Aksara-Group12/Aksaraweb.git
cd Aksaraweb
```

#### 2. Install Dependensi (Backend & Frontend)
```bash
# Install paket PHP via Composer
composer install

# Install pustaka Node.js via NPM
npm install
```

#### 3. Konfigurasi Environment (`.env`)
Salin file template `.env.example` menjadi `.env`:
```bash
cp .env.example .env
# Jika menggunakan Windows CMD/PowerShell:
copy .env.example .env
```

Generate application encryption key:
```bash
php artisan key:generate
```

Buka file `.env` dan sesuaikan koneksi database MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aksara_web
DB_USERNAME=root
DB_PASSWORD=
```

#### 4. Migrasi & Import Database
Jalankan migrasi tabel database:
```bash
php artisan migrate
```

> **Alternatif Data Awal:** Jika Anda memiliki file dump SQL (misalnya `golday.sql`), Anda dapat mengimpornya langsung ke database:
> ```bash
> mysql -u root aksara_web < golday.sql
> ```

#### 5. Buat Symbolic Link Storage
Untuk memastikan berkas unggahan publik (seperti logo toko atau bukti pembayaran) dapat diakses:
```bash
php artisan storage:link
```

#### 6. Menjalankan Aplikasi
Buka dua sesi terminal terpisah:

- **Terminal 1 (Server Laravel):**
  ```bash
  php artisan serve
  ```
  *Aplikasi akan berjalan di `http://127.0.0.1:8000`*

- **Terminal 2 (Vite Asset Bundler):**
  ```bash
  npm run dev
  ```

Buka browser Anda dan akses:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 📄 Lisensi

Hak Cipta © 2026 **Aksara Developer**. Seluruh hak cipta dilindungi undang-undang (*Proprietary Software*).
Dikembangkan menggunakan fondasi framework terbuka [Laravel](https://laravel.com) di bawah [MIT License](https://opensource.org/licenses/MIT).
