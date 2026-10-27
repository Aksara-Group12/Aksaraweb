@extends('layouts.horizon')

@section('page_title', 'Shift Kasir')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Shift Kasir</h2>
			<p class="hz-top-sub">Manajemen shift kasir dan modal kas.</p>
		</div>
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px;">
			<form method="GET" action="{{ route('shift.index') }}" style="display:flex; align-items:center; gap:6px;">
				<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor shift..." style="border-radius:999px; border:1px solid rgba(148,163,184,0.5); background:#020617; padding:6px 10px; font-size:0.8rem; color:#e5e7eb; min-width:200px;">
			</form>
			@if (!session('active_shift_id'))
				<a href="{{ route('shift.create') }}" class="hz-btn hz-btn-primary">
					+ Buka Shift
				</a>
			@endif
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
				<p class="hz-card-title">Daftar Shift ({{ $shifts->count() }})</p>
			</div>
			<div style="margin-top: 8px; overflow-x: auto;">
				@if ($shifts->count() > 0)
					<table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
						<thead>
							<tr style="border-bottom: 1px solid rgba(251, 191, 36, 0.4); color:#9ca3af;">
								<th style="text-align:left; padding:14px 10px;">Nomor Shift</th>
								<th style="text-align:left; padding:14px 10px;">Tanggal</th>
								<th style="text-align:left; padding:14px 10px;">Kasir</th>
								<th style="text-align:right; padding:14px 10px;">Modal Awal</th>
								<th style="text-align:right; padding:14px 10px;">Modal Akhir</th>
								<th style="text-align:right; padding:14px 10px;">Selisih Kas</th>
								<th style="text-align:left; padding:14px 10px;">Status</th>
								<th style="text-align:center; padding:14px 10px;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($shifts as $item)
								<tr style="border-bottom: 1px solid rgba(15,23,42,0.8);">
									<td style="padding:14px 10px; color: #e5e7eb; font-weight: 500;">{{ $item->shift_number }}</td>
									<td style="padding:14px 10px; color: #cbd5e1;">{{ $item->tanggal_shift->format('d/m/Y') }}</td>
									<td style="padding:14px 10px; color: #cbd5e1;">{{ $item->admin->name ?? auth()->user()->name }}</td>
									<td style="padding:14px 10px; text-align:right; color: #fbbf24; font-weight: 600;">Rp {{ number_format($item->modal_awal, 0, ',', '.') }}</td>
									<td style="padding:14px 10px; text-align:right; color: {{ $item->modal_akhir ? '#86efac' : '#9ca3af' }}; font-weight: 600;">{{ $item->modal_akhir ? 'Rp ' . number_format($item->modal_akhir, 0, ',', '.') : '-' }}</td>
									<td style="padding:14px 10px; text-align:right; color: {{ $item->selisih_kas >= 0 ? '#86efac' : '#fca5a5' }}; font-weight: 600;">{{ $item->selisih_kas !== null ? 'Rp ' . number_format($item->selisih_kas, 0, ',', '.') : '-' }}</td>
									<td style="padding:14px 10px;">
										<span style="padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; background: {{ $item->status === 'closed' ? 'rgba(34,197,94,0.2)' : 'rgba(251,191,36,0.2)' }}; color: {{ $item->status === 'closed' ? '#86efac' : '#fbbf24' }};">
											{{ $item->status === 'open' ? 'Aktif' : 'Tutup' }}
										</span>
									</td>
									<td style="padding:14px 10px; text-align:center;">
										@if ($item->status === 'open')
											<a href="{{ route('shift.close', $item->id) }}" class="hz-btn hz-btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;">Tutup</a>
										@else
											<span style="color: #9ca3af; font-size: 0.85rem;">-</span>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					<div style="margin-top: 12px; display:flex; justify-content:center;">
						{{ $shifts->links() }}
					</div>
				@else
					<div style="padding: 40px; text-align: center; color: #9ca3af;">
						<p>Belum ada data shift.</p>
					</div>
				@endif
			</div>
		</div>
	</section>
@endsection
