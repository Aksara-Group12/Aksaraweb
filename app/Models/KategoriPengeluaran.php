<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPengeluaran extends Model
{
    protected $table = 'kategori_pengeluaran';

    protected $fillable = [
        'name',
        'user_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk kategori aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
