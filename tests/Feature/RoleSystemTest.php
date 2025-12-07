<?php

use App\Models\User;
use App\Models\Business;
use App\Models\BusinessJoinRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('assigns owner role when creating a business', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post(route('setup-bisnis.store'), [
        'nama_bisnis' => 'Test Business',
        'status_bisnis' => 'aktif',
        'kategori_bisnis' => 'Teknologi',
        'channel_penjualan' => 'Online',
        'target_pasar' => 'B2C',
        'range_omset' => '< 10 juta',
        'jumlah_tim' => '1-5',
        'tujuan_utama' => 'Meningkatkan penjualan',
    ]);

    $user->refresh();

    expect($user->role)->toBe('owner');
    expect($user->business_id)->not->toBeNull();
});

it('assigns staf role when join request is approved', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $business = Business::factory()->create(['user_id' => $owner->id]);
    $owner->update(['business_id' => $business->id]);

    $staffUser = User::factory()->create();

    $joinRequest = BusinessJoinRequest::create([
        'user_id' => $staffUser->id,
        'business_id' => $business->id,
        'status' => 'pending',
    ]);

    $this->actingAs($owner);

    $response = $this->post(route('business.request.action', $joinRequest->id), [
        'action' => 'approve',
    ]);

    $staffUser->refresh();

    expect($staffUser->role)->toBe('staf');
    expect($staffUser->business_id)->toBe($business->id);
});

it('displays owner separately from paginated staff', function () {
    $owner = User::factory()->create(['role' => 'owner']);
    $business = Business::factory()->create(['user_id' => $owner->id]);
    $owner->update(['business_id' => $business->id]);

    $staffMembers = User::factory()->count(7)->create([
        'business_id' => $business->id,
        'role' => 'staf',
    ]);

    $this->actingAs($owner);

    $response = $this->get(route('profil_bisnis'));

    $response->assertSuccessful();
    $response->assertSee($owner->name);
    $response->assertSee('Owner');
    $response->assertSee('Staf');

    $firstPageStaff = $staffMembers->take(5);
    foreach ($firstPageStaff as $staff) {
        $response->assertSee($staff->name);
    }
});
