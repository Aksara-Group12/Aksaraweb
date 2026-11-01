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
        // Drop foreign keys using raw SQL (safer)
        $dropFk = function ($table, $fkName) {
            try {
                \DB::statement("ALTER TABLE `$table` DROP FOREIGN KEY `$fkName`");
            } catch (\Exception $e) {
                // Ignore if doesn't exist
            }
        };

        // Drop all foreign keys
        $dropFk('products', 'products_category_id_foreign');
        $dropFk('transaksi_keluar', 'transaksi_keluar_account_id_foreign');
        // Note: products foreign keys already dropped in previous migration
        $dropFk('pengeluaran', 'expenses_account_id_foreign');
        $dropFk('pengeluaran', 'expenses_category_id_foreign');
        $dropFk('pengeluaran', 'expenses_business_id_foreign');
        $dropFk('subscriptions', 'subscriptions_business_id_foreign');
        $dropFk('subscriptions', 'subscriptions_plan_id_foreign');
        $dropFk('stock_movements', 'stock_movements_business_id_foreign');
        $dropFk('stock_movements', 'stock_movements_product_id_foreign');
        $dropFk('financial_transactions', 'financial_transactions_account_id_foreign');
        $dropFk('financial_transactions', 'financial_transactions_category_id_foreign');
        $dropFk('financial_transactions', 'financial_transactions_business_id_foreign');
        $dropFk('financial_accounts', 'financial_accounts_business_id_foreign');
        $dropFk('finance_categories', 'finance_categories_business_id_foreign');
        $dropFk('sale_items', 'sale_items_sale_id_foreign');
        $dropFk('sale_items', 'sale_items_product_id_foreign');
        $dropFk('sale_payments', 'sale_payments_sale_id_foreign');
        $dropFk('sale_returns', 'sale_returns_sale_id_foreign');
        $dropFk('payments', 'payments_sale_id_foreign');
        $dropFk('payments', 'payments_business_id_foreign');
        $dropFk('product_categories', 'product_categories_business_id_foreign');
        $dropFk('business_users', 'business_users_business_id_foreign');
        $dropFk('stock_adjustment_items', 'stock_adjustment_items_stock_adjustment_id_foreign');
        $dropFk('stock_adjustment_items', 'stock_adjustment_items_product_id_foreign');
        $dropFk('transaksi_masuk_items', 'transaksi_masuk_items_transaksi_masuk_id_foreign');
        $dropFk('transaksi_masuk_items', 'transaksi_masuk_items_product_id_foreign');
        $dropFk('transaksi_masuk_audit_logs', 'transaksi_masuk_audit_logs_transaksi_masuk_id_foreign');
        $dropFk('transaksi_masuk', 'transaksi_masuk_supplier_id_foreign');
        $dropFk('transaksi_masuk', 'transaksi_masuk_account_id_foreign');
        $dropFk('transaksi_keluar_items', 'transaksi_keluar_items_product_id_foreign');
        $dropFk('invoice_items', 'invoice_items_invoice_id_foreign');
        $dropFk('journal_items', 'journal_items_journal_id_foreign');
        $dropFk('subscription_modules', 'subscription_modules_subscription_id_foreign');
        $dropFk('paket_module', 'paket_module_paket_id_foreign');
        $dropFk('paket_module', 'paket_module_module_id_foreign');
        $dropFk('product_serial_numbers', 'product_serial_numbers_product_id_foreign');
        $dropFk('invoices', 'invoices_pelanggan_id_foreign');
        $dropFk('transaksi_biaya_tambahan', 'transaksi_biaya_tambahan_transaksi_masuk_id_foreign');
        $dropFk('transaksi_biaya_tambahan', 'transaksi_biaya_tambahan_transaksi_keluar_id_foreign');

        // Drop unused columns from products (only if they exist)
        // Note: products table already cleaned up in previous migration
        // No legacy columns exist anymore

        // Drop unused columns from transaksi_keluar (only if they exist)
        $tkColumns = ['shift_id', 'is_invoice', 'account_id'];
        $existingTkColumns = [];
        foreach ($tkColumns as $column) {
            if (Schema::hasColumn('transaksi_keluar', $column)) {
                $existingTkColumns[] = $column;
            }
        }
        if (!empty($existingTkColumns)) {
            Schema::table('transaksi_keluar', function (Blueprint $table) use ($existingTkColumns) {
                $table->dropColumn($existingTkColumns);
            });
        }

        // Drop unused columns from subscriptions (only if they exist)
        $subColumns = ['business_id', 'plan_id'];
        $existingSubColumns = [];
        foreach ($subColumns as $column) {
            if (Schema::hasColumn('subscriptions', $column)) {
                $existingSubColumns[] = $column;
            }
        }
        if (!empty($existingSubColumns)) {
            Schema::table('subscriptions', function (Blueprint $table) use ($existingSubColumns) {
                $table->dropColumn($existingSubColumns);
            });
        }

        // Drop unused columns from pengeluaran (only if they exist)
        $pengColumns = ['business_id', 'account_id', 'category_id'];
        $existingPengColumns = [];
        foreach ($pengColumns as $column) {
            if (Schema::hasColumn('pengeluaran', $column)) {
                $existingPengColumns[] = $column;
            }
        }
        if (!empty($existingPengColumns)) {
            Schema::table('pengeluaran', function (Blueprint $table) use ($existingPengColumns) {
                $table->dropColumn($existingPengColumns);
            });
        }

        // Drop unused tables
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('transaksi_biaya_tambahan');
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('stock_adjustments');
        Schema::dropIfExists('stock_adjustment_items');
        Schema::dropIfExists('transaksi_masuk');
        Schema::dropIfExists('transaksi_masuk_items');
        Schema::dropIfExists('transaksi_masuk_audit_logs');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('journals');
        Schema::dropIfExists('journal_items');
        Schema::dropIfExists('pelanggan');
        Schema::dropIfExists('supplier');
        Schema::dropIfExists('kategori_produk');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('product_serial_numbers');
        Schema::dropIfExists('sale_returns');
        Schema::dropIfExists('finance_categories');
        Schema::dropIfExists('financial_accounts');
        Schema::dropIfExists('financial_transactions');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sale_payments');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('businesses');
        Schema::dropIfExists('business_users');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('subscription_modules');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('paket_module');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('stock_movements');
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // WARNING: This migration cannot be easily reversed
        // because it drops tables. You would need to recreate them manually.
        // This is intentional for cleanup migration.
    }
};
