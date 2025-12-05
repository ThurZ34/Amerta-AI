<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class produk extends Model
{
    protected $table = 'produk';
    protected $fillable = [
        'business_id',
        'nama_produk',
        'modal',
        'harga_jual',
        'jenis_produk',
        'gambar',
    ];

    /**
     * Relationship to DailySaleItem
     */
    public function dailySaleItems()
    {
        return $this->hasMany(DailySaleItem::class, 'produk_id');
    }

    /**
     * Calculate total sales for a specific month
     *
     * @param string $month Format: 'Y-m' (e.g., '2025-12')
     * @return int
     */
    public function getTotalTerjualPerBulan($month, $year): int
    {
        $date = Carbon::createFromDate($year, $month, 1);

        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        return $this->dailySaleItems()
            ->whereHas('dailySale', function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
            })
            ->sum('quantity');
    }
}
