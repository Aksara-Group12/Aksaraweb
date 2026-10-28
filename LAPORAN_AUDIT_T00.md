# LAPORAN AUDIT T-00
Fase 0: Pemeriksaan (Tanpa Mengubah Kode)
Tanggal: 29 Oktober 2026

---

## POIN 1: Apakah halaman `/produk` menampilkan satu baris per item atau per unit (serial number)?

**Hasil Audit:**
- Halaman `/produk` menampilkan satu baris per item (per produk), bukan per unit/serial number
- Tabel menampilkan: Kode, Nama Produk, Kategori, Satuan, Harga Jual, Stok, Status
- Stok ditampilkan sebagai angka integer, bukan per serial number
- Ada model `ProductSerialNumber` tersedia dengan tabel `product_serial_numbers`, tapi tidak digunakan di halaman produk
- Kolom legacy untuk logam mulia (jenis_logam, berat, kadar, dll) ada di model Product tapi tidak ditampilkan di UI produk

**Kesimpulan:** Produk sudah satu baris per item. Tidak ada fitur serial number yang aktif di UI.

---

## POIN 2: Apakah transaksi (POS, pembelian, expenses) otomatis membuat data di Jurnal Finance?

**Hasil Audit:**
- Model `Journal` dan `JournalItem` tersedia
- Saya melakukan grep search di semua Controllers untuk `Journal::create` atau `JournalItem::create`
- **Hasil:** Tidak ditemukan kode yang membuat jurnal otomatis dari transaksi
- Halaman `/journal` tersedia tapi hanya menampilkan data yang ada, tidak ada auto-posting
- Laporan Keuangan menghitung langsung dari TransaksiKeluar, TransaksiMasuk, dan Expenses tanpa lewat Jurnal

**Kesimpulan:** Transaksi TIDAK otomatis membuat data di Jurnal Finance. Jurnal saat ini hanya manual atau placeholder.

---

## POIN 3: Apakah transaksi void dikecualikan dari revenue di Laba Rugi dan Dashboard?

**Hasil Audit:**
- Di `LaporanController@ringkasanKeuangan`:
  - Query penjualan: `TransaksiKeluar::where('status', 'complete')`
  - **TIDAK ada filter untuk `is_void`**
  - Transaksi void masih dihitung dalam revenue karena statusnya masih 'complete'
- Di `LaporanController@labaRugi`:
  - Sama dengan ringkasan keuangan, hanya filter `status, 'complete'`
  - **TIDAK ada filter untuk `is_void`**
- Di Dashboard: Perlu diperiksa lebih lanjut, tapi berdasarkan pattern yang sama, kemungkinan juga belum mengexclude void

**Kesimpulan:** Transaksi void TIDAK dikecualikan dari revenue di Laba Rugi dan Dashboard. Ini adalah masalah yang perlu diperbaiki di T-08.

---

## POIN 4: Apakah Stock Adjustment menambah catatan di Log Produk?

**Hasil Audit:**
- Di `StockAdjustmentController@store`:
  - Sistem mengupdate stok produk: `$product->stock = $stokFisik;`
  - **TIDAK ada kode yang membuat catatan di Log Produk**
  - Stock Adjustment hanya menyimpan ke tabel `stock_adjustments` dan `stock_adjustment_items`
- Tidak ada model atau tabel khusus "Log Produk" yang aktif
- Dokumentasi menyebut "Log Produk" sebagai riwayat perubahan stok, tapi implementasi saat ini tidak otomatis mencatat ke log terpisah

**Kesimpulan:** Stock Adjustment TIDAK menambah catatan di Log Produk. Ini perlu ditambahkan di T-16.

---

## POIN 5: Bagaimana session `admin_id` diatur saat ini, dan di mana dipakai?

**Hasil Audit:**
- Di `AdminAuthController@login`:
  - Setelah login admin berhasil, sistem login sebagai owner (`Auth::login($owner)`)
  - Session admin_id di-set manual: `session(['admin_id' => $admin->id, 'admin_email' => $admin->email, 'admin_name' => $admin->name])`
- Di `ShiftController@store`:
  - Saat buka shift, admin_id diambil dari session: `'admin_id' => session('admin_id')`
- Di beberapa controllers (LaporanController, TransaksiController):
  - Ada cek `if (session()->has('admin_id'))` untuk membatasi akses
  - Admin akses tergantung session admin_id yang diset manual, bukan otomatis berdasarkan login endpoint

