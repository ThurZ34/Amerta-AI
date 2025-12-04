<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Riwayat extends Model
{
    protected $fillable = [
        'business_id',
        'tanggal_pembelian',
        'nama_barang',
        'keterangan',
        'total_harga',
        'bukti_pembayaran',
        'jenis',
        'cash_journal_id',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function cashJournal()
    {
        return $this->belongsTo(CashJournal::class);
    }
}
