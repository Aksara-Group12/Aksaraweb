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
            $table->boolean('is_void')->default(false)->after('is_invoice');
            $table->text('void_reason')->nullable()->after('is_void');
            $table->timestamp('voided_at')->nullable()->after('void_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi_keluar', function (Blueprint $table) {
            $table->dropColumn(['is_void', 'void_reason', 'voided_at']);
        });
    }
};
