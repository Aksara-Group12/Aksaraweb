<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::where('user_id', auth()->user()->id);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kontak', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 20);
        if ($perPage <= 0) {
            $perPage = 20;
        }

        $supplier = $query
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->only('search', 'per_page'));

        return view('supplier.index', compact('supplier'));
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'kontak' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        Supplier::create([
            'user_id' => auth()->user()->id,
            'nama' => $validated['nama'],
            'kontak' => $validated['kontak'] ?? null,
            'email' => $validated['email'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan');
    }

    public function edit(Supplier $supplier)
    {
        if ((int) $supplier->user_id !== (int) auth()->user()->id) {
            abort(403);
        }
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        if ((int) $supplier->user_id !== (int) auth()->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'kontak' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $supplier->update($validated);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil diperbarui');
    }

    public function destroy(Supplier $supplier)
    {
        if ((int) $supplier->user_id !== (int) auth()->user()->id) {
            abort(403);
        }

        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus');
    }
}
