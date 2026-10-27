<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustmentItem extends Model
{
    protected $table = 'stock_adjustment_items';

    protected $fillable = [
        'stock_adjustment_id',
        'product_id',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'alasan',
        'total_nilai_selisih',
    ];

    protected $casts = [
        'stok_sistem' => 'decimal:2',
        'stok_fisik' => 'decimal:2',
        'selisih' => 'decimal:2',
        'total_nilai_selisih' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class);
    }
}
