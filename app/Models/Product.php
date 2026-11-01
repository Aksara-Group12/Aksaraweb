<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'user_id',
        'code',
        'sku',
        'nama_produk',
        'kategori', // Kategori produk umum
        'tipe', // Tipe produk: Barang, Menu, Jasa
        'lacak_stok', // Lacak stok: true/false
        'unit', // Satuan produk (pcs, kg, gram, dll)
        'harga_beli', // Harga modal referensi
        'harga_jual', // Harga jual - wajib untuk POS
        'stock', // Stok fisik
        'min_stock', // Stok minimum
        'is_active', // Status aktif/non-aktif
        'description', // Deskripsi produk
        'barcode', // Barcode untuk scan
        'lokasi', // Lokasi penyimpanan
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'is_active' => 'boolean',
        'lacak_stok' => 'boolean',
    ];

    /**
     * Get transaksi masuk items
     */
    public function transaksiMasukItems()
    {
        // TransaksiMasuk dihapus, relationship dihapus
        // return $this->hasMany(TransaksiMasukItem::class);
    }

    /**
     * Get transaksi keluar items
     */
    public function transaksiKeluarItems()
    {
        return $this->hasMany(TransaksiKeluarItem::class);
    }

    /**
     * Generate product code
     */
    public static function generateCode()
    {
        $prefix = 'PRD-' . date('Ymd') . '-';

        $lastCode = static::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->value('code');

        $nextNumber = 1;
        if ($lastCode) {
            $parts = explode('-', $lastCode);
            $lastNumber = (int) end($parts);
            $nextNumber = $lastNumber + 1;
        }

        $code = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        while (static::where('code', $code)->exists()) {
            $nextNumber++;
            $code = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        return $code;
    }
}
