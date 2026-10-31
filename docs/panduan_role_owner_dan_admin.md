# 📘 Panduan Akun: Cara Menjadi Owner & Admin Kasir

Dokumen ini menjelaskan alur resmi dan mekanisme teknis untuk mendaftar sebagai **Owner (Pemilik Toko)** serta prosedur penugasan dan aktivasi **Admin (Kasir / Pegawai Toko)** pada sistem **Aksara Developer (Aksara POS)**.

---

## 🏢 1. Cara Menjadi OWNER (Pemilik Toko / Merchant)

Peran **Owner** diperuntukkan bagi pemilik usaha atau merchant UMKM yang ingin mengelola operasional toko, inventori, laporan laba rugi, dan manajemen kasir secara mandiri.

```
[Halaman /register] ──> [Input Data Akun] ──> [Auto Role: owner] 
         │
         ├──> [Trial 7 Hari Otomatis Aktif]
         └──> [Kirim Email Verifikasi] ──> [Verifikasi & Login di /login] ──> [Dashboard Toko]
```

### Langkah-langkah Pendaftaran Owner:

1. **Pendaftaran Mandiri (*Self-Registration*)**:
   - Buka peramban (*browser*) dan akses halaman registrasi di:
     ```
     http://127.0.0.1:8000/register
     ```
   - Lengkapi formulir pendaftaran:
     - **Nama Lengkap**: Nama pemilik usaha (contoh: *Wahyu Widodo*).
     - **Alamat Email**: Email aktif yang digunakan untuk login dan menerima nota/pemberitahuan.
     - **Kata Sandi & Konfirmasi**: Minimal 8 karakter kombinasi huruf dan angka.
   - Klik tombol **Daftar**.

2. **Aktivasi Paket Trial Otomatis**:
   - Sistem secara otomatis mencatat akun ke tabel `users` dengan peran **`role = owner`**.
   - Sistem langsung mengaktifkan lisensi **Trial Gratis 7 Hari** (`harga: Rp0`, durasi 7 hari) tanpa perlu menunggu persetujuan manual, sehingga seluruh modul toko langsung terbuka.

3. **Verifikasi Alamat Email**:
   - Sistem mengirimkan surel aktivasi akun ke email yang Anda daftarkan.
   - Buka kotak masuk email Anda dan klik tautan **Verifikasi Email** untuk memvalidasi kepemilikan akun.

4. **Masuk ke Dashboard Toko**:
   - Buka halaman login di:
     ```
     http://127.0.0.1:8000/login
     ```
   - Masukkan email dan password Anda.
   - Sistem akan langsung mengarahkan Anda ke **Dashboard Toko (`/dashboard`)**.

5. **Perpanjangan Langganan (Setelah Masa Trial 7 Hari Berakhir)**:
   - Jika masa trial habis, akses menu **Profil & Berlangganan** (`/subscribe/package`).
   - Pilih paket langganan (Standar, Premium, atau Enterprise).
   - Lakukan transfer pembayaran ke Rekening Bank resmi platform yang tertera.
   - Unggah bukti transfer struk pembayaran.
   - Superadmin akan memverifikasi mutasi dan menyetujui langganan di konsol pusat, dan toko Anda kembali aktif secara penuh.

---

## 🧑‍💼 2. Cara Menjadi ADMIN / KASIR (Staff Toko)

Akun **Admin (Kasir)** **tidak dapat mendaftar sendiri** melalui form registrasi umum publik. Akun Admin **dibuat dan dikontrol langsung oleh Owner Toko**. Setiap akun Admin terikat secara ketat (*foreign key: `user_id`*) ke toko milik Owner yang bersangkutan.

```
[Owner Login] ──> [Menu Manajemen Admin (/admin)] ──> [Form Tambah Admin (/admin/create)]
                                                                  │
                                                                  ▼
[Admin Login via /admin/login atau /login] <── [Aktivasi Email] <── [Tersimpan di tabel `admins`]
```

### Langkah-langkah Pembuatan Admin Kasir oleh Owner:

