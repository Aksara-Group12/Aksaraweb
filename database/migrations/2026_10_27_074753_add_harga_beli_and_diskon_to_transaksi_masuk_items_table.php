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
        Schema::table('transaksi_masuk_items', function (Blueprint $table) {
            $table->decimal('harga_beli', 15, 2)->nullable();
            $table->decimal('diskon', 15, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_masuk_items', function (Blueprint $table) {
            $table->dropColumn(['harga_beli', 'diskon']);
        });
    }
};
