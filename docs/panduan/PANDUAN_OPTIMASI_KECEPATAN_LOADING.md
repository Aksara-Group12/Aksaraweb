# Panduan Lengkap Optimasi Kecepatan Loading (Performance Guide)

**Proyek:** Aksara Web (POS & Manajemen Toko)  
**Dokumen:** Panduan Optimasi Performa & Kecepatan Akses  
**Status:** Diterapkan di Kode Lokal  

---

## 1. Pendahuluan

Kecepatan loading (*loading speed / latency*) sebuah aplikasi web berbasis Laravel dan Blade sangat dipengaruhi oleh tiga lapisan utama:
1. **Frontend & Asset Delivery** (CSS, Font, dan Script yang memblokir proses render browser).
2. **Backend & Framework Overhead** (Pembacaan file konfigurasi, routing, dan Blade compilation).
3. **Database & Environment Windows** (Query database yang berulang dan latency resolusi host lokal).

Panduan ini mendokumentasikan optimasi yang telah diterapkan langsung pada kode Aksara Web serta langkah-langkah praktis yang dapat dijalankan oleh tim pengembang.

---

## 2. Optimasi yang Telah Diterapkan di Kode Proyek ⚡

Berikut adalah perbaikan kode yang telah diterapkan langsung pada template:

### A. Menghapus `@import` Google Fonts yang Memblokir Render (Render-Blocking)
- **Lokasi File:** `resources/views/layouts/horizon.blade.php`
- **Masalah Sebelumnya:**  
  Di dalam tag `<style>`, terdapat baris:
  ```css
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
  @import url('https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');
  ```
  Di browser modern, `@import` di dalam tag `<style>` memaksa browser berhenti memproses HTML sampai kedua file font eksternal selesai diunduh. Hal ini menyebabkan layar putih (*white blank screen*) selama 1–2 detik saat pertama kali halaman dibuka.
- **Solusi yang Diterapkan:**  
  Kedua font (`Inter` dan `Newsreader`) disatukan ke dalam satu `<link>` tunggal di dalam `<head>` dengan parameter `&display=swap`:
  ```html
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,400..700;1,6..72,400&display=swap" rel="stylesheet">
  ```
  Parameter `&display=swap` memastikan teks langsung muncul dengan font sistem terlebih dahulu tanpa menunggu font Google selesai diunduh.

---

### B. Migrasi CDN Ikon & Penambahan Atribut `defer`
- **Lokasi File:** 
  - `resources/views/layouts/horizon.blade.php`
  - `resources/views/landingpage/index.blade.php`
- **Masalah Sebelumnya:**  
  Menggunakan `https://unpkg.com/lucide@latest` sinkron tanpa `defer` di `<head>`. CDN Unpkg tidak memiliki server edge di banyak kota di Indonesia dan tag `@latest` memicu HTTP 302 Redirect setiap kali diminta.
- **Solusi yang Diterapkan:**  
  Beralih ke CDN **jsDelivr** (yang memiliki Anycast server di Jakarta & Singapura) dengan versi terkunci dan penambahan atribut `defer`:
  ```html
  <script src="https://cdn.jsdelivr.net/npm/lucide@0.468.0/dist/umd/lucide.min.js" defer></script>
  ```
  Atribut `defer` membuat file JavaScript diunduh secara paralel di latar belakang tanpa menahan browser menampilkan elemen HTML.

---

### C. Pembersihan Query Database Tak Terpakai di Komponen Header
- **Lokasi File:** `resources/views/component/header.blade.php`
- **Masalah Sebelumnya:**  
  Pada baris atas file header, terdapat baris:
  ```php
  $paketType = $isOwner ? \App\Models\Subscribe::getPackageType($currentUser->id) : 1;
  $currentProfile = $currentUser ? ($currentUser->profile ?? \App\Models\Profile::where('user_id', $currentUser->id)->first()) : null;
  ```
  Query ini dieksekusi **pada setiap klik halaman** di seluruh dashboard toko, padahal variabel `$paketType` sama sekali tidak pernah ditampilkan di komponen header.
- **Solusi yang Diterapkan:**  
  Menghapus kedua query tak terpakai tersebut sehingga mengurangi 1–2 panggilan query database ke MySQL pada setiap navigasi menu.

---

## 3. Perintah Cache Artisan Laravel (Paling Ampuh)

