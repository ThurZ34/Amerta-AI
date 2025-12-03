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
        if (array_key_exists('total_revenue', $this->attributes)) {
            return $this->attributes['total_revenue'];
        }

        return CashJournal::where('business_id', $this->business_id)
            ->inflows()
            ->whereDate('transaction_date', $this->date)
            ->sum('amount');
    }

    public function getTotalExpenseAttribute()
    {
        if (array_key_exists('total_expense', $this->attributes)) {
            return $this->attributes['total_expense'];
        }

        return CashJournal::where('business_id', $this->business_id)
            ->outflows()
            ->whereDate('transaction_date', $this->date)
            ->sum('amount');
    }

    public function getTotalProfitAttribute()
    {
        if (array_key_exists('total_profit', $this->attributes)) {
            return $this->attributes['total_profit'];
        }

        return $this->total_revenue - $this->total_expense;
    }
}
