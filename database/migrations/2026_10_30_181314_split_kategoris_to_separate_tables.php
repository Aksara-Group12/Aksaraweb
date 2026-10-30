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
        // Create kategori_produk table
        Schema::create('kategori_produk', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
        });

        // Create kategori_pengeluaran table
        Schema::create('kategori_pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
        });

        // Migrate data from kategoris to new tables
        \DB::statement("
            INSERT INTO kategori_produk (name, user_id, is_active, created_at, updated_at)
            SELECT name, user_id, is_active, created_at, updated_at
            FROM kategoris
            WHERE tipe = 'produk'
        ");

        \DB::statement("
            INSERT INTO kategori_pengeluaran (name, user_id, is_active, created_at, updated_at)
            SELECT name, user_id, is_active, created_at, updated_at
            FROM kategoris
            WHERE tipe = 'pengeluaran'
        ");

        // Drop old kategoris table
        Schema::dropIfExists('kategoris');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate kategoris table
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tipe');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
        });

        // Migrate data back
        \DB::statement("
            INSERT INTO kategoris (name, tipe, user_id, is_active, created_at, updated_at)
            SELECT name, 'produk', user_id, is_active, created_at, updated_at
            FROM kategori_produk
        ");

        \DB::statement("
            INSERT INTO kategoris (name, tipe, user_id, is_active, created_at, updated_at)
            SELECT name, 'pengeluaran', user_id, is_active, created_at, updated_at
            FROM kategori_pengeluaran
        ");

        // Drop new tables
        Schema::dropIfExists('kategori_produk');
        Schema::dropIfExists('kategori_pengeluaran');
    }
};
