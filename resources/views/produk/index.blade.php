@extends('layouts.horizon')

@section('page_title', 'Produk')

@section('content')
<div class="hz-top-row">
	<div>
		<h2 class="hz-top-title">Manajemen Produk</h2>
		<p class="hz-top-sub">Daftar dan pengelolaan produk pada sistem. Total Produk: {{ $products->count() }}</p>
	</div>
	<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px;">
		<form method="GET" action="{{ route('produk.index') }}" style="display:flex; align-items:center; gap:6px;">
			<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode / nama / brand" style="border-radius:999px; border:1px solid rgba(148,163,184,0.5); background:#020617; padding:6px 10px; font-size:0.8rem; color:#e5e7eb; min-width:200px;">
			<select name="per_page" onchange="this.form.submit()" style="border-radius:999px; border:1px solid rgba(148,163,184,0.5); background:#020617; padding:6px 8px; font-size:0.8rem; color:#e5e7eb;">
				<option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
				<option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
				<option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
			</select>
		</form>
		<a href="{{ route('produk.create') }}" class="hz-btn hz-btn-primary">
			+ Tambah Produk
		</a>
	</div>
</div>

@if ($message = Session::get('success'))
	<div style="margin-top: 20px; padding: 14px 16px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 10px; color: #86efac;">
		{{ $message }}
	</div>
@endif

@if ($message = Session::get('error'))
	<div style="margin-top: 20px; padding: 14px 16px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 10px; color: #fca5a5;">
		{{ $message }}
	</div>
@endif

<section class="hz-secondary-grid" style="margin-top: 24px;">
	<div class="hz-card" style="grid-column: 1 / -1;">
		<div class="hz-card-header" style="margin-bottom: 12px;">
			<div>
				<p class="hz-card-title">Daftar Produk ({{ $products->count() }} produk)</p>
				<p class="hz-card-sub">Katalog produk untuk POS dan Inventory.</p>
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
							<th style="text-align:right; padding:14px 10px;">Harga Jual</th>
							<th style="text-align:center; padding:14px 10px;">Stok</th>
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
									Rp {{ number_format($product->harga_jual, 0) }}
								</td>
								<td style="padding:14px 10px; text-align:center;">
									<span style="background: {{ $product->stock > 0 ? 'rgba(34,197,94,0.2)' : 'rgba(239,68,68,0.2)' }}; padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; color: {{ $product->stock > 0 ? '#86efac' : '#fca5a5' }};">
										{{ $product->stock }}
									</span>
								</td>
								<td style="padding:14px 10px; text-align:center;">
									@if($product->is_active)
										<span style="background: rgba(34,197,94,0.2); padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; color: #86efac;">Aktif</span>
									@else
										<span style="background: rgba(148,163,184,0.2); padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; color: #9ca3af;">Non-aktif</span>
									@endif
								</td>
								<td style="padding:14px 10px; text-align:right; position: relative;">
									<button type="button" class="hz-more-btn" style="background: transparent; border:none; color:#9ca3af; cursor:pointer; padding:4px 8px; border-radius:999px;" onclick="toggleDropdown('dropdown-{{ $product->id }}')">
										⋮
									</button>
									<div id="dropdown-{{ $product->id }}" class="hz-dropdown" style="display:none; position:absolute; right:0; top:100%; background:#020617; border:1px solid rgba(148,163,184,0.5); border-radius:8px; min-width:150px; z-index:10; box-shadow:0 4px 12px rgba(0,0,0,0.5);">
										<a href="{{ route('produk.edit', $product->id) }}" style="display:block; padding:10px 16px; color:#e5e7eb; text-decoration:none; font-size:0.9rem;">Edit</a>
										<form action="{{ route('produk.destroy', $product->id) }}" method="POST" style="display:block;">
											@csrf
											@method('DELETE')
											<button type="submit" style="width:100%; text-align:left; padding:10px 16px; background:none; border:none; color:#fca5a5; cursor:pointer; font-size:0.9rem;" onclick="return confirm('Yakin ingin menghapus produk ini?')">
												Hapus
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="8" style="padding:18px 10px; text-align:center; color:#9ca3af;">Belum ada produk.</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			@else
				<div style="padding: 40px; text-align: center; color: #9ca3af;">
					<p style="margin-bottom: 16px;">Belum ada produk dalam katalog.</p>
					<a href="{{ route('produk.create') }}" class="hz-btn hz-btn-primary">
						+ Tambah Produk Pertama
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

<script>
	function toggleDropdown(id) {
		const dropdown = document.getElementById(id);
		const isVisible = dropdown.style.display === 'block';
		
		// Close all dropdowns
		document.querySelectorAll('.hz-dropdown').forEach(el => {
			el.style.display = 'none';
		});
		
		// Toggle this dropdown
		if (!isVisible) {
			dropdown.style.display = 'block';
		}
	}
	
	// Close dropdown when clicking outside
	document.addEventListener('click', function(e) {
		if (!e.target.closest('.hz-more-btn')) {
			document.querySelectorAll('.hz-dropdown').forEach(el => {
				el.style.display = 'none';
			});
		}
	});
</script>
@endsection
