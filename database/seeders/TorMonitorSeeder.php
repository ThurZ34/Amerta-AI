<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TorMonitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'tormonitor@amerta.ai'],
            [
                'name' => 'tor monitor ketua',
                'password' => Hash::make('123123123'),
            ]
        );

        $category = Category::firstOrCreate(['name' => 'F&B']);

        try {
            $business = Business::create([
                'user_id' => $user->id,
                'invite_code' => 'TOR123',
                'nama_bisnis' => 'Tor Monitor Ketua coffee',
                'status_bisnis' => 'Berjalan',
                'category_id' => $category->id,
                'masalah_utama' => 'Ingin ekspansi',
                'channel_penjualan' => 'Offline',
                'range_omset' => '< 10 Juta',
                'target_revenue' => 50000000,
                'target_pasar' => 'Mahasiswa',
                'jumlah_tim' => 5,
                'tujuan_utama' => 'Scale Up',
                'alamat' => 'Jl. Monitor No. 1',
                'telepon' => '081234567890',
            ]);
        } catch (\Exception $e) {
            $this->command->error('Error creating business: ' . $e->getMessage());
            return;
        }

        $user->business_id = $business->id;
        $user->save();

        $this->command->info('User "tor monitor ketua" and business "Tor Monitor Ketua coffee" created successfully.');
        $this->command->info('Email: tormonitor@amerta.ai');
        $this->command->info('Password: 123123123');
        $this->command->info('Invite Code: ' . $business->invite_code);
    }
}
