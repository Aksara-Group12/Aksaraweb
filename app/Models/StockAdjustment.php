<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $table = 'stock_adjustments';

    protected $fillable = [
        'user_id',
        'nomor_adjustment',
        'tanggal',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(StockAdjustmentItem::class);
    }
}
