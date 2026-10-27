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
        // Drop kadar column from products table
        if (Schema::hasColumn('products', 'kadar')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('kadar');
            });
        }

        // Drop qty and kadar from transaksi_masuk_items
        if (Schema::hasTable('transaksi_masuk_items')) {
            if (Schema::hasColumn('transaksi_masuk_items', 'qty')) {
                Schema::table('transaksi_masuk_items', function (Blueprint $table) {
                    $table->dropColumn('qty');
                });
            }
            if (Schema::hasColumn('transaksi_masuk_items', 'kadar')) {
                Schema::table('transaksi_masuk_items', function (Blueprint $table) {
                    $table->dropColumn('kadar');
                });
            }
        }

        // Drop qty_keluar from transaksi_keluar_items
        if (Schema::hasTable('transaksi_keluar_items')) {
            if (Schema::hasColumn('transaksi_keluar_items', 'qty_keluar')) {
                Schema::table('transaksi_keluar_items', function (Blueprint $table) {
                    $table->dropColumn('qty_keluar');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore columns if needed
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('kadar', 5, 1)->nullable()->after('berat_standar');
            });
        }

        if (Schema::hasTable('transaksi_masuk_items')) {
            Schema::table('transaksi_masuk_items', function (Blueprint $table) {
                $table->integer('qty')->after('berat');
                $table->decimal('kadar', 5, 1)->after('qty');
            });
        }

        if (Schema::hasTable('transaksi_keluar_items')) {
            Schema::table('transaksi_keluar_items', function (Blueprint $table) {
                $table->integer('qty_keluar')->after('product_id');
            });
        }
    }
};
