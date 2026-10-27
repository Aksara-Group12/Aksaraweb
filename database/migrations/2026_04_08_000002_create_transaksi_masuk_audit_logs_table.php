<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi_masuk_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_masuk_id')->constrained('transaksi_masuk')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('action_type', ['created', 'item_added', 'item_updated', 'item_deleted', 'items_rebuilt', 'status_changed', 'header_updated'])->index();
            $table->json('old_data')->nullable(); // Data sebelum perubahan
            $table->json('new_data')->nullable(); // Data setelah perubahan
            $table->text('description')->nullable(); // Deskripsi human-readable
            $table->string('changed_by')->nullable(); // Email/nama user yang melakukan perubahan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_masuk_audit_logs');
    }
};
