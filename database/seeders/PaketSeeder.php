<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua paket yang ada
        DB::table('paket')->truncate();

        // Tambah paket baru
        DB::table('paket')->insert([
            [
                'nama_paket' => 'Single Outlet',
                'deskripsi' => 'Akses untuk 1 outlet lokasi',
                'harga' => 50000,
                'harga_diskon' => null,
                'durasi_hari' => 7,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Toko Online',
                'deskripsi' => 'Kelola toko online dengan fitur lengkap',
                'harga' => 150000,
                'harga_diskon' => null,
                'durasi_hari' => 30,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Aplikasi Kasir Online',
                'deskripsi' => 'Aplikasi kasir online untuk transaksi',
                'harga' => 200000,
                'harga_diskon' => null,
                'durasi_hari' => 30,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Manajemen Inventori',
                'deskripsi' => 'Manajemen stok dan inventori lengkap',
                'harga' => 250000,
                'harga_diskon' => null,
                'durasi_hari' => 30,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Laporan',
                'deskripsi' => 'Laporan penjualan dan keuangan',
                'harga' => 100000,
                'harga_diskon' => null,
                'durasi_hari' => 30,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Manajemen Karyawan',
                'deskripsi' => 'Manajemen karyawan dan shift',
                'harga' => 300000,
                'harga_diskon' => null,
                'durasi_hari' => 30,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'Pencatatan Keuangan',
                'deskripsi' => 'Pencatatan keuangan dan pembukuan',
                'harga' => 350000,
                'harga_diskon' => null,
                'durasi_hari' => 30,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
