<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalItem extends Model
{
    protected $table = 'journal_items';

    protected $fillable = [
        'journal_id',
        'akun_id',
        'debit',
        'kredit',
        'keterangan',
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'kredit' => 'decimal:2',
    ];

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }
}
