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
        Schema::table('daily_sales', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sales', 'business_id')) {
                $table->foreignId('business_id')->after('id')->nullable()->constrained('businesses')->onDelete('cascade');
            }
            
            // Drop the existing unique index on date
            // We use array syntax which Laravel converts to index name: table_column_unique
            $table->dropUnique(['date']);
            
            // Add new composite unique index
            $table->unique(['date', 'business_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_sales', function (Blueprint $table) {
            $table->dropUnique(['date', 'business_id']);
            $table->unique('date');
            
            // We don't drop business_id here because we don't know if we created it or it existed before.
            // Or we can check if we want to be strict, but safer to leave it if we are unsure.
            // However, for this task, let's assume if we roll back we want to revert to previous state.
            // But since it existed before, we shouldn't drop it?
            // Let's just leave it.
        });
    }
};
