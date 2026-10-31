@extends('layouts.horizon')

@section('page_title', 'Laporan Produk Terlaris')
@section('page_subtitle', 'Daftar produk paling diminati berdasarkan total unit terjual.')

@section('content')
<div class="hz-top-row" style="display:flex; justify-content:flex-start; align-items:center; margin-bottom: 22px;">
	<div class="hz-top-actions" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
		<form action="{{ route('laporan.produk-terlaris') }}" method="GET" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
			<select name="period" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; outline:none;">
				<option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hari ini</option>
				<option value="week" {{ $period === 'week' ? 'selected' : '' }}>Minggu ini</option>
				<option value="month" {{ $period === 'month' ? 'selected' : '' }}>Bulan ini</option>
				<option value="year" {{ $period === 'year' ? 'selected' : '' }}>Tahun ini</option>
				<option value="all" {{ $period === 'all' ? 'selected' : '' }}>Semua data</option>
				<option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom</option>
			</select>
			<div style="display: {{ $period === 'custom' ? 'flex' : 'none' }}; align-items: center; gap: 6px;">
				<input type="date" name="start_date" value="{{ $startDate }}" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; outline:none;">
				<span style="color:#64748B; font-size:0.85rem;">s/d</span>
				<input type="date" name="end_date" value="{{ $endDate }}" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; outline:none;">
			</div>
			<button type="submit" style="border-radius:10px; border:none; background:#0F223D; color:#FFFFFF; padding:8px 16px; font-size:0.85rem; font-weight:600; cursor:pointer;">Filter</button>
		</form>
	</div>
</div>

<section class="hz-secondary-grid" style="margin-top: 24px;">
	<div class="hz-card" style="grid-column: 1 / -1; padding: 24px;">
		<div class="hz-card-header" style="margin-bottom: 16px;">
			<h3 class="hz-card-title">Peringkat Produk Terlaris</h3>
			<p class="hz-card-sub">Data diperbarui otomatis berdasarkan transaksi penjualan kasir.</p>
		</div>
		<div style="overflow-x: auto;">
			<table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
				<thead>
					<tr style="border-bottom: 1px solid #E2E8F0; background: #F8FAFC; color: #475569; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">
						<th style="text-align: left; padding: 14px 12px; font-weight: 600;">Produk</th>
						<th style="text-align: left; padding: 14px 12px; font-weight: 600;">Tipe</th>
						<th style="text-align: right; padding: 14px 12px; font-weight: 600;">Jumlah Terjual</th>
						<th style="text-align: right; padding: 14px 12px; font-weight: 600;">Total Revenue</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($products as $product)
						<tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease;">
							<td style="padding: 14px 12px; font-weight: 600; color: #0F223D;">{{ $product->nama_produk }}</td>
							<td style="padding: 14px 12px; color: #64748B;">
								<span class="hz-pill-info" style="text-transform: capitalize;">{{ $product->tipe }}</span>
							</td>
							<td style="padding: 14px 12px; text-align: right; font-weight: 600; color: #0F223D;">{{ number_format($product->total_qty, 0, ',', '.') }} unit</td>
							<td style="padding: 14px 12px; text-align: right; font-weight: 600; color: #0F223D;">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="4" style="padding: 40px 12px; text-align: center; color: #64748b;">Belum ada data penjualan pada periode ini.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</section>
@endsection
