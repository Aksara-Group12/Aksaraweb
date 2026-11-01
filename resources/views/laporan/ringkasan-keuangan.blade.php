@extends('layouts.horizon')

@section('page_title', 'Laporan Keuangan')

@section('header_content')
	<div>
		<h1 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.45rem; font-weight: 500; color: #0F223D; margin: 0; line-height: 1.2; letter-spacing: -0.01em;">
			Laporan Keuangan
		</h1>
		<p style="font-family: 'Inter', sans-serif; font-size: 0.8rem; color: #64748B; margin: 3px 0 0 0;">
			Ikhtisar performa pendapatan, laba kotor, beban operasional, laba bersih, dan arus kas toko.
		</p>
	</div>
@endsection

@section('content')
	<style>
		@media (max-width: 768px) {
			.core-metrics-grid-2x2 {
				grid-template-columns: 1fr !important;
			}
		}
	</style>

	<!-- Toolbar Filter Periode & Aksi -->
	<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
		<form action="{{ route('laporan.keuangan') }}" method="GET" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin: 0;">
			<div style="position: relative; display: inline-flex; align-items: center;">
				<select name="period" id="rk-period-select" 
					style="appearance: none; -webkit-appearance: none; padding: 9px 34px 9px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.85rem; font-weight: 500; outline: none; cursor: pointer; transition: border-color 0.15s ease;"
					onfocus="this.style.borderColor='#0F223D'" onblur="this.style.borderColor='#CBD5E1'">
					<option value="today" {{ $period === 'today' ? 'selected' : '' }}>Hari ini</option>
					<option value="week" {{ $period === 'week' ? 'selected' : '' }}>Minggu ini</option>
					<option value="month" {{ $period === 'month' ? 'selected' : '' }}>Bulan ini</option>
					<option value="year" {{ $period === 'year' ? 'selected' : '' }}>Tahun ini</option>
					<option value="all" {{ $period === 'all' ? 'selected' : '' }}>Semua Periode</option>
					<option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Rentang Kustom</option>
				</select>
				<i data-lucide="chevron-down" style="position: absolute; right: 10px; width: 15px; height: 15px; color: #64748B; pointer-events: none;"></i>
			</div>

			<div id="rk-custom-range" style="display: {{ $period === 'custom' ? 'flex' : 'none' }}; align-items: center; gap: 6px;">
				<input type="date" name="start_date" value="{{ $startDate }}" style="border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.85rem; padding: 8px 12px; outline: none;">
				<span style="font-size: 0.8rem; color: #64748B;">s/d</span>
				<input type="date" name="end_date" value="{{ $endDate }}" style="border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.85rem; padding: 8px 12px; outline: none;">
			</div>

			<button type="submit" 
				style="border-radius: 10px; border: none; background: #0F223D; color: #FFFFFF; padding: 9px 18px; font-size: 0.85rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 6px rgba(15, 34, 61, 0.12); transition: all 0.15s ease;"
				onmouseover="this.style.background='#162C50'; this.style.boxShadow='0 0 14px rgba(197, 160, 89, 0.25)';" 
				onmouseout="this.style.background='#0F223D'; this.style.boxShadow='0 2px 6px rgba(15, 34, 61, 0.12)';">
				<i data-lucide="filter" style="width: 14px; height: 14px; color: #C5A059;"></i>
				<span>Terapkan</span>
			</button>
		</form>

		<!-- Export Tracking Produk Button -->
		<form action="{{ route('laporan.tracking-produk.export') }}" method="GET" style="margin: 0;">
			<input type="hidden" name="start_date" value="{{ $startDate }}">
			<input type="hidden" name="end_date" value="{{ $endDate }}">
			<button type="submit" 
				title="Export laporan tracking produk dari pembelian hingga penjualan" 
				style="display: inline-flex; align-items: center; gap: 8px; border-radius: 10px; font-size: 0.85rem; font-weight: 600; padding: 9px 16px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; cursor: pointer; transition: all 0.15s ease;"
				onmouseover="this.style.background='#F8FAFC'; this.style.borderColor='#94A3B8';"
				onmouseout="this.style.background='#FFFFFF'; this.style.borderColor='#CBD5E1';">
				<i data-lucide="download" style="width: 15px; height: 15px; color: #64748B;"></i>
				<span>Export Tracking Produk</span>
			</button>
		</form>
	</div>

	<!-- 1. Core Metrics Container Card -->
	<div style="background: #FFFFFF; border-radius: 18px; border: 1px solid rgba(226, 232, 240, 0.95); box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04); padding: 24px; margin-bottom: 24px;">
		<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 8px;">
			<div>
				<h3 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.25rem; font-weight: 600; color: #0F223D; margin: 0; line-height: 1.2;">
					Core Metrics (Indikator Utama)
				</h3>
				<p style="font-size: 0.8rem; color: #64748B; margin: 3px 0 0 0;">
					Periode Aktif: {{ $startDate }} s/d {{ $endDate }}
				</p>
			</div>
			<span style="font-size: 0.75rem; font-weight: 700; background: rgba(197, 160, 89, 0.12); color: #9E7E36; padding: 4px 12px; border-radius: 999px;">
				Ringkasan Keuangan
			</span>
		</div>

		<!-- Grid 4 Kartu Metrik (Susunan 2x2: 2 Atas, 2 Bawah) -->
		<div class="core-metrics-grid-2x2" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px;">
			<!-- Card 1: Revenue -->
			<div style="background: #FAF8F5; border: 1px solid rgba(226, 232, 240, 0.95); border-radius: 14px; padding: 18px 20px; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease;" onmouseover="this.style.background='#FFFFFF'; this.style.borderColor='rgba(197, 160, 89, 0.6)'; this.style.boxShadow='0 0 16px rgba(197, 160, 89, 0.15)';" onmouseout="this.style.background='#FAF8F5'; this.style.borderColor='rgba(226, 232, 240, 0.95)'; this.style.boxShadow='none';">
				<div>
					<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
						<span style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; letter-spacing: 0.04em;">Revenue (Pendapatan)</span>
						<div style="width: 28px; height: 28px; border-radius: 8px; background: rgba(15, 34, 61, 0.08); display: flex; align-items: center; justify-content: center; color: #0F223D;">
							<i data-lucide="dollar-sign" style="width: 15px; height: 15px;"></i>
						</div>
					</div>
					<p style="font-size: 1.55rem; font-weight: 800; color: #0F223D; margin: 0; line-height: 1.2;">
						<span style="font-size: 0.95rem; font-weight: 600; color: #C5A059;">Rp</span> {{ number_format($totalRevenue, 0, ',', '.') }}
					</p>
				</div>
				<p style="font-size: 0.75rem; color: #64748B; margin: 12px 0 0 0; padding-top: 10px; border-top: 1px solid rgba(226, 232, 240, 0.8);">
					{{ $totalSalesCount }} Transaksi Penjualan Selesai
				</p>
			</div>

			<!-- Card 2: Gross Profit -->
			<div style="background: #FAF8F5; border: 1px solid rgba(226, 232, 240, 0.95); border-radius: 14px; padding: 18px 20px; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease;" onmouseover="this.style.background='#FFFFFF'; this.style.borderColor='rgba(197, 160, 89, 0.6)'; this.style.boxShadow='0 0 16px rgba(197, 160, 89, 0.15)';" onmouseout="this.style.background='#FAF8F5'; this.style.borderColor='rgba(226, 232, 240, 0.95)'; this.style.boxShadow='none';">
				<div>
					<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
						<span style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; letter-spacing: 0.04em;">Gross Profit (Laba Kotor)</span>
						<div style="width: 28px; height: 28px; border-radius: 8px; background: #ECFDF5; display: flex; align-items: center; justify-content: center; color: #10B981;">
							<i data-lucide="trending-up" style="width: 15px; height: 15px;"></i>
						</div>
					</div>
					<p style="font-size: 1.55rem; font-weight: 800; color: #0F223D; margin: 0; line-height: 1.2;">
						<span style="font-size: 0.95rem; font-weight: 600; color: #C5A059;">Rp</span> {{ number_format($grossProfit, 0, ',', '.') }}
					</p>
				</div>
				<div style="margin-top: 12px; padding-top: 10px; border-top: 1px solid rgba(226, 232, 240, 0.8); display: flex; justify-content: space-between; align-items: center;">
					<span style="font-size: 0.75rem; color: #64748B;">Gross Margin:</span>
					<span style="font-size: 0.75rem; font-weight: 700; color: #059669; background: #ECFDF5; padding: 2px 8px; border-radius: 6px;">
						{{ number_format($grossMargin, 2) }}%
					</span>
				</div>
			</div>

			<!-- Card 3: Operating Expenses -->
			<div style="background: #FAF8F5; border: 1px solid rgba(226, 232, 240, 0.95); border-radius: 14px; padding: 18px 20px; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease;" onmouseover="this.style.background='#FFFFFF'; this.style.borderColor='rgba(197, 160, 89, 0.6)'; this.style.boxShadow='0 0 16px rgba(197, 160, 89, 0.15)';" onmouseout="this.style.background='#FAF8F5'; this.style.borderColor='rgba(226, 232, 240, 0.95)'; this.style.boxShadow='none';">
				<div>
					<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
						<span style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; letter-spacing: 0.04em;">Operating Expenses</span>
						<div style="width: 28px; height: 28px; border-radius: 8px; background: #FEF2F2; display: flex; align-items: center; justify-content: center; color: #EF4444;">
							<i data-lucide="receipt" style="width: 15px; height: 15px;"></i>
						</div>
					</div>
					<p style="font-size: 1.55rem; font-weight: 800; color: #0F223D; margin: 0; line-height: 1.2;">
						<span style="font-size: 0.95rem; font-weight: 600; color: #EF4444;">Rp</span> {{ number_format($totalOperatingExpenses, 0, ',', '.') }}
					</p>
				</div>
				<p style="font-size: 0.75rem; color: #64748B; margin: 12px 0 0 0; padding-top: 10px; border-top: 1px solid rgba(226, 232, 240, 0.8);">
					Total Beban Operasional Toko
				</p>
			</div>

			<!-- Card 4: Net Profit -->
			<div style="background: #FAF8F5; border: 1px solid rgba(226, 232, 240, 0.95); border-radius: 14px; padding: 18px 20px; display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s ease;" onmouseover="this.style.background='#FFFFFF'; this.style.borderColor='rgba(197, 160, 89, 0.6)'; this.style.boxShadow='0 0 16px rgba(197, 160, 89, 0.15)';" onmouseout="this.style.background='#FAF8F5'; this.style.borderColor='rgba(226, 232, 240, 0.95)'; this.style.boxShadow='none';">
				<div>
					<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
						<span style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; letter-spacing: 0.04em;">Net Profit (Laba Bersih)</span>
						<div style="width: 28px; height: 28px; border-radius: 8px; background: {{ $netProfit >= 0 ? 'rgba(197, 160, 89, 0.15)' : '#FEF2F2' }}; display: flex; align-items: center; justify-content: center; color: {{ $netProfit >= 0 ? '#C5A059' : '#EF4444' }};">
							<i data-lucide="pie-chart" style="width: 15px; height: 15px;"></i>
						</div>
					</div>
					<p style="font-size: 1.55rem; font-weight: 800; color: {{ $netProfit >= 0 ? '#0F223D' : '#DC2626' }}; margin: 0; line-height: 1.2;">
						<span style="font-size: 0.95rem; font-weight: 600; color: #C5A059;">Rp</span> {{ number_format($netProfit, 0, ',', '.') }}
					</p>
				</div>
				<div style="margin-top: 12px; padding-top: 10px; border-top: 1px solid rgba(226, 232, 240, 0.8); display: flex; justify-content: space-between; align-items: center;">
					<span style="font-size: 0.75rem; color: #64748B;">Net Margin:</span>
					<span style="font-size: 0.75rem; font-weight: 700; color: {{ $netMargin >= 0 ? '#0F223D' : '#DC2626' }}; background: #F1F5F9; padding: 2px 8px; border-radius: 6px;">
						{{ number_format($netMargin, 2) }}%
					</span>
				</div>
			</div>
		</div>
	</div>

	<!-- 2. Cash Flow Container Card -->
	<div style="background: #FFFFFF; border-radius: 18px; border: 1px solid rgba(226, 232, 240, 0.95); box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04); padding: 24px; margin-bottom: 24px;">
		<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 8px;">
			<div>
				<h3 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.25rem; font-weight: 600; color: #0F223D; margin: 0; line-height: 1.2;">
					Arus Kas (Cash Flow)
				</h3>
				<p style="font-size: 0.8rem; color: #64748B; margin: 3px 0 0 0;">
					Pergerakan uang masuk dan uang keluar pada kas operasional.
				</p>
			</div>
			<span style="font-size: 0.75rem; font-weight: 600; color: #64748B; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 4px 12px; border-radius: 8px;">
				Periode: {{ $startDate }} s/d {{ $endDate }}
			</span>
		</div>

		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
			<!-- Cash In -->
			<div style="background: #FAF8F5; border: 1px solid rgba(226, 232, 240, 0.95); border-radius: 14px; padding: 18px 20px;">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
					<p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin: 0; letter-spacing: 0.04em;">Cash In (Pemasukan)</p>
					<span style="width: 8px; height: 8px; border-radius: 50%; background: #10B981;"></span>
				</div>
				<p style="font-size: 1.45rem; font-weight: 800; color: #059669; margin: 0;">
					<span style="font-size: 0.9rem; font-weight: 600;">Rp</span> {{ number_format($cashIn, 0, ',', '.') }}
				</p>
				<p style="font-size: 0.75rem; color: #64748B; margin: 6px 0 0 0;">Total Penjualan Selesai</p>
			</div>

			<!-- Cash Out -->
			<div style="background: #FAF8F5; border: 1px solid rgba(226, 232, 240, 0.95); border-radius: 14px; padding: 18px 20px;">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
					<p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin: 0; letter-spacing: 0.04em;">Cash Out (Pengeluaran)</p>
					<span style="width: 8px; height: 8px; border-radius: 50%; background: #EF4444;"></span>
				</div>
				<p style="font-size: 1.45rem; font-weight: 800; color: #EF4444; margin: 0;">
					<span style="font-size: 0.9rem; font-weight: 600;">Rp</span> {{ number_format($cashOut, 0, ',', '.') }}
				</p>
				<p style="font-size: 0.75rem; color: #64748B; margin: 6px 0 0 0;">Total Beban Operasional</p>
			</div>

			<!-- Free Cash Flow -->
			<div style="background: #FAF8F5; border: 1px solid rgba(226, 232, 240, 0.95); border-radius: 14px; padding: 18px 20px;">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
					<p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin: 0; letter-spacing: 0.04em;">Free Cash Flow</p>
					<span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $freeCashFlow >= 0 ? '#C5A059' : '#EF4444' }};"></span>
				</div>
				<p style="font-size: 1.45rem; font-weight: 800; color: {{ $freeCashFlow >= 0 ? '#0F223D' : '#EF4444' }}; margin: 0;">
					<span style="font-size: 0.9rem; font-weight: 600; color: #C5A059;">Rp</span> {{ number_format($freeCashFlow, 0, ',', '.') }}
				</p>
				<p style="font-size: 0.75rem; color: #64748B; margin: 6px 0 0 0;">Selisih Bersih (Cash In - Cash Out)</p>
			</div>
		</div>
	</div>

	<!-- 3. Dua Kolom: Payment Method & Detail Beban -->
	<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
		<!-- Kartu Payment Method Breakdown -->
		<div style="background: #FFFFFF; border-radius: 18px; border: 1px solid rgba(226, 232, 240, 0.95); box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04); padding: 24px;">
			<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
				<div>
					<h3 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.25rem; font-weight: 600; color: #0F223D; margin: 0; line-height: 1.2;">
						Metode Pembayaran
					</h3>
					<p style="font-size: 0.8rem; color: #64748B; margin: 3px 0 0 0;">
						Distribusi transaksi berdasarkan channel bayar.
					</p>
				</div>
				<i data-lucide="credit-card" style="width: 18px; height: 18px; color: #C5A059;"></i>
			</div>

			@if(count($paymentBreakdown))
				<div style="display: flex; flex-direction: column; gap: 12px;">
					@foreach($paymentBreakdown as $method => $amount)
						@php
							$pct = $totalRevenue > 0 ? ($amount / $totalRevenue) * 100 : 0;
						@endphp
						<div style="background: #FAF8F5; border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px 16px;">
							<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
								<span style="font-size: 0.825rem; font-weight: 700; color: #0F223D; text-transform: uppercase;">
									{{ $method }}
								</span>
								<span style="font-size: 0.9rem; font-weight: 800; color: #0F223D;">
									Rp {{ number_format($amount, 0, ',', '.') }}
								</span>
							</div>
							<div style="display: flex; align-items: center; gap: 8px;">
								<div style="flex: 1; height: 6px; background: #E2E8F0; border-radius: 999px; overflow: hidden;">
									<div style="width: {{ min(100, max(5, $pct)) }}%; height: 100%; background: #0F223D; border-radius: 999px;"></div>
								</div>
								<span style="font-size: 0.72rem; font-weight: 600; color: #64748B; min-width: 40px; text-align: right;">
									{{ number_format($pct, 1) }}%
								</span>
							</div>
						</div>
					@endforeach
				</div>
			@else
				<div style="padding: 32px 16px; text-align: center; color: #64748B;">
					<p style="font-size: 0.85rem; margin: 0;">Belum ada data metode pembayaran untuk periode ini.</p>
				</div>
			@endif
		</div>

		<!-- Kartu Operating Expenses Detail -->
		<div style="background: #FFFFFF; border-radius: 18px; border: 1px solid rgba(226, 232, 240, 0.95); box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04); padding: 24px;">
			<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
				<div>
					<h3 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.25rem; font-weight: 600; color: #0F223D; margin: 0; line-height: 1.2;">
						Rincian Beban Operasional
					</h3>
					<p style="font-size: 0.8rem; color: #64748B; margin: 3px 0 0 0;">
						Pengeluaran terbaru dan kategori biaya operasional.
					</p>
				</div>
				<i data-lucide="receipt" style="width: 18px; height: 18px; color: #EF4444;"></i>
			</div>

			<div style="display: flex; flex-direction: column; gap: 18px;">
				<!-- Kategori Biaya -->
				<div>
					<p style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #64748B; margin: 0 0 8px 0; letter-spacing: 0.04em;">Berdasarkan Kategori</p>
					@if(count($expensesByType))
						<div style="display: flex; flex-direction: column; gap: 8px;">
							@foreach($expensesByType as $type => $amount)
								<div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; background: #FAF8F5; border-radius: 8px; border: 1px solid #F1F5F9; font-size: 0.85rem;">
									<span style="color: #0F223D; font-weight: 500;">{{ $type ?: 'Operasional Umum' }}</span>
									<span style="font-weight: 700; color: #EF4444;">Rp {{ number_format($amount, 0, ',', '.') }}</span>
								</div>
							@endforeach
						</div>
					@else
						<p style="font-size: 0.825rem; color: #94A3B8; margin: 0; font-style: italic;">Belum ada catatan kategori pengeluaran.</p>
					@endif
				</div>

				<!-- Pengeluaran Terbaru -->
				<div>
					<p style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: #64748B; margin: 0 0 8px 0; letter-spacing: 0.04em;">Pengeluaran Terakhir</p>
					@if($expenses->count())
						<div style="display: flex; flex-direction: column; gap: 8px;">
							@foreach($expenses->take(4) as $expense)
								<div style="display: flex; justify-content: space-between; align-items: center; padding: 8px 12px; border-bottom: 1px solid #F1F5F9; font-size: 0.825rem;">
									<div>
										<p style="margin: 0; font-weight: 600; color: #0F223D;">{{ $expense->keterangan ?: ($expense->kategori ?: 'Biaya Operasional') }}</p>
										<p style="margin: 2px 0 0 0; font-size: 0.72rem; color: #94A3B8;">{{ optional($expense->tanggal ?: $expense->created_at)->format('d M Y') }}</p>
									</div>
									<span style="font-weight: 700; color: #0F223D;">Rp {{ number_format($expense->nominal, 0, ',', '.') }}</span>
								</div>
							@endforeach
						</div>
					@else
						<p style="font-size: 0.825rem; color: #94A3B8; margin: 0; font-style: italic;">Belum ada data pengeluaran operasional.</p>
					@endif
				</div>
			</div>
		</div>
	</div>
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
