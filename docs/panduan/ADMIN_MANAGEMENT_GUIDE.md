# 📋 DOKUMENTASI MANAJEMEN ADMIN

## Ringkasan Fitur

Fitur **Manajemen Admin** memungkinkan setiap **Owner** mengelola admin toko mereka dengan lengkap. Admin yang dibuat akan selalu terikat ke **user_id** owner yang membuatnya.

---

## 📁 File-File yang Dibuat

### Backend (PHP/Laravel)

1. **Model**: `app/Models/Admin.php`
   - Model dengan SoftDeletes
   - Relationship ke User model

2. **Controller**: `app/Http/Controllers/AdminController.php`
   - Method: `index()`, `create()`, `store()`, `edit()`, `update()`, `destroy()`, `restore()`, `bulkDestroy()`
   - Termasuk: Search, Filter, Pagination, Authorization

3. **Routes**: `routes/web.php`
   - Prefix: `/admin`
   - Routes: `admin.index`, `admin.create`, `admin.store`, `admin.edit`, `admin.update`, `admin.destroy`, `admin.restore`

4. **Migration**: `database/migrations/2026_04_07_000000_create_admins_table.php`
   - Membuat table `admins` dengan fitur soft delete

### Frontend (Blade Templates)

1. **Index**: `resources/views/admin/index.blade.php`
   - List semua admin dengan pagination
   - Search by name/email/phone
   - Filter by status
   - View deleted records
   - Action buttons (Edit/Hapus/Pulihkan)
   - Checkbox untuk bulk delete

2. **Create**: `resources/views/admin/create.blade.php`
   - Form untuk tambah admin baru
   - Fields: Nama, Email, Phone, Address, Status
   - Validation messages

3. **Edit**: `resources/views/admin/edit.blade.php`
   - Form untuk edit admin existing
   - Pre-populated data

### UI Components

- **Sidebar**: Ditambahkan menu "Manajemen Admin" di section "Manajemen" untuk owner role

---

## 🗄️ Database Schema

### Table `admins`

```sql
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL COMMENT 'Owner yang membuat admin ini',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama lengkap admin',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email admin',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nomor telepon admin',
  `address` text COLLATE utf8mb4_unicode_ci COMMENT 'Alamat admin',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active' COMMENT 'Status admin',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT 'Soft delete timestamp',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_user_unique` (`email`, `user_id`) COMMENT 'Email unik per user',
  KEY `user_id` (`user_id`),
  KEY `status` (`status`),
  KEY `deleted_at` (`deleted_at`),
  CONSTRAINT `admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Manajemen admin untuk setiap owner/user';
```

**Columns:**
- `id` - Primary key (auto increment)
- `user_id` - Foreign key ke table users (owner yang membuat)
- `name` - Nama lengkap admin
- `email` - Email admin (unique per user)
- `phone` - Nomor telepon admin
- `address` - Alamat admin (optional)
- `status` - Status admin (active/inactive)
- `created_at` - Timestamp saat dibuat
- `updated_at` - Timestamp saat diupdate
- `deleted_at` - Timestamp soft delete (NULL jika aktif)

**Indexes:**
- `email_user_unique` - Email unik per user
- `user_id` - Untuk query cepat
- `status` - Untuk filtering
- `deleted_at` - Untuk soft delete query

---

## 🚀 Cara Implementasi di Production

### Langkah 1: Upload Files

Upload file-file berikut ke server:

```
app/Models/Admin.php
app/Http/Controllers/AdminController.php
database/migrations/2026_04_07_000000_create_admins_table.php
resources/views/admin/index.blade.php
resources/views/admin/create.blade.php
resources/views/admin/edit.blade.php
resources/views/component/sidebar.blade.php (sudah terupdate)
routes/web.php (sudah terupdate)
```

### Langkah 2: Run Migration

**Via Terminal/SSH:**
```bash
cd /home/idotechm/public_html
php artisan migrate
```

Atau **Via Database Query** (jika tidak bisa SSH):

Jalankan query dari file: `SQL_ADMINS_TABLE.sql`

```sql
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_user_unique` (`email`, `user_id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`),
  KEY `deleted_at` (`deleted_at`),
  CONSTRAINT `admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Langkah 3: Clear Cache

**Via Artisan:**
```bash
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

**Atau lewat URL:** (jika sudah ada di routing)
```
https://yourdomain.com/clear-cache
```

### Langkah 4: Test

1. Login sebagai **Owner**
2. di Sidebar, di section "Manajemen" akan muncul menu **"Manajemen Admin"**
3. Klik menu untuk akses halaman index
4. Coba fitur: Tambah, Edit, Hapus, Filter, Search, Restore

---

## 📋 Fitur-Fitur yang Tersedia

### ✅ Index (List)
- **Pagination**: 10, 25, 50 per page
- **Search**: Cari berdasarkan Nama, Email, atau Phone
- **Filter Status**: Active / Inactive / Semua
- **Tampil Deleted**: Toggle untuk melihat admin yang sudah dihapus
- **Bulk Delete**: Select multiple admin dan delete sekaligus
- **Total Count**: Menampilkan total admin

### ✅ Create (Tambah)
- Form input: Nama, Email, Phone, Address, Status
- Validation: Email unik per user
- Auto assign `user_id` = current user

### ✅ Edit (Ubah)
- Edit semua field
- Authorization: Hanya owner bisa edit admin miliknya
- Validation tetap berlaku

