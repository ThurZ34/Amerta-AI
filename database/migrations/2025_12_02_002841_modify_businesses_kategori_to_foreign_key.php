<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, sync all existing kategori_bisnis values to categories table
        $businesses = DB::table('businesses')->whereNotNull('kategori_bisnis')->get();
        
        foreach ($businesses as $business) {
            if (!empty($business->kategori_bisnis)) {
                DB::table('categories')->insertOrIgnore([
                    'name' => $business->kategori_bisnis,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Add the category_id column
        Schema::table('businesses', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('status_bisnis')->constrained()->onDelete('set null');
        });

        // Map existing kategori_bisnis to category_id
        $businesses = DB::table('businesses')->whereNotNull('kategori_bisnis')->get();
        
        foreach ($businesses as $business) {
            if (!empty($business->kategori_bisnis)) {
                $category = DB::table('categories')->where('name', $business->kategori_bisnis)->first();
                if ($category) {
                    DB::table('businesses')->where('id', $business->id)->update([
                        'category_id' => $category->id
                    ]);
                }
            }
        }

        // Drop the old kategori_bisnis column
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn('kategori_bisnis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add the kategori_bisnis column
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('kategori_bisnis')->nullable()->after('status_bisnis');
        });

        // Restore kategori_bisnis from category relationship
        $businesses = DB::table('businesses')->whereNotNull('category_id')->get();
        
        foreach ($businesses as $business) {
            $category = DB::table('categories')->where('id', $business->category_id)->first();
            if ($category) {
                DB::table('businesses')->where('id', $business->id)->update([
                    'kategori_bisnis' => $category->name
                ]);
            }
        }

        // Drop the category_id foreign key and column
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
