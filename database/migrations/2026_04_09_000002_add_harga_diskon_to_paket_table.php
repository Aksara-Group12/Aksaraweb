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
        Schema::table('paket', function (Blueprint $table) {
            $table->unsignedBigInteger('harga_diskon')->nullable()->after('harga')->comment('Harga diskon jika ada promo atau penawaran khusus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket', function (Blueprint $table) {
            $table->dropColumn('harga_diskon');
        });
    }
};
