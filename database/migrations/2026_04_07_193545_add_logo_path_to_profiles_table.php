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
        Schema::table('profiles', function (Blueprint $table) {
            // Tambah kolom logo_path setelah store_name
            if (!Schema::hasColumn('profiles', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('store_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (Schema::hasColumn('profiles', 'logo_path')) {
                $table->dropColumn('logo_path');
            }
        });
    }
};
