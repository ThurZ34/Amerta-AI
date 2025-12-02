<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    use HasFactory;

    protected $table = 'coa';

    protected $fillable = [
        'name',
        'type',
        'is_operational',
    ];

    public function cashJournals()
    {
        return $this->hasMany(CashJournal::class, 'coa_id');
    }

    public function scopeInflow($query)
    {
        return $query->where('type', 'INFLOW');
    }

    public function scopeOutflow($query)
    {
        return $query->where('type', 'OUTFLOW');
    }
}
