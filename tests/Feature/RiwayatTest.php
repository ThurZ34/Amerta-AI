<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Business;
use App\Models\Riwayat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RiwayatTest extends TestCase
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

    public function test_index_displays_riwayat()
    {
        Riwayat::create([
            'business_id' => $this->business->id,
            'nama_barang' => 'Test Item',
            'tanggal_pembelian' => '2023-01-01',
            'total_harga' => 10000,
            'keterangan' => 'Test Keterangan',
            'jenis' => 'pengeluaran',
        ]);

        $response = $this->actingAs($this->user)->get(route('riwayat.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Item');
    }

    public function test_store_creates_riwayat_with_file_and_jenis()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('receipt.jpg');

        $response = $this->actingAs($this->user)->post(route('riwayat.store'), [
            'nama_barang' => 'New Item',
            'tanggal_pembelian' => '2023-01-02',
            'total_harga' => 50000,
            'keterangan' => 'New Keterangan',
            'bukti_pembayaran' => $file,
            'jenis' => 'pendapatan',
        ]);

        $response->assertRedirect(route('riwayat.index'));
        
        $this->assertDatabaseHas('riwayats', [
            'nama_barang' => 'New Item',
            'total_harga' => 50000,
            'jenis' => 'pendapatan',
        ]);

        $riwayat = Riwayat::where('nama_barang', 'New Item')->first();
        $this->assertNotNull($riwayat->bukti_pembayaran);
        Storage::disk('public')->assertExists($riwayat->bukti_pembayaran);
    }

    public function test_update_updates_riwayat_and_replaces_file_and_jenis()
    {
        Storage::fake('public');

        $oldFile = UploadedFile::fake()->image('old_receipt.jpg');
        $oldPath = $oldFile->store('receipts', 'public');

        $riwayat = Riwayat::create([
            'business_id' => $this->business->id,
            'nama_barang' => 'Old Item',
            'tanggal_pembelian' => '2023-01-01',
            'total_harga' => 10000,
            'keterangan' => 'Old Keterangan',
            'bukti_pembayaran' => $oldPath,
            'jenis' => 'pengeluaran',
        ]);

        $newFile = UploadedFile::fake()->image('new_receipt.jpg');

        $response = $this->actingAs($this->user)->put(route('riwayat.update', $riwayat->id), [
            'nama_barang' => 'Updated Item',
            'tanggal_pembelian' => '2023-01-03',
            'total_harga' => 75000,
            'keterangan' => 'Updated Keterangan',
            'bukti_pembayaran' => $newFile,
            'jenis' => 'pendapatan',
        ]);

        $response->assertRedirect(route('riwayat.index'));

        $this->assertDatabaseHas('riwayats', [
            'id' => $riwayat->id,
            'nama_barang' => 'Updated Item',
            'jenis' => 'pendapatan',
        ]);

        $riwayat->refresh();
        $this->assertNotEquals($oldPath, $riwayat->bukti_pembayaran);
        Storage::disk('public')->assertExists($riwayat->bukti_pembayaran);
        Storage::disk('public')->assertMissing($oldPath);
    }
}
