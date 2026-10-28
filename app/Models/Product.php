<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'user_id',
        'business_id',
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
        'brand', // Merek produk umum
        // Field legacy dipertahankan untuk kompatibilitas fitur lain (Transaksi, dll)
        'category_id',
        'unit_id',
        'jenis_logam',
        'berat_standar',
        'weight',
        'purity',
        'kadar',
        'cost_price',
        'stock_minimum',
    ];

    protected $casts = [
        'berat_standar' => 'decimal:2',
        'weight' => 'decimal:2',
        'purity' => 'decimal:1',
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'stock_minimum' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get transaksi masuk items
     */
    public function transaksiMasukItems()
    {
        return $this->hasMany(TransaksiMasukItem::class);
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
