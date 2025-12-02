<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\DailySale;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiTenancyTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_cannot_see_other_business_products()
    {
        // Create a category first
        $category = \App\Models\Category::create(['name' => 'General']);

        // Setup User A and Business A
        $userA = User::factory()->create();
        $businessA = Business::create([
            'user_id' => $userA->id,
            'nama_bisnis' => 'Business A',
            'status_bisnis' => 'Berjalan',
            'category_id' => $category->id,
            'channel_penjualan' => 'Offline',
            'target_pasar' => 'Umum',
            'range_omset' => '< 10 Juta',
            'jumlah_tim' => 1,
            'tujuan_utama' => 'Profit',
        ]);
        
        // Create Product for Business A
        $productA = Produk::create([
            'business_id' => $businessA->id,
            'nama_produk' => 'Product A',
            'modal' => 10000,
            'harga_jual' => 15000,
            'inventori' => 10,
            'jenis_produk' => 'Food',
            'gambar' => 'path/to/image.jpg',
        ]);

        // Setup User B and Business B
        $userB = User::factory()->create();
        $businessB = Business::create([
            'user_id' => $userB->id,
            'nama_bisnis' => 'Business B',
            'status_bisnis' => 'Berjalan',
            'category_id' => $category->id,
            'channel_penjualan' => 'Offline',
            'target_pasar' => 'Umum',
            'range_omset' => '< 10 Juta',
            'jumlah_tim' => 1,
            'tujuan_utama' => 'Profit',
        ]);

        // Authenticate as User B
        $this->actingAs($userB);

        // Visit Product Index
        $response = $this->get(route('produk.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Product A');

        // Create Product for Business B
        $response = $this->post(route('produk.store'), [
            'nama_produk' => 'Product B',
            'modal' => 20000,
            'harga_jual' => 25000,
            'inventori' => 5,
            'jenis_produk' => 'Drink',
            'gambar' => \Illuminate\Http\UploadedFile::fake()->image('product.jpg'),
        ]);
        $response->assertRedirect(route('produk.index'));

        // Verify Product B is created and associated with Business B
        $this->assertDatabaseHas('produk', [
            'nama_produk' => 'Product B',
            'business_id' => $businessB->id,
        ]);
    }

    public function test_users_cannot_see_other_business_daily_sales()
    {
        // Create a category first
        $category = \App\Models\Category::firstOrCreate(['name' => 'General']);

        // Setup User A and Business A
        $userA = User::factory()->create();
        $businessA = Business::create([
            'user_id' => $userA->id,
            'nama_bisnis' => 'Business A',
            'status_bisnis' => 'Berjalan',
            'category_id' => $category->id,
            'channel_penjualan' => 'Offline',
            'target_pasar' => 'Umum',
            'range_omset' => '< 10 Juta',
            'jumlah_tim' => 1,
            'tujuan_utama' => 'Profit',
        ]);
        
        // Create Daily Sale for Business A
        $dailySaleA = DailySale::create([
            'business_id' => $businessA->id,
            'date' => now()->format('Y-m-d'),
            'total_revenue' => 100000,
            'total_profit' => 50000,
            'ai_analysis' => 'Good job',
        ]);

        // Setup User B and Business B
        $userB = User::factory()->create();
        $businessB = Business::create([
            'user_id' => $userB->id,
            'nama_bisnis' => 'Business B',
            'status_bisnis' => 'Berjalan',
            'category_id' => $category->id,
            'channel_penjualan' => 'Offline',
            'target_pasar' => 'Umum',
            'range_omset' => '< 10 Juta',
            'jumlah_tim' => 1,
            'tujuan_utama' => 'Profit',
        ]);

        // Authenticate as User B
        $this->actingAs($userB);

        // Visit Daily Checkin Index
        $response = $this->get(route('daily-checkin.index'));
        $response->assertStatus(200);
        // We can't easily assertDontSee the ID or date if it's just a list, but we can check the view data
        $response->assertViewHas('dailySales', function ($dailySales) {
            return $dailySales->isEmpty();
        });

        // Create Daily Sale for Business B (same date)
        // We need a product first
        $productB = Produk::create([
            'business_id' => $businessB->id,
            'nama_produk' => 'Product B',
            'modal' => 10000,
            'harga_jual' => 15000,
            'inventori' => 10,
            'jenis_produk' => 'Food',
            'gambar' => 'path/to/image.jpg',
        ]);

        $response = $this->post(route('daily-checkin.store'), [
            'date' => now()->format('Y-m-d'),
            'sales' => [$productB->id => 5],
        ]);
        
        // It might redirect to show page
        $response->assertRedirect();
        
        // Verify Daily Sale B is created
        // Verify Daily Sale B is created
        $this->assertEquals(1, DailySale::where('business_id', $businessB->id)->count());
        $dailySaleB = DailySale::where('business_id', $businessB->id)->first();
        $this->assertEquals(now()->format('Y-m-d'), $dailySaleB->date->format('Y-m-d'));
        
        // Verify we have 2 daily sales in total
        $this->assertEquals(2, DailySale::count());
    }
}
