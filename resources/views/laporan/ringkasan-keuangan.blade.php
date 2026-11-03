@extends('layouts.horizon')

@section('page_title', 'Laporan Keuangan')

@section('header_content')
	<div>
		<h1 style="font-family: 'Geist', 'Geist Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 1.45rem; font-weight: 600; color: #0F223D; margin: 0; line-height: 1.2; letter-spacing: -0.02em;">
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

		/* Filter Toolbar & Actions (Matching Superadmin Style) */
		.rk-action-bar {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 22px;
			flex-wrap: wrap;
			gap: 12px;
		}

		.filter-action-wrapper {
			display: flex;
			align-items: center;
			justify-content: flex-end;
			gap: 10px;
		}

		.btn-filter-trigger {
			background: #2563EB;
			color: #FFFFFF;
			border: 1px solid #2563EB;
			border-radius: 8px;
			padding: 8px 18px;
			font-size: 0.825rem;
			font-weight: 600;
			cursor: pointer;
			display: inline-flex;
			align-items: center;
			gap: 8px;
			transition: all 0.2s ease;
			box-shadow: 0 1px 2px rgba(37, 99, 235, 0.15);
			font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
		}

		.btn-filter-trigger:hover {
			background: #1D4ED8;
			border-color: #1D4ED8;
		}

		.btn-filter-trigger.is-active {
			background: #1E40AF;
			border-color: #1E40AF;
			box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
		}

		.btn-outline-action {
			background: #FFFFFF;
			color: #09090B;
			border: 1px solid #E4E4E7;
			border-radius: 8px;
			padding: 8px 16px;
			font-size: 0.825rem;
			font-weight: 600;
			cursor: pointer;
			display: inline-flex;
			align-items: center;
			gap: 8px;
			transition: all 0.15s ease;
			box-shadow: 0 1px 2px rgba(0,0,0,0.03);
			font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
			text-decoration: none;
		}

		.btn-outline-action:hover {
			background: #F4F4F5;
			border-color: #D4D4D8;
		}

		/* Collapsible Filter Panel */
		.superadmin-filter-panel {
			background: #FFFFFF;
			border: 1px solid #E4E4E7;
			border-radius: 14px;
			padding: 22px 24px;
			margin-bottom: 22px;
			box-shadow: 0 1px 3px rgba(0,0,0,0.03);
			animation: slideDownFade 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
		}

		@keyframes slideDownFade {
			from {
				opacity: 0;
				transform: translateY(-8px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.filter-grid-row-bottom {
			display: grid;
			grid-template-columns: 1fr 1fr 1fr 1.2fr;
			gap: 20px;
			align-items: flex-end;
		}

		.filter-field-group {
			display: flex;
			flex-direction: column;
			gap: 6px;
		}

		.filter-field-label {
			font-size: 0.8rem;
			font-weight: 600;
			color: #09090B;
			font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
		}

		.filter-select,
		.filter-select option,
		.filter-select optgroup,
		.filter-input-date,
		.filter-field-label,
		.btn-filter-reset,
		.btn-filter-submit {
			font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
		}

		.filter-select,
		.filter-input-date {
			width: 100%;
			background: #FFFFFF;
			border: 1px solid #E4E4E7;
			border-radius: 8px;
			padding: 9px 12px;
			font-size: 0.825rem;
			color: #09090B;
			outline: none;
			transition: all 0.15s ease;
		}

		.filter-select option {
			padding: 8px 12px;
			background-color: #FFFFFF;
			color: #09090B;
			font-size: 0.825rem;
		}

		.filter-select:focus,
		.filter-input-date:focus {
			border-color: #2563EB;
			box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.12);
		}

		.filter-buttons-group {
			display: flex;
			align-items: center;
			justify-content: flex-end;
			gap: 10px;
		}

		.btn-filter-reset {
			background: #FFFFFF;
			color: #09090B;
			border: 1px solid #E4E4E7;
			border-radius: 8px;
			padding: 9px 18px;
			font-size: 0.825rem;
			font-weight: 600;
			cursor: pointer;
			display: inline-flex;
			align-items: center;
			gap: 6px;
			text-decoration: none;
			transition: all 0.15s ease;
		}

		.btn-filter-reset:hover {
			background: #F4F4F5;
		}

		.btn-filter-submit {
			background: #09090B;
			color: #FFFFFF;
			border: 1px solid #09090B;
			border-radius: 8px;
			padding: 9px 22px;
			font-size: 0.825rem;
			font-weight: 600;
			cursor: pointer;
			display: inline-flex;
			align-items: center;
			gap: 6px;
			transition: all 0.15s ease;
		}

		.btn-filter-submit:hover {
			background: #27272A;
		}

		@media (max-width: 900px) {
			.filter-grid-row-bottom {
				grid-template-columns: 1fr;
				gap: 14px;
			}
			.filter-buttons-group {
				justify-content: stretch;
			}
			.btn-filter-reset,
			.btn-filter-submit {
				flex: 1;
				justify-content: center;
			}
		}
	</style>

	<!-- Toolbar Filter Periode & Aksi (Gaya Superadmin) -->
	<div class="rk-action-bar">
		<div class="flex items-center gap-3">
			@if($isFilterActive)
				<div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-full text-xs font-medium text-blue-800">
					<span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
					<span>Filter Aktif: <strong>{{ $period === 'custom' ? 'Rentang Khusus' : ($period === 'month' ? 'Bulan Ini' : ($period === 'today' ? 'Hari Ini' : ($period === 'week' ? 'Minggu Ini' : ($period === 'year' ? 'Tahun Ini' : 'Semua Periode')))) }}</strong></span>
					@if($startDate && $endDate)
						<span class="text-blue-400">•</span>
						<span>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</span>
					@endif
				</div>
			@endif
		</div>

		<div class="filter-action-wrapper">
			<!-- Export Tracking Produk Button -->
			<form action="{{ route('laporan.tracking-produk.export') }}" method="GET" style="margin: 0;">
				<input type="hidden" name="start_date" value="{{ $startDate }}">
				<input type="hidden" name="end_date" value="{{ $endDate }}">
				<button type="submit" class="btn-outline-action" title="Export laporan tracking produk dari pembelian hingga penjualan">
					<i data-lucide="download" class="w-4 h-4"></i>
					<span>Export Tracking Produk</span>
				</button>
			</form>

			<button type="button" id="btn-toggle-rk-filter" class="btn-filter-trigger {{ $isFilterActive ? 'is-active' : '' }}" onclick="toggleRkFilter()" title="Buka / Tutup Panel Filter">
				<i data-lucide="filter" class="w-4 h-4"></i>
				<span>Filter</span>
				@if($isFilterActive)
					<span class="inline-block w-2 h-2 rounded-full bg-amber-300 ml-0.5"></span>
				@endif
			</button>
		</div>
	</div>

	<!-- Collapsible Filter Panel -->
	<div id="rk-filter-panel" class="superadmin-filter-panel" style="{{ $isFilterActive ? 'display: block;' : 'display: none;' }}">
		<form method="GET" action="{{ route('laporan.keuangan') }}" id="rk-filter-form">
			<div class="filter-grid-row-bottom">
				<div class="filter-field-group">
					<label for="rk_filter_period" class="filter-field-label">Periode Waktu</label>
					<select name="period" id="rk_filter_period" class="filter-select" onchange="handleRkPeriodChange(this.value)">
						<option value="month" {{ ($period ?? 'month') === 'month' ? 'selected' : '' }}>Bulan Ini</option>
						<option value="today" {{ ($period ?? '') === 'today' ? 'selected' : '' }}>Hari Ini</option>
						<option value="week" {{ ($period ?? '') === 'week' ? 'selected' : '' }}>Minggu Ini</option>
						<option value="year" {{ ($period ?? '') === 'year' ? 'selected' : '' }}>Tahun Ini</option>
						<option value="all" {{ ($period ?? '') === 'all' ? 'selected' : '' }}>Semua Periode</option>
						<option value="custom" {{ ($period ?? '') === 'custom' ? 'selected' : '' }}>Rentang Khusus</option>
					</select>
				</div>

				<div class="filter-field-group">
					<label for="rk_filter_start_date" class="filter-field-label">Tanggal Awal</label>
					<input type="date" name="start_date" id="rk_filter_start_date" value="{{ $startDate ?? '' }}" class="filter-input-date" onchange="markRkPeriodAsCustom()">
				</div>

				<div class="filter-field-group">
					<label for="rk_filter_end_date" class="filter-field-label">Tanggal Akhir</label>
					<input type="date" name="end_date" id="rk_filter_end_date" value="{{ $endDate ?? '' }}" class="filter-input-date" onchange="markRkPeriodAsCustom()">
				</div>

				<div class="filter-buttons-group">
					<a href="{{ route('laporan.keuangan') }}" class="btn-filter-reset" title="Kembalikan Filter ke Awal">
						<i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
						<span>Reset</span>
					</a>
					<button type="submit" class="btn-filter-submit" title="Terapkan Filter">
						<i data-lucide="search" class="w-3.5 h-3.5"></i>
						<span>Cari</span>
					</button>
				</div>
			</div>
		</form>
	</div>

	<!-- 1. Core Metrics Container Card -->
	<div style="background: #FFFFFF; border-radius: 18px; border: 1px solid rgba(226, 232, 240, 0.95); box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04); padding: 24px; margin-bottom: 24px;">
		<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 8px;">
			<div>
				<h3 style="font-family: 'Geist', 'Geist Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 1.2rem; font-weight: 700; color: #0F223D; margin: 0; line-height: 1.2; letter-spacing: -0.015em;">
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
				<h3 style="font-family: 'Geist', 'Geist Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 1.2rem; font-weight: 700; color: #0F223D; margin: 0; line-height: 1.2; letter-spacing: -0.015em;">
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
					<h3 style="font-family: 'Geist', 'Geist Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 1.2rem; font-weight: 700; color: #0F223D; margin: 0; line-height: 1.2; letter-spacing: -0.015em;">
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
					<h3 style="font-family: 'Geist', 'Geist Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 1.2rem; font-weight: 700; color: #0F223D; margin: 0; line-height: 1.2; letter-spacing: -0.015em;">
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
	// Toggle Filter Panel Ringkasan Keuangan
	function toggleRkFilter() {
		var panel = document.getElementById('rk-filter-panel');
		var btn = document.getElementById('btn-toggle-rk-filter');
		if (!panel) return;

		if (panel.style.display === 'none' || panel.classList.contains('hidden')) {
			panel.style.display = 'block';
			panel.classList.remove('hidden');
			if (btn) btn.classList.add('is-active');
		} else {
			panel.style.display = 'none';
			panel.classList.add('hidden');
			if (btn && !{{ $isFilterActive ? 'true' : 'false' }}) {
				btn.classList.remove('is-active');
			}
		}
	}

	function handleRkPeriodChange(period) {
		const today = new Date();
		const pad = (n) => String(n).padStart(2, '0');
		const formatDate = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;

		let start = new Date();
		let end = new Date();

		if (period === 'today') {
			// today
		} else if (period === 'week') {
			const day = today.getDay();
			const diff = today.getDate() - day + (day === 0 ? -6 : 1);
			start = new Date(today.setDate(diff));
			end = new Date(start);
			end.setDate(start.getDate() + 6);
		} else if (period === 'month') {
			start = new Date(today.getFullYear(), today.getMonth(), 1);
			end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
		} else if (period === 'year') {
			start = new Date(today.getFullYear(), 0, 1);
			end = new Date(today.getFullYear(), 11, 31);
		} else if (period === 'all') {
			start = new Date(2020, 0, 1);
			end = new Date();
		} else if (period === 'custom') {
			var startInput = document.getElementById('rk_filter_start_date');
			if (startInput) startInput.focus();
			return;
		}

		var startEl = document.getElementById('rk_filter_start_date');
		var endEl = document.getElementById('rk_filter_end_date');
		if (startEl) startEl.value = formatDate(start);
		if (endEl) endEl.value = formatDate(end);
	}

	function markRkPeriodAsCustom() {
		var periodSelect = document.getElementById('rk_filter_period');
		if (periodSelect) {
			periodSelect.value = 'custom';
		}
	}

	document.addEventListener('DOMContentLoaded', function () {
		if (typeof lucide !== 'undefined') {
			lucide.createIcons();
		}
	});
</script>
@endpush
