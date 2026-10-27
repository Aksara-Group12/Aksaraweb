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
        Schema::table('transaksi_biaya_tambahan', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi_biaya_tambahan', 'keterangan')) {
                $table->string('keterangan')->nullable()->after('nama');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_biaya_tambahan', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi_biaya_tambahan', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
        });
    }
};
