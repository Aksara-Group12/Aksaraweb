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
            if (Schema::hasColumn('profiles', 'bank_name')) {
                $table->dropColumn('bank_name');
            }
            if (Schema::hasColumn('profiles', 'bank_account_name')) {
                $table->dropColumn('bank_account_name');
            }
            if (Schema::hasColumn('profiles', 'bank_account_number')) {
                $table->dropColumn('bank_account_number');
            }
            if (Schema::hasColumn('profiles', 'invoice_header')) {
                $table->dropColumn('invoice_header');
            }
            if (Schema::hasColumn('profiles', 'invoice_footer')) {
                $table->dropColumn('invoice_footer');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'bank_name')) {
                $table->string('bank_name')->nullable();
            }
            if (!Schema::hasColumn('profiles', 'bank_account_name')) {
                $table->string('bank_account_name')->nullable();
            }
            if (!Schema::hasColumn('profiles', 'bank_account_number')) {
                $table->string('bank_account_number')->nullable();
            }
            if (!Schema::hasColumn('profiles', 'invoice_header')) {
                $table->string('invoice_header')->nullable();
            }
            if (!Schema::hasColumn('profiles', 'invoice_footer')) {
                $table->text('invoice_footer')->nullable();
            }
        });
    }
};
