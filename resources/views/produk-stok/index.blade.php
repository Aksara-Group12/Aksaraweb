@extends('layouts.horizon')

@section('page_title', 'Manajemen Stok')
@section('page_subtitle', 'Pantau persediaan stok fisik dan peringatan stok menipis.')

@section('content')
<div class="hz-top-row" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px; flex-wrap:wrap; gap:12px;">
    <div class="hz-top-actions" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; width:100%; justify-content:space-between;">
        <form method="GET" action="{{ route('produkstok.index') }}" style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
            <div style="position:relative; display:inline-flex; align-items:center;">
                <svg style="position:absolute; left:12px; width:15px; height:15px; color:#94A3B8; pointer-events:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / kode / SKU..." style="padding-left:36px; min-width:240px;">
            </div>
            <select name="per_page" onchange="this.form.submit()">
                <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
            </select>
        </form>
    </div>
</div>

<section class="hz-secondary-grid" style="margin-top: 24px;">
    <div class="hz-card" style="grid-column: 1 / -1; padding: 24px;">
        <div class="hz-card-header" style="margin-bottom: 16px;">
            <div>
                <p class="hz-card-title">Daftar Stok Produk</p>
                <p class="hz-card-sub">Satu baris = satu produk dalam satuan dasar.</p>
            </div>
        </div>
        <div style="margin-top: 8px; overflow-x: auto;">
            @if ($products->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="text-align:left;">Kode</th>
                            <th style="text-align:left;">Nama Produk</th>
                            <th style="text-align:left;">Kategori</th>
                            <th style="text-align:center;">Satuan</th>
                            <th style="text-align:right;">Stok</th>
                            <th style="text-align:right;">Min Stok</th>
                            <th style="text-align:center;">Status</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td style="font-weight: 600; color: #0F223D;">{{ $product->code }}</td>
                                <td>
                                    <div style="font-weight: 600; color: #0F223D;">{{ $product->nama_produk }}</div>
                                    @if($product->brand)
                                        <small style="color: #64748B;">{{ $product->brand }}</small>
                                    @endif
                                </td>
                                <td style="color: #475569;">{{ $product->kategori ?? '-' }}</td>
                                <td style="text-align:center; color: #475569;">{{ $product->unit }}</td>
                                <td style="text-align:right; font-weight: 700; color: #0F223D;">
                                    {{ $product->stock }}
                                </td>
                                <td style="text-align:right; color: #64748B;">
                                    {{ $product->min_stock ?? '-' }}
                                </td>
                                <td style="text-align:center;">
                                    @if($product->status_stok === 'Habis')
                                        <span style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; padding: 3px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">Habis</span>
                                    @elseif($product->status_stok === 'Menipis')
                                        <span style="background: #FFFBEB; border: 1px solid #FDE68A; color: #92400E; padding: 3px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">Menipis</span>
                                    @else
                                        <span style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 3px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">Aman</span>
                                    @endif
                                </td>
                                <td style="text-align:right;">
                                    <a href="{{ route('produkstok.show', $product->id) }}" class="hz-btn-secondary" style="display:inline-flex; align-items:center; padding:5px 12px; border-radius:8px; font-size:0.8rem; font-weight:600; text-decoration:none;">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding:32px 10px; text-align:center; color:#94A3B8;">Belum ada produk dengan stok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <div style="padding: 48px; text-align: center; color: #64748b;">
                    <p style="margin-bottom: 16px; font-size:0.95rem;">Belum ada produk dalam katalog.</p>
                    <a href="{{ route('produk.create') }}" class="hz-btn hz-btn-primary">
                        + Tambah Produk
                    </a>
                </div>
            @endif
        </div>
        @if (method_exists($products, 'links'))
            <div style="margin-top: 18px; display:flex; justify-content:space-between; align-items:center; font-size:0.8rem; color:#64748B;">
                <div>
                    Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
                </div>
                <div>
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