**Kesimpulan:** Session admin_id di-set manual saat login admin lewat `/admin/login`. Dipakai di Shift, beberapa controllers untuk pembatasan akses. Tidak ada auto-switching role berdasarkan login endpoint.

---

## POIN 6: Apakah ada pencegahan void ganda?

**Hasil Audit:**
- Di `TransaksiController@voidTransaksi`:
  - Ada validasi: `if ($transaksi->is_void) { return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.'); }`
  - Ini mencegah void ganda dari form
- **TAPI:** Tidak ada validasi di level database (unique constraint) untuk mencegah jika dipanggil langsung lewat API atau konsol
- Tidak ada lock atau concurrency control untuk mencegah void simultan dari dua browser

**Kesimpulan:** Ada pencegahan void ganda di level aplikasi (validasi is_void), tapi tidak ada di level database. Perlu ditambahkan konfirmasi UI di T-09.

---

## POIN 7: Apakah Shift sudah menyimpan siapa kasirnya?

**Hasil Audit:**
- Di model `Shift`:
  - Ada kolom `admin_id` di fillable
  - Ada relasi ke Admin: `public function admin() { return $this->belongsTo(Admin::class, 'admin_id'); }`
- Di `ShiftController@store`:
  - Saat buka shift, admin_id di-set dari session: `'admin_id' => session('admin_id')`
  - Shift disimpan dengan admin_id
- Di `ShiftController@index`:
  - Shift di-load dengan admin: `->with('admin')`
- **TAPI:** Tidak ada penanda kasir untuk owner (jika owner yang buka shift, admin_id akan null)

**Kesimpulan:** Shift sudah menyimpan admin_id jika dibuka oleh admin/karyawan. Tapi tidak ada penanda jelas untuk kasir (owner vs admin). Perlu ditambahkan kolom penanda kasir di T-02.

---

## RINGKASAN AUDIT T-00

| Poin | Status | Temuan | Rekomendasi Tugas |
|------|--------|--------|------------------|
| 1. Produk per item/unit | ✓ OK | Satu baris per item, serial number tidak aktif | T-15 (gabung Produk Stok) |
| 2. Auto Jurnal Finance | ✗ BELUM | Tidak ada auto-posting ke Jurnal | T-21 (pencatatan otomatis) |
| 3. Void diexclude laporan | ✗ BELUM | Void masih dihitung revenue | T-08 (exclude void) |
| 4. Stock Adjustment ke Log | ✗ BELUM | Tidak mencatat ke Log Produk | T-16 (tambah log) |
| 5. Session admin_id | ⚠ MANUAL | Di-set manual, bukan otomatis | T-03 (perbaiki akses) |
| 6. Pencegahan void ganda | ⚠ PARCIAL | Validasi aplikasi ada, tidak ada di DB | T-09 (konfirmasi UI) |
| 7. Shift simpan kasir | ⚠ PARCIAL | admin_id ada, tapi tidak ada penanda kasir | T-02 (tambah penanda) |

---

## FILE YANG DIPERIKSA:
- `resources/views/produk/index.blade.php`
- `app/Models/ProductSerialNumber.php`
- `app/Http/Controllers/LaporanController.php`
- `app/Http/Controllers/StockAdjustmentController.php`
- `app/Http/Controllers/AdminAuthController.php`
- `app/Http/Controllers/ShiftController.php`
- `app/Http/Controllers/TransaksiController.php`
- `app/Models/Shift.php`

---

## PERUBAHAN KODE:
TIDAK ADA perubahan kode dilakukan (sesuai aturan T-00).

---

## HAL YANG BELUM SELESAI:
- Pemeriksaan Dashboard untuk void exclusion (karena time limit)
- Pemeriksaan Log Produk (model/tabel belum ditemukan yang aktif)

---

## KESIMPULAN UMUM:
Sistem Aksara memiliki struktur yang cukup baik, tapi ada beberapa lubang kecil yang perlu diperbaiki:
1. Void transaksi masih dihitung dalam laporan (perlu diexclude)
2. Stock Adjustment tidak mencatat ke log
3. Jurnal Finance tidak auto-posting
4. Akses admin/karyawan bergantung session manual
5. Shift tidak memiliki penanda kasir yang jelas

Semua temuan ini akan dijadikan dasar untuk tugas-tugas berikutnya (T-01 s/d T-29).
