@extends('layouts.horizon')

@section('page_title', 'Riwayat Transaksi')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Riwayat Transaksi</h2>
			<p class="hz-top-sub">Riwayat lengkap transaksi masuk, keluar, dan pengeluaran lainnya.</p>
		</div>
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
			<div style="display:flex; align-items:center; gap:8px;">
				<form method="GET" action="{{ route('transaksi.log') }}" style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
					<select name="tipe" onchange="this.form.submit()" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; outline:none;">
						<option value="" {{ !request('tipe') ? 'selected' : '' }}>Semua Tipe</option>
						<option value="masuk" {{ request('tipe') === 'masuk' ? 'selected' : '' }}>Transaksi Masuk</option>
						<option value="keluar" {{ request('tipe') === 'keluar' ? 'selected' : '' }}>Transaksi Keluar</option>
						<option value="expenses" {{ request('tipe') === 'expenses' ? 'selected' : '' }}>Pengeluaran</option>
					</select>
					
					<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari referensi / supplier / customer" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; min-width:240px; outline:none;">
					
					<input type="date" name="start_date" value="{{ request('start_date') }}" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; outline:none;">
					
					<input type="date" name="end_date" value="{{ request('end_date') }}" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; outline:none;">
					
					<select name="per_page" onchange="this.form.submit()" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; outline:none;">
						<option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
						<option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
						<option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
						<option value="100" {{ request('per_page', 20) == 100 ? 'selected' : '' }}>100</option>
					</select>
					
					<button type="submit" style="border-radius:10px; border:none; background:#0F223D; color:#FFFFFF; padding:8px 16px; font-size:0.85rem; font-weight:600; cursor:pointer; box-shadow:0 2px 6px rgba(15,34,61,0.15);">
						Filter
					</button>
				</form>
			</div>
			
			<div style="display:flex; gap:8px;">
				<a href="{{ route('transaksi.log.export.excel', request()->only('tipe', 'search', 'start_date', 'end_date')) }}" style="border-radius:10px; border:1px solid #A7F3D0; background:#ECFDF5; color:#065F46; padding:8px 14px; font-size:0.85rem; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:6px;">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M12 2V12M12 12L7 7M12 12L17 7M3 12V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					Excel
				</a>
				<a href="{{ route('transaksi.log.export.pdf', request()->only('tipe', 'search', 'start_date', 'end_date')) }}" style="border-radius:10px; border:1px solid #FECACA; background:#FEF2F2; color:#991B1B; padding:8px 14px; font-size:0.85rem; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:6px;">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M12 2V12M12 12L7 7M12 12L17 7M3 12V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					PDF
				</a>
			</div>
		</div>
	</div>

	@if ($message = Session::get('success'))
		<div style="margin-top: 20px; padding: 14px 16px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 10px; color: #16a34a;">
			{{ $message }}
		</div>
	@endif

	@if ($message = Session::get('error'))
		<div style="margin-top: 20px; padding: 14px 16px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 10px; color: #dc2626;">
			{{ $message }}
		</div>
	@endif

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 12px;">
				<div>
					<p class="hz-card-title">Daftar Riwayat Transaksi ({{ $histories->total() }} transaksi)</p>
					<p class="hz-card-sub">Riwayat lengkap semua transaksi masuk, keluar, dan pengeluaran lainnya.</p>
				</div>
			</div>
			<div style="margin-top: 8px; overflow-x: auto;">
				@if ($histories->count() > 0)
					<table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
						<thead>
							<tr style="border-bottom: 1px solid #E2E8F0; color:#64748B; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em;">
								<th style="text-align:left; padding:14px 10px;">Tanggal</th>
								<th style="text-align:center; padding:14px 10px;">Tipe</th>
								<th style="text-align:left; padding:14px 10px;">No. Referensi</th>
								<th style="text-align:left; padding:14px 10px;">Keterangan</th>
								<th style="text-align:left; padding:14px 10px;">Lokasi/Kategori</th>
								<th style="text-align:right; padding:14px 10px;">Nominal</th>
								<th style="text-align:center; padding:14px 10px;">Status</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($histories as $history)
								<tr style="border-bottom: 1px solid #F1F5F9;">
									<td style="padding:14px 10px; color: #0F223D;">
										{{ \Carbon\Carbon::parse($history->tanggal)->format('d/m/Y') }}
									</td>
									<td style="padding:14px 10px; text-align:center;">
										@if ($history->tipe === 'masuk')
											<span style="background:#ECFDF5; color:#065F46; border:1px solid #A7F3D0; padding:4px 8px; border-radius:6px; font-size:0.75rem; font-weight:600;">MASUK</span>
										@elseif ($history->tipe === 'keluar')
											<span style="background:#FEF2F2; color:#991B1B; border:1px solid #FECACA; padding:4px 8px; border-radius:6px; font-size:0.75rem; font-weight:600;">KELUAR</span>
										@else
											<span style="background:#EEF2FF; color:#3730A3; border:1px solid #C7D2FE; padding:4px 8px; border-radius:6px; font-size:0.75rem; font-weight:600;">PENGELUARAN</span>
										@endif
									</td>
									<td style="padding:14px 10px; font-weight:600; color:#0F223D;">
										{{ $history->referensi_no }}
									</td>
									<td style="padding:14px 10px; color:#475569;">
										@if ($history->tipe === 'masuk')
											{{ $history->supplier_name ?? '-' }}
										@elseif ($history->tipe === 'keluar')
											{{ $history->customer_name ?? '-' }}
										@else
											{{ $history->keterangan ?? '-' }}
										@endif
									</td>
									<td style="padding:14px 10px; color:#64748B; font-size:0.9rem;">
										@if ($history->tipe === 'expenses')
											<span style="background:#F1F5F9; color:#475569; padding:2px 8px; border-radius:6px;">{{ $history->kategori ?? '-' }}</span>
										@else
											{{ $history->lokasi ?? '-' }}
										@endif
									</td>
									<td style="padding:14px 10px; text-align:right; font-weight:600; color:#0F223D;">
										Rp {{ number_format($history->nominal, 0, ',', '.') }}
									</td>
									<td style="padding:14px 10px; text-align:center;">
										@if ($history->status === 'complete')
											<span style="background:#ECFDF5; color:#065F46; border:1px solid #A7F3D0; padding:4px 8px; border-radius:6px; font-size:0.75rem; font-weight:600;">Selesai</span>
										@elseif ($history->status === 'pending')
											<span style="background:#FFFBEB; color:#92400E; border:1px solid #FDE68A; padding:4px 8px; border-radius:6px; font-size:0.75rem; font-weight:600;">Pending</span>
										@else
											<span style="background:#FEF2F2; color:#991B1B; border:1px solid #FECACA; padding:4px 8px; border-radius:6px; font-size:0.75rem; font-weight:600;">Dibatalkan</span>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>

					<!-- Pagination -->
					<div style="margin-top: 24px; display:flex; justify-content:center; gap:8px; flex-wrap:wrap;">
						@if ($histories->onFirstPage())
							<button style="padding:8px 14px; border:1px solid #E2E8F0; background:#F8FAFC; color:#94A3B8; border-radius:8px; cursor:not-allowed; font-size:0.85rem;">← Sebelumnya</button>
						@else
							<a href="{{ $histories->previousPageUrl() }}" style="padding:8px 14px; border:1px solid #CBD5E1; background:#FFFFFF; color:#0F223D; border-radius:8px; text-decoration:none; font-size:0.85rem; font-weight:500;">← Sebelumnya</a>
						@endif

						@foreach ($histories->getUrlRange(max(1, $histories->currentPage() - 2), min($histories->lastPage(), $histories->currentPage() + 2)) as $page => $url)
							@if ($page == $histories->currentPage())
								<button style="padding:8px 14px; border:1px solid #0F223D; background:#0F223D; color:#FFFFFF; border-radius:8px; font-weight:600; font-size:0.85rem;">{{ $page }}</button>
							@else
								<a href="{{ $url }}" style="padding:8px 14px; border:1px solid #CBD5E1; background:#FFFFFF; color:#0F223D; border-radius:8px; text-decoration:none; font-size:0.85rem; font-weight:500;">{{ $page }}</a>
							@endif
						@endforeach

						@if ($histories->hasMorePages())
							<a href="{{ $histories->nextPageUrl() }}" style="padding:8px 14px; border:1px solid #CBD5E1; background:#FFFFFF; color:#0F223D; border-radius:8px; text-decoration:none; font-size:0.85rem; font-weight:500;">Selanjutnya →</a>
						@else
							<button style="padding:8px 14px; border:1px solid #E2E8F0; background:#F8FAFC; color:#94A3B8; border-radius:8px; cursor:not-allowed; font-size:0.85rem;">Selanjutnya →</button>
						@endif
					</div>
				@else
					<div style="padding: 40px; text-align: center; color: #64748b;">
						<p style="margin-bottom: 12px;">Belum ada riwayat transaksi.</p>
					</div>
				@endif
			</div>
		</div>
	</section>
@endsection
