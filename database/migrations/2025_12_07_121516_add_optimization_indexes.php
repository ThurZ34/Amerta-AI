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
        // Riwayat Indexes for Dashboard Filtering
        Schema::table('riwayats', function (Blueprint $table) {
            $table->index(['business_id', 'tanggal_pembelian']); // Compound index for efficient range queries per business
            $table->index(['business_id', 'jenis']); // Filter by type ('pengeluaran')
            $table->index('kategori'); // Group By
        });

        // CashJournal Indexes for Dashboard Filtering
        Schema::table('cash_journals', function (Blueprint $table) {
            $table->index(['business_id', 'transaction_date']); // Compound index for ranges
            $table->index(['business_id', 'is_inflow']); // Filter inflow/outflow
            $table->index('coa_id'); // Foreign key lookup
        });

        // DailySale Indexes for Dashboard Filtering
        Schema::table('daily_sales', function (Blueprint $table) {
            $table->index(['business_id', 'date']); // High value range query
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayats', function (Blueprint $table) {
            $table->dropIndex(['business_id', 'tanggal_pembelian']);
            $table->dropIndex(['business_id', 'jenis']);
            $table->dropIndex('kategori');
        });

        Schema::table('cash_journals', function (Blueprint $table) {
            $table->dropIndex(['business_id', 'transaction_date']);
            $table->dropIndex(['business_id', 'is_inflow']);
            $table->dropIndex('coa_id');
        });

        Schema::table('daily_sales', function (Blueprint $table) {
            $table->dropIndex(['business_id', 'date']);
        });
    }
};
