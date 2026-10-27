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
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('harga_beli_gram', 'harga_beli');
            $table->renameColumn('harga_jual_gram', 'harga_jual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('harga_beli', 'harga_beli_gram');
            $table->renameColumn('harga_jual', 'harga_jual_gram');
        });
    }
};
