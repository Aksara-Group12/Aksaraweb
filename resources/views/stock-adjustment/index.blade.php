@extends('layouts.horizon')

@section('page_title', 'Stock Adjustment')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Stock Adjustment</h2>
			<p class="hz-top-sub">Penyesuaian stok fisik vs sistem.</p>
		</div>
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px;">
			<form method="GET" action="{{ route('stock-adjustment.index') }}" style="display:flex; align-items:center; gap:6px;">
				<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor adjustment..." style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 10px; font-size:0.8rem; color:#1e293b; min-width:200px;">
			</form>
			<a href="{{ route('stock-adjustment.create') }}" class="hz-btn hz-btn-primary">
				+ Buat Adjustment
			</a>
		</div>
	</div>

	@if ($message = Session::get('success'))
		<div style="margin-top: 20px; padding: 14px 16px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 10px; color: #16a34a;">
			{{ $message }}
		</div>
	@endif

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 12px;">
				<p class="hz-card-title">Daftar Adjustment ({{ $adjustments->count() }})</p>
			</div>
			<div style="margin-top: 8px; overflow-x: auto;">
				@if ($adjustments->count() > 0)
					<table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
						<thead>
							<tr style="border-bottom: 1px solid rgba(59, 130, 246, 0.4); color:#9ca3af;">
								<th style="text-align:left; padding:14px 10px;">Nomor</th>
								<th style="text-align:left; padding:14px 10px;">Tanggal</th>
								<th style="text-align:left; padding:14px 10px;">Keterangan</th>
								<th style="text-align:left; padding:14px 10px;">Status</th>
								<th style="text-align:right; padding:14px 10px;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($adjustments as $item)
								<tr style="border-bottom: 1px solid rgba(15,23,42,0.8);">
									<td style="padding:14px 10px; color: #1e293b; font-weight: 500;">{{ $item->nomor_adjustment }}</td>
									<td style="padding:14px 10px; color: #cbd5e1;">{{ $item->tanggal->format('d/m/Y') }}</td>
									<td style="padding:14px 10px; color: #cbd5e1;">{{ $item->keterangan ?? '-' }}</td>
									<td style="padding:14px 10px;">
										<span style="padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; background: {{ $item->status === 'completed' ? 'rgba(34,197,94,0.2)' : 'rgba(59,130,246,0.2)' }}; color: {{ $item->status === 'completed' ? '#86efac' : '#3b82f6' }};">
											{{ ucfirst($item->status) }}
										</span>
									</td>
									<td style="padding:14px 10px; text-align:right;">
										<a href="#" style="color: #3b82f6;">Detail</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					<div style="margin-top: 12px; display:flex; justify-content:center;">
						{{ $adjustments->links() }}
					</div>
				@else
					<div style="padding: 40px; text-align: center; color: #64748b;">
						<p>Belum ada data adjustment.</p>
					</div>
				@endif
			</div>
		</div>
	</section>
@endsection
