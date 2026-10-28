<?php

namespace App\Http\Controllers;

use App\Models\StockAdjustment;
use App\Models\StockAdjustmentItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    public function index(Request $request)
    {
        $query = StockAdjustment::where('user_id', auth()->user()->id)
            ->with('items.product');

        if ($search = $request->input('search')) {
            $query->where('nomor_adjustment', 'like', "%{$search}%");
        }

        $perPage = (int) $request->input('per_page', 20);
        if ($perPage <= 0) {
            $perPage = 20;
        }

        $adjustments = $query
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->only('search', 'per_page'));

        return view('stock-adjustment.index', compact('adjustments'));
    }

    public function create()
    {
        $products = Product::where('user_id', auth()->user()->id)
            ->where('is_active', true)
            ->orderBy('nama_produk')
            ->get();

        return view('stock-adjustment.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.stok_fisik' => 'required|numeric|min:0',
            'items.*.alasan' => 'required|in:rusak,kadaluarsa,hilang,selisih_hitung,lainnya',
        ]);

        DB::transaction(function () use ($validated, &$adjustmentRef) {
            $nomorAdjustment = 'ADJ-' . date('Ymd') . '-' . str_pad(StockAdjustment::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            $adjustment = StockAdjustment::create([
                'user_id' => auth()->user()->id,
                'nomor_adjustment' => $nomorAdjustment,
                'tanggal' => $validated['tanggal'],
                'keterangan' => $validated['keterangan'] ?? null,
                'status' => 'completed',
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $stokSistem = $product->stock;
                $stokFisik = floatval($item['stok_fisik']);
                $selisih = $stokFisik - $stokSistem;

                // Hitung total nilai selisih = selisih * harga_beli
                $hargaBeli = floatval($product->harga_beli ?? 0);
                $totalNilaiSelisih = $selisih * $hargaBeli;

                StockAdjustmentItem::create([
                    'stock_adjustment_id' => $adjustment->id,
                    'product_id' => $item['product_id'],
                    'stok_sistem' => $stokSistem,
                    'stok_fisik' => $stokFisik,
                    'selisih' => $selisih,
                    'alasan' => $item['alasan'],
                    'total_nilai_selisih' => $totalNilaiSelisih,
                ]);

                // Update stok produk
                $product->stock = $stokFisik;
                $product->save();

                // Catat ke Log Produk (StockMovement)
                StockMovement::create([
                    'user_id' => auth()->user()->id,
                    'product_id' => $product->id,
                    'tipe' => 'adjustment',
                    'jumlah' => $selisih,
                    'stok_sebelum' => $stokSistem,
                    'stok_sesudah' => $stokFisik,
                    'tanggal' => now(),
                    'keterangan' => 'Stock Adjustment #' . $nomorAdjustment . ' - ' . $item['alasan'],
                ]);
            }

            $adjustmentRef = $adjustment;
        });

        return redirect()->route('stock-adjustment.index')->with('success', 'Stock adjustment berhasil disimpan');
    }
}
