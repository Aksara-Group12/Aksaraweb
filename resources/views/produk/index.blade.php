@extends('layouts.horizon')

@section('page_title', 'Manajemen Produk')
@section('page_subtitle', 'Daftar dan pengelolaan katalog produk pada sistem.')

@section('content')
<div class="hz-top-row" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px; flex-wrap:wrap; gap:12px;">
	<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; width:100%; justify-content:space-between;">
		<form method="GET" action="{{ route('produk.index') }}" style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
			<div style="position:relative; display:inline-flex; align-items:center;">
				<svg style="position:absolute; left:12px; width:15px; height:15px; color:#94A3B8; pointer-events:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="11" cy="11" r="8"></circle>
					<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
				</svg>
				<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode / nama / brand..." style="padding-left:36px; min-width:240px;">
			</div>
			<select name="per_page" onchange="this.form.submit()">
				<option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
				<option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
				<option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
			</select>
		</form>
		<a href="{{ route('produk.create') }}" class="hz-btn hz-btn-primary">
			<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
				<line x1="12" y1="5" x2="12" y2="19"></line>
				<line x1="5" y1="12" x2="19" y2="12"></line>
			</svg>
			<span>Tambah Produk</span>
		</a>
	</div>
</div>

@if ($message = Session::get('success'))
	<div style="margin-top: 20px; padding: 14px 18px; background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 12px; color: #065F46; font-size: 0.875rem; font-weight: 500; display:flex; align-items:center; gap:8px;">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
			<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
			<polyline points="22 4 12 14.01 9 11.01"></polyline>
		</svg>
		<span>{{ $message }}</span>
	</div>
@endif

@if ($message = Session::get('error'))
	<div style="margin-top: 20px; padding: 14px 18px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 12px; color: #991B1B; font-size: 0.875rem; display:flex; align-items:center; gap:8px;">
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
			<circle cx="12" cy="12" r="10"></circle>
			<line x1="12" y1="8" x2="12" y2="12"></line>
			<line x1="12" y1="16" x2="12.01" y2="16"></line>
		</svg>
		<span>{{ $message }}</span>
	</div>
@endif

<section class="hz-secondary-grid" style="margin-top: 24px;">
	<div class="hz-card" style="grid-column: 1 / -1; padding: 24px;">
		<div class="hz-card-header" style="margin-bottom: 16px;">
			<div>
				<p class="hz-card-title">Daftar Produk ({{ $products->total() ?? $products->count() }} produk)</p>
				<p class="hz-card-sub">Katalog produk untuk POS dan Inventory.</p>
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
							<th style="text-align:right;">Harga Jual</th>
							<th style="text-align:center;">Stok</th>
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
									Rp {{ number_format($product->harga_jual, 0, ',', '.') }}
								</td>
								<td style="text-align:center;">
									@if($product->stock > 0)
										<span style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 3px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">
											{{ $product->stock }}
										</span>
									@else
										<span style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; padding: 3px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">
											0
										</span>
									@endif
								</td>
								<td style="text-align:center;">
									@if($product->is_active)
										<span style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 3px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">Aktif</span>
									@else
										<span style="background: #F1F5F9; border: 1px solid #CBD5E1; color: #64748B; padding: 3px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">Non-aktif</span>
									@endif
								</td>
								<td style="text-align:right; position: relative;">
									<button type="button" class="hz-more-btn" style="background: transparent; border:1px solid #CBD5E1; color:#64748B; cursor:pointer; padding:5px 9px; border-radius:8px; font-weight:700;" onclick="toggleDropdown('dropdown-{{ $product->id }}')">
										⋮
									</button>
									<div id="dropdown-{{ $product->id }}" class="hz-dropdown" style="display:none; position:absolute; right:0; top:100%; min-width:140px; z-index:20;">
										<a href="{{ route('produk.edit', $product->id) }}" style="display:flex; align-items:center; gap:8px; text-decoration:none;">
											<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
												<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
											</svg>
											<span>Edit</span>
										</a>
										<form action="{{ route('produk.destroy', $product->id) }}" method="POST" style="margin:0;">
											@csrf
											@method('DELETE')
											<button type="submit" style="width:100%; text-align:left; background:none; border:none; color:#DC2626 !important; cursor:pointer; display:flex; align-items:center; gap:8px;" onclick="return confirm('Yakin ingin menghapus produk ini?')">
												<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
													<polyline points="3 6 5 6 21 6"></polyline>
													<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
												</svg>
												<span>Hapus</span>
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="8" style="padding:32px 10px; text-align:center; color:#94A3B8;">Belum ada produk.</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			@else
				<div style="padding: 48px; text-align: center; color: #64748b;">
					<p style="margin-bottom: 16px; font-size: 0.95rem;">Belum ada produk dalam katalog toko Anda.</p>
					<a href="{{ route('produk.create') }}" class="hz-btn hz-btn-primary">
						+ Tambah Produk Pertama
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
