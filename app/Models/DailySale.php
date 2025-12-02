<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySale extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'date',
        'ai_analysis',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function items()
    {
        return $this->hasMany(DailySaleItem::class);
    }
    public function getTotalRevenueAttribute()
    {
        return CashJournal::inflows()
            ->whereDate('transaction_date', $this->date)
            ->sum('amount');
    }

    public function getTotalExpenseAttribute()
    {
        return CashJournal::outflows()
            ->whereDate('transaction_date', $this->date)
            ->sum('amount');
    }
    public function getTotalProfitAttribute()
    {
        return $this->getTotalRevenueAttribute() - $this->getTotalExpenseAttribute();
    }
}
