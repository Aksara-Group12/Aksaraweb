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
        Schema::table('transaksi_keluar_items', function (Blueprint $table) {
            // Qty keluar untuk laporan produk terlaris
            if (!Schema::hasColumn('transaksi_keluar_items', 'qty_keluar')) {
                $table->integer('qty_keluar')->default(1)->after('product_id');
            }
            // HPP snapshot untuk laba rugi
            if (!Schema::hasColumn('transaksi_keluar_items', 'hpp_snapshot')) {
                $table->decimal('hpp_snapshot', 15, 2)->nullable()->after('total_harga');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_keluar_items', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi_keluar_items', 'qty_keluar')) {
                $table->dropColumn('qty_keluar');
            }
            if (Schema::hasColumn('transaksi_keluar_items', 'hpp_snapshot')) {
                $table->dropColumn('hpp_snapshot');
            }
        });
    }
};
