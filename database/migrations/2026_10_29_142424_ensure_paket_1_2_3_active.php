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
        // Ensure Paket 1, 2, 3 exist and are active
        DB::statement("INSERT INTO paket (nama_paket, deskripsi, harga, durasi_bulan, is_active, created_at, updated_at) VALUES 
            ('Paket 1', 'Kasir + Keuangan', 150000, 1, true, NOW(), NOW()),
            ('Paket 2', 'Kasir + Keuangan + Stok', 300000, 1, true, NOW(), NOW()),
            ('Paket 3', 'Lengkap', 500000, 1, true, NOW(), NOW())
            ON DUPLICATE KEY UPDATE is_active = true");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
