@extends('layouts.horizon')

@section('page_title', 'Data Pelanggan')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Data Pelanggan</h2>
			<p class="hz-top-sub">Kelola data pelanggan untuk transaksi penjualan.</p>
		</div>
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px;">
			<form method="GET" action="{{ route('pelanggan.index') }}" style="display:flex; align-items:center; gap:6px;">
				<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / kontak / email" style="border-radius:999px; border:1px solid rgba(148,163,184,0.5); background:#020617; padding:6px 10px; font-size:0.8rem; color:#e5e7eb; min-width:200px;">
				<select name="per_page" onchange="this.form.submit()" style="border-radius:999px; border:1px solid rgba(148,163,184,0.5); background:#020617; padding:6px 8px; font-size:0.8rem; color:#e5e7eb;">
					<option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
					<option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
					<option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
				</select>
			</form>
			<a href="{{ route('pelanggan.create') }}" class="hz-btn hz-btn-primary">
				+ Tambah Pelanggan
			</a>
		</div>
	</div>

	@if ($message = Session::get('success'))
		<div style="margin-top: 20px; padding: 14px 16px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 10px; color: #86efac;">
			{{ $message }}
		</div>
	@endif

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 12px;">
				<p class="hz-card-title">Daftar Pelanggan ({{ $pelanggan->count() }})</p>
			</div>
			<div style="margin-top: 8px; overflow-x: auto;">
				@if ($pelanggan->count() > 0)
					<table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
						<thead>
							<tr style="border-bottom: 1px solid rgba(251, 191, 36, 0.4); color:#9ca3af;">
								<th style="text-align:left; padding:14px 10px;">Nama</th>
								<th style="text-align:left; padding:14px 10px;">Kontak</th>
								<th style="text-align:left; padding:14px 10px;">Email</th>
								<th style="text-align:left; padding:14px 10px;">Alamat</th>
								<th style="text-align:right; padding:14px 10px;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($pelanggan as $item)
								<tr style="border-bottom: 1px solid rgba(15,23,42,0.8);">
									<td style="padding:14px 10px; color: #e5e7eb; font-weight: 500;">{{ $item->nama }}</td>
									<td style="padding:14px 10px; color: #cbd5e1;">{{ $item->kontak ?? '-' }}</td>
									<td style="padding:14px 10px; color: #cbd5e1;">{{ $item->email ?? '-' }}</td>
									<td style="padding:14px 10px; color: #cbd5e1;">{{ $item->alamat ?? '-' }}</td>
									<td style="padding:14px 10px; text-align:right;">
										<a href="{{ route('pelanggan.edit', $item->id) }}" style="color: #fbbf24; margin-right: 8px;">Edit</a>
										<form action="{{ route('pelanggan.destroy', $item->id) }}" method="POST" style="display:inline;">
											@csrf
											@method('DELETE')
											<button type="submit" style="color: #fca5a5; background:none; border:none; cursor:pointer;" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
										</form>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					<div style="margin-top: 12px; display:flex; justify-content:center;">
						{{ $pelanggan->links() }}
					</div>
				@else
					<div style="padding: 40px; text-align: center; color: #9ca3af;">
						<p>Belum ada data pelanggan.</p>
					</div>
				@endif
			</div>
		</div>
	</section>
@endsection
