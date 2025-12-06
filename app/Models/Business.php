<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Business extends Model
{
    protected $fillable = [
        'user_id',
        'invite_code',
        'nama_bisnis',
        'status_bisnis',
        'category_id',
        'masalah_utama',
        'channel_penjualan',
        'range_omset',
        'target_revenue',
        'target_pasar',
        'jumlah_tim',
        'tujuan_utama',
        'alamat',
        'telepon',
        'gambar'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($business) {
            $business->invite_code = strtoupper(\Illuminate\Support\Str::random(6));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function joinRequests()
    {
        return $this->hasMany(BusinessJoinRequest::class);
    }
}
