<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiBiayaTambahan extends Model
{
    protected $table = 'transaksi_biaya_tambahan';

    protected $fillable = [
        'user_id',
        'transaksi_masuk_id',
        'transaksi_keluar_id',
        'nama',
        'keterangan',
        'nominal',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
    ];

    public function transaksiMasuk()
    {
        return $this->belongsTo(TransaksiMasuk::class, 'transaksi_masuk_id');
    }

    public function transaksiKeluar()
    {
        return $this->belongsTo(TransaksiKeluar::class, 'transaksi_keluar_id');
    }
}
