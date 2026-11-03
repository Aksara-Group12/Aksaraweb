@extends('layouts.horizon')

@section('page_title', 'Dashboard')
@section('page_subtitle', 'Gambaran singkat performa penjualan dan ketersediaan stok hari ini.')

@section('content')
	@php
		$salesPerHourData = json_encode($salesPerHour->pluck('amount')->all());
		$salesPerHourLabels = json_encode($salesPerHour->pluck('hour')->all());
	@endphp

	<style>
		.shadcn-container {
			max-width: 100%;
			padding: 4px 0 24px 0;
			font-family: 'Geist', 'Geist Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
		}

		.shadcn-container h1,
		.shadcn-container h2,
		.shadcn-container h3,
		.shadcn-container h4,
		.shadcn-container h5,
		.shadcn-container h6,
		.shadcn-container p,
		.shadcn-container span,
		.shadcn-container button,
		.shadcn-container input,
		.shadcn-container select {
			font-family: 'Geist', 'Geist Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
		}

		/* Top Action & Filter Header Bar */
		.shadcn-action-bar {
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

		/* 4 Top KPI Cards Grid (Format 2x2) */
		.shadcn-cards-grid {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 16px;
			margin-bottom: 22px;
		}

		.shadcn-card {
			background: #FFFFFF;
			border: 1px solid #E4E4E7;
			border-radius: 12px;
			padding: 20px 22px;
			box-shadow: 0 1px 3px rgba(0,0,0,0.02);
			transition: border-color 0.25s ease, box-shadow 0.25s ease;
			cursor: default;
		}

		/* Efek Bersinar Murni (Tanpa Translasi / Pergerakan Ke Atas) */
		.shadcn-card:hover {
			border-color: rgba(197, 160, 89, 0.55);
			box-shadow: 0 0 18px rgba(197, 160, 89, 0.22), 0 2px 8px rgba(15, 34, 61, 0.04);
		}

		.shadcn-card-top {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 10px;
		}

		.shadcn-card-label {
			font-size: 0.85rem;
			font-weight: 500;
			color: #09090B;
			margin: 0;
		}

		.shadcn-card-icon {
			color: #71717A;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.shadcn-card-icon i,
		.shadcn-card-icon svg {
			width: 16px;
			height: 16px;
		}

		.shadcn-card-value {
			font-size: 1.65rem;
			font-weight: 700;
			color: #09090B;
			letter-spacing: -0.025em;
			margin: 0 0 4px 0;
			line-height: 1.2;
		}

		.shadcn-card-subtext {
			font-size: 0.75rem;
			color: #71717A;
			margin: 0;
		}

		/* Panel Card Style */
		.shadcn-panel-card {
			background: #FFFFFF;
			border: 1px solid #E4E4E7;
			border-radius: 12px;
			padding: 24px;
			box-shadow: 0 1px 3px rgba(0,0,0,0.02);
			transition: border-color 0.25s ease, box-shadow 0.25s ease;
			display: flex;
			flex-direction: column;
		}

		/* Efek Bersinar Murni (Tanpa Translasi / Pergerakan Ke Atas) */
		.shadcn-panel-card:hover {
			border-color: rgba(197, 160, 89, 0.45);
			box-shadow: 0 0 18px rgba(197, 160, 89, 0.16), 0 2px 8px rgba(15, 34, 61, 0.03);
		}

		.shadcn-panel-header {
			margin-bottom: 20px;
		}

		.shadcn-panel-title {
			font-size: 1.05rem;
			font-weight: 700;
			color: #09090B;
			margin: 0;
			letter-spacing: -0.02em;
		}

		.shadcn-panel-subtitle {
			font-size: 0.8rem;
			color: #71717A;
			margin: 4px 0 0 0;
			line-height: 1.4;
		}

		/* 3-Column Grid untuk Card-card di Bawah Chart */
		.shadcn-grid-3 {
			display: grid;
			grid-template-columns: repeat(3, 1fr);
			gap: 16px;
			margin-bottom: 22px;
		}

		/* List Items */
		.shadcn-list-item {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 12px 14px;
			border-radius: 8px;
			background: #F8FAFC;
			border: 1px solid #E2E8F0;
			margin-bottom: 8px;
			transition: border-color 0.2s ease, box-shadow 0.2s ease;
		}

		.shadcn-list-item:hover {
			border-color: rgba(197, 160, 89, 0.4);
			box-shadow: 0 0 12px rgba(197, 160, 89, 0.12);
		}

		.customer-avatar {
			width: 36px;
			height: 36px;
			border-radius: 8px;
			background: #0F223D;
			color: #FFFFFF;
			display: flex;
			align-items: center;
			justify-content: center;
			font-weight: 700;
			font-size: 0.8rem;
			flex-shrink: 0;
		}

		@media (max-width: 1024px) {
			.shadcn-grid-3 {
				grid-template-columns: repeat(2, 1fr);
			}
		}

		@media (max-width: 868px) {
			.shadcn-cards-grid {
				grid-template-columns: 1fr;
			}
			.shadcn-grid-3 {
				grid-template-columns: 1fr;
			}
		}
	</style>

	<div class="shadcn-container">
		<!-- Top Action & Filter Header Bar -->
		<div class="shadcn-action-bar">
			<div class="flex items-center gap-3">
				@if($isFilterActive)
					<div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-full text-xs font-medium text-blue-800">
						<span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
						<span>Filter Aktif: <strong>{{ $period === 'custom' ? 'Rentang Khusus' : ($period === 'month' ? 'Bulan Ini' : ($period === 'today' ? 'Hari Ini' : ($period === 'week' ? 'Minggu Ini' : ($period === 'year' ? 'Tahun Ini' : 'Semua Data')))) }}</strong></span>
						@if($startDate && $endDate)
							<span class="text-blue-400">•</span>
							<span>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</span>
						@endif
					</div>
				@endif
			</div>

			<div class="filter-action-wrapper">
				<button type="button" id="btn-toggle-owner-filter" class="btn-filter-trigger {{ $isFilterActive ? 'is-active' : '' }}" onclick="toggleOwnerDashboardFilter()" title="Buka / Tutup Panel Filter">
					<i data-lucide="filter" class="w-4 h-4"></i>
					<span>Filter</span>
					@if($isFilterActive)
						<span class="inline-block w-2 h-2 rounded-full bg-amber-300 ml-0.5"></span>
					@endif
				</button>
			</div>
		</div>

		<!-- Collapsible Filter Panel -->
		<div id="owner-dashboard-filter-panel" class="superadmin-filter-panel" style="{{ $isFilterActive ? 'display: block;' : 'display: none;' }}">
			<form method="GET" action="{{ route('dashboard') }}" id="owner-dashboard-filter-form">
				<div class="filter-grid-row-bottom">
					<div class="filter-field-group">
						<label for="owner_filter_period" class="filter-field-label">Periode Waktu</label>
						<select name="period" id="owner_filter_period" class="filter-select" onchange="handleOwnerPeriodChange(this.value)">
							<option value="month" {{ ($period ?? 'month') === 'month' ? 'selected' : '' }}>Bulan Ini</option>
							<option value="today" {{ ($period ?? '') === 'today' ? 'selected' : '' }}>Hari Ini</option>
							<option value="week" {{ ($period ?? '') === 'week' ? 'selected' : '' }}>Minggu Ini</option>
							<option value="year" {{ ($period ?? '') === 'year' ? 'selected' : '' }}>Tahun Ini</option>
							<option value="all" {{ ($period ?? '') === 'all' ? 'selected' : '' }}>Semua Data</option>
							<option value="custom" {{ ($period ?? '') === 'custom' ? 'selected' : '' }}>Rentang Khusus</option>
						</select>
					</div>

					<div class="filter-field-group">
						<label for="owner_filter_start_date" class="filter-field-label">Tanggal Awal</label>
						<input type="date" name="start_date" id="owner_filter_start_date" value="{{ $startDate ?? '' }}" class="filter-input-date" onchange="markOwnerPeriodAsCustom()">
					</div>

					<div class="filter-field-group">
						<label for="owner_filter_end_date" class="filter-field-label">Tanggal Akhir</label>
						<input type="date" name="end_date" id="owner_filter_end_date" value="{{ $endDate ?? '' }}" class="filter-input-date" onchange="markOwnerPeriodAsCustom()">
					</div>

					<div class="filter-buttons-group">
						<a href="{{ route('dashboard') }}" class="btn-filter-reset" title="Kembalikan Filter ke Awal">
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

		<!-- 4 Kartu Metrik Utama (Gaya Shadcn UI Superadmin, Grid 2x2) -->
		<div class="shadcn-cards-grid">
			<!-- Card 1: Total Pendapatan -->
			<div class="shadcn-card">
				<div class="shadcn-card-top">
					<span class="shadcn-card-label">Total Pendapatan</span>
					<div class="shadcn-card-icon">
						<i data-lucide="dollar-sign"></i>
					</div>
				</div>
				<div class="shadcn-card-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
				<p class="shadcn-card-subtext">
					+Rp {{ number_format($todayRevenue, 0, ',', '.') }} pendapatan hari ini
				</p>
			</div>

			<!-- Card 2: Estimasi Profit -->
			<div class="shadcn-card">
				<div class="shadcn-card-top">
					<span class="shadcn-card-label">Estimasi Profit</span>
					<div class="shadcn-card-icon">
						<i data-lucide="trending-up"></i>
					</div>
				</div>
				<div class="shadcn-card-value">Rp {{ number_format($estimatedProfit, 0, ',', '.') }}</div>
				<p class="shadcn-card-subtext">
					Profit Margin {{ $totalRevenue > 0 ? round(($estimatedProfit / $totalRevenue) * 100, 1) : 0 }}% (Penjualan - HPP - Pengeluaran)
				</p>
			</div>

			<!-- Card 3: Total Pengeluaran -->
			<div class="shadcn-card">
				<div class="shadcn-card-top">
					<span class="shadcn-card-label">Total Pengeluaran</span>
					<div class="shadcn-card-icon">
						<i data-lucide="credit-card"></i>
					</div>
				</div>
				<div class="shadcn-card-value">Rp {{ number_format($expensesByCategory->sum('total'), 0, ',', '.') }}</div>
				<p class="shadcn-card-subtext">
					{{ $expensesByCategory->count() }} kategori pengeluaran • Tertinggi: {{ $expensesByCategory->first()->kategori ?? '-' }}
				</p>
			</div>

			<!-- Card 4: Stok Menipis -->
			<div class="shadcn-card">
				<div class="shadcn-card-top">
					<span class="shadcn-card-label">Stok Menipis</span>
					<div class="shadcn-card-icon">
						<i data-lucide="alert-triangle"></i>
					</div>
				</div>
				<div class="shadcn-card-value">{{ $lowStockProducts->count() }} Produk</div>
				<p class="shadcn-card-subtext">
					{{ $lowStockProducts->count() > 0 ? 'Perlu restock inventori segera' : 'Semua stok dalam batas aman' }}
				</p>
			</div>
		</div>

		<!-- 1. Full-Width Section: Roda Penjualan Per Jam (Grafik Luas & Lega) -->
		<div class="shadcn-panel-card" style="margin-bottom: 22px;">
			<div class="shadcn-panel-header">
				<h3 class="shadcn-panel-title">Roda Penjualan Per Jam</h3>
				<p class="shadcn-panel-subtitle">Aktivitas dan volume transaksi penjualan per jam hari ini secara menyeluruh.</p>
			</div>
			<div style="position: relative; width: 100%; height: 320px;">
				<canvas id="hourlySalesChart"></canvas>
			</div>
		</div>

		<!-- 2. Row Tengah (Grid 3 Kolom): Metode Pembayaran + Pengeluaran Per Kategori + Pelanggan Teratas -->
		<div class="shadcn-grid-3">
			<!-- Card 1: Metode Pembayaran (Sekarang Disesuaikan di Bawah Chart) -->
			<div class="shadcn-panel-card">
				<div class="shadcn-panel-header">
					<h3 class="shadcn-panel-title">Metode Pembayaran</h3>
					<p class="shadcn-panel-subtitle">Distribusi transaksi berdasarkan metode bayar periode ini.</p>
				</div>
				<ul style="list-style: none; padding: 0; margin: 0; flex: 1;">
					@forelse ($paymentMethods as $method)
						<li class="shadcn-list-item">
							<div>
								<p style="font-size: 0.85rem; font-weight: 600; color: #09090B; margin: 0;">{{ ucfirst(str_replace('-', ' ', $method->metode_pembayaran)) }}</p>
								<p style="font-size: 0.725rem; color: #71717A; margin: 2px 0 0 0;">{{ $method->count }} transaksi</p>
							</div>
							<div style="text-align: right;">
								<p style="font-size: 0.875rem; font-weight: 700; color: #09090B; margin: 0;">Rp {{ number_format($method->total, 0, ',', '.') }}</p>
							</div>
						</li>
					@empty
						<li style="padding: 24px; text-align: center; color: #71717A; font-size: 0.825rem;">Belum ada data pembayaran</li>
					@endforelse
				</ul>
			</div>

			<!-- Card 2: Pengeluaran Per Kategori -->
			<div class="shadcn-panel-card">
				<div class="shadcn-panel-header">
					<h3 class="shadcn-panel-title">Pengeluaran Per Kategori</h3>
					<p class="shadcn-panel-subtitle">Rincian beban biaya dan pengeluaran operasional toko.</p>
				</div>
				<ul style="list-style: none; padding: 0; margin: 0; max-height: 290px; overflow-y: auto; flex: 1;">
					@forelse ($expensesByCategory as $expense)
						<li class="shadcn-list-item">
							<div>
								<p style="font-size: 0.85rem; font-weight: 600; color: #09090B; margin: 0;">{{ $expense->kategori }}</p>
							</div>
							<div style="text-align: right;">
								<p style="font-size: 0.875rem; font-weight: 700; color: #DC2626; margin: 0;">Rp {{ number_format($expense->total, 0, ',', '.') }}</p>
							</div>
						</li>
					@empty
						<li style="padding: 24px; text-align: center; color: #71717A; font-size: 0.825rem;">Belum ada pengeluaran</li>
					@endforelse
				</ul>
			</div>

			<!-- Card 3: Pelanggan Teratas (Top 10) -->
			<div class="shadcn-panel-card">
				<div class="shadcn-panel-header">
					<h3 class="shadcn-panel-title">Pelanggan Teratas (Top 10)</h3>
					<p class="shadcn-panel-subtitle">Pelanggan dengan total transaksi belanja tertinggi.</p>
				</div>
				<ul style="list-style: none; padding: 0; margin: 0; max-height: 290px; overflow-y: auto; flex: 1;">
					@forelse ($topCustomers as $customer)
						<li class="shadcn-list-item">
							<div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
								<div class="customer-avatar">{{ strtoupper(substr($customer->customer_name, 0, 1)) }}</div>
								<div style="min-width: 0;">
									<p style="font-size: 0.825rem; font-weight: 600; color: #09090B; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $customer->customer_name }}</p>
									<p style="font-size: 0.72rem; color: #71717A; margin: 2px 0 0 0;">{{ $customer->lokasi ?? '-' }}</p>
								</div>
							</div>
							<div style="text-align: right; flex-shrink: 0;">
								<p style="font-size: 0.85rem; font-weight: 700; color: #059669; margin: 0;">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</p>
								<p style="font-size: 0.7rem; color: #71717A; margin: 2px 0 0 0;">{{ $customer->total_transactions }} trx</p>
							</div>
						</li>
					@empty
						<li style="padding: 24px; text-align: center; color: #71717A; font-size: 0.825rem;">Belum ada data pelanggan</li>
					@endforelse
				</ul>
			</div>
		</div>

		<!-- 3. Row Bawah (Grid 3 Kolom): Top 5 Produk Terlaris + Produk Restock + Pengeluaran Terbaru -->
		<div class="shadcn-grid-3">
			<!-- Card 4: Top 5 Produk Terlaris -->
			<div class="shadcn-panel-card">
				<div class="shadcn-panel-header">
					<h3 class="shadcn-panel-title">Top 5 Produk Terlaris</h3>
					<p class="shadcn-panel-subtitle">Berdasarkan kuantitas terjual periode ini.</p>
				</div>
				<ul style="list-style: none; padding: 0; margin: 0; flex: 1;">
					@forelse ($topProducts as $product)
						<li class="shadcn-list-item">
							<div>
								<p style="font-size: 0.85rem; font-weight: 600; color: #09090B; margin: 0;">{{ $product->nama_produk }}</p>
								<p style="font-size: 0.725rem; color: #71717A; margin: 2px 0 0 0;">{{ $product->total_qty }} terjual</p>
							</div>
							<div style="text-align: right;">
								<p style="font-size: 0.875rem; font-weight: 700; color: #09090B; margin: 0;">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
							</div>
						</li>
					@empty
						<li style="padding: 20px; text-align: center; color: #71717A; font-size: 0.825rem;">Belum ada data penjualan</li>
					@endforelse
				</ul>
			</div>

			<!-- Card 5: Produk Stok Menipis -->
			<div class="shadcn-panel-card">
				<div class="shadcn-panel-header">
					<h3 class="shadcn-panel-title">Produk Perlu Restock</h3>
					<p class="shadcn-panel-subtitle">Stok saat ini di bawah batas minimum toko.</p>
				</div>
				<ul style="list-style: none; padding: 0; margin: 0; flex: 1;">
					@forelse ($lowStockProducts as $product)
						<li class="shadcn-list-item">
							<div>
								<p style="font-size: 0.85rem; font-weight: 600; color: #09090B; margin: 0;">{{ $product->nama_produk }}</p>
								<p style="font-size: 0.725rem; color: #71717A; margin: 2px 0 0 0;">Kategori: {{ $product->kategori }}</p>
							</div>
							<div style="text-align: right;">
								<p style="font-size: 0.875rem; font-weight: 700; color: #DC2626; margin: 0;">Sisa: {{ $product->stock }}</p>
								<p style="font-size: 0.7rem; color: #71717A; margin: 2px 0 0 0;">Min: {{ $product->min_stock }}</p>
							</div>
						</li>
					@empty
						<li style="padding: 20px; text-align: center; color: #059669; font-size: 0.825rem; font-weight: 500;">✓ Semua stok aman terkendali</li>
					@endforelse
				</ul>
			</div>

			<!-- Card 6: Rincian Pengeluaran Terbaru -->
			<div class="shadcn-panel-card">
				<div class="shadcn-panel-header">
					<h3 class="shadcn-panel-title">Pengeluaran Terbaru</h3>
					<p class="shadcn-panel-subtitle">5 catatan pengeluaran kas toko terakhir.</p>
				</div>
				<ul style="list-style: none; padding: 0; margin: 0; flex: 1;">
					@forelse ($recentExpenses as $expense)
						<li class="shadcn-list-item">
							<div>
								<p style="font-size: 0.85rem; font-weight: 600; color: #09090B; margin: 0;">{{ $expense->keterangan }}</p>
								<p style="font-size: 0.725rem; color: #71717A; margin: 2px 0 0 0;">{{ $expense->kategori }} • {{ $expense->tanggal ? $expense->tanggal->format('d/m/Y') : '-' }}</p>
							</div>
							<div style="text-align: right;">
								<p style="font-size: 0.875rem; font-weight: 700; color: #DC2626; margin: 0;">Rp {{ number_format($expense->nominal, 0, ',', '.') }}</p>
							</div>
						</li>
					@empty
						<li style="padding: 20px; text-align: center; color: #71717A; font-size: 0.825rem;">Belum ada data pengeluaran</li>
					@endforelse
				</ul>
			</div>
		</div>
	</div>

	{{-- Chart.js untuk grafik penjualan --}}
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Period custom box logic
		var periodSelect = document.getElementById('db-period-select');
		var customRange = document.getElementById('db-custom-range');
		
		if (periodSelect && customRange) {
			periodSelect.addEventListener('change', function () {
				if (this.value === 'custom') {
					customRange.classList.add('active');
				} else {
					customRange.classList.remove('active');
					this.form.submit();
				}
			});
		}

		// Hourly Sales Chart (Full-Width Responsive)
		var hourlyCanvas = document.getElementById('hourlySalesChart');
		if (hourlyCanvas) {
			var hourlyCtx = hourlyCanvas.getContext('2d');
			new Chart(hourlyCtx, {
				type: 'line',
				data: {
					labels: {!! $salesPerHourLabels !!},
					datasets: [{
						label: 'Penjualan (Rp)',
						data: {!! $salesPerHourData !!},
						backgroundColor: 'rgba(15, 34, 61, 0.05)',
						borderColor: '#0F223D',
						borderWidth: 2,
						pointBackgroundColor: '#0F223D',
						pointBorderColor: '#FFFFFF',
						pointBorderWidth: 2,
						pointRadius: 4,
						pointHoverRadius: 6,
						tension: 0.3,
						fill: true
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero: true,
								fontColor: '#71717A',
								fontSize: 11,
								callback: function(value) {
									return 'Rp ' + Number(value).toLocaleString('id-ID');
								}
							},
							gridLines: {
								color: '#F4F4F5',
								drawBorder: false
							}
						}],
						xAxes: [{
							ticks: {
								fontColor: '#71717A',
								fontSize: 11
							},
							gridLines: {
								display: false
							}
						}]
					},
					tooltips: {
						backgroundColor: '#09090B',
						titleFontColor: '#FFFFFF',
						bodyFontColor: '#FFFFFF',
						cornerRadius: 8,
						xPadding: 12,
						yPadding: 10,
						callbacks: {
							label: function(tooltipItem) {
								return 'Penjualan: Rp ' + Number(tooltipItem.yLabel).toLocaleString('id-ID');
							}
						}
					}
				}
			});
		}

		if (typeof lucide !== 'undefined') {
			lucide.createIcons();
		}
	});

	// Toggle Filter Panel Owner Dashboard
	function toggleOwnerDashboardFilter() {
		var panel = document.getElementById('owner-dashboard-filter-panel');
		var btn = document.getElementById('btn-toggle-owner-filter');
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

	function handleOwnerPeriodChange(period) {
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
			var startInput = document.getElementById('owner_filter_start_date');
			if (startInput) startInput.focus();
			return;
		}

		var startEl = document.getElementById('owner_filter_start_date');
		var endEl = document.getElementById('owner_filter_end_date');
		if (startEl) startEl.value = formatDate(start);
		if (endEl) endEl.value = formatDate(end);
	}

	function markOwnerPeriodAsCustom() {
		var periodSelect = document.getElementById('owner_filter_period');
		if (periodSelect) {
			periodSelect.value = 'custom';
		}
	}
	</script>
@endsection
