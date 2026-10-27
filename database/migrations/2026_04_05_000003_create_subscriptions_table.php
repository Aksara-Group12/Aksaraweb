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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('paket_id')->constrained('paket')->onDelete('restrict');
            $table->string('paket_name')->comment('Nama paket: trial, standard, dll');
            $table->decimal('price', 10, 2)->comment('Harga paket');
            $table->integer('duration_days')->comment('Durasi berlangganan dalam hari');
            $table->date('start_date')->comment('Tanggal mulai berlangganan');
            $table->date('end_date')->comment('Tanggal berakhir berlangganan');
            $table->foreignId('bank_id')->nullable()->constrained()->onDelete('set null');
            $table->string('bukti_transaksi')->nullable()->comment('Path/nama file bukti transaksi');
            $table->enum('status', ['pending', 'active', 'expired', 'cancelled'])->default('pending')->comment('Status berlangganan');
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
