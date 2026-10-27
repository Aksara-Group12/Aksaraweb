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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->after('status')
                  ->comment('Admin approval status');
            
            $table->timestamp('approved_at')
                  ->nullable()
                  ->after('approval_status')
                  ->comment('Waktu disetujui admin');
            
            $table->unsignedBigInteger('approved_by')
                  ->nullable()
                  ->after('approved_at')
                  ->comment('ID admin yang approve');
            
            $table->string('admin_notes', 500)
                  ->nullable()
                  ->after('approved_by')
                  ->comment('Catatan dari admin');
            
            $table->boolean('is_trial')
                  ->default(false)
                  ->after('admin_notes')
                  ->comment('Apakah ini trial atau berbayar');
            
            // Foreign key untuk approved_by
            $table->foreign('approved_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // Indexes untuk query lebih cepat
            $table->index('approval_status');
            $table->index('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['approved_by']);
            
            // Drop indexes
            $table->dropIndex('subscriptions_approval_status_index');
            $table->dropIndex('subscriptions_approved_by_index');
            
            // Drop columns
            $table->dropColumn([
                'approval_status',
                'approved_at',
                'approved_by',
                'admin_notes',
                'is_trial'
            ]);
        });
    }
};
