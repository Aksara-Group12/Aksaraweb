<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProdukStokController extends Controller
{
    public function index()
    {
        $stok = Product::selectRaw('jenis_logam, berat_standar as gramasi, COUNT(*) as stok')
            ->groupBy('jenis_logam', 'berat_standar')
            ->orderBy('jenis_logam')
            ->orderBy('berat_standar')
            ->get();

        return view('produk-stok.index', compact('stok'));
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        abort(404);
    }

    public function edit($id)
    {
        abort(404);
    }

    public function update(Request $request, $id)
    {
        abort(404);
    }
}