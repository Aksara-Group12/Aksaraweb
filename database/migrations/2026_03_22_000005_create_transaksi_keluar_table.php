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
        Schema::create('transaksi_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique(); // OUT-20260322-001
            $table->date('tanggal_transaksi');
            $table->enum('status', ['pending', 'complete', 'cancel'])->default('pending');
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->string('metode_pembayaran')->nullable(); // cash/non-cash
            $table->decimal('nominal_bayar', 15, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('tanggal_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_keluar');
    }
};
