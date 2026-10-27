@extends('layouts.horizon')

@section('page_title', 'Detail Stok')

@section('content')
<div class="hz-top-row">
	<div>
		<h2 class="hz-top-title">Detail Stok Produk</h2>
		<p class="hz-top-sub">Informasi stok dan riwayat pergerakan untuk {{ $product->nama_produk }}</p>
	</div>
	<div class="hz-top-actions">
		<a href="{{ route('produkstok.index') }}" class="hz-btn hz-btn-secondary">
			← Kembali
		</a>
	</div>
</div>

<section class="hz-secondary-grid" style="margin-top: 24px;">
	<div class="hz-card" style="grid-column: 1 / -1;">
		<div class="hz-card-header" style="margin-bottom: 16px;">
			<p class="hz-card-title">Informasi Produk</p>
		</div>

		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
			<div style="display: flex; flex-direction: column; gap: 6px;">
				<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Kode Produk</label>
				<div style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #9ca3af; font-size: 0.9rem;">
					{{ $product->code }}
				</div>
			</div>

			<div style="display: flex; flex-direction: column; gap: 6px;">
				<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Nama Produk</label>
				<div style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
					{{ $product->nama_produk }}
				</div>
			</div>

			<div style="display: flex; flex-direction: column; gap: 6px;">
				<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Kategori</label>
				<div style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #9ca3af; font-size: 0.9rem;">
					{{ $product->kategori ?? '-' }}
				</div>
			</div>

			<div style="display: flex; flex-direction: column; gap: 6px;">
				<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Satuan</label>
				<div style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #9ca3af; font-size: 0.9rem;">
					{{ $product->unit }}
				</div>
			</div>

			<div style="display: flex; flex-direction: column; gap: 6px;">
				<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Stok Tersedia</label>
				<div style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #fbbf24; font-size: 0.9rem; font-weight: 600;">
					{{ $product->stock }}
				</div>
			</div>

			<div style="display: flex; flex-direction: column; gap: 6px;">
				<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Stok Minimum</label>
				<div style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #9ca3af; font-size: 0.9rem;">
					{{ $product->min_stock ?? '-' }}
				</div>
			</div>

			<div style="display: flex; flex-direction: column; gap: 6px;">
				<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Status Stok</label>
				<div style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); font-size: 0.9rem;">
					@if($product->status_stok === 'Habis')
						<span style="background: rgba(239,68,68,0.2); padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; color: #fca5a5;">Habis</span>
					@elseif($product->status_stok === 'Menipis')
						<span style="background: rgba(251,191,36,0.2); padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; color: #fbbf24;">Menipis</span>
					@else
						<span style="background: rgba(34,197,94,0.2); padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; color: #86efac;">Aman</span>
					@endif
				</div>
			</div>
		</div>
	</div>

	<div class="hz-card" style="grid-column: 1 / -1;">
		<div class="hz-card-header" style="margin-bottom: 16px;">
			<p class="hz-card-title">Riwayat Stok</p>
			<p class="hz-card-sub">Pergerakan stok akan ditampilkan setelah fitur transaksi pembelian dan penjualan disesuaikan.</p>
		</div>
		<div style="padding: 40px; text-align: center; color: #9ca3af;">
			<p>Riwayat stok belum tersedia.</p>
			<p style="font-size: 0.85rem; margin-top: 8px;">Fitur ini akan diimplementasikan pada tahap Inventory.</p>
		</div>
	</div>
</section>
@endsection
