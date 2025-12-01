<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_bisnis');
            $table->string('status_bisnis'); // 'Baru Mulai', 'Sudah Berjalan'
            $table->string('kategori_bisnis');
            $table->text('masalah_utama')->nullable();
            $table->string('channel_penjualan'); // 'Online', 'Offline', 'Hybrid'
            $table->string('range_omset');
            $table->string('target_pasar');
            $table->string('jumlah_tim');
            $table->string('tujuan_utama');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
