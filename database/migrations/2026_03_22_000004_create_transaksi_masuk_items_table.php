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
        Schema::create('transaksi_masuk_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_masuk_id')->constrained('transaksi_masuk')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('berat', 8, 2); // gram
            $table->integer('qty'); // jumlah unit
            $table->decimal('kadar', 5, 1);
            $table->decimal('harga_gram', 15, 2);
            $table->decimal('total_harga', 15, 2);
            $table->decimal('biaya_tambahan', 15, 2)->default(0);
            $table->json('nomor_seri')->nullable(); // ['SN001', 'SN002', ...]
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_masuk_items');
    }
};
