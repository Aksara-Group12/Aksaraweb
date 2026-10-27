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
        // Drop biaya_tambahan from transaksi_masuk_items
        if (Schema::hasTable('transaksi_masuk_items')) {
            if (Schema::hasColumn('transaksi_masuk_items', 'biaya_tambahan')) {
                Schema::table('transaksi_masuk_items', function (Blueprint $table) {
                    $table->dropColumn('biaya_tambahan');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('transaksi_masuk_items')) {
            Schema::table('transaksi_masuk_items', function (Blueprint $table) {
                $table->decimal('biaya_tambahan', 15, 2)->default(0)->after('harga_gram');
            });
        }
    }
};
