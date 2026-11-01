# 📜 AI AGENT CODING & COLLABORATION RULES — AKSARA WEB
**Dokumen Panduan & Aturan Resmi untuk AI Coding Assistant (Antigravity, Devin, Cursor, Claude, Copilot, dll.)**  
*Versi:* 1.0  
*Terakhir Diperbarui:* 01 November 2026  

---

## 🎯 1. Filosofi & Arsitektur Utama Proyek
Aksara Web adalah aplikasi **Point of Sale (POS), Manajemen Inventori, dan Pembukuan Keuangan UMKM** terpadu berbasis Laravel.
- **Stack Inti:** Laravel 11/12, PHP 8.2+, Tailwind CSS, Blade Template Engine, Lucide Icons, Vanilla JS / Alpine.js.
- **Prinsip Dasar:**
  1. **Ringan & Cepat:** Jangan gunakan dependensi JavaScript eksternal yang besar/berat jika kebutuhan dapat diselesaikan dengan CSS modern dan native browser API.
  2. **Toleran & Defensif:** Kode harus mampu menangani data kosong (*null-safe*) tanpa menyebabkan HTTP 500.
  3. **Konsisten & Selaras:** Seluruh tim dan agent harus menghormati standar visual dan konvensi skema yang telah disepakati.

---

## 🛡️ 2. Aturan Kolaborasi Git & Pencegahan Konflik (SANGAT KRUSIAL)
> [!IMPORTANT]
> **Penyebab Konflik 60 File Sebelumnya:** Terjadi karena AI agent melakukan perombakan skema dan penghapusan file secara sepihak langsung di branch `main` tanpa membaca kontribusi rekan tim yang sedang berjalan secara paralel.

Setiap AI Agent **WAJIB MEMATUHI** aturan kerja berikut:

### A. Prosedur Wajib Sebelum Mulai Ngoding (Pre-flight Git Check)
> [!IMPORTANT]
> **ATURAN WAJIB DI AWAL SETIAP SESI / TUGAS:**
> Setiap AI Agent **WAJIB** mengecek kondisi Git terlebih dahulu sebelum menulis atau mengedit baris kode apa pun:
> 1. **Cek Status Lokal:**
>    ```bash
>    git status
>    ```
> 2. **Periksa Perubahan dari Rekan Tim (Fetch Remote):**
>    ```bash
>    git fetch origin
>    git status
>    ```
> 3. **Wajib Pull Jika Ada Push Terbaru dari Rekan Tim:**
>    - Jika status menunjukkan branch lokal tertinggal (*"Your branch is behind 'origin/main' by X commits"*), agent **WAJIB langsung menjalankan `git pull origin main`** terlebih dahulu.
>    - Pastikan seluruh kode terbaru dari rekan tim sudah masuk ke lokal secara mulus sebelum agent mulai menambahkan atau mengubah kode, agar **tidak terjadi tabrakan atau konflik file**.

### B. Selalu Baca & Pahami File Sebelum Mengubah (`No Blind Overwrite`)
- **DILARANG KERAS** menimpa (*overwrite*) file secara menyeluruh tanpa membaca isi file terlebih dahulu.
- Gunakan tool edit yang presisi. Periksa apakah ada fitur rekan tim (misal: menu ekspor PDF/Excel, footer profil, modal konfirmasi) di file tersebut.
- Jika ada kontribusi rekan tim, terapkan pendekatan **Hybrid Integration**: gabungkan logika baru tanpa menghapus fitur lama yang masih valid.

### C. Disiplin Push & Commit
- **DILARANG KERAS menjalankan `git push --force`** ke branch utama (`main`).
- Jangan jalankan `git commit` secara sembarangan kecuali jika user secara spesifik menginstruksikannya.

### D. Lindungi Fitur Rekan Tim
File-file berikut merupakan komponen sensitif yang sering mengalami tabrakan:
1. `resources/views/component/sidebar.blade.php`: Menggabungkan menu dinamis tim dengan footer profile card dan indikator role milik Faraysz.
2. `resources/views/layouts/horizon.blade.php`: Memuat modul modal universal, Geist font import, dan auto-injector password toggle.
3. `app/Http/Controllers/AuthController.php`: Alur login tunggal yang sudah distabilkan.

