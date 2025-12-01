<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Business extends Model
{
    protected $fillable = [
        'user_id',
        'nama_bisnis',
        'status_bisnis',
        'kategori_bisnis',
        'masalah_utama',
        'channel_penjualan',
        'range_omset',
        'target_pasar',
        'jumlah_tim',
        'tujuan_utama',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
