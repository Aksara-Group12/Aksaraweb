<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSerialNumber extends Model
{
    protected $table = 'product_serial_numbers';
    protected $fillable = [
        'user_id',
        'product_id',
        'nomor_seri',
        'status'
    ];

    /**
     * Get the product that owns this serial number
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope untuk nomor seri yang tersedia
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope untuk nomor seri yang sudah terjual
     */
    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }
}
