@extends('layouts.horizon')

@section('page_title', 'Laporan Keuangan')
@section('page_subtitle', 'Ikhtisar pendapatan, HPP, laba kotor, beban operasional, laba bersih, dan arus kas.')

@section('content')
	<style>
		.filter-control-input {
			border-radius: 10px;
			background: #FFFFFF;
			border: 1px solid #CBD5E1;
			color: #0F223D;
			font-family: inherit;
			font-size: 0.825rem;
			padding: 8px 12px;
			outline: none;
			transition: all 0.15s ease;
		}

		.filter-control-input:focus {
			border-color: #0F223D;
			box-shadow: 0 0 0 3px rgba(15, 34, 61, 0.08);
		}

		.btn-filter-navy {
			border-radius: 10px;
			border: none;
			background: #0F223D;
			color: #FFFFFF;
			padding: 8px 16px;
			font-family: inherit;
			font-size: 0.825rem;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.15s ease;
			display: inline-flex;
			align-items: center;
			gap: 6px;
			box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15);
		}

		.btn-filter-navy:hover {
			background: #162C50;
			transform: translateY(-1px);
		}

		.btn-tracking-export {
			display: inline-flex;
			align-items: center;
			gap: 6px;
			border-radius: 10px;
			font-size: 0.825rem;
			font-weight: 600;
			padding: 8px 14px;
			border: 1px solid #CBD5E1;
			cursor: pointer;
			background: #FFFFFF;
			color: #475569;
			transition: all 0.15s ease;
			text-decoration: none;
			box-shadow: 0 1px 3px rgba(15, 34, 61, 0.04);
		}

		.btn-tracking-export:hover {
			background: #FAF8F5;
			color: #0F223D;
			border-color: #94A3B8;
		}

		.metric-card-clean {
			background: #FFFFFF;
			border-radius: 16px;
			padding: 20px 22px;
			border: 1px solid rgba(226, 232, 240, 0.95);
			box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04);
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			transition: transform 0.15s ease;
		}

		.metric-card-clean:hover {
			transform: translateY(-2px);
			border-color: rgba(197, 160, 89, 0.4);
		}

		.core-metrics-grid-3x2 {
			display: grid;
			grid-template-columns: repeat(3, 1fr);
			gap: 18px;
		}

		@media (max-width: 1024px) {
			.core-metrics-grid-3x2 {
				grid-template-columns: repeat(2, 1fr);
			}
		}

		@media (max-width: 640px) {
			.core-metrics-grid-3x2 {
				grid-template-columns: 1fr;
			}
		}

		.metric-label-clean {
			font-size: 0.75rem;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.06em;
			color: #64748B;
			margin: 0 0 6px 0;
		}

		.metric-val-clean {
			font-size: 1.7rem;
			font-weight: 800;
			color: #0F223D;
			margin: 0;
			line-height: 1.1;
			letter-spacing: -0.02em;
		}

		.metric-sub-clean {
			font-size: 0.75rem;
			color: #94A3B8;
			margin-top: 6px;
		}

		.hz-card-soft-clean {
			background: #FAF8F5;
			border: 1px solid #E2E8F0;
			border-radius: 14px;
			padding: 16px 18px;
		}
	</style>

	<div class="hz-top-row" style="display: flex; justify-content: flex-start; align-items: center; margin-bottom: 22px;">
		<div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
			<!-- Filter Periode -->
			<form action="{{ route('laporan.keuangan') }}" method="GET" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
				<select name="period" id="rk-period-select" class="filter-control-input">
					<option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hari ini</option>
					<option value="week" {{ $period === 'week' ? 'selected' : '' }}>Minggu ini</option>
					<option value="month" {{ $period === 'month' ? 'selected' : '' }}>Bulan ini</option>
					<option value="year" {{ $period === 'year' ? 'selected' : '' }}>Tahun ini</option>
					<option value="all" {{ $period === 'all' ? 'selected' : '' }}>Semua data</option>
					<option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Rentang Khusus</option>
				</select>

				<div id="rk-custom-range" style="display: {{ $period === 'custom' ? 'flex' : 'none' }}; align-items: center; gap: 6px;">
					<input type="date" name="start_date" value="{{ $startDate }}" class="filter-control-input">
					<span style="font-size: 0.8rem; color: #64748B; font-weight: 600;">s/d</span>
					<input type="date" name="end_date" value="{{ $endDate }}" class="filter-control-input">
				</div>

				<button type="submit" class="btn-filter-navy">
					<i data-lucide="filter" style="width: 14px; height: 14px;"></i>
					<span>Terapkan</span>
				</button>
			</form>

			<!-- Export Tracking Produk Button -->
			<form action="{{ route('laporan.tracking-produk.export') }}" method="GET" style="display: inline;">
				<input type="hidden" name="start_date" value="{{ $startDate }}">
				<input type="hidden" name="end_date" value="{{ $endDate }}">
				<button type="submit" class="btn-tracking-export" title="Export laporan tracking produk dari pembelian hingga penjualan">
					<i data-lucide="download" style="width: 14px; height: 14px;"></i>
					<span>Tracking Produk</span>
				</button>
			</form>
		</div>
	</div>

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<!-- Core Metrics Card Container -->
		<div class="hz-card" style="grid-column: 1 / -1; padding: 24px;">
			<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
				<h3 class="hz-card-title" style="margin: 0;">Core Metrics</h3>
				<span style="font-size: 0.75rem; font-weight: 700; background: rgba(197, 160, 89, 0.12); color: #9E7E36; padding: 3px 10px; border-radius: 999px;">Metrik Utama</span>
			</div>
			
			<div class="core-metrics-grid-3x2">
				<!-- 1. Revenue -->
				<div class="metric-card-clean">
					<div>
						<p class="metric-label-clean">Revenue (Pendapatan)</p>
						<p class="metric-val-clean">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
					</div>
					<p class="metric-sub-clean">{{ $totalSalesCount }} Transaksi Penjualan</p>
				</div>

				<!-- 2. HPP -->
				<div class="metric-card-clean">
					<div>
						<p class="metric-label-clean">HPP / COGS</p>
						<p class="metric-val-clean">Rp {{ number_format($totalHPP, 0, ',', '.') }}</p>
					</div>
					<p class="metric-sub-clean">Harga Pokok Pembelian</p>
				</div>

				<!-- 3. Gross Profit -->
				<div class="metric-card-clean">
					<div>
						<p class="metric-label-clean">Gross Profit (Laba Kotor)</p>
						<p class="metric-val-clean">Rp {{ number_format($grossProfit, 0, ',', '.') }}</p>
					</div>
					<p class="metric-sub-clean" style="color: #059669; font-weight: 600;">Margin: {{ number_format($grossMargin, 2) }}%</p>
				</div>

				<!-- 4. Operating Expenses -->
				<div class="metric-card-clean">
					<div>
						<p class="metric-label-clean">Operating Expenses</p>
						<p class="metric-val-clean">Rp {{ number_format($totalOperatingExpenses, 0, ',', '.') }}</p>
					</div>
					<p class="metric-sub-clean">Total Biaya Operasional</p>
				</div>

				<!-- 5. Net Profit -->
				<div class="metric-card-clean">
					<div>
						<p class="metric-label-clean">Net Profit (Laba Bersih)</p>
						<p class="metric-val-clean" style="color: {{ $netProfit >= 0 ? '#0F223D' : '#DC2626' }};">Rp {{ number_format($netProfit, 0, ',', '.') }}</p>
					</div>
					<p class="metric-sub-clean" style="color: {{ $netMargin >= 0 ? '#C5A059' : '#DC2626' }}; font-weight: 700;">Margin: {{ number_format($netMargin, 2) }}%</p>
				</div>

				<!-- 6. Free Cash Flow (Arus Kas Bersih) -->
				<div class="metric-card-clean">
					<div>
						<p class="metric-label-clean">Arus Kas Bersih (Cash Flow)</p>
						<p class="metric-val-clean" style="color: {{ $freeCashFlow >= 0 ? '#0F223D' : '#DC2626' }};">Rp {{ number_format($freeCashFlow, 0, ',', '.') }}</p>
					</div>
					<p class="metric-sub-clean" style="color: {{ $freeCashFlow >= 0 ? '#059669' : '#DC2626' }}; font-weight: 600;">Cash In - Cash Out</p>
				</div>
			</div>
		</div>

		<!-- Piutang Belum Tertagih (AR) -->
		<div class="hz-card" style="grid-column: 1 / -1; padding: 24px;">
			<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
				<h3 class="hz-card-title" style="margin: 0;">Piutang Belum Tertagih (AR)</h3>
				<p style="font-size: 0.8rem; color: #64748B; margin: 0;">Akumulasi seluruh waktu</p>
			</div>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
				<div class="hz-card-soft-clean">
					<p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin-bottom: 4px;">Total Piutang</p>
					<p style="font-size: 1.45rem; font-weight: 800; color: #0F223D; margin: 0;">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</p>
					<p style="font-size: 0.75rem; color: #94A3B8; margin-top: 4px;">Sisa tagihan invoice aktif</p>
				</div>
				<div class="hz-card-soft-clean">
					<p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin-bottom: 4px;">Jatuh Tempo</p>
					<p style="font-size: 1.45rem; font-weight: 800; color: #EF4444; margin: 0;">Rp {{ number_format($piutangJatuhTempo, 0, ',', '.') }}</p>
					<p style="font-size: 0.75rem; color: #EF4444; margin-top: 4px; font-weight: 500;">Invoice overdue</p>
				</div>
				<div class="hz-card-soft-clean">
					<p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin-bottom: 4px;">Belum Jatuh Tempo</p>
					<p style="font-size: 1.45rem; font-weight: 800; color: #059669; margin: 0;">Rp {{ number_format($piutangBelumJatuhTempo, 0, ',', '.') }}</p>
					<p style="font-size: 0.75rem; color: #059669; margin-top: 4px; font-weight: 500;">Invoice on-time</p>
				</div>
			</div>
		</div>

		<!-- Cash Flow -->
		<div class="hz-card" style="grid-column: 1 / -1; padding: 24px;">
			<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
				<h3 class="hz-card-title" style="margin: 0;">Arus Kas (Cash Flow)</h3>
				<p style="font-size: 0.8rem; color: #64748B; margin: 0;">Periode: {{ $startDate }} s/d {{ $endDate }}</p>
			</div>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
				<div class="hz-card-soft-clean">
					<p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin-bottom: 4px;">Cash In (Pemasukan)</p>
					<p style="font-size: 1.45rem; font-weight: 800; color: #059669; margin: 0;">Rp {{ number_format($cashIn, 0, ',', '.') }}</p>
					<p style="font-size: 0.75rem; color: #94A3B8; margin-top: 4px;">Total Revenue</p>
				</div>
				<div class="hz-card-soft-clean">
					<p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin-bottom: 4px;">Cash Out (Pengeluaran)</p>
					<p style="font-size: 1.45rem; font-weight: 800; color: #EF4444; margin: 0;">Rp {{ number_format($cashOut, 0, ',', '.') }}</p>
					<p style="font-size: 0.75rem; color: #94A3B8; margin-top: 4px;">HPP + Beban Operasional</p>
				</div>
				<div class="hz-card-soft-clean">
					<p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin-bottom: 4px;">Free Cash Flow</p>
					<p style="font-size: 1.45rem; font-weight: 800; color: {{ $freeCashFlow >= 0 ? '#0F223D' : '#EF4444' }}; margin: 0;">Rp {{ number_format($freeCashFlow, 0, ',', '.') }}</p>
					<p style="font-size: 0.75rem; color: #94A3B8; margin-top: 4px;">Cash In - Cash Out</p>
				</div>
			</div>
		</div>

		<!-- Payment Breakdown & Operating Expenses Detail -->
		<div class="hz-card" style="padding: 24px;">
			<h3 class="hz-card-title" style="margin-bottom: 16px;">Rincian Metode Pembayaran</h3>
			@if(count($paymentBreakdown))
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">
					@foreach($paymentBreakdown as $method => $amount)
						<div class="hz-card-soft-clean">
							<p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin-bottom: 4px;">{{ strtoupper($method) }}</p>
							<p style="font-size: 1.15rem; font-weight: 700; color: #0F223D; margin: 0;">Rp {{ number_format($amount, 0, ',', '.') }}</p>
							<p style="font-size: 0.75rem; color: #C5A059; font-weight: 600; margin-top: 4px;">
								{{ $totalRevenue > 0 ? number_format(($amount / $totalRevenue) * 100, 1) : 0 }}% dari omset
							</p>
						</div>
					@endforeach
				</div>
			@else
				<p style="font-size: 0.85rem; color: #94A3B8; margin: 0;">Tidak ada data metode pembayaran untuk periode ini.</p>
			@endif
		</div>

		<div class="hz-card" style="padding: 24px;">
			<h3 class="hz-card-title" style="margin-bottom: 16px;">Rincian Pengeluaran (Expenses)</h3>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
				<div>
					<h4 style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B; margin-bottom: 10px;">Berdasarkan Kategori</h4>
					@if(count($expensesByType))
						@foreach($expensesByType as $type => $amount)
							<div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #F1F5F9; font-size: 0.85rem;">
								<span style="color: #0F223D; font-weight: 500;">{{ $type ?: 'Lainnya' }}</span>
								<span style="font-weight: 700; color: #0F223D;">Rp {{ number_format($amount, 0, ',', '.') }}</span>
							</div>
						@endforeach
					@else
						<p style="font-size: 0.85rem; color: #94A3B8; margin: 0;">Belum ada data expenses pada periode ini.</p>
					@endif
				</div>
				<div>
					<h4 style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748B; margin-bottom: 10px;">Pengeluaran Terbaru</h4>
					@if($expenses->count())
						@foreach($expenses as $expense)
							<div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #F1F5F9; font-size: 0.85rem;">
								<div>
									<p style="color: #0F223D; font-weight: 600; margin: 0;">{{ $expense->keterangan ?: ($expense->kategori ?: 'Expense') }}</p>
									<p style="font-size: 0.75rem; color: #94A3B8; margin: 2px 0 0 0;">{{ optional($expense->tanggal ?: $expense->created_at)->format('d M Y') }}</p>
								</div>
								<span style="font-weight: 700; color: #EF4444;">Rp {{ number_format($expense->nominal, 0, ',', '.') }}</span>
							</div>
						@endforeach
					@else
						<p style="font-size: 0.85rem; color: #94A3B8; margin: 0;">Belum ada data transaksi pengeluaran.</p>
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

		if (typeof lucide !== 'undefined') {
			lucide.createIcons();
		}
	});
</script>
@endpush
