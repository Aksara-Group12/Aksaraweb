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
                'nama_paket' => 'AKSARA Daily',
                'deskripsi' => 'Akses semua fitur selama 1 hari',
                'harga' => 10000,
                'harga_diskon' => null,
                'durasi_hari' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'AKSARA 3 Days',
                'deskripsi' => 'Akses semua fitur selama 3 hari',
                'harga' => 25000,
                'harga_diskon' => null,
                'durasi_hari' => 3,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'AKSARA Weekly',
                'deskripsi' => 'Akses semua fitur selama 7 hari',
                'harga' => 50000,
                'harga_diskon' => null,
                'durasi_hari' => 7,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_paket' => 'AKSARA Monthly',
                'deskripsi' => 'Akses semua fitur selama 30 hari',
                'harga' => 150000,
                'harga_diskon' => null,
                'durasi_hari' => 30,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
