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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('shift_number');
            $table->date('tanggal_shift');
            $table->decimal('modal_awal', 15, 2)->default(0);
            $table->decimal('modal_akhir', 15, 2)->nullable();
            $table->decimal('total_penjualan', 15, 2)->default(0);
            $table->decimal('total_penerimaan', 15, 2)->default(0);
            $table->decimal('selisih_kas', 15, 2)->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamp('waktu_buka')->nullable();
            $table->timestamp('waktu_tutup')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
