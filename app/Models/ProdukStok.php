<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukStok extends Model
{
    protected $table = 'produk_stok';
    protected $fillable = [
        'jenis_logam',
        'gramasi',
        'stok',
    ];
}