Secara default, pada mode *development*, Laravel membaca puluhan file konfigurasi dan mencocokkan puluhan rute dari awal di setiap permintaan HTTP.

### Perintah Caching untuk Kecepatan Maksimal:
Jalankan perintah ini di terminal proyek:

```bash
# 1. Menggabungkan puluhan file config menjadi 1 file cache di memori
php artisan config:cache

# 2. Meng-compile seluruh routes ke dalam hash-map cepat
php artisan route:cache

# 3. Melakukan pre-compile pada semua file Blade template
php artisan view:cache
```

### Cara Membersihkan Cache Kembali (Jika Sedang Edit Kode):
Jika kamu sedang mengubah file `.env` atau menambahkan rute baru di `routes/web.php` dan perubahannya belum terlihat, jalankan:
```bash
php artisan optimize:clear
```

---

## 4. Trik Lingkungan Pengembangan di Windows

Pengembang yang menjalankan Laravel di Windows (menggunakan Laragon, XAMPP, atau `php artisan serve`) sering mengalami perlambatan karena 2 konfigurasi berikut:

### A. Akses Menggunakan `127.0.0.1`, Jangan `localhost`
- **Masalah:** Pada sistem operasi Windows, hostname `localhost` secara default akan mencoba meresolusi alamat IPv6 (`::1`) terlebih dahulu selama ~1000ms sebelum *timeout* dan beralih ke alamat IPv4 (`127.0.0.1`).
- **Solusi:** Di bilah URL browser, selalu ketik:
  ```
  http://127.0.0.1:8000
  ```
  Bukan `http://localhost:8000`. Ini memangkas delay 1 detik pada setiap klik halaman.

---

### B. Mengaktifkan Ekstensi PHP OPcache
OPcache adalah fitur bawaan PHP yang menyimpan kode bytecode PHP yang telah dikompilasi langsung di memori RAM, sehingga komputer tidak perlu membaca ulang ratusan file PHP dari harddisk (SSD/HDD) pada setiap *refresh*.

**Cara Mengaktifkan di `php.ini`:**
1. Buka file `php.ini` (melalui Laragon / XAMPP / folder PHP Anda).
2. Cari kata `opcache` dan pastikan konfigurasi berikut aktif (tidak diawali tanda titik koma `;`):
   ```ini
   zend_extension=opcache
   opcache.enable=1
   opcache.enable_cli=1
   opcache.memory_consumption=128
   opcache.max_accelerated_files=10000
   opcache.revalidate_freq=2
   ```
3. Restart server Apache / Nginx / terminal `php artisan serve`.
4. *Hasil:* Waktu respon halaman dapat meningkat **2x hingga 4x lebih cepat**.

---

## 5. Praktik Terbaik Optimasi Query Database (Eager Loading)

Hindari masalah **N+1 Query** pada Controller saat menampilkan data tabel:

- **Contoh Kurang Efisien (N+1 Query):**
  ```php
  // Ini akan melakukan 1 query untuk mengambil order,
  // lalu 1 query tambahan untuk SETIAP baris order untuk mengambil user!
  $orders = Order::all();
  foreach ($orders as $order) {
      echo $order->user->name;
  }
  ```
- **Contoh Efisien (Eager Loading dengan `with`):**
  ```php
  // Hanya melakukan 2 query total ke database sekaligus!
  $orders = Order::with('user')->get();
  ```

---

## 6. Ringkasan Checklist Cepat

| Kategori | Tindakan | Dampak |
|---|---|---|
| **Frontend** | Hapus `@import` di CSS, gunakan `<link>` dengan `display=swap` | Menghilangkan blank screen 1-2 detik |
| **Frontend** | Gunakan jsDelivr CDN + atribut `defer` untuk Lucide icon | Browser memproses HTML tanpa terblokir |
| **Backend** | Hapus query database redundant di komponen Blade | Menurunkan beban koneksi MySQL |
| **Laravel** | Jalankan `php artisan config:cache` & `route:cache` | Respon bootstrap Laravel menjadi instan |
| **Windows** | Buka via `http://127.0.0.1:8000` | Memangkas delay resolusi DNS IPv6 ~1 detik |
| **PHP** | Aktifkan `opcache` di `php.ini` | Eksekusi file PHP 2x–4x lebih cepat via RAM |

