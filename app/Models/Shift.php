<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = 'shifts';

    protected $fillable = [
        'user_id',
        'admin_id',
        'shift_number',
        'tanggal_shift',
        'modal_awal',
        'modal_akhir',
        'total_penjualan',
        'total_penerimaan',
        'selisih_kas',
        'status',
        'waktu_buka',
        'waktu_tutup',
        'catatan',
    ];

    protected $casts = [
        'tanggal_shift' => 'date',
        'modal_awal' => 'decimal:2',
        'modal_akhir' => 'decimal:2',
        'total_penjualan' => 'decimal:2',
        'total_penerimaan' => 'decimal:2',
        'selisih_kas' => 'decimal:2',
        'waktu_buka' => 'datetime',
        'waktu_tutup' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function transaksiKeluar()
    {
        return $this->hasMany(TransaksiKeluar::class, 'shift_id');
    }

    /**
     * Generate shift number
     */
    public static function generateNumber()
    {
        $count = static::whereDate('tanggal_shift', today())->count() + 1;
        return 'SHIFT-' . date('Ymd') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