---

## 🗄️ 3. Konvensi Skema Basis Data & Model (Status Terkini)
Pahami skema terkini berikut agar tidak membuat kode yang memanggil tabel/kolom yang sudah tidak ada:

1. **Manajemen Pengguna & Profil:**
   - Tabel `profiles` **TELAH DIHAPUS (DROPPED)**.
   - Seluruh data nama, email, nomor HP, dan role tersimpan langsung di tabel `users`.
   - Model `App\Models\Admin` telah dipensiunkan; gunakan `App\Models\User` untuk otentikasi semua tingkatan user.

2. **Paket Berlangganan (*Subscription*):**
   - Model langganan saat ini menggunakan paket **berbasis durasi waktu** (`Subscribe` dengan kolom `durasi_hari`):
     - `Aksara Daily` (1 Hari)
     - `Aksara 3 Days` (3 Hari)
     - `Aksara Weekly` (7 Hari)
     - `Aksara Monthly` (30 Hari)
   - **TIDAK ADA PEMBATASAN FITUR:** Semua paket mendapatkan akses 100% penuh ke seluruh modul sistem.
   - Sisa hari aktif wajib dihitung dengan pembulatan ke atas tanpa pecahan desimal (`ceil(max(0, $diffInDays))`).

3. **Modul Pengeluaran Operasional:**
   - Gunakan rute `pengeluaran.*` dan tabel `pengeluarans`. Modul `expenses` telah di-refactor.

4. **Data Master:**
   - Master data aktif: `Kategori Produk`, `Kategori Pengeluaran`, `Role`, `Pengguna`, dan `RBAC`.

---

## 🎨 4. Standar Desain UI/UX & Tipografi

### A. Tipografi: Geist Sans vs Newsreader
- **Panel Admin / Dashboard / Data Master / Laporan / POS:**
  - **WAJIB menggunakan font Geist Sans.**
  - **DILARANG** menggunakan font *Newsreader Serif* pada judul kartu, tabel, modal, atau form di area operasional toko.
- **Landing Page (`resources/views/landingpage/index.blade.php`):**
  - Menggunakan font *Newsreader Serif* khusus untuk editorial headline utama dan *Plus Jakarta Sans* untuk teks isi.

### B. Animasi & Interaktivitas
- Gunakan transisi halus dengan kurva pegas ala Framer Motion: `cubic-bezier(0.16, 1, 0.3, 1)`.
- Prioritaskan GPU-accelerated CSS (`transform`, `opacity`, `filter`).
- Gunakan *Native Intersection Observer* untuk mendeteksi scroll reveal.
- Matikan animasi jika user mengaktifkan mode hemat daya/aksesibilitas via `@media (prefers-reduced-motion: reduce)`.

### C. Input Password & Keamanan Form
- Setiap input bertipe password (`type="password"`) di modal maupun halaman form **wajib memiliki tombol toggle intip kata sandi** (icon mata `lucide-eye` / `lucide-eye-off`).

---

## ✅ 5. Quality Gate: Prosedur Verifikasi Sebelum Menyelesaikan Tugas
Sebelum memberitahu user bahwa tugas telah selesai, AI agent **WAJIB menjalankan langkah verifikasi berikut**:

1. **Verifikasi Sintaks Blade & View Cache:**
   ```bash
   php artisan view:clear
   php artisan view:cache
   ```
   *Jika ada error compile Blade, segera perbaiki sebelum turn selesai.*
2. **Bersihkan Cache Template untuk Development:**
   ```bash
   php artisan view:clear
   ```
3. **Cek Log Kesalahan:**
   Periksa apakah ada exception baru di `storage/logs/laravel.log`.

---

## 💬 6. Gaya Komunikasi dengan Pengguna
- **Bahasa:** Selalu gunakan Bahasa Indonesia yang ramah, sopan, santai/semi-formal, dan solutif.
- **Transparansi:** Jelaskan ringkasan perubahan teknis dengan bahasa yang mudah dipahami.
- **File Link:** Selalu sertakan link ke file yang dimodifikasi menggunakan format `file:///path/to/file`.
- **Integritas:** Jangan menghapus komentar atau kode yang sudah berfungsi dengan baik kecuali atas permintaan user.

