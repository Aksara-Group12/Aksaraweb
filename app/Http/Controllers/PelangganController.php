<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::where('user_id', auth()->user()->id);

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

        $pelanggan = $query
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->only('search', 'per_page'));

        return view('pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('pelanggan.create');
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

        Pelanggan::create([
            'user_id' => auth()->user()->id,
            'nama' => $validated['nama'],
            'kontak' => $validated['kontak'] ?? null,
            'email' => $validated['email'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function edit(Pelanggan $pelanggan)
    {
        if ((int) $pelanggan->user_id !== (int) auth()->user()->id) {
            abort(403);
        }
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        if ((int) $pelanggan->user_id !== (int) auth()->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'kontak' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $pelanggan->update($validated);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        if ((int) $pelanggan->user_id !== (int) auth()->user()->id) {
            abort(403);
        }

        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus');
    }
}
