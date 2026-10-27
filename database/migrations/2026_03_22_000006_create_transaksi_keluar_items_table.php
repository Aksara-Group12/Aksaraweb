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
        Schema::create('transaksi_keluar_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_keluar_id')->constrained('transaksi_keluar')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('qty_keluar');
            $table->decimal('total_harga', 15, 2);
            $table->decimal('biaya_tambahan', 15, 2)->default(0);
            $table->json('nomor_seri')->nullable(); // ['SN001', 'SN005', ...]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_keluar_items');
    }
};
