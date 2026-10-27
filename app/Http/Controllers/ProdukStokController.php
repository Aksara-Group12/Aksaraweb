<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ProdukStokController extends Controller
{
    /**
     * Tampilkan daftar stok produk
     */
    public function index(Request $request)
    {
        $query = Product::where('user_id', auth()->user()->id);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 20);
        if ($perPage <= 0) {
            $perPage = 20;
        }

        $products = $query
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->only('search', 'per_page'));

        // Hitung status stok untuk setiap produk
        $products->getCollection()->transform(function ($product) {
            $product->status_stok = $this->getStatusStok($product->stock, $product->min_stock);
            return $product;
        });

        return view('produk-stok.index', compact('products'));
    }

    /**
     * Hitung status stok
     */
    private function getStatusStok($stock, $minStock)
    {
        if ($stock <= 0) {
            return 'Habis';
        } elseif ($minStock && $stock <= $minStock) {
            return 'Menipis';
        } else {
            return 'Aman';
        }
    }

    /**
     * Detail stok produk
     */
    public function show($id)
    {
        $product = Product::where('user_id', auth()->user()->id)->findOrFail($id);
        $product->status_stok = $this->getStatusStok($product->stock, $product->min_stock);

        return view('produk-stok.show', compact('product'));
    }

    // Nonaktifkan create/store/edit/update karena stok diambil dari data produk
    public function create() { abort(404); }
    public function store(Request $request) { abort(404); }
    public function edit($id) { abort(404); }
    public function update(Request $request, $id) { abort(404); }
}
