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
        Schema::create('transaksi_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique(); // IN-20260322-001
            $table->string('supplier_name')->nullable(); // nama supplier
            $table->string('supplier_contact')->nullable(); // kontak supplier
            $table->string('po_invoice')->nullable(); // PO atau invoice dari supplier
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
        Schema::dropIfExists('transaksi_masuk');
    }
};
