@extends('layouts.horizon')

@section('page_title', 'Riwayat Produk')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Riwayat Produk</h2>
			<p class="hz-top-sub">Riwayat lengkap transaksi masuk dan keluar produk dalam inventory.</p>
		</div>
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
			<div style="display:flex; align-items:center; gap:6px;">
				<form method="GET" action="{{ route('produk.log') }}" style="display:flex; align-items:center; gap:6px;">
					<select name="tipe" onchange="this.form.submit()" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 8px; font-size:0.8rem; color:#1e293b;">
						<option value="" {{ !request('tipe') ? 'selected' : '' }}>Semua Tipe</option>
						<option value="masuk" {{ request('tipe') === 'masuk' ? 'selected' : '' }}>Masuk</option>
						<option value="keluar" {{ request('tipe') === 'keluar' ? 'selected' : '' }}>Keluar</option>
					</select>
					
					<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk / referensi" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 10px; font-size:0.8rem; color:#1e293b; min-width:200px;">
					
					<input type="date" name="start_date" value="{{ request('start_date') }}" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 8px; font-size:0.8rem; color:#1e293b;">
					
					<input type="date" name="end_date" value="{{ request('end_date') }}" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 8px; font-size:0.8rem; color:#1e293b;">
					
					<select name="per_page" onchange="this.form.submit()" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 8px; font-size:0.8rem; color:#1e293b;">
						<option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
						<option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
						<option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
						<option value="100" {{ request('per_page', 20) == 100 ? 'selected' : '' }}>100</option>
					</select>
					
					<button type="submit" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); color:#1e293b; padding:6px 12px; font-size:0.8rem; cursor:pointer;">
						Filter
					</button>
				</form>
			</div>
			
			<div style="display:flex; gap:6px;">
				<a href="{{ route('produk.log.export.excel', request()->only('tipe', 'search', 'start_date', 'end_date')) }}" style="border-radius:999px; border:1px solid rgba(34, 197, 94, 0.3); background:rgba(34, 197, 94, 0.1); color:#86efac; padding:6px 12px; font-size:0.8rem; text-decoration:none; display:flex; align-items:center; gap:4px;">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M12 2V12M12 12L7 7M12 12L17 7M3 12V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					Excel
				</a>
				<a href="{{ route('produk.log.export.pdf', request()->only('tipe', 'search', 'start_date', 'end_date')) }}" style="border-radius:999px; border:1px solid rgba(239, 68, 68, 0.3); background:rgba(239, 68, 68, 0.1); color:#fca5a5; padding:6px 12px; font-size:0.8rem; text-decoration:none; display:flex; align-items:center; gap:4px;">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M12 2V12M12 12L7 7M12 12L17 7M3 12V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
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
					<p class="hz-card-title">Daftar Riwayat Produk ({{ $histories->total() }} transaksi)</p>
					<p class="hz-card-sub">Riwayat transaksi masuk dan keluar produk dari inventory.</p>
				</div>
			</div>
			<div style="margin-top: 8px; overflow-x: auto;">
				@if ($histories->count() > 0)
					<table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
						<thead>
							<tr style="border-bottom: 1px solid rgba(59, 130, 246, 0.4); color:#9ca3af;">
								<th style="text-align:left; padding:14px 10px;">Tanggal</th>
								<th style="text-align:center; padding:14px 10px;">Tipe</th>
								<th style="text-align:left; padding:14px 10px;">No. Referensi</th>
								<th style="text-align:left; padding:14px 10px;">Nama Produk</th>
								<th style="text-align:left; padding:14px 10px;">SKU</th>
								<th style="text-align:left; padding:14px 10px;">Supplier/Customer</th>
								<th style="text-align:center; padding:14px 10px;">Qty</th>
								<th style="text-align:right; padding:14px 10px;">Total Harga</th>
								<th style="text-align:center; padding:14px 10px;">Status</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($histories as $history)
								<tr style="border-bottom: 1px solid rgba(15,23,42,0.8);">
									<td style="padding:14px 10px; color: #1e293b;">
										{{ \Carbon\Carbon::parse($history->tanggal_transaksi)->format('d/m/Y') }}
									</td>
									<td style="padding:14px 10px; text-align:center;">
										@if ($history->tipe === 'masuk')
											<span style="background:rgba(34, 197, 94, 0.2); color:#86efac; padding:4px 8px; border-radius:6px; font-size:0.75rem; font-weight:600;">MASUK</span>
										@else
											<span style="background:rgba(239, 68, 68, 0.2); color:#fca5a5; padding:4px 8px; border-radius:6px; font-size:0.75rem; font-weight:600;">KELUAR</span>
										@endif
									</td>
									<td style="padding:14px 10px; font-weight:500; color:#3b82f6;">
										{{ $history->referensi_no }}
									</td>
									<td style="padding:14px 10px; color:#cbd5e1;">
										{{ $history->nama_produk }}
									</td>
									<td style="padding:14px 10px; color:#9ca3af; font-size:0.85rem;">
										{{ $history->sku ?? '-' }}
									</td>
									<td style="padding:14px 10px; color:#cbd5e1;">
										{{ $history->tipe === 'masuk' ? $history->supplier_name : $history->customer_name }}
									</td>
									<td style="padding:14px 10px; text-align:center; font-weight:600; color:#3b82f6;">
										{{ $history->qty }} unit
									</td>
									<td style="padding:14px 10px; text-align:right; font-weight:600; color:#3b82f6;">
										Rp {{ number_format($history->total_harga, 0, ',', '.') }}
									</td>
									<td style="padding:14px 10px; text-align:center;">
										@if ($history->status === 'complete')
											<span style="background:rgba(34, 197, 94, 0.2); color:#86efac; padding:4px 8px; border-radius:6px; font-size:0.75rem;">Selesai</span>
										@elseif ($history->status === 'pending')
											<span style="background:rgba(251,191,36,0.2); color:#3b82f6; padding:4px 8px; border-radius:6px; font-size:0.75rem;">Pending</span>
										@else
											<span style="background:rgba(239, 68, 68, 0.2); color:#fca5a5; padding:4px 8px; border-radius:6px; font-size:0.75rem;">Dibatalkan</span>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>

					<!-- Pagination -->
					<div style="margin-top: 24px; display:flex; justify-content:center; gap:8px; flex-wrap:wrap;">
						@if ($histories->onFirstPage())
							<button style="padding:8px 12px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); color:#64748b; border-radius:6px; cursor:not-allowed; font-size:0.85rem;">← Sebelumnya</button>
						@else
							<a href="{{ $histories->previousPageUrl() }}" style="padding:8px 12px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); color:#1e293b; border-radius:6px; text-decoration:none; font-size:0.85rem;">← Sebelumnya</a>
						@endif

						@foreach ($histories->getUrlRange(max(1, $histories->currentPage() - 2), min($histories->lastPage(), $histories->currentPage() + 2)) as $page => $url)
							@if ($page == $histories->currentPage())
								<button style="padding:8px 12px; border:1px solid rgba(251,191,36,0.5); background:rgba(251,191,36,0.2); color:#3b82f6; border-radius:6px; font-weight:600; font-size:0.85rem;">{{ $page }}</button>
							@else
								<a href="{{ $url }}" style="padding:8px 12px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); color:#1e293b; border-radius:6px; text-decoration:none; font-size:0.85rem;">{{ $page }}</a>
							@endif
						@endforeach

						@if ($histories->hasMorePages())
							<a href="{{ $histories->nextPageUrl() }}" style="padding:8px 12px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); color:#1e293b; border-radius:6px; text-decoration:none; font-size:0.85rem;">Selanjutnya →</a>
						@else
							<button style="padding:8px 12px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); color:#64748b; border-radius:6px; cursor:not-allowed; font-size:0.85rem;">Selanjutnya →</button>
						@endif
					</div>
				@else
					<div style="padding: 40px; text-align: center; color: #64748b;">
						<p style="margin-bottom: 12px;">Belum ada riwayat transaksi produk.</p>
					</div>
				@endif
			</div>
		</div>
	</section>
@endsection
