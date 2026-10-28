# LAPORAN T-02: Shift Mencatat Siapa Kasirnya
Tanggal: 29 Oktober 2026

---

## TUJUAN
Owner tahu kasir mana yang memegang shift.

---

## FILE YANG DIUBAH:
1. `app/Http/Controllers/ShiftController.php`
2. `resources/views/shift/index.blade.php` (SUDAH BENAR, tidak perlu ubah)

---

## PERUBAHAN YANG DILAKUKAN:

### 1. Controller (`ShiftController.php`)
- **Lama:** `'admin_id' => session('admin_id')`
- **Baru:** `'admin_id' => session('admin_id') ?: null`
- **Keterangan:** Jika owner buka shift (tidak ada session admin_id), admin_id akan null

### 2. View (`shift/index.blade.php`)
- **Status:** SUDAH BENAR
- Line 60: `{{ $item->admin->name ?? auth()->user()->name }}`
- Jika admin_id ada → tampilkan nama admin/karyawan
- Jika admin_id null (owner) → tampilkan nama owner

---

## CARA MENGUJI:
1. Login sebagai owner, buka shift → cek di `/shift` → harus tampil nama owner sebagai kasir
2. Login sebagai admin/karyawan, buka shift → cek di `/shift` → harus tampil nama admin/karyawan sebagai kasir

---

## HAL YANG BELUM SELESAI:
- Tidak ada

---

## CATATAN:
- Tidak perlu tambah kolom database karena admin_id sudah ada
- Penanda kasir sudah ada melalui admin_id, hanya perlu pastikan logic sudah benar
