<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Category;
use App\Models\DailySale;
use App\Models\DailySaleItem;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProdukMonthlySalesTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_calculate_total_terjual_per_bulan(): void
    {
        // Create user and business
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description',
        ]);

        $business = Business::create([
            'user_id' => $user->id,
            'nama_bisnis' => 'Test Business',
            'status_bisnis' => 'Aktif',
            'category_id' => $category->id,
            'masalah_utama' => 'Test',
            'channel_penjualan' => 'Online',
            'range_omset' => '< 10 juta',
            'target_revenue' => 10000000,
            'target_pasar' => 'Test',
            'jumlah_tim' => 1,
            'tujuan_utama' => 'Test',
        ]);

        $user->business_id = $business->id;
        $user->save();

        // Create a product
        $produk = Produk::create([
            'business_id' => $business->id,
            'nama_produk' => 'Test Product',
            'modal' => 5000,
            'harga_jual' => 10000,
            'total_terjual' => 0,
            'jenis_produk' => 'Makanan',
            'gambar' => 'test.jpg',
        ]);

        // Create daily sales for December 2025
        $dailySale1 = DailySale::create([
            'business_id' => $business->id,
            'date' => '2025-12-01',
            'ai_analysis' => 'Test analysis',
        ]);

        $dailySale2 = DailySale::create([
            'business_id' => $business->id,
            'date' => '2025-12-15',
            'ai_analysis' => 'Test analysis',
        ]);

        // Create daily sale items
        DailySaleItem::create([
            'daily_sale_id' => $dailySale1->id,
            'produk_id' => $produk->id,
            'quantity' => 5,
            'price' => 10000,
            'cost' => 5000,
        ]);

        DailySaleItem::create([
            'daily_sale_id' => $dailySale2->id,
            'produk_id' => $produk->id,
            'quantity' => 10,
            'price' => 10000,
            'cost' => 5000,
        ]);

        // Create a sale in different month (should not be counted)
        $dailySale3 = DailySale::create([
            'business_id' => $business->id,
            'date' => '2025-11-15',
            'ai_analysis' => 'Test analysis',
        ]);

        DailySaleItem::create([
            'daily_sale_id' => $dailySale3->id,
            'produk_id' => $produk->id,
            'quantity' => 3,
            'price' => 10000,
            'cost' => 5000,
        ]);

        // Test: Calculate total for December 2025
        $totalDecember = $produk->getTotalTerjualPerBulan('2025-12');
        $this->assertEquals(15, $totalDecember); // 5 + 10 = 15

        // Test: Calculate total for November 2025
        $totalNovember = $produk->getTotalTerjualPerBulan('2025-11');
        $this->assertEquals(3, $totalNovember);

        // Test: Calculate total for a month with no sales
        $totalJanuary = $produk->getTotalTerjualPerBulan('2025-01');
        $this->assertEquals(0, $totalJanuary);
    }

    public function test_produk_index_includes_monthly_sales(): void
    {
        // Create user and business
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => Hash::make('password'),
        ]);

        $category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description',
        ]);

        $business = Business::create([
            'user_id' => $user->id,
            'nama_bisnis' => 'Test Business',
            'status_bisnis' => 'Aktif',
            'category_id' => $category->id,
            'masalah_utama' => 'Test',
            'channel_penjualan' => 'Online',
            'range_omset' => '< 10 juta',
            'target_revenue' => 10000000,
            'target_pasar' => 'Test',
            'jumlah_tim' => 1,
            'tujuan_utama' => 'Test',
        ]);

        $user->business_id = $business->id;
        $user->save();

        // Create a product
        $produk = Produk::create([
            'business_id' => $business->id,
            'nama_produk' => 'Test Product',
            'modal' => 5000,
            'harga_jual' => 10000,
            'total_terjual' => 0,
            'jenis_produk' => 'Makanan',
            'gambar' => 'test.jpg',
        ]);

        // Create daily sale for current month
        $dailySale = DailySale::create([
            'business_id' => $business->id,
            'date' => now()->format('Y-m-d'),
            'ai_analysis' => 'Test analysis',
        ]);

        DailySaleItem::create([
            'daily_sale_id' => $dailySale->id,
            'produk_id' => $produk->id,
            'quantity' => 7,
            'price' => 10000,
            'cost' => 5000,
        ]);

        // Act: Visit produk index
        $response = $this->actingAs($user)->get(route('produk.index'));

        // Assert: Response is successful and contains the product
        $response->assertStatus(200);
        $response->assertViewHas('produks');
        
        $produks = $response->viewData('produks');
        $this->assertCount(1, $produks);
        $this->assertEquals(7, $produks->first()->total_terjual_bulan_ini);
    }
}
