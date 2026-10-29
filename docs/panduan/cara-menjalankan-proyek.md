# Cara Menjalankan Proyek Aksara

Panduan ini menjelaskan cara menyiapkan dan menjalankan Aksara secara lokal di Windows menggunakan PowerShell. Aksara adalah aplikasi Laravel dengan aset frontend yang dikelola oleh Vite.

## Prasyarat

Pastikan perangkat sudah memiliki:

- PHP **8.3 atau lebih baru**, dengan ekstensi `pdo_sqlite`, `mbstring`, `openssl`, dan `fileinfo` aktif.
- Composer.
- Node.js **20.19+** atau **22.12+**, beserta NPM.
- Git, jika repositori belum tersedia di komputer.

Periksa instalasi melalui PowerShell:

```powershell
php -v
composer --version
node --version
npm --version
```

Jika PHP tidak menemukan ekstensi SQLite, aktifkan `pdo_sqlite` dan `sqlite3` pada `php.ini` yang digunakan oleh PHP CLI, lalu buka kembali PowerShell.

## Menyiapkan proyek

Jika belum memiliki salinan proyek, clone repositori lalu masuk ke foldernya:

```powershell
git clone https://github.com/Aksara-Group12/Aksaraweb.git
Set-Location Aksaraweb
```

Jika proyek sudah ada di komputer, buka PowerShell pada folder utama proyek.

### 1. Pasang dependensi

```powershell
composer install
npm ci
```

Gunakan `npm install` jika instalasi `npm ci` tidak dapat dilakukan karena `package-lock.json` tidak tersedia atau berubah.

### 2. Buat konfigurasi lokal

Salin template environment jika file `.env` belum ada:

```powershell
Copy-Item .env.example .env
```

Buat kunci aplikasi:

```powershell
php artisan key:generate
```

Konfigurasi bawaan `.env.example` menggunakan SQLite. Siapkan file database lokal:

```powershell
if (-not (Test-Path .\database\database.sqlite)) {
    New-Item -ItemType File -Path .\database\database.sqlite | Out-Null
}
```

Nilai `DB_CONNECTION` bawaan adalah `sqlite`; database digunakan dari `database/database.sqlite`. Jika ingin menggunakan MySQL atau MariaDB, ubah pengaturan `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env`, lalu siapkan database tersebut terlebih dahulu.

### 3. Siapkan tabel database

```powershell
php artisan migrate
```

Jika aplikasi perlu menampilkan file yang diunggah ke storage publik, buat symbolic link:

```powershell
php artisan storage:link
```

Jalankan seeder hanya jika membutuhkan data uji yang disediakan proyek:

```powershell
php artisan db:seed
```

Seeder membuat satu pengguna uji. Hindari memakai data uji untuk penggunaan produksi.

### 4. Jalankan aplikasi

Buka dua jendela PowerShell pada folder proyek.

Di jendela pertama, jalankan server Laravel:

```powershell
php artisan serve
```

Di jendela kedua, jalankan Vite untuk membangun aset selama pengembangan:

```powershell
npm run dev
```

Buka alamat yang ditampilkan oleh Artisan, biasanya <http://127.0.0.1:8000>. Biarkan kedua proses tetap berjalan selama menggunakan aplikasi; hentikan dengan `Ctrl+C`.

## Perintah tambahan

Bangun aset frontend untuk penggunaan tanpa Vite development server:

```powershell
npm run build
```

Jalankan pengujian aplikasi:

```powershell
php artisan test
```

## Pemecahan masalah

- **`could not find driver`**: aktifkan `pdo_sqlite` untuk konfigurasi PHP CLI, atau pastikan ekstensi driver database yang dipilih aktif.
- **Database SQLite tidak ditemukan**: pastikan file `database/database.sqlite` sudah dibuat dan `.env` menggunakan `DB_CONNECTION=sqlite`.
- **Perubahan `.env` tidak terbaca**: bersihkan cache konfigurasi dengan `php artisan config:clear`.
- **Port 8000 sedang digunakan**: jalankan server dengan port lain, misalnya `php artisan serve --port=8001`, lalu buka alamat yang ditampilkan.
- **Aset tidak termuat**: pastikan `npm install` atau `npm ci` selesai dan proses `npm run dev` masih berjalan; untuk build statis, jalankan `npm run build`.
