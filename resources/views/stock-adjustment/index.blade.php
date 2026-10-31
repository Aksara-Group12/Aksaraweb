@extends('layouts.horizon')

@section('page_title', 'Stock Adjustment')
@section('page_subtitle', 'Penyesuaian stok fisik vs sistem inventaris toko.')

@section('content')
	<div class="hz-top-row" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px; flex-wrap:wrap; gap:12px;">
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; width:100%; justify-content:space-between;">
			<form method="GET" action="{{ route('stock-adjustment.index') }}" style="display:flex; align-items:center; gap:6px;">
				<div style="position: relative; display: flex; align-items: center;">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" style="position: absolute; left: 12px; pointer-events: none;">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
					<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor adjustment..." style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px 8px 34px; font-size:0.85rem; color:#0F223D; min-width:240px; outline:none;">
				</div>
			</form>
			<a href="{{ route('stock-adjustment.create') }}" class="hz-btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; padding: 9px 18px; gap: 8px;">
				<svg width="14" height="14" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M10 4.5V15.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
					<path d="M4.5 10H15.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
				</svg>
				<span>Buat Adjustment</span>
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
							<tr style="border-bottom: 1px solid #E2E8F0; background: #F8FAFC; color: #475569; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">
								<th style="text-align:left; padding:14px 12px; font-weight: 600;">Nomor</th>
								<th style="text-align:left; padding:14px 12px; font-weight: 600;">Tanggal</th>
								<th style="text-align:left; padding:14px 12px; font-weight: 600;">Keterangan</th>
								<th style="text-align:left; padding:14px 12px; font-weight: 600;">Status</th>
								<th style="text-align:right; padding:14px 12px; font-weight: 600;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($adjustments as $item)
								<tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease;">
									<td style="padding:14px 12px; color: #0F223D; font-weight: 600;">{{ $item->nomor_adjustment }}</td>
									<td style="padding:14px 12px; color: #64748B;">{{ $item->tanggal->format('d/m/Y') }}</td>
									<td style="padding:14px 12px; color: #0F223D;">{{ $item->keterangan ?? '-' }}</td>
									<td style="padding:14px 12px;">
										<span class="{{ $item->status === 'completed' ? 'hz-pill-success' : 'hz-pill-warning' }}">
											{{ ucfirst($item->status) }}
										</span>
									</td>
									<td style="padding:14px 12px; text-align:right;">
										<a href="#" style="color: #0F223D; font-weight: 600; text-decoration: none;">Detail →</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					<div style="margin-top: 12px; display:flex; justify-content:center;">
						{{ $adjustments->links() }}
					</div>
				@else
					<div style="padding: 40px; text-align: center; color: #64748B;">
						<p>Belum ada data adjustment.</p>
					</div>
				@endif
			</div>
		</div>
	</section>
@endsection
