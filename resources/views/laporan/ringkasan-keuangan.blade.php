@extends('layouts.horizon')

@section('page_title', 'Ringkasan Keuangan')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Ringkasan Keuangan</h2>
			<p class="hz-top-sub">Ikhtisar pendapatan, HPP, laba, dan arus kas.</p>
		</div>
		<div style="display: flex; gap: 10px; align-items: center;">
			<!-- Filter Periode -->
			<form action="{{ route('laporan.keuangan') }}" method="GET" style="display: flex; align-items: center; gap: 8px;">
				<select name="period" id="rk-period-select" style="border-radius:999px; background:rgba(15,23,42,0.9); border:1px solid rgba(51,65,85,0.9); color:#e5e7eb; font-size:0.75rem; padding:6px 10px; outline:none;">
					<option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hari ini</option>
					<option value="week" {{ $period === 'week' ? 'selected' : '' }}>Minggu ini</option>
					<option value="month" {{ $period === 'month' ? 'selected' : '' }}>Bulan ini</option>
					<option value="year" {{ $period === 'year' ? 'selected' : '' }}>Tahun ini</option>
					<option value="all" {{ $period === 'all' ? 'selected' : '' }}>Semua data</option>
					<option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom</option>
				</select>

				<div id="rk-custom-range" style="display: {{ $period === 'custom' ? 'flex' : 'none' }}; align-items: center; gap: 6px;">
					<input type="date" name="start_date" value="{{ $startDate }}" style="background:rgba(15,23,42,0.9); border-radius:999px; border:1px solid rgba(51,65,85,0.9); color:#e5e7eb; font-size:0.75rem; padding:6px 10px;">
					<span style="font-size:0.75rem; color:#9ca3af;">s/d</span>
					<input type="date" name="end_date" value="{{ $endDate }}" style="background:rgba(15,23,42,0.9); border-radius:999px; border:1px solid rgba(51,65,85,0.9); color:#e5e7eb; font-size:0.75rem; padding:6px 10px;">
				</div>

				<button type="submit" style="border-radius:999px; border:none; background:linear-gradient(135deg,#f59e0b,#d97706); color:#111827; padding:6px 12px; font-size:0.75rem; font-weight:600; cursor:pointer;">Terapkan</button>
			</form>

			<!-- Export Tracking Produk Button -->
			<form action="{{ route('laporan.tracking-produk.export') }}" method="GET" style="display: inline;">
				<input type="hidden" name="start_date" value="{{ $startDate }}">
				<input type="hidden" name="end_date" value="{{ $endDate }}">
				<button type="submit" class="hz-btn-secondary" title="Export laporan tracking produk dari pembelian hingga penjualan" style="display: inline-flex; align-items: center; gap: 8px; border-radius: 999px; font-size: 0.75rem; font-weight: 500; padding: 8px 14px; border: 1px solid rgba(51, 65, 85, 0.8); cursor: pointer; background: rgba(15, 23, 42, 0.8); color: inherit; transition: background 0.15s ease, border-color 0.15s ease; text-decoration: none;">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
						<polyline points="7 10 12 15 17 10"></polyline>
						<line x1="12" y1="15" x2="12" y2="3"></line>
					</svg>
					Tracking Produk
				</button>
			</form>
		</div>
	</div>

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1; padding: 20px;">
			<h3 class="hz-card-title" style="margin-bottom: 16px;">Core Metrics</h3>
			<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
				<div class="hz-metric-card" style="border-radius:16px; padding:16px; background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;">
					<p style="font-size:0.8rem; opacity:0.8;">Revenue (Pendapatan)</p>
					<p style="font-size:1.6rem; font-weight:700; margin-top:4px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
					<p style="font-size:0.75rem; opacity:0.8; margin-top:2px;">{{ $totalSalesCount }} transaksi</p>
				</div>
				<div class="hz-metric-card" style="border-radius:16px; padding:16px; background:linear-gradient(135deg,#fb923c,#f97316); color:#fff;">
					<p style="font-size:0.8rem; opacity:0.8;">HPP / COGS</p>
					<p style="font-size:1.6rem; font-weight:700; margin-top:4px;">Rp {{ number_format($totalHPP, 0, ',', '.') }}</p>
					<p style="font-size:0.75rem; opacity:0.8; margin-top:2px;">Harga pokok penjualan (berdasarkan pembelian)</p>
				</div>
				<div class="hz-metric-card" style="border-radius:16px; padding:16px; background:linear-gradient(135deg,#22c55e,#16a34a); color:#fff;">
					<p style="font-size:0.8rem; opacity:0.8;">Gross Profit (Laba Kotor)</p>
					<p style="font-size:1.6rem; font-weight:700; margin-top:4px;">Rp {{ number_format($grossProfit, 0, ',', '.') }}</p>
					<p style="font-size:0.75rem; opacity:0.8; margin-top:2px;">Margin: {{ number_format($grossMargin, 2) }}%</p>
				</div>
				<div class="hz-metric-card" style="border-radius:16px; padding:16px; background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff;">
					<p style="font-size:0.8rem; opacity:0.8;">Operating Expenses</p>
					<p style="font-size:1.6rem; font-weight:700; margin-top:4px;">Rp {{ number_format($totalOperatingExpenses, 0, ',', '.') }}</p>
					<p style="font-size:0.75rem; opacity:0.8; margin-top:2px;">Biaya operasional</p>
				</div>
				<div class="hz-metric-card" style="border-radius:16px; padding:16px; background:linear-gradient(135deg,#8b5cf6,#7c3aed); color:#fff;">
					<p style="font-size:0.8rem; opacity:0.8;">Net Profit (Laba Bersih)</p>
					<p style="font-size:1.6rem; font-weight:700; margin-top:4px;">Rp {{ number_format($netProfit, 0, ',', '.') }}</p>
					<p style="font-size:0.75rem; opacity:0.8; margin-top:2px;">Margin: {{ number_format($netMargin, 2) }}%</p>
				</div>
			</div>
		</div>

		<div class="hz-card" style="grid-column: 1 / -1; padding: 20px;">
			<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
				<h3 class="hz-card-title">Cash Flow</h3>
				<p style="font-size:0.8rem; color:#9ca3af;">Periode: {{ $startDate }} s/d {{ $endDate }}</p>
			</div>
			<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
				<div class="hz-card-soft">
					<p class="hz-card-sub">Cash In</p>
					<p class="hz-card-metric">Rp {{ number_format($cashIn, 0, ',', '.') }}</p>
					<p style="font-size:0.75rem; color:#9ca3af; margin-top:4px;">Total Revenue</p>
				</div>
				<div class="hz-card-soft">
					<p class="hz-card-sub">Cash Out</p>
					<p class="hz-card-metric">Rp {{ number_format($cashOut, 0, ',', '.') }}</p>
					<p style="font-size:0.75rem; color:#9ca3af; margin-top:4px;">HPP + Expenses</p>
				</div>
				<div class="hz-card-soft">
					<p class="hz-card-sub">Free Cash Flow</p>
					<p class="hz-card-metric" style="color: {{ $freeCashFlow >= 0 ? '#22c55e' : '#f97316' }};">Rp {{ number_format($freeCashFlow, 0, ',', '.') }}</p>
					<p style="font-size:0.75rem; color:#9ca3af; margin-top:4px;">Cash In - Cash Out</p>
				</div>
			</div>
		</div>

		<div class="hz-card" style="padding:20px;">
			<h3 class="hz-card-title" style="margin-bottom:12px;">Payment Method Breakdown</h3>
			@if(count($paymentBreakdown))
				<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:12px;">
					@foreach($paymentBreakdown as $method => $amount)
						<div class="hz-card-soft">
							<p class="hz-card-sub">{{ strtoupper($method) }}</p>
							<p class="hz-card-metric">Rp {{ number_format($amount, 0, ',', '.') }}</p>
							<p style="font-size:0.75rem; color:#9ca3af; margin-top:4px;">
								{{ $totalRevenue > 0 ? number_format(($amount / $totalRevenue) * 100, 1) : 0 }}% dari revenue
							</p>
						</div>
					@endforeach
				</div>
			@else
				<p style="font-size:0.85rem; color:#9ca3af;">Tidak ada data metode pembayaran untuk periode ini.</p>
			@endif
		</div>

		<div class="hz-card" style="padding:20px;">
			<h3 class="hz-card-title" style="margin-bottom:12px;">Operating Expenses Detail</h3>
			<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:16px;">
				<div>
					<h4 style="font-size:0.9rem; font-weight:600; margin-bottom:8px;">Breakdown by Type</h4>
					@if(count($expensesByType))
						@foreach($expensesByType as $type => $amount)
							<div style="display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid rgba(148,163,184,0.25); font-size:0.85rem;">
								<span style="color:#e5e7eb;">{{ $type ?: 'Lainnya' }}</span>
								<span style="font-weight:600; color:#f97316;">Rp {{ number_format($amount, 0, ',', '.') }}</span>
							</div>
						@endforeach
					@else
						<p style="font-size:0.85rem; color:#9ca3af;">Belum ada data expenses pada periode ini.</p>
					@endif
				</div>
				<div>
					<h4 style="font-size:0.9rem; font-weight:600; margin-bottom:8px;">Recent Expenses</h4>
					@if($expenses->count())
						@foreach($expenses as $expense)
							<div style="display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid rgba(148,163,184,0.25); font-size:0.85rem;">
								<div>
									<p style="color:#e5e7eb;">{{ $expense->keterangan ?: ($expense->kategori ?: 'Expense') }}</p>
									<p style="font-size:0.75rem; color:#9ca3af;">{{ optional($expense->tanggal ?: $expense->created_at)->format('d M Y') }}</p>
								</div>
								<span style="font-weight:600; color:#f97316;">Rp {{ number_format($expense->nominal, 0, ',', '.') }}</span>
							</div>
						@endforeach
					@else
						<p style="font-size:0.85rem; color:#9ca3af;">Belum ada data expenses.</p>
					@endif
				</div>
			</div>
		</div>
	</section>
@endsection

@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const periodSelect = document.getElementById('rk-period-select');
		const customRange = document.getElementById('rk-custom-range');

		if (periodSelect && customRange) {
			periodSelect.addEventListener('change', function () {
				if (this.value === 'custom') {
					customRange.style.display = 'flex';
				} else {
					customRange.style.display = 'none';
				}
			});
		}
	});
</script>
@endpush


