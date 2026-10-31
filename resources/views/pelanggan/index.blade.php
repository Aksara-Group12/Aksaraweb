@extends('layouts.horizon')

@section('page_title', 'Data Pelanggan')
@section('page_subtitle', 'Kelola informasi pelanggan untuk kemudahan transaksi kasir & invoice.')

@section('content')
	<div class="hz-top-row" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px; flex-wrap:wrap; gap:12px;">
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; width:100%; justify-content:space-between;">
			<form method="GET" action="{{ route('pelanggan.index') }}" style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
				<div style="position:relative; display:inline-flex; align-items:center;">
					<svg style="position:absolute; left:12px; width:15px; height:15px; color:#94A3B8; pointer-events:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
					<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / kontak / email..." style="padding-left:36px; min-width:240px;">
				</div>
				<select name="per_page" onchange="this.form.submit()">
					<option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
					<option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
					<option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
				</select>
			</form>
			<a href="{{ route('pelanggan.create') }}" class="hz-btn hz-btn-primary">
				<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
					<line x1="12" y1="5" x2="12" y2="19"></line>
					<line x1="5" y1="12" x2="19" y2="12"></line>
				</svg>
				<span>Tambah Pelanggan</span>
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

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1; padding: 24px;">
			<div class="hz-card-header" style="margin-bottom: 16px;">
				<p class="hz-card-title">Daftar Pelanggan ({{ $pelanggan->total() ?? $pelanggan->count() }})</p>
			</div>
			<div style="margin-top: 8px; overflow-x: auto;">
				@if ($pelanggan->count() > 0)
					<table>
						<thead>
							<tr>
								<th style="text-align:left;">Nama</th>
								<th style="text-align:left;">Kontak</th>
								<th style="text-align:left;">Email</th>
								<th style="text-align:left;">Alamat</th>
								<th style="text-align:right;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($pelanggan as $item)
								<tr>
									<td style="font-weight: 600; color: #0F223D;">{{ $item->nama }}</td>
									<td style="color: #475569;">{{ $item->kontak ?? '-' }}</td>
									<td style="color: #475569;">{{ $item->email ?? '-' }}</td>
									<td style="color: #475569;">{{ $item->alamat ?? '-' }}</td>
									<td style="text-align:right;">
										<a href="{{ route('pelanggan.edit', $item->id) }}" style="color: #0F223D; font-weight:600; text-decoration:none; margin-right: 12px;">Edit</a>
										<form action="{{ route('pelanggan.destroy', $item->id) }}" method="POST" style="display:inline;">
											@csrf
											@method('DELETE')
											<button type="submit" style="color: #DC2626; background:none; border:none; cursor:pointer; font-weight:600;" onclick="return confirm('Yakin ingin menghapus pelanggan ini?')">Hapus</button>
										</form>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					@if (method_exists($pelanggan, 'links'))
						<div style="margin-top: 18px; display:flex; justify-content:center;">
							{{ $pelanggan->links() }}
						</div>
					@endif
				@else
					<div style="padding: 48px; text-align: center; color: #64748b;">
						<p style="margin-bottom:16px;">Belum ada data pelanggan.</p>
						<a href="{{ route('pelanggan.create') }}" class="hz-btn hz-btn-primary">
							+ Tambah Pelanggan
						</a>
					</div>
				@endif
			</div>
		</div>
	</section>
@endsection
