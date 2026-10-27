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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('account_holder');
            $table->string('bank_name');
            $table->string('account_number');
            $table->timestamps();
        });

        // Insert data bank
        \DB::table('banks')->insert([
            [
                'account_holder' => 'WAHYU WIDODO',
                'bank_name' => 'Seabank',
                'account_number' => '901050355787',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'account_holder' => 'WAHYU WIDODO',
                'bank_name' => 'Bank Mandiri',
                'account_number' => '1430033784586',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
