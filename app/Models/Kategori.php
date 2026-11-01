<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategoris';

    protected $fillable = [
        'name',
        'tipe',
        'user_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Scope untuk kategori produk
     */
    public function scopeProduk($query)
    {
        return $query->where('tipe', 'produk');
    }

    /**
     * Scope untuk kategori pengeluaran
     */
    public function scopePengeluaran($query)
    {
        return $query->where('tipe', 'pengeluaran');
    }
}
