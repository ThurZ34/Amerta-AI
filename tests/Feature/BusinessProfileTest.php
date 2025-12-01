<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_business_profile_page_can_be_rendered()
    {
        $user = User::factory()->create();
        $business = Business::create([
            'user_id' => $user->id,
            'nama_bisnis' => 'Test Business',
            'kategori' => 'Technology',
            'target_pasar' => 'General',
            'jumlah_tim' => 5,
            'tujuan_utama' => 'Growth',
            'alamat' => 'Test Address',
            'telepon' => '08123456789',
        ]);

        $response = $this->actingAs($user)->get(route('profil_bisnis'));

        $response->assertStatus(200);
        $response->assertSee('Test Business');
        $response->assertSee('Test Address');
    }

    public function test_business_profile_can_be_updated()
    {
        $user = User::factory()->create();
        $business = Business::create([
            'user_id' => $user->id,
            'nama_bisnis' => 'Old Name',
        ]);

        $response = $this->actingAs($user)->put(route('profil_bisnis.update'), [
            'nama_bisnis' => 'New Name',
            'kategori' => 'New Category',
            'target_pasar' => 'New Target',
            'jumlah_tim' => 10,
            'tujuan_utama' => 'New Goal',
            'alamat' => 'New Address',
            'telepon' => '08987654321',
            'deskripsi' => 'New Description',
        ]);

        $response->assertRedirect(route('profil_bisnis'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('businesses', [
            'user_id' => $user->id,
            'nama_bisnis' => 'New Name',
            'alamat' => 'New Address',
            'telepon' => '08987654321',
        ]);
    }
}
