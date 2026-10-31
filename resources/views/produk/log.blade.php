@extends('layouts.horizon')

@section('page_title', 'Riwayat Produk')
@section('page_subtitle', 'Riwayat lengkap transaksi masuk dan keluar produk dalam inventory.')

@section('content')
	<div class="hz-top-row" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px; flex-wrap:wrap; gap:12px;">
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; width:100%; justify-content:space-between;">
			<form method="GET" action="{{ route('produk.log') }}" style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
				<select name="tipe" onchange="this.form.submit()" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; outline:none;">
					<option value="" {{ !request('tipe') ? 'selected' : '' }}>Semua Tipe</option>
					<option value="masuk" {{ request('tipe') === 'masuk' ? 'selected' : '' }}>Masuk</option>
					<option value="keluar" {{ request('tipe') === 'keluar' ? 'selected' : '' }}>Keluar</option>
				</select>
				
				<div style="position: relative; display: flex; align-items: center;">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" style="position: absolute; left: 12px; pointer-events: none;">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
					<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk / referensi" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px 8px 34px; font-size:0.85rem; color:#0F223D; min-width:200px; outline:none;">
				</div>
				
				<input type="date" name="start_date" value="{{ request('start_date') }}" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; outline:none;">
				<input type="date" name="end_date" value="{{ request('end_date') }}" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; outline:none;">
				
				<select name="per_page" onchange="this.form.submit()" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; outline:none;">
					<option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
					<option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
					<option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
					<option value="100" {{ request('per_page', 20) == 100 ? 'selected' : '' }}>100</option>
				</select>
				
				<button type="submit" style="border-radius:10px; border:none; background:#0F223D; color:#FFFFFF; padding:8px 16px; font-size:0.85rem; font-weight:600; cursor:pointer;">
					Filter
				</button>
			</form>
			
			<div style="display:flex; gap:8px;">
				<a href="{{ route('produk.log.export.excel', request()->only('tipe', 'search', 'start_date', 'end_date')) }}" class="hz-btn" style="border-radius:10px; border:1px solid #E2E8F0; background:#FFFFFF; color:#0F223D; padding:8px 14px; font-size:0.85rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
					📊 Excel
				</a>
				<a href="{{ route('produk.log.export.pdf', request()->only('tipe', 'search', 'start_date', 'end_date')) }}" class="hz-btn" style="border-radius:10px; border:1px solid #E2E8F0; background:#FFFFFF; color:#0F223D; padding:8px 14px; font-size:0.85rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
					📄 PDF
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
							<tr style="border-bottom: 1px solid #E2E8F0; background: #F8FAFC; color: #475569; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">
								<th style="text-align:left; padding:14px 12px; font-weight:600;">Tanggal</th>
								<th style="text-align:center; padding:14px 12px; font-weight:600;">Tipe</th>
								<th style="text-align:left; padding:14px 12px; font-weight:600;">No. Referensi</th>
								<th style="text-align:left; padding:14px 12px; font-weight:600;">Nama Produk</th>
								<th style="text-align:left; padding:14px 12px; font-weight:600;">SKU</th>
								<th style="text-align:left; padding:14px 12px; font-weight:600;">Supplier/Customer</th>
								<th style="text-align:center; padding:14px 12px; font-weight:600;">Qty</th>
								<th style="text-align:right; padding:14px 12px; font-weight:600;">Total Harga</th>
								<th style="text-align:center; padding:14px 12px; font-weight:600;">Status</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($histories as $history)
								<tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease;">
									<td style="padding:14px 12px; color: #64748B;">
										{{ \Carbon\Carbon::parse($history->tanggal_transaksi)->format('d/m/Y') }}
									</td>
									<td style="padding:14px 12px; text-align:center;">
										@if ($history->tipe === 'masuk')
											<span class="hz-pill-success" style="font-size:0.75rem; font-weight:700;">MASUK</span>
										@else
											<span class="hz-pill-danger" style="font-size:0.75rem; font-weight:700;">KELUAR</span>
										@endif
									</td>
									<td style="padding:14px 12px; font-weight:600; color:#0F223D;">
										{{ $history->referensi_no }}
									</td>
									<td style="padding:14px 12px; color:#0F223D; font-weight:500;">
										{{ $history->nama_produk }}
									</td>
									<td style="padding:14px 12px; color:#64748B; font-size:0.85rem;">
										{{ $history->sku ?? '-' }}
									</td>
									<td style="padding:14px 12px; color:#0F223D;">
										{{ $history->tipe === 'masuk' ? $history->supplier_name : $history->customer_name }}
									</td>
									<td style="padding:14px 12px; text-align:center; font-weight:600; color:#0F223D;">
										{{ $history->qty }} unit
									</td>
									<td style="padding:14px 12px; text-align:right; font-weight:600; color:#0F223D;">
										Rp {{ number_format($history->total_harga, 0, ',', '.') }}
									</td>
									<td style="padding:14px 12px; text-align:center;">
										@if ($history->status === 'complete')
											<span class="hz-pill-success">Selesai</span>
										@elseif ($history->status === 'pending')
											<span class="hz-pill-warning">Pending</span>
										@else
											<span class="hz-pill-danger">Dibatalkan</span>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>

					<!-- Pagination -->
					<div style="margin-top: 20px; display:flex; justify-content:center;">
						{{ $histories->appends(request()->all())->links() }}
					</div>
				@else
					<div style="padding: 40px; text-align: center; color: #64748b;">
						<p>Belum ada riwayat produk.</p>
					</div>
				@endif
			</div>
		</div>
	</section>
@endsection
