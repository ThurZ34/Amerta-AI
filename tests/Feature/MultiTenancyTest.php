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
        $category = \App\Models\Category::create(['name' => 'General']);

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

        $productA = Produk::create([
            'business_id' => $businessA->id,
            'nama_produk' => 'Product A',
            'modal' => 10000,
            'harga_jual' => 15000,
            'inventori' => 10,
            'jenis_produk' => 'Food',
            'gambar' => 'path/to/image.jpg',
        ]);

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

        $this->actingAs($userB);

        $response = $this->get(route('produk.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Product A');

        $response = $this->post(route('produk.store'), [
            'nama_produk' => 'Product B',
            'modal' => 20000,
            'harga_jual' => 25000,
            'inventori' => 5,
            'jenis_produk' => 'Drink',
            'gambar' => \Illuminate\Http\UploadedFile::fake()->image('product.jpg'),
        ]);
        $response->assertRedirect(route('produk.index'));

        $this->assertDatabaseHas('produk', [
            'nama_produk' => 'Product B',
            'business_id' => $businessB->id,
        ]);
    }

    public function test_users_cannot_see_other_business_daily_sales()
    {
        $category = \App\Models\Category::firstOrCreate(['name' => 'General']);

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

        $dailySaleA = DailySale::create([
            'business_id' => $businessA->id,
            'date' => now()->format('Y-m-d'),
            'total_revenue' => 100000,
            'total_profit' => 50000,
            'ai_analysis' => 'Good job',
        ]);

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

        $this->actingAs($userB);

        $response = $this->get(route('daily-checkin.index'));
        $response->assertStatus(200);
        $response->assertViewHas('dailySales', function ($dailySales) {
            return $dailySales->isEmpty();
        });

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

        $response->assertRedirect();

        $this->assertEquals(1, DailySale::where('business_id', $businessB->id)->count());
        $dailySaleB = DailySale::where('business_id', $businessB->id)->first();
        $this->assertEquals(now()->format('Y-m-d'), $dailySaleB->date->format('Y-m-d'));

        $this->assertEquals(2, DailySale::count());
    }
}
