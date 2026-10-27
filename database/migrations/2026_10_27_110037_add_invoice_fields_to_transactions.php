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
        Schema::table('transaksi_keluar', function (Blueprint $table) {
            $table->unsignedBigInteger('shift_id')->nullable()->after('catatan');
            $table->boolean('is_invoice')->default(false)->after('shift_id');
        });

        Schema::table('transaksi_keluar_items', function (Blueprint $table) {
            $table->decimal('hpp_snapshot', 15, 2)->nullable()->after('nomor_seri');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_transaction_id')->nullable()->after('keterangan');
            $table->decimal('sisa_tagihan', 15, 2)->default(0)->after('total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_keluar', function (Blueprint $table) {
            $table->dropColumn(['shift_id', 'is_invoice']);
        });

        Schema::table('transaksi_keluar_items', function (Blueprint $table) {
            $table->dropColumn('hpp_snapshot');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['sales_transaction_id', 'sisa_tagihan']);
        });
    }
};
