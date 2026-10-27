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
        // Add kota_kabupaten to transaksi_masuk
        Schema::table('transaksi_masuk', function (Blueprint $table) {
            $table->string('kota_kabupaten_supplier')->nullable()->after('supplier_contact');
        });

        // Add kota_kabupaten to transaksi_keluar
        Schema::table('transaksi_keluar', function (Blueprint $table) {
            $table->string('kota_kabupaten_pembeli')->nullable()->after('invoice_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_masuk', function (Blueprint $table) {
            $table->dropColumn('kota_kabupaten_supplier');
        });

        Schema::table('transaksi_keluar', function (Blueprint $table) {
            $table->dropColumn('kota_kabupaten_pembeli');
        });
    }
};
