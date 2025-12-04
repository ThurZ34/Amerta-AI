<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Business;
use App\Models\Riwayat;
use App\Models\CashJournal;
use App\Models\Coa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RiwayatIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $business;
    protected $coaIncome;
    protected $coaExpense;

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

        $this->coaIncome = Coa::create(['name' => 'Pendapatan Lainnya', 'type' => 'INFLOW', 'is_operational' => true]);
        $this->coaExpense = Coa::create(['name' => 'Beban Operasional', 'type' => 'OUTFLOW', 'is_operational' => true]);
    }

    public function test_creating_riwayat_creates_cash_journal()
    {
        $response = $this->actingAs($this->user)->post(route('riwayat.store'), [
            'nama_barang' => 'Office Supplies',
            'tanggal_pembelian' => '2023-01-01',
            'total_harga' => 50000,
            'keterangan' => 'Paper and pens',
            'jenis' => 'pengeluaran',
        ]);

        $response->assertRedirect(route('riwayat.index'));

        $this->assertDatabaseHas('riwayats', [
            'nama_barang' => 'Office Supplies',
            'total_harga' => 50000,
        ]);

        $riwayat = Riwayat::where('nama_barang', 'Office Supplies')->first();
        $this->assertNotNull($riwayat->cash_journal_id);

        $this->assertDatabaseHas('cash_journals', [
            'id' => $riwayat->cash_journal_id,
            'amount' => 50000,
            'is_inflow' => false,
            'coa_id' => $this->coaExpense->id,
        ]);
    }

    public function test_updating_riwayat_updates_cash_journal()
    {
        $response = $this->actingAs($this->user)->post(route('riwayat.store'), [
            'nama_barang' => 'Initial Item',
            'tanggal_pembelian' => '2023-01-01',
            'total_harga' => 10000,
            'jenis' => 'pengeluaran',
        ]);

        $riwayat = Riwayat::where('nama_barang', 'Initial Item')->first();
        $cashJournalId = $riwayat->cash_journal_id;

        $response = $this->actingAs($this->user)->put(route('riwayat.update', $riwayat->id), [
            'nama_barang' => 'Updated Item',
            'tanggal_pembelian' => '2023-01-02',
            'total_harga' => 20000,
            'jenis' => 'pendapatan',
        ]);

        $response->assertRedirect(route('riwayat.index'));

        $this->assertDatabaseHas('riwayats', [
            'id' => $riwayat->id,
            'nama_barang' => 'Updated Item',
            'total_harga' => 20000,
            'jenis' => 'pendapatan',
        ]);

        $this->assertDatabaseHas('cash_journals', [
            'id' => $cashJournalId,
            'amount' => 20000,
            'is_inflow' => true,
            'coa_id' => $this->coaIncome->id,
        ]);
    }

    public function test_deleting_riwayat_deletes_cash_journal()
    {
        $response = $this->actingAs($this->user)->post(route('riwayat.store'), [
            'nama_barang' => 'To Delete',
            'tanggal_pembelian' => '2023-01-01',
            'total_harga' => 10000,
            'jenis' => 'pengeluaran',
        ]);

        $riwayat = Riwayat::where('nama_barang', 'To Delete')->first();
        $cashJournalId = $riwayat->cash_journal_id;

        $response = $this->actingAs($this->user)->delete(route('riwayat.destroy', $riwayat->id));

        $response->assertRedirect(route('riwayat.index'));

        $this->assertDatabaseMissing('riwayats', ['id' => $riwayat->id]);
        $this->assertDatabaseMissing('cash_journals', ['id' => $cashJournalId]);
    }
}
