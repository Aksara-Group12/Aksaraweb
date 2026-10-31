@extends('layouts.horizon')

@section('page_title', 'Dashboard')

@section('header_content')
	<div>
		<h1 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.35rem; font-weight: 500; color: #0F223D; margin: 0; line-height: 1.2;">
			Ringkasan Penjualan & Stok
		</h1>
		<p style="font-size: 0.8rem; color: #64748B; margin: 2px 0 0 0;">
			Gambaran performa penjualan, ketersediaan inventaris, dan pelanggan aktif.
		</p>
	</div>
@endsection

@section('content')
	<style>
		/* Header Section */
		.dashboard-header {
			margin-bottom: 28px;
		}

		.header-title-block {
			margin-bottom: 18px;
		}

		.header-title-block h2 {
			font-family: 'Newsreader', Georgia, serif;
			font-size: 2.25rem;
			font-weight: 500;
			margin: 0 0 6px 0;
			color: #0F223D;
			letter-spacing: -0.01em;
			line-height: 1.2;
		}

		.header-title-block p {
			font-size: 0.95rem;
			color: #64748B;
			margin: 0;
		}

		.header-controls {
			display: flex;
			align-items: center;
			gap: 12px;
			flex-wrap: wrap;
		}

		.period-select {
			border-radius: 10px;
			background: #FFFFFF;
			border: 1px solid #CBD5E1;
			color: #0F223D;
			font-family: inherit;
			font-size: 0.825rem;
			padding: 9px 14px;
			outline: none;
			cursor: pointer;
			transition: all 0.15s ease;
			font-weight: 600;
			box-shadow: 0 1px 3px rgba(15, 34, 61, 0.04);
		}

		.period-select:hover {
			border-color: rgba(197, 160, 89, 0.6);
		}

		.period-select:focus {
			border-color: #0F223D;
			box-shadow: 0 0 0 3px rgba(15, 34, 61, 0.08);
		}

		.custom-date-range {
			display: none;
			align-items: center;
			gap: 8px;
		}

		.custom-date-range.active {
			display: flex;
		}

		.date-input {
			background: #FFFFFF;
			border-radius: 10px;
			border: 1px solid #CBD5E1;
			color: #0F223D;
			font-family: inherit;
			font-size: 0.825rem;
			padding: 8px 12px;
			outline: none;
			transition: all 0.15s ease;
			box-shadow: 0 1px 3px rgba(15, 34, 61, 0.04);
		}

		.date-input:focus {
			border-color: #0F223D;
			box-shadow: 0 0 0 3px rgba(15, 34, 61, 0.08);
		}

		.date-separator {
			font-size: 0.8rem;
			color: #64748B;
			font-weight: 600;
		}

		.apply-btn {
			border-radius: 10px;
			border: none;
			background: #0F223D;
			color: #FFFFFF;
			padding: 9px 16px;
			font-family: inherit;
			font-size: 0.825rem;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.15s ease;
			display: flex;
			align-items: center;
			gap: 6px;
			box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15);
		}

		.apply-btn:hover {
			background: #162C50;
			transform: translateY(-1px);
			box-shadow: 0 4px 12px rgba(15, 34, 61, 0.2);
		}

		/* KPI Card Grid: 4 Kolom Selaras Horizon Luxury */
		.kpi-card-wrapper {
			display: grid;
			grid-template-columns: repeat(4, minmax(0, 1fr));
			gap: 20px;
			margin-bottom: 28px;
		}

		.kpi-card {
			position: relative;
			background: #FFFFFF;
			border-radius: 18px;
			padding: 22px 24px;
			border: 1px solid rgba(226, 232, 240, 0.95);
			box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04), 0 2px 4px -2px rgba(15, 34, 61, 0.02);
			transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
			overflow: hidden;
		}

		.kpi-card:hover {
			transform: translateY(-3px);
			border-color: rgba(197, 160, 89, 0.4);
			box-shadow: 0 12px 24px -4px rgba(15, 34, 61, 0.08);
		}

		.kpi-card-inner {
			position: relative;
			z-index: 2;
		}

		.kpi-card-header-section {
			display: flex;
			justify-content: space-between;
			align-items: center;
			gap: 14px;
			margin-bottom: 14px;
		}

		.kpi-card-title-section {
			display: flex;
			align-items: center;
			gap: 12px;
			flex: 1;
		}

		.kpi-icon-badge {
			width: 38px;
			height: 38px;
			border-radius: 10px;
			display: flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
			background: rgba(197, 160, 89, 0.1);
			color: #C5A059;
			border: 1px solid rgba(197, 160, 89, 0.2);
			transition: all 0.2s ease;
		}

		.kpi-card:hover .kpi-icon-badge {
			background: rgba(197, 160, 89, 0.18);
			border-color: rgba(197, 160, 89, 0.35);
			transform: scale(1.03);
		}

		.kpi-icon-badge i,
		.kpi-icon-badge svg {
			width: 19px;
			height: 19px;
			stroke-width: 2;
		}

		.kpi-card-title {
			font-size: 0.75rem;
			font-weight: 700;
			letter-spacing: 0.06em;
			text-transform: uppercase;
			color: #64748B;
			margin: 0;
			line-height: 1.2;
		}

		.kpi-card-value-section {
			margin-bottom: 16px;
		}

		.kpi-card-value {
			font-size: 2.2rem;
			font-weight: 800;
			color: #0F223D;
			margin: 0;
			line-height: 1.1;
			letter-spacing: -0.02em;
		}

		.kpi-card-value-sub {
			font-size: 0.75rem;
			margin-top: 4px;
			color: #94A3B8;
		}

		.kpi-card-meta {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 14px;
			padding-top: 14px;
			border-top: 1px solid #F1F5F9;
		}

		.kpi-meta-item {
			display: flex;
			flex-direction: column;
			gap: 3px;
		}

		.kpi-meta-label {
			font-size: 0.68rem;
			font-weight: 600;
			text-transform: uppercase;
			color: #94A3B8;
			letter-spacing: 0.04em;
		}

		.kpi-meta-value {
			font-size: 0.95rem;
			font-weight: 700;
			color: #0F223D;
			margin: 0;
			line-height: 1.2;
		}

		/* Status Badge Shadcn Minimalist */
		.kpi-status-badge {
			display: inline-flex;
			align-items: center;
			gap: 5px;
			padding: 2px 8px;
			border-radius: 6px;
			font-size: 0.72rem;
			font-weight: 600;
			width: fit-content;
			line-height: 1.4;
			margin-top: 2px;
		}

		.status-indicator-dot {
			width: 6px;
			height: 6px;
			border-radius: 50%;
			flex-shrink: 0;
		}

		.kpi-status-active {
			background: #ECFDF5;
			color: #065F46;
			border: 1px solid #A7F3D0;
		}

		.kpi-status-active .status-indicator-dot {
			background: #10B981;
		}

		.kpi-status-wait {
			background: #F8FAFC;
			color: #64748B;
			border: 1px solid #E2E8F0;
		}

		.kpi-status-wait .status-indicator-dot {
			background: #94A3B8;
		}

		/* Secondary Grid Layout */
		.secondary-grid {
			display: grid;
			grid-template-columns: 1fr;
			gap: 24px;
			margin-bottom: 28px;
		}

		.secondary-card {
			position: relative;
			border-radius: 18px;
			padding: 24px;
			background: #FFFFFF;
			border: 1px solid rgba(226, 232, 240, 0.95);
			box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04);
		}

		.card-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 20px;
			padding-bottom: 14px;
			border-bottom: 1px solid #F1F5F9;
		}

		.card-header-title {
			display: flex;
			flex-direction: column;
			gap: 3px;
		}

		.card-title {
			font-family: 'Newsreader', Georgia, serif;
			font-size: 1.35rem;
			font-weight: 600;
			color: #0F223D;
			margin: 0;
			line-height: 1.2;
		}

		.card-subtitle {
			font-size: 0.8rem;
			color: #64748B;
			margin: 0;
		}

		.card-badge {
			display: inline-flex;
			align-items: center;
			gap: 6px;
			background: rgba(197, 160, 89, 0.12);
			color: #9E7E36;
			padding: 4px 12px;
			border-radius: 9999px;
			font-size: 0.7rem;
			font-weight: 700;
			text-transform: uppercase;
			border: 1px solid rgba(197, 160, 89, 0.25);
			letter-spacing: 0.04em;
		}

		.chart-container {
			position: relative;
			height: 300px;
			width: 100%;
		}

		/* Bottom Dual Grid: Top Customer & Top Produk */
		.bottom-dual-grid {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 24px;
			margin-bottom: 32px;
		}

		.customer-list, .product-list {
			list-style: none;
			padding: 0;
			margin: 0;
			display: flex;
			flex-direction: column;
			gap: 10px;
		}

		.customer-item, .product-item {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 12px 16px;
			border-radius: 12px;
			background: #FAF8F5;
			border: 1px solid #E2E8F0;
			transition: all 0.15s ease;
		}

		.customer-item:hover, .product-item:hover {
			background: #FFFFFF;
			border-color: rgba(197, 160, 89, 0.4);
			box-shadow: 0 4px 12px rgba(15, 34, 61, 0.04);
			transform: translateX(2px);
		}

		.customer-info, .product-info {
			display: flex;
			align-items: center;
			gap: 12px;
			flex: 1;
			min-width: 0;
		}

		.customer-avatar, .product-rank-badge {
			width: 38px;
			height: 38px;
			border-radius: 10px;
			background: #0F223D;
			color: #C5A059;
			display: flex;
			align-items: center;
			justify-content: center;
			font-weight: 700;
			font-size: 0.85rem;
			flex-shrink: 0;
		}

		.customer-details, .product-details {
			display: flex;
			flex-direction: column;
			gap: 2px;
			min-width: 0;
		}

		.customer-name, .product-name {
			font-size: 0.875rem;
			font-weight: 600;
			color: #0F223D;
			margin: 0;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.customer-meta, .product-meta {
			font-size: 0.75rem;
			color: #64748B;
			margin: 0;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.customer-stats, .product-stats {
			display: flex;
			flex-direction: column;
			align-items: flex-end;
			gap: 3px;
			flex-shrink: 0;
		}

		.customer-amount, .product-amount {
			font-size: 0.95rem;
			font-weight: 700;
			color: #0F223D;
			margin: 0;
		}

		.customer-transaction, .product-sales-qty {
			font-size: 0.75rem;
			color: #64748B;
			margin: 0;
		}

		.empty-state {
			text-align: center;
			padding: 36px 20px;
		}

		.empty-state-title {
			font-size: 0.95rem;
			font-weight: 600;
			color: #0F223D;
			margin: 0 0 6px 0;
		}

		.empty-state-text {
			font-size: 0.8rem;
			color: #64748B;
			margin: 0;
		}

		/* Responsive Rules */
		@media (max-width: 1200px) {
			.kpi-card-wrapper {
				grid-template-columns: repeat(2, 1fr);
				gap: 16px;
			}
			.bottom-dual-grid {
				grid-template-columns: 1fr;
				gap: 20px;
			}
		}

		@media (max-width: 768px) {
			.header-controls {
				flex-direction: column;
				align-items: stretch;
			}

			.period-select, .date-input, .apply-btn {
				width: 100%;
			}

			.custom-date-range {
				flex-direction: column;
			}

			.kpi-card-wrapper {
				grid-template-columns: 1fr;
				gap: 14px;
			}

			.kpi-card {
				padding: 18px 20px;
			}

			.kpi-card-value {
				font-size: 1.85rem;
			}

			.bottom-dual-grid {
				grid-template-columns: 1fr;
				gap: 16px;
			}
		}
	</style>

	<div class="dashboard-controls-bar" style="display:flex; justify-content:flex-start; align-items:center; margin-bottom: 22px;">
		<div class="header-controls">
			<select name="period" id="db-period-select" class="period-select" onchange="changePeriod(this.value)">
				<option value="today" {{ ($period ?? 'month') === 'today' ? 'selected' : '' }}>Hari Ini</option>
				<option value="week" {{ ($period ?? 'month') === 'week' ? 'selected' : '' }}>Minggu Ini</option>
				<option value="month" {{ ($period ?? 'month') === 'month' ? 'selected' : '' }}>Bulan Ini</option>
				<option value="year" {{ ($period ?? 'month') === 'year' ? 'selected' : '' }}>Tahun Ini</option>
				<option value="all" {{ ($period ?? 'month') === 'all' ? 'selected' : '' }}>Semua Data</option>
				<option value="custom" {{ ($period ?? 'month') === 'custom' ? 'selected' : '' }}>Rentang Khusus</option>
			</select>

			<div id="db-custom-range" class="custom-date-range {{ ($period ?? 'month') === 'custom' ? 'active' : '' }}">
				<input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="date-input" id="start_date_input">
				<span class="date-separator">s/d</span>
				<input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="date-input" id="end_date_input">
				<button type="button" class="apply-btn" onclick="applyCustomDate()">
					<i data-lucide="filter" style="width: 14px; height: 14px;"></i>
					<span>Terapkan Filter</span>
				</button>
			</div>
		</div>
	</div>

	<!-- 4 KPI Cards: Sesuai Standar Superadmin -->
	<section class="kpi-card-wrapper">
		<!-- Card 1: Total Penjualan -->
		<div class="kpi-card">
			<div class="kpi-card-inner">
				<div class="kpi-card-header-section">
					<div class="kpi-card-title-section">
						<div class="kpi-icon-badge">
							<i data-lucide="dollar-sign"></i>
						</div>
						<p class="kpi-card-title">Total Penjualan</p>
					</div>
				</div>

				<div class="kpi-card-value-section">
					<p class="kpi-card-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
					<p class="kpi-card-value-sub">Performa Periode Terpilih</p>
				</div>

				<div class="kpi-card-meta">
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Hari Ini</p>
						<p class="kpi-meta-value">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
					</div>
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Status</p>
						<span class="kpi-status-badge {{ $totalRevenue > 0 ? 'kpi-status-active' : 'kpi-status-wait' }}">
							<span class="status-indicator-dot"></span>
							{{ $totalRevenue > 0 ? 'Aktif' : 'Pending' }}
						</span>
					</div>
				</div>
			</div>
		</div>

		<!-- Card 2: Transaksi Hari Ini -->
		<div class="kpi-card">
			<div class="kpi-card-inner">
				<div class="kpi-card-header-section">
					<div class="kpi-card-title-section">
						<div class="kpi-icon-badge">
							<i data-lucide="shopping-cart"></i>
						</div>
						<p class="kpi-card-title">Transaksi Hari Ini</p>
					</div>
				</div>

				<div class="kpi-card-value-section">
					<p class="kpi-card-value">{{ number_format($todayTransactions, 0, ',', '.') }}</p>
					<p class="kpi-card-value-sub">Total Penjualan Tercatat</p>
				</div>

				<div class="kpi-card-meta">
					<div class="kpi-meta-item" style="grid-column: 1 / -1;">
						<p class="kpi-meta-label">Performa</p>
						<p class="kpi-meta-value">{{ $todayTransactions }} Transaksi</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Card 3: Total Transaksi -->
		<div class="kpi-card">
			<div class="kpi-card-inner">
				<div class="kpi-card-header-section">
					<div class="kpi-card-title-section">
						<div class="kpi-icon-badge">
							<i data-lucide="arrow-left-right"></i>
						</div>
						<p class="kpi-card-title">Total Transaksi</p>
					</div>
				</div>

				<div class="kpi-card-value-section">
					<p class="kpi-card-value">{{ number_format($totalTransactions, 0, ',', '.') }}</p>
					<p class="kpi-card-value-sub">Arus Transaksi Periode</p>
				</div>

				<div class="kpi-card-meta">
					<div class="kpi-meta-item">
						<div style="display: flex; align-items: center; gap: 5px;">
							<span style="width: 6px; height: 6px; border-radius: 50%; background: #10B981;"></span>
							<p class="kpi-meta-label" style="margin: 0;">Jual</p>
						</div>
						<p class="kpi-meta-value">{{ $totalSalesTransactions }}</p>
					</div>
					<div class="kpi-meta-item">
						<div style="display: flex; align-items: center; gap: 5px;">
							<span style="width: 6px; height: 6px; border-radius: 50%; background: #EF4444;"></span>
							<p class="kpi-meta-label" style="margin: 0;">Beli</p>
						</div>
						<p class="kpi-meta-value" style="color: #EF4444;">{{ $totalTransactions - $totalSalesTransactions }}</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Card 4: Total Stok -->
		<div class="kpi-card">
			<div class="kpi-card-inner">
				<div class="kpi-card-header-section">
					<div class="kpi-card-title-section">
						<div class="kpi-icon-badge">
							<i data-lucide="package"></i>
						</div>
						<p class="kpi-card-title">Total Stok</p>
					</div>
				</div>

				<div class="kpi-card-value-section">
					<p class="kpi-card-value">{{ number_format($totalStockUnits, 0, ',', '.') }}</p>
					<p class="kpi-card-value-sub">Unit Tersedia di Toko</p>
				</div>

				<div class="kpi-card-meta">
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Master Produk</p>
						<p class="kpi-meta-value">{{ $totalProducts }} Item</p>
					</div>
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Status Stok</p>
						<span class="kpi-status-badge kpi-status-active">
							<span class="status-indicator-dot"></span>
							Tercatat
						</span>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Chart Section -->
	<section class="secondary-grid">
		<div class="secondary-card">
			<div class="card-header">
				<div class="card-header-title">
					<p class="card-title">Tren Penjualan 7 Hari Terakhir</p>
					<p class="card-subtitle">Pergerakan total nominal penjualan harian</p>
				</div>
				<span class="card-badge">Grafik Harian</span>
			</div>
			<div class="chart-container">
				<canvas id="revenueChart"></canvas>
			</div>
		</div>
	</section>

	<!-- Dual Grid: Top Customer & Top 5 Produk Terlaris -->
	<section class="bottom-dual-grid">
		<!-- Top Customer Card -->
		<div class="secondary-card">
			<div class="card-header">
				<div class="card-header-title">
					<p class="card-title">Top Pelanggan</p>
					<p class="card-subtitle">Pelanggan dengan total transaksi terbesar</p>
				</div>
				<span class="card-badge">Top Pelanggan</span>
			</div>
			<ul class="customer-list">
				@forelse ($topCustomers as $customer)
					<li class="customer-item">
						<div class="customer-info">
							<div class="customer-avatar">{{ strtoupper(substr($customer->customer_name, 0, 1)) }}</div>
							<div class="customer-details">
								<p class="customer-name">{{ $customer->customer_name }}</p>
								<p class="customer-meta">
									{{ $customer->lokasi ?? '-' }}
									@if ($customer->customer_contact)
										• {{ $customer->customer_contact }}
									@endif
								</p>
							</div>
						</div>
						<div class="customer-stats">
							<p class="customer-amount">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</p>
							<p class="customer-transaction">{{ $customer->total_transactions }} Transaksi</p>
						</div>
					</li>
				@empty
					<li>
						<div class="empty-state">
							<p class="empty-state-title">Belum ada data pelanggan</p>
							<p class="empty-state-text">Data akan tampil otomatis setelah ada transaksi penjualan yang menyertakan nama pelanggan.</p>
						</div>
					</li>
				@endforelse
			</ul>
		</div>

		<!-- Top 5 Produk Terlaris Card -->
		<div class="secondary-card">
			<div class="card-header">
				<div class="card-header-title">
					<p class="card-title">Top 5 Produk Terlaris</p>
					<p class="card-subtitle">Berdasarkan kuantitas terjual (tanpa void)</p>
				</div>
				<span class="card-badge">Produk Favorit</span>
			</div>
			<ul class="product-list">
				@forelse ($topProducts as $index => $product)
					<li class="product-item">
						<div class="product-info">
							<div class="product-rank-badge">#{{ $index + 1 }}</div>
							<div class="product-details">
								<p class="product-name">{{ $product->nama_produk }}</p>
								<p class="product-meta">{{ $product->total_qty }} Unit Terjual</p>
							</div>
						</div>
						<div class="product-stats">
							<p class="product-amount">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
							<p class="product-sales-qty">Total Omset</p>
						</div>
					</li>
				@empty
					<li>
						<div class="empty-state">
							<p class="empty-state-title">Belum ada data penjualan produk</p>
							<p class="empty-state-text">Daftar produk terlaris akan otomatis terangkum saat transaksi penjualan diselesaikan.</p>
						</div>
					</li>
				@endforelse
			</ul>
		</div>
	</section>

	{{-- Chart.js CDN & Script --}}
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
	<script>
		function changePeriod(period) {
			if (period === 'custom') {
				document.getElementById('db-custom-range').classList.add('active');
				if (typeof lucide !== 'undefined') lucide.createIcons();
			} else {
				document.getElementById('db-custom-range').classList.remove('active');
				const url = new URL(window.location);
				url.searchParams.set('period', period);
				window.location = url.toString();
			}
		}

		function applyCustomDate() {
			const startDate = document.getElementById('start_date_input').value;
			const endDate = document.getElementById('end_date_input').value;
			
			if (!startDate || !endDate) {
				alert('Silakan tentukan tanggal awal dan akhir.');
				return;
			}

			const url = new URL(window.location);
			url.searchParams.set('period', 'custom');
			url.searchParams.set('start_date', startDate);
			url.searchParams.set('end_date', endDate);
			window.location = url.toString();
		}

		document.addEventListener('DOMContentLoaded', function () {
			if (typeof lucide !== 'undefined') {
				lucide.createIcons();
			}

			// Render Tren Penjualan Chart dengan tema Aksara Horizon
			var canvas = document.getElementById('revenueChart');
			if (canvas) {
				var ctx = canvas.getContext('2d');
				new Chart(ctx, {
					type: 'line',
					data: {
						labels: {!! json_encode($revenueChartLabels) !!},
						datasets: [{
							label: 'Penjualan',
							data: {!! json_encode($revenueChartValues) !!},
							borderColor: '#0F223D',
							backgroundColor: 'rgba(197, 160, 89, 0.12)',
							borderWidth: 2.5,
							pointBackgroundColor: '#C5A059',
							pointBorderColor: '#0F223D',
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
									fontColor: '#64748B',
									fontSize: 11,
									callback: function(value) {
										try {
											return 'Rp ' + Number(value).toLocaleString('id-ID');
										} catch (e) {
											return value;
										}
									},
									padding: 10
								},
								gridLines: {
									color: 'rgba(226, 232, 240, 0.8)',
									drawBorder: false,
									zeroLineColor: 'rgba(226, 232, 240, 0.8)'
								}
							}],
							xAxes: [{
								ticks: {
									fontColor: '#64748B',
									fontSize: 11,
									padding: 8
								},
								gridLines: {
									display: false,
									drawBorder: false
								}
							}]
						},
						tooltips: {
							backgroundColor: '#0F223D',
							titleFontColor: '#C5A059',
							bodyFontColor: '#FFFFFF',
							borderColor: 'rgba(197, 160, 89, 0.4)',
							borderWidth: 1,
							cornerRadius: 8,
							displayColors: false,
							xPadding: 14,
							yPadding: 10,
							callbacks: {
								label: function(tooltipItem) {
									try {
										return 'Penjualan: Rp ' + Number(tooltipItem.yLabel).toLocaleString('id-ID');
									} catch (e) {
										return 'Penjualan: ' + tooltipItem.yLabel;
									}
								}
							}
						}
					}
				});
			}
		});
	</script>
@endsection
