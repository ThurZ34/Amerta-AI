<?php

namespace Database\Seeders;

use App\Models\CashJournal;
use App\Models\Coa;
use App\Models\DailySale;
use App\Models\DailySaleItem;
use App\Models\Produk;
use App\Models\Riwayat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'tormonitor@amerta.ai')->first();
        if (! $user || ! $user->business) {
            $this->command->error('User or Business not found. Please run TorMonitorSeeder first.');

            return;
        }

        $business = $user->business;
        $businessId = $business->id;

        $this->command->info('Seeding demo data for: '.$business->nama_bisnis);

        // 1. Initial Capital (Modal Awal)
        // Target: 15.000.000 Opening Balance
        $equityCoa = Coa::firstOrCreate(
            ['name' => 'Modal Disetor'],
            ['type' => 'EQUITY']
        );

        CashJournal::firstOrCreate(
            [
                'business_id' => $businessId,
                'description' => 'Modal Awal Bisnis',
            ],
            [
                'transaction_date' => Carbon::now()->startOfMonth()->subDay(), // Start of yesterday/month
                'coa_id' => $equityCoa->id,
                'amount' => 15000000,
                'is_inflow' => true,
                'payment_method' => 'Transfer',
            ]
        );

        // 2. Create Products
        $productsData = [
            ['nama_produk' => 'Kopi Susu Aren', 'harga_jual' => 18000, 'harga_hpp' => 8000, 'stok' => 100, 'kategori' => 'Minuman', 'unit' => 'Cup'],
            ['nama_produk' => 'Americano', 'harga_jual' => 15000, 'harga_hpp' => 5000, 'stok' => 100, 'kategori' => 'Minuman', 'unit' => 'Cup'],
            ['nama_produk' => 'Caffe Latte', 'harga_jual' => 22000, 'harga_hpp' => 9000, 'stok' => 80, 'kategori' => 'Minuman', 'unit' => 'Cup'],
            ['nama_produk' => 'Cromboloni', 'harga_jual' => 25000, 'harga_hpp' => 12000, 'stok' => 50, 'kategori' => 'Makanan', 'unit' => 'Pcs'],
            ['nama_produk' => 'Croissant', 'harga_jual' => 20000, 'harga_hpp' => 10000, 'stok' => 60, 'kategori' => 'Makanan', 'unit' => 'Pcs'],
        ];

        $products = collect();
        foreach ($productsData as $p) {
            $products->push(Produk::create([
                'business_id' => $businessId,
                'nama_produk' => $p['nama_produk'],
                'modal' => $p['harga_hpp'],
                'harga_jual' => $p['harga_jual'],
                'jenis_produk' => $p['kategori'],
                // 'stok' => $p['stok'], // Not in schema
                // 'unit' => $p['unit'], // Not in schema
            ]));
        }

        // 3. Generate Transactions for this Month
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now();

        $salesCoa = Coa::firstOrCreate(['name' => 'Penjualan Produk'], ['type' => 'INFLOW']);

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            // High volume for healthy stats
            $txCount = rand(35, 55);

            // Daily Aggregation Variables
            $dailyRevenue = 0;
            $dailyCost = 0;
            $dailyItemsMap = []; // product_id => [qty, price, cost]

            for ($i = 0; $i < $txCount; $i++) {
                // Random product purchase
                $prod = $products->random();
                $qty = rand(1, 4);
                $totalPrice = $prod->harga_jual * $qty;
                $totalCost = $prod->modal * $qty;

                // Create CashJournal for this specific transaction (Simulating Kasir flow)
                CashJournal::create([
                    'business_id' => $businessId,
                    'transaction_date' => $date,
                    'coa_id' => $salesCoa->id,
                    'amount' => $totalPrice, // Revenue
                    'is_inflow' => true,
                    'payment_method' => 'Cash',
                    'description' => 'Penjualan '.$prod->nama_produk,
                ]);

                // Create Riwayat (Transaction History)
                Riwayat::create([
                    'business_id' => $businessId,
                    'tanggal_pembelian' => $date,
                    'nama_barang' => $prod->nama_produk.' x'.$qty,
                    'keterangan' => 'Penjualan Kasir',
                    'total_harga' => $totalPrice,
                    'jenis' => 'pendapatan',
                    'kategori' => 'Penjualan',
                    // 'cash_journal_id' => ... (Linked implicitly by logic or future ID)
                ]);

                // Aggregate for DailySale
                $dailyRevenue += $totalPrice;
                $dailyCost += $totalCost;

                if (! isset($dailyItemsMap[$prod->id])) {
                    $dailyItemsMap[$prod->id] = ['qty' => 0, 'price' => $prod->harga_jual, 'cost' => $prod->modal];
                }
                $dailyItemsMap[$prod->id]['qty'] += $qty;
            }

            // Create Daily Sale Record
            $dailySale = DailySale::create([
                'business_id' => $businessId,
                'date' => $date,
                'ai_analysis' => 'Data penjualan otomatis generated.',
            ]);

            // Create Daily Sale Items
            foreach ($dailyItemsMap as $prodId => $data) {
                DailySaleItem::create([
                    'daily_sale_id' => $dailySale->id,
                    'produk_id' => $prodId,
                    'quantity' => $data['qty'],
                    'price' => $data['price'],
                    'cost' => $data['cost'],
                ]);
            }
        }

        // 4. Create Operational Expenses (To manage Profit Margin)
        // Target Revenue (Approx): 30 days * 5 tx * 50k = 7.500.000
        // Target Profit Margin ~25%.
        // Cost (HPP) is approx 40-50%.
        // OpEx should be ~25%.

        $expenses = [
            ['name' => 'Token Listrik', 'amount' => 500000, 'cat' => 'Listrik & Air'],
            ['name' => 'Internet IndiHome', 'amount' => 350000, 'cat' => 'Internet'],
            ['name' => 'Gaji Part-time Barista', 'amount' => 1500000, 'cat' => 'Gaji Karyawan'],
            ['name' => 'Beli Cup & Sedotan', 'amount' => 800000, 'cat' => 'Perlengkapan'],
            ['name' => 'Maintenance Mesin Kopi', 'amount' => 250000, 'cat' => 'Maintenance'],
        ];

        $expenseCoa = Coa::firstOrCreate(['name' => 'Beban Operasional'], ['type' => 'OUTFLOW']);

        foreach ($expenses as $exp) {
            $date = Carbon::now()->startOfMonth()->addDays(rand(1, 20)); // Random date this month

            CashJournal::create([
                'business_id' => $businessId,
                'transaction_date' => $date,
                'coa_id' => $expenseCoa->id,
                'amount' => $exp['amount'],
                'is_inflow' => false,
                'payment_method' => 'Transfer',
                'description' => $exp['name'],
            ]);

            Riwayat::create([
                'business_id' => $businessId,
                'tanggal_pembelian' => $date,
                'nama_barang' => $exp['name'],
                'keterangan' => 'Biaya Operasional Bulanan',
                'total_harga' => $exp['amount'],
                'jenis' => 'pengeluaran',
                'kategori' => $exp['cat'],
            ]);
        }

        $this->command->info('Demo data seeded successfully. Business Health should be optimal (~85-100%).');
    }
}
