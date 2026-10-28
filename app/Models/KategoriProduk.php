<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    protected $table = 'kategori_produk';

    protected $fillable = [
        'nama_kategori',
        'tipe',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Scope untuk kategori produk
     */
    public function scopeProduk($query)
    {
        return $query->where('tipe', 'produk');
    }

    /**
     * Scope untuk kategori expenses
     */
    public function scopeExpenses($query)
    {
        return $query->where('tipe', 'expenses');
    }
}
