@extends('layouts.horizon')

@section('page_title', 'Shift Kasir')
@section('page_subtitle', 'Rekapitulasi shift kasir, jam kerja, dan rekonsiliasi kas.')

@section('content')
	<div class="hz-top-row" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px; flex-wrap:wrap; gap:12px;">
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; width:100%; justify-content:space-between;">
			<form method="GET" action="{{ route('shift.index') }}" style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
				<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor shift..." style="min-width:180px;">
				<select name="admin_id">
					<option value="">Semua Kasir</option>
					@foreach($admins as $admin)
						<option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
					@endforeach
				</select>
				<input type="date" name="start_date" value="{{ request('start_date') }}" title="Tanggal Mulai">
				<input type="date" name="end_date" value="{{ request('end_date') }}" title="Tanggal Akhir">
				<button type="submit">Filter</button>
			</form>
			@if (!session('active_shift_id'))
				<a href="{{ route('shift.create') }}" class="hz-btn hz-btn-primary">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
						<line x1="12" y1="5" x2="12" y2="19"></line>
						<line x1="5" y1="12" x2="19" y2="12"></line>
					</svg>
					<span>Buka Shift</span>
				</a>
			@endif
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
				<p class="hz-card-title">Daftar Shift ({{ $shifts->total() ?? $shifts->count() }})</p>
			</div>
			<div style="margin-top: 8px; overflow-x: auto;">
				@if ($shifts->count() > 0)
					<table>
						<thead>
							<tr>
								<th style="text-align:left;">Nomor Shift</th>
								<th style="text-align:left;">Tanggal</th>
								<th style="text-align:left;">Kasir</th>
								<th style="text-align:right;">Modal Awal</th>
								<th style="text-align:right;">Modal Akhir</th>
								<th style="text-align:right;">Selisih Kas</th>
								<th style="text-align:center;">Status</th>
								<th style="text-align:center;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($shifts as $item)
								<tr>
									<td style="font-weight: 600; color: #0F223D;">{{ $item->shift_number }}</td>
									<td style="color: #475569;">{{ $item->tanggal_shift->format('d/m/Y') }}</td>
									<td style="color: #0F223D; font-weight: 500;">{{ $item->admin->name ?? auth()->user()->name }}</td>
									<td style="text-align:right; color: #0F223D; font-weight: 700;">Rp {{ number_format($item->modal_awal, 0, ',', '.') }}</td>
									<td style="text-align:right; color: #0F223D; font-weight: 700;">{{ $item->modal_akhir ? 'Rp ' . number_format($item->modal_akhir, 0, ',', '.') : '-' }}</td>
									<td style="text-align:right; color: {{ $item->selisih_kas >= 0 ? '#059669' : '#DC2626' }}; font-weight: 700;">{{ $item->selisih_kas !== null ? 'Rp ' . number_format($item->selisih_kas, 0, ',', '.') : '-' }}</td>
									<td style="text-align:center;">
										@if($item->status === 'open')
											<span style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 3px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">Aktif</span>
										@else
											<span style="background: #F1F5F9; border: 1px solid #CBD5E1; color: #64748B; padding: 3px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">Tutup</span>
										@endif
									</td>
									<td style="text-align:center;">
										@if ($item->status === 'open')
											<a href="{{ route('shift.close', $item->id) }}" class="hz-btn-secondary" style="padding: 5px 12px; font-size: 0.8rem; font-weight: 600; text-decoration: none; border-radius: 8px;">Tutup Shift</a>
										@else
											<span style="color: #94A3B8; font-size: 0.85rem;">-</span>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					@if (method_exists($shifts, 'links'))
						<div style="margin-top: 18px; display:flex; justify-content:center;">
							{{ $shifts->links() }}
						</div>
					@endif
				@else
					<div style="padding: 48px; text-align: center; color: #64748b;">
						<p style="margin-bottom:16px;">Belum ada data shift.</p>
					</div>
				@endif
			</div>
		</div>
	</section>
@endsection
