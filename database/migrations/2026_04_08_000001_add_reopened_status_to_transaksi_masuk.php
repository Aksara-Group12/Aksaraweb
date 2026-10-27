<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE transaksi_masuk MODIFY status ENUM('pending','complete','cancel','reopened') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE transaksi_masuk MODIFY status ENUM('pending','complete','cancel') NOT NULL DEFAULT 'pending'");
    }
};
