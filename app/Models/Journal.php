<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $table = 'journals';

    protected $fillable = [
        'user_id',
        'nomor_jurnal',
        'tanggal',
        'keterangan',
        'ref_type',
        'ref_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(JournalItem::class);
    }
}
