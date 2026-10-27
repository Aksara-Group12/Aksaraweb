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
        Schema::table('transaksi_masuk', function (Blueprint $table) {
            $table->renameColumn('kota_kabupaten_supplier', 'lokasi');
        });

        Schema::table('transaksi_keluar', function (Blueprint $table) {
            $table->renameColumn('kota_kabupaten_pembeli', 'lokasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_masuk', function (Blueprint $table) {
            $table->renameColumn('lokasi', 'kota_kabupaten_supplier');
        });

        Schema::table('transaksi_keluar', function (Blueprint $table) {
            $table->renameColumn('lokasi', 'kota_kabupaten_pembeli');
        });
    }
};
