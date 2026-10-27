<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiKeluarItem extends Model
{
    protected $table = 'transaksi_keluar_items';
    protected $fillable = [
        'user_id',
        'transaksi_keluar_id',
        'product_id',
        'qty_keluar', // Jumlah yang dijual
        'harga_jual', // Harga jual per satuan
        'diskon', // Diskon per baris
        'total_harga', // Subtotal = qty_keluar * harga_jual - diskon
        'biaya_tambahan', // Opsional
        'nomor_seri', // Legacy untuk kompatibilitas
        'hpp_snapshot', // Snapshot HPP saat transaksi
    ];

    protected $casts = [
        'qty_keluar' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'biaya_tambahan' => 'decimal:2',
        'nomor_seri' => 'array',
        'hpp_snapshot' => 'decimal:2',
    ];

    /**
     * Get parent transaksi keluar
     */
    public function transaksi()
    {
        return $this->belongsTo(TransaksiKeluar::class, 'transaksi_keluar_id');
    }

    /**
     * Get product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Hitung total harga otomatis (untuk produk umum)
     */
    public static function calculateTotal($qty, $hargaJual, $diskon = 0, $biayaTambahan = 0)
    {
        $subtotal = ($qty * $hargaJual) - $diskon;
        return $subtotal + $biayaTambahan;
    }

    /**
     * Hitung total harga legacy (untuk kompatibilitas logam mulia)
     */
    public static function calculateTotalLegacy($qty, $harga_gram, $berat, $biaya_tambahan = 0)
    {
        return ($qty * $harga_gram * $berat) + $biaya_tambahan;
    }
}
