<?php

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessSetupTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_business_is_redirected_to_setup_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('setup-bisnis'));
    }

    public function test_user_with_business_can_access_dashboard()
    {
        $user = User::factory()->create();
        Business::create([
            'user_id' => $user->id,
            'nama_bisnis' => 'Test Business',
            'status_bisnis' => 'Baru Mulai',
            'kategori_bisnis' => 'Tech',
            'channel_penjualan' => 'Online',
            'range_omset' => '< 10 Juta',
            'target_pasar' => 'B2B',
            'jumlah_tim' => '1-5',
            'tujuan_utama' => 'Scale Up',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_user_can_submit_setup_form()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('setup-bisnis.store'), [
            'nama_bisnis' => 'New Business',
            'status_bisnis' => 'Baru Mulai',
            'kategori_bisnis' => 'Retail',
            'channel_penjualan' => 'Offline',
            'range_omset' => '10-50 Juta',
            'target_pasar' => 'General',
            'jumlah_tim' => '1-5',
            'tujuan_utama' => 'Profit',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('businesses', [
            'user_id' => $user->id,
            'nama_bisnis' => 'New Business',
        ]);
    }
}
