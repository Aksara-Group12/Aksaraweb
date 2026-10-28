<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('paket')->insert([
            ['nama_paket' => 'Paket 1 - Kasir + Keuangan', 'deskripsi' => 'Kasir, dashboard, laporan keuangan, expenses', 'harga' => 150000, 'durasi_bulan' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nama_paket' => 'Paket 2 - Kasir + Keuangan + Stok', 'deskripsi' => 'Semua fitur Paket 1 ditambah manajemen stok, stock adjustment, log produk', 'harga' => 300000, 'durasi_bulan' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nama_paket' => 'Paket 3 - Lengkap', 'deskripsi' => 'Semua fitur Paket 2 ditambah master pelanggan, supplier, invoice/piutang', 'harga' => 500000, 'durasi_bulan' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
