<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_sale_id',
        'produk_id',
        'quantity',
        'price',
        'cost',
    ];

    public function dailySale()
    {
        return $this->belongsTo(DailySale::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
