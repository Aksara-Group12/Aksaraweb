<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiMasukItem extends Model
{
    protected $table = 'transaksi_masuk_items';
    protected $fillable = [
        'user_id',
        'transaksi_masuk_id',
        'product_id',
        'qty', // Jumlah yang dibeli
        'harga_beli', // Harga beli aktual per satuan
        'total_harga', // Subtotal = qty * harga_beli
        'diskon', // Diskon per baris (opsional)
        'deskripsi', // Keterangan tambahan
        // Field legacy dipertahankan untuk kompatibilitas data lama
        'berat',
        'harga_gram',
        'kadar',
        'nomor_seri',
    ];

    protected $casts = [
        'qty' => 'decimal:2',
        'harga_beli' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'diskon' => 'decimal:2',
        'berat' => 'decimal:2',
        'harga_gram' => 'decimal:2',
        'kadar' => 'decimal:1',
        'nomor_seri' => 'array',
    ];

    /**
     * Get parent transaksi masuk
     */
    public function transaksi()
    {
        return $this->belongsTo(TransaksiMasuk::class, 'transaksi_masuk_id');
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
    public static function calculateTotal($qty, $hargaBeli, $diskon = 0)
    {
        $subtotal = $qty * $hargaBeli;
        return $subtotal - $diskon;
    }

    /**
     * Hitung total harga legacy (untuk kompatibilitas logam mulia)
     */
    public static function calculateTotalLegacy($berat, $qty, $harga_gram)
    {
        return $berat * $qty * $harga_gram;
    }
}
