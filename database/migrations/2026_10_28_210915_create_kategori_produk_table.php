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
        Schema::create('kategori_produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 100)->unique();
            $table->string('tipe')->default('produk'); // produk atau expenses
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        // Insert kategori bawaan untuk Expenses
        DB::table('kategori_produk')->insert([
            ['nama_kategori' => 'Belanja Bahan', 'tipe' => 'expenses', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Gaji/Upah', 'tipe' => 'expenses', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Sewa Tempat', 'tipe' => 'expenses', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Listrik/Air', 'tipe' => 'expenses', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Lainnya', 'tipe' => 'expenses', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Insert kategori bawaan untuk Produk
        DB::table('kategori_produk')->insert([
            ['nama_kategori' => 'Makanan', 'tipe' => 'produk', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Minuman', 'tipe' => 'produk', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Pakaian', 'tipe' => 'produk', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Elektronik', 'tipe' => 'produk', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Peralatan', 'tipe' => 'produk', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Kesehatan', 'tipe' => 'produk', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Jasa', 'tipe' => 'produk', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Lainnya', 'tipe' => 'produk', 'is_default' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_produk');
    }
};
