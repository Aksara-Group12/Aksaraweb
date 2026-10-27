@extends('layouts.horizon')

@section('page_title', 'Manajemen Stok')

@section('content')
<div class="hz-top-row">
    <div>
        <h2 class="hz-top-title">Manajemen Stok</h2>
        <p class="hz-top-sub">Persediaan produk berdasarkan master produk. Total Produk: {{ $products->count() }}</p>
    </div>
    <div class="hz-top-actions" style="display:flex; align-items:center; gap:10px;">
        <form method="GET" action="{{ route('produkstok.index') }}" style="display:flex; align-items:center; gap:6px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / kode / SKU" style="border-radius:999px; border:1px solid rgba(148,163,184,0.5); background:#020617; padding:6px 10px; font-size:0.8rem; color:#e5e7eb; min-width:200px;">
            <select name="per_page" onchange="this.form.submit()" style="border-radius:999px; border:1px solid rgba(148,163,184,0.5); background:#020617; padding:6px 8px; font-size:0.8rem; color:#e5e7eb;">
                <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
            </select>
        </form>
    </div>
</div>

<section class="hz-secondary-grid" style="margin-top: 24px;">
    <div class="hz-card" style="grid-column: 1 / -1;">
        <div class="hz-card-header" style="margin-bottom: 12px;">
            <div>
                <p class="hz-card-title">Daftar Stok Produk</p>
                <p class="hz-card-sub">Satu baris = satu produk dalam satuan dasar.</p>
            </div>
        </div>
        <div style="margin-top: 8px; overflow-x: auto;">
            @if ($products->count() > 0)
                <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(251, 191, 36, 0.4); color:#9ca3af;">
                            <th style="text-align:left; padding:14px 10px;">Kode</th>
                            <th style="text-align:left; padding:14px 10px;">Nama Produk</th>
                            <th style="text-align:left; padding:14px 10px;">Kategori</th>
                            <th style="text-align:center; padding:14px 10px;">Satuan</th>
                            <th style="text-align:right; padding:14px 10px;">Stok</th>
                            <th style="text-align:right; padding:14px 10px;">Min Stok</th>
                            <th style="text-align:center; padding:14px 10px;">Status</th>
                            <th style="text-align:right; padding:14px 10px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr style="border-bottom: 1px solid rgba(15,23,42,0.8);">
                                <td style="padding:14px 10px; font-weight: 500; color: #fbbf24;">{{ $product->code }}</td>
                                <td style="padding:14px 10px; color: #e5e7eb;">
                                    {{ $product->nama_produk }}
                                    @if($product->brand)
                                        <br><small style="color: #9ca3af;">{{ $product->brand }}</small>
                                    @endif
                                </td>
                                <td style="padding:14px 10px; color: #cbd5e1;">{{ $product->kategori ?? '-' }}</td>
                                <td style="padding:14px 10px; text-align:center; color: #cbd5e1;">{{ $product->unit }}</td>
                                <td style="padding:14px 10px; text-align:right; font-weight: 600; color: #fbbf24;">
                                    {{ $product->stock }}
                                </td>
                                <td style="padding:14px 10px; text-align:right; color: #cbd5e1;">
                                    {{ $product->min_stock ?? '-' }}
                                </td>
                                <td style="padding:14px 10px; text-align:center;">
                                    @if($product->status_stok === 'Habis')
                                        <span style="background: rgba(239,68,68,0.2); padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; color: #fca5a5;">Habis</span>
                                    @elseif($product->status_stok === 'Menipis')
                                        <span style="background: rgba(251,191,36,0.2); padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; color: #fbbf24;">Menipis</span>
                                    @else
                                        <span style="background: rgba(34,197,94,0.2); padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; color: #86efac;">Aman</span>
                                    @endif
                                </td>
                                <td style="padding:14px 10px; text-align:right;">
                                    <a href="{{ route('produkstok.show', $product->id) }}" class="hz-btn hz-btn-secondary" style="padding: 4px 12px; font-size: 0.8rem;">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding:18px 10px; text-align:center; color:#9ca3af;">Belum ada produk dengan stok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <div style="padding: 40px; text-align: center; color: #9ca3af;">
                    <p style="margin-bottom: 16px;">Belum ada produk dalam katalog.</p>
                    <a href="{{ route('produk.create') }}" class="hz-btn hz-btn-primary">
                        + Tambah Produk
                    </a>
                </div>
            @endif
        </div>
        @if (method_exists($products, 'links'))
            <div style="margin-top: 12px; display:flex; justify-content:space-between; align-items:center; font-size:0.8rem; color:#9ca3af;">
                <div>
                    Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} baris
                </div>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