1. **Owner Membuat Akun Pegawai**:
   - Owner toko masuk ke akun tokonya di `http://127.0.0.1:8000/login`.
   - Pada sidebar navigasi kiri, pilih menu **Manajemen > Manajemen Admin** (`/admin`).
   - Klik tombol **"+ Tambah Admin"** (`/admin/create`).
   - Isi formulir identitas pegawai kasir:
     - **Nama Lengkap Pegawai**: Nama kasir yang bertugas (contoh: *Budi Santoso*).
     - **Email Admin**: Alamat email unik yang digunakan pegawai untuk login.
     - **Nomor Telepon / WhatsApp**: Nomor kontak pegawai kasir.
     - **Alamat Tempat Tinggal**: Alamat pegawai (opsional).
     - **Kata Sandi Awal & Konfirmasi**: Kata sandi awal yang diberikan kepada pegawai kasir (minimal 8 karakter).
     - **Status Akun**: Pastikan diset ke **Active**.
   - Klik tombol **Simpan**.

2. **Aktivasi Akun Pegawai**:
   - Sistem otomatis mengirimkan email verifikasi aktivasi ke surel pegawai kasir (`AdminVerifyEmail`).
   - Pegawai kasir mengklik tautan verifikasi yang ada pada email tersebut untuk mengaktifkan akun.

3. **Login Staff Kasir**:
   - Pegawai kasir dapat login melalui:
     - Portal kasir khusus: `http://127.0.0.1:8000/admin/login`, atau
     - Form login utama: `http://127.0.0.1:8000/login`
   - Masukkan email dan password yang telah didaftarkan oleh Owner.
   - Sistem akan memeriksa tabel `admins`, memastikan status akun `active`, dan mengaitkan sesi transaksi kasir ke toko milik Owner (`user_id`).

4. **Operasional Harian Kasir**:
   - Setelah login, kasir siap bertugas:
     - Buka menu **Shift Kasir** (`/shift`) dan masukkan modal kas awal kasir (*opening balance*).
     - Buka menu **Point of Sale (POS)** (`/transaksi/pos`) untuk melayani pelanggan, memproses transaksi tunai (*cash*) maupun kredit/tempo.
     - Cetak nota struk belanja pelanggan (`/transaksi/keluar/{id}/nota`).
     - Lakukan penutupan shift kasir (*close shift*) saat pergantian jam kerja untuk merekonsiliasi uang fisik kasir dengan catatan sistem.

---

## ⚖️ 3. Perbandingan Hak Akses: Owner vs Admin Kasir

| Parameter | Owner (Pemilik Toko) | Admin (Kasir / Staff) |
|---|---|---|
| **Cara Mendapatkan Akun** | Registrasi mandiri di `/register` | Dibuatkan oleh Owner di menu `/admin` |
| **Penyimpanan Database** | Tabel `users` (`role = owner`) | Tabel `admins` (`user_id = id_owner`) |
| **Portal Login** | `/login` | `/admin/login` atau `/login` |
| **Pengelolaan Lisensi Toko** | ✅ Ya (Pilih paket, bayar langganan) | ❌ Tidak (Hanya pengguna lisensi toko) |
| **Buka / Tutup Shift Kasir** | ✅ Ya | ✅ Ya (Tugas utama) |
| **Input Penjualan (POS Kasir)** | ✅ Ya | ✅ Ya (Tugas utama) |
| **Cetak Nota Struk Kasir** | ✅ Ya | ✅ Ya |
| **Input Pembelian Supplier** | ✅ Ya | ⚠️ Sesuai izin operasional |
| **Laporan Laba Rugi & HPP Toko** | ✅ Ya (Lengkap dengan laba bersih) | ❌ Dibatasi (Rahasia bisnis owner) |
| **Manajemen Pegawai Kasir** | ✅ Ya (Bisa tambah/nonaktifkan kasir) | ❌ Tidak memiliki akses |
| **Profil & Logo Toko** | ✅ Ya | ❌ Tidak memiliki akses |

---

## 🛡️ 4. Catatan Teknis & Keamanan

1. **Isolasi Data Toko (*Multi-Tenancy*)**:
   - Seluruh transaksi yang diinput oleh Admin/Kasir otomatis disematkan `user_id` milik Owner toko bersangkutan, sehingga data produk, penjualan, dan keuangan tidak akan pernah tertukar dengan toko merchant lainnya.
2. **Kontrol Nonaktif Instan**:
   - Jika seorang pegawai kasir berhenti bekerja atau terjadi pelanggaran, Owner dapat langsung mengubah status akun admin menjadi **Inactive** atau melakukan **Soft Delete** pada menu `/admin`. Pegawai tersebut seketika tidak dapat lagi login ke dalam sistem.

---

*Dokumen ini dibuat sebagai panduan resmi operasional akun pada platform Aksara Developer.*

