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
        Schema::table('admins', function (Blueprint $table) {
            $table->string('password')->nullable()->after('status')->comment('Password untuk login');
            $table->timestamp('email_verified_at')->nullable()->after('password')->comment('Waktu email diverifikasi');
            $table->string('remember_token', 100)->nullable()->after('email_verified_at')->comment('Token remember me untuk login');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['password', 'email_verified_at', 'remember_token']);
        });
    }
};