### ✅ Delete (Hapus)
- Soft Delete (tidak benar-benar dihapus)
- Record bisa di-restore
- Bulk delete support

### ✅ Restore (Pulihkan)
- Restore admin yang sudah dihapus
- Hanya tampil untuk deleted records

### ✅ Authorization
- Admin hanya bisa diakses owner yang membuat
- Superadmin tidak bisa melihat admin milik owner lain
- All operations protected dengan authorization check

---

## 📊 UI Design

### Theme: Dark Mode (Sesuai Existing)
- **Background**: Dark Navy (#020617, #1e293b)
- **Text**: Light Gray (#e5e7eb, #cbd5e1)
- **Actions**: Blue (#3b82f6), Red (#ef4444), Green (#10b981)
- **Borders**: Subtle Gray (rgba(71,85,105,0.3))

### Components:
- Table dengan hover effect
- Button dengan hover state
- Form input dengan validation messages
- Pagination pagination
- Filter section

---

## 🔒 Security Features

1. **Authorization**: Semua method protected dengan auth check
2. **Authorization Policy**: `user_id` harus sesuai dengan auth()->id()
3. **Validation**: Email unique per user, required fields
4. **Soft Delete**: Prevent permanent data loss
5. **CSRF Protection**: All forms protected

---

## 📝 SQL Queries Reference

### 1. Tambah Admin Baru
```sql
INSERT INTO admins (user_id, name, email, phone, address, status) 
VALUES (1, 'Admin Toko', 'admin@toko.com', '08123456789', 'Alamat', 'active');
```

### 2. List Admin per User
```sql
SELECT * FROM admins 
WHERE user_id = 1 AND deleted_at IS NULL 
ORDER BY created_at DESC;
```

### 3. Update Status
```sql
UPDATE admins SET status = 'inactive' WHERE id = 1;
```

### 4. Soft Delete
```sql
UPDATE admins SET deleted_at = NOW() WHERE id = 1;
```

### 5. Restore
```sql
UPDATE admins SET deleted_at = NULL WHERE id = 1;
```

### 6. Search Admin
```sql
SELECT * FROM admins 
WHERE user_id = 1 
  AND deleted_at IS NULL
  AND (name LIKE '%search%' OR email LIKE '%search%' OR phone LIKE '%search%');
```

### 7. Get All Admin Dengan Status Filter
```sql
SELECT * FROM admins 
WHERE user_id = 1 
  AND status = 'active' 
  AND deleted_at IS NULL;
```

---

## 🎯 Routes Available

```
GET    /admin                    → Show all admins (admin.index)
GET    /admin/create             → Show create form (admin.create)
POST   /admin                    → Store new admin (admin.store)
GET    /admin/{admin}/edit       → Show edit form (admin.edit)
PUT    /admin/{admin}            → Update admin (admin.update)
DELETE /admin/{admin}            → Delete admin (admin.destroy)
POST   /admin/{admin}/restore    → Restore deleted admin (admin.restore)
POST   /admin/bulk-destroy       → Delete multiple admins (admin.bulk-destroy)
```

---

## ⚙️ Configuration

### Pagination Per Page (Editable di Index)
- Default: 10
- Available: 10, 25, 50

### Status Enum Values
- `active` - Admin aktif
- `inactive` - Admin tidak aktif

### Soft Delete
- Enabled: Admin tidak benar-benar dihapus, hanya `deleted_at` di-set
- Restore: Bisa di-restore dari halaman index

---

## 🐛 Troubleshooting

### Admin tidak muncul di sidebar
**Solusi**: Clear cache
```bash
php artisan cache:clear
php artisan view:clear
```

### Migration error: Table already exists
**Solusi**: Jika table sudah ada, bisa:
1. Uncomment `DROP TABLE` di SQL file
2. Atau skip migration jika table sudah benar struktur-nya

### Authorization error (403)
**Artinya**: User mencoba access admin milik user lain
**Solusi**: Hanya owner bisa access admin miliknya sendiri

### Email unique validation error
**Artinya**: Email sudah digunakan di admin lain user yang sama
**Solusi**: Gunakan email yang berbeda

---

## 📚 Dokumentasi Laravel yang Relevan

- Eloquent Model: https://laravel.com/docs/11.x/eloquent
- Soft Delete: https://laravel.com/docs/11.x/eloquent#soft-deleting
- Authorization: https://laravel.com/docs/11.x/authorization
- Validation: https://laravel.com/docs/11.x/validation
- Routing: https://laravel.com/docs/11.x/routing

---

## ✨ Features Summary

| Fitur | Status | Keterangan |
|-------|--------|-----------|
| Create Admin | ✅ | Tambah admin baru |
| Read/List | ✅ | List semua admin dengan pagination |
| Update/Edit | ✅ | Edit data admin |
| Delete | ✅ | Soft delete |
| Restore | ✅ | Restore deleted admin |
| Search | ✅ | Cari by name/email/phone |
| Filter | ✅ | Filter by status & deleted state |
| Bulk Delete | ✅ | Delete multiple admin |
| Authorization | ✅ | Owner only can access own admin |
| Validation | ✅ | Email unique per user |
| UI/UX | ✅ | Dark theme, responsive |
| PDF Export | ❌ | Sesuai request (tanpa PDF) |
| Excel Export | ❌ | Sesuai request (tanpa Excel) |

---

**Selesai! Manajemen Admin siap digunakan. 🎉**
