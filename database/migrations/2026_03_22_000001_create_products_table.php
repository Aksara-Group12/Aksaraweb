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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // PRD-20260322-001
            $table->enum('jenis_logam', ['emas', 'perak']);
            $table->string('brand');
            $table->string('nama_produk');
            $table->decimal('berat_standar', 8, 2); // gram
            $table->decimal('kadar', 5, 1); // 999.9 or 999
            $table->decimal('harga_beli_gram', 15, 2);
            $table->decimal('harga_jual_gram', 15, 2);
            $table->integer('stok')->default(0); // jumlah unit
            $table->string('kategori')->nullable(); // Batang Utuh, Pecahan, Limited
            $table->string('lokasi')->nullable(); // lokasi penyimpanan
            $table->string('status')->default('draft'); // draft/active/inactive
            $table->timestamps();
            
            $table->index('jenis_logam');
            $table->index('brand');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
