<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class produk extends Model
{
    protected $table = 'produk';
    protected $fillable = [
        'business_id',
        'nama_produk',
        'modal',
        'harga_jual',
        'inventori',
        'jenis_produk',
        'gambar',
    ];
}
