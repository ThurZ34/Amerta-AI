<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Category;
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
        $category = Category::create(['name' => 'Tech']);
        
        Business::create([
            'user_id' => $user->id,
            'nama_bisnis' => 'Test Business',
            'status_bisnis' => 'Baru Mulai',
            'category_id' => $category->id,
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

        $response = $this->actingAs($user)
            ->post(route('setup-bisnis.store'), [
                'nama_bisnis' => 'My Business',
                'status_bisnis' => 'Baru Mulai',
                'kategori_bisnis' => 'Teknologi',
                'channel_penjualan' => 'Online',
                'target_pasar' => 'UMKM',
                'range_omset' => '< 10 Juta',
                'jumlah_tim' => '1 (Sendiri)',
                'tujuan_utama' => 'Scale Up',
                'masalah_utama' => 'Modal',
            ]);

        $response->assertRedirect(route('dashboard'));

        // Check that category was created
        $this->assertDatabaseHas('categories', [
            'name' => 'Teknologi',
        ]);

        // Get the created category
        $category = Category::where('name', 'Teknologi')->first();

        // Check that business was created with correct category_id
        $this->assertDatabaseHas('businesses', [
            'user_id' => $user->id,
            'nama_bisnis' => 'My Business',
            'status_bisnis' => 'Baru Mulai',
            'category_id' => $category->id,
        ]);
    }
}
