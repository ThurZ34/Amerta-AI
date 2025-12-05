<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Business;
use App\Models\Produk;
use App\Models\Riwayat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $business;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        
        $this->business = Business::create([
            'user_id' => $this->user->id,
            'nama_bisnis' => 'Test Business',
            'status_bisnis' => 'Baru Mulai',
            'kategori_bisnis' => 'Retail',
            'channel_penjualan' => 'Online',
            'range_omset' => '< 10 Juta',
            'target_pasar' => 'Umum',
            'jumlah_tim' => '1-5',
            'tujuan_utama' => 'Profit',
        ]);

        $this->user->update(['business_id' => $this->business->id]);
    }

    public function test_cashier_page_loads_and_displays_products()
    {
        Produk::create([
            'business_id' => $this->business->id,
            'nama_produk' => 'Test Product',
            'modal' => 5000,
            'harga_jual' => 10000,
            'jenis_produk' => 'Barang',
        ]);

        $response = $this->actingAs($this->user)->get(route('riwayat.kasir'));

        $response->assertStatus(200);
        $response->assertSee('Test Product');
        $response->assertSee('10.000');
    }

    public function test_cashier_transaction_creates_riwayat_entry()
    {
        $response = $this->actingAs($this->user)->post(route('riwayat.store'), [
            'nama_barang' => 'Penjualan Kasir',
            'tanggal_pembelian' => now()->format('Y-m-d'),
            'total_harga' => 20000,
            'keterangan' => '2x Test Product',
            'jenis' => 'pendapatan',
        ]);

        $response->assertRedirect(route('riwayat.index'));

        $this->assertDatabaseHas('riwayats', [
            'business_id' => $this->business->id,
            'nama_barang' => 'Penjualan Kasir',
            'total_harga' => 20000,
            'keterangan' => '2x Test Product',
            'jenis' => 'pendapatan',
        ]);
    }
}
