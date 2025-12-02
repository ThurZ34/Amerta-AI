<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coa', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('type', ['INFLOW', 'OUTFLOW']);
            $table->boolean('is_operational')->default(true);
            $table->timestamps();
        });

        DB::table('coa')->insert([
            ['name' => 'Penjualan Produk', 'type' => 'INFLOW', 'is_operational' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pendapatan Lain-lain', 'type' => 'INFLOW', 'is_operational' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Beban Bahan Baku', 'type' => 'OUTFLOW', 'is_operational' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Beban Gaji Karyawan', 'type' => 'OUTFLOW', 'is_operational' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Beban Listrik & Air', 'type' => 'OUTFLOW', 'is_operational' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Beban Lain-lain', 'type' => 'OUTFLOW', 'is_operational' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('coa');
    }
};
