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
        // Drop qty, kadar, biaya_tambahan dari transaksi_masuk_items
        Schema::table('transaksi_masuk_items', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi_masuk_items', 'qty')) {
                $table->dropColumn('qty');
            }
            if (Schema::hasColumn('transaksi_masuk_items', 'kadar')) {
                $table->dropColumn('kadar');
            }
            if (Schema::hasColumn('transaksi_masuk_items', 'biaya_tambahan')) {
                $table->dropColumn('biaya_tambahan');
            }
        });

        // Drop qty_keluar, biaya_tambahan dari transaksi_keluar_items
        Schema::table('transaksi_keluar_items', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi_keluar_items', 'qty_keluar')) {
                $table->dropColumn('qty_keluar');
            }
            if (Schema::hasColumn('transaksi_keluar_items', 'biaya_tambahan')) {
                $table->dropColumn('biaya_tambahan');
            }
        });

        // Tabel baru untuk biaya tambahan transaksi (masuk & keluar)
        Schema::create('transaksi_biaya_tambahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_masuk_id')->nullable()->constrained('transaksi_masuk')->onDelete('cascade');
            $table->foreignId('transaksi_keluar_id')->nullable()->constrained('transaksi_keluar')->onDelete('cascade');
            $table->string('nama');
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_biaya_tambahan');

        // Tidak menambahkan kembali kolom qty/kadar/biaya_tambahan agar aman untuk rollback parsial
        // Jika benar-benar perlu, bisa dibuat migration terpisah untuk menambahkannya lagi.
    }
};
