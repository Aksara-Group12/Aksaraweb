<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'user_id',
        'nomor_invoice',
        'pelanggan_id',
        'tanggal_invoice',
        'jatuh_tempo',
        'subtotal',
        'ppn',
        'total',
        'status',
        'keterangan',
        'sales_transaction_id',
        'sisa_tagihan',
    ];

    protected $casts = [
        'tanggal_invoice' => 'date',
        'jatuh_tempo' => 'date',
        'subtotal' => 'decimal:2',
        'ppn' => 'decimal:2',
        'total' => 'decimal:2',
        'sisa_tagihan' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
