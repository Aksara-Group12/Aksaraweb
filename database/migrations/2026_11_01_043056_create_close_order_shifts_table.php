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
        Schema::create('close_order_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('operator_name'); // Nama yang mengakses POS
            $table->timestamp('opened_at'); // Waktu open order
            $table->timestamp('closed_at')->nullable(); // Waktu close order
            $table->decimal('total_revenue', 15, 2)->default(0); // Pendapatan selama shift
            $table->integer('total_transactions')->default(0); // Jumlah transaksi
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('close_order_shifts');
    }
};
