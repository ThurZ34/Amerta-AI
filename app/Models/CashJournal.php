<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashJournal extends Model
{
    use HasFactory;

    protected $table = 'cash_journals';

    protected $fillable = [
        'transaction_date',
        'business_id',
        'coa_id',
        'amount',
        'is_inflow',
        'payment_method',
        'description',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'is_inflow' => 'boolean',
        'amount' => 'decimal:2',
    ];

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'coa_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function scopeInflows($query)
    {
        return $query->where('is_inflow', true);
    }

    public function scopeOutflows($query)
    {
        return $query->where('is_inflow', false);
    }
}
