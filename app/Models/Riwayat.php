<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    protected $fillable = [
        'business_id',
        'nama_barang',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'inventori',
        'jenis',
        'metode_pembayaran',
        'keterangan',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
