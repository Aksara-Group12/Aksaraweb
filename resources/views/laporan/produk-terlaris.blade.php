@extends('layouts.horizon')

@section('page_title', 'Laporan Produk Terlaris')

@section('content')
<div class="hz-top-row">
	<div>
		<h2 class="hz-top-title">Laporan Produk Terlaris</h2>
		<p class="hz-top-sub">Daftar produk paling laku berdasarkan jumlah terjual.</p>
	</div>
	<div style="display: flex; gap: 10px; align-items: center;">
		<form action="{{ route('laporan.produk-terlaris') }}" method="GET" style="display: flex; align-items: center; gap: 8px;">
			<select name="period" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 10px; font-size:0.8rem;">
				<option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hari ini</option>
				<option value="week" {{ $period === 'week' ? 'selected' : '' }}>Minggu ini</option>
				<option value="month" {{ $period === 'month' ? 'selected' : '' }}>Bulan ini</option>
				<option value="year" {{ $period === 'year' ? 'selected' : '' }}>Tahun ini</option>
				<option value="all" {{ $period === 'all' ? 'selected' : '' }}>Semua data</option>
				<option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom</option>
			</select>
			<div style="display: {{ $period === 'custom' ? 'flex' : 'none' }}; align-items: center; gap: 6px;">
				<input type="date" name="start_date" value="{{ $startDate }}" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 10px; font-size:0.8rem;">
				<span>s/d</span>
				<input type="date" name="end_date" value="{{ $endDate }}" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 10px; font-size:0.8rem;">
			</div>
			<button type="submit" style="border-radius:999px; border:none; background:#3b82f6; color:#fff; padding:6px 12px; font-size:0.8rem; cursor:pointer;">Filter</button>
		</form>
	</div>
</div>

<section class="hz-secondary-grid" style="margin-top: 24px;">
	<div class="hz-card" style="grid-column: 1 / -1; padding: 20px;">
		<h3 class="hz-card-title" style="margin-bottom: 16px;">Daftar Produk Terlaris</h3>
		<table style="width: 100%; border-collapse: collapse;">
			<thead>
				<tr style="border-bottom: 1px solid rgba(59,130,246,0.4);">
					<th style="text-align: left; padding: 12px;">Produk</th>
					<th style="text-align: left; padding: 12px;">Tipe</th>
					<th style="text-align: right; padding: 12px;">Jumlah Terjual</th>
					<th style="text-align: right; padding: 12px;">Total Revenue</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($products as $product)
					<tr style="border-bottom: 1px solid rgba(59,130,246,0.2);">
						<td style="padding: 12px;">{{ $product->nama_produk }}</td>
						<td style="padding: 12px;">{{ $product->tipe }}</td>
						<td style="padding: 12px; text-align: right;">{{ $product->total_qty }}</td>
						<td style="padding: 12px; text-align: right;">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
					</tr>
				@empty
					<tr>
						<td colspan="4" style="padding: 20px; text-align: center; color: #64748b;">Belum ada data penjualan</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</section>
@endsection
