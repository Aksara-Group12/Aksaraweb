@extends('layouts.horizon')

@section('page_title', 'Dashboard')

@section('content')
	<style>
		/* Header Section */
		.dashboard-header {
			margin-bottom: 32px;
		}

		.header-title-block {
			margin-bottom: 20px;
		}

		.header-title-block h2 {
			font-size: 2rem;
			font-weight: 800;
			margin: 0 0 8px 0;
			background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			background-clip: text;
		}

		.header-title-block p {
			font-size: 0.95rem;
			color: #64748b;
			margin: 0;
		}

		.header-controls {
			display: flex;
			align-items: center;
			gap: 12px;
			flex-wrap: wrap;
		}

		.header-controls select,
		.header-controls input,
		.header-controls button {
			font-family: inherit;
		}

		.period-select {
			border-radius: 12px;
			background: rgba(255, 255, 255, 0.95);
			border: 1.5px solid rgba(59, 130, 246, 0.3);
			color: #1e293b;
			font-size: 0.85rem;
			padding: 10px 14px;
			outline: none;
			cursor: pointer;
			transition: all 0.3s ease;
			font-weight: 600;
		}

		.period-select:hover {
			border-color: rgba(59, 130, 246, 0.6);
			box-shadow: 0 0 20px rgba(59, 130, 246, 0.1);
		}

		.period-select:focus {
			border-color: #3b82f6;
			background: rgba(255, 255, 255, 1);
		}

		.period-select option {
			background: #ffffff;
			color: #1e293b;
			font-weight: 600;
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
			background: rgba(255, 255, 255, 0.95);
			border-radius: 12px;
			border: 1.5px solid rgba(59, 130, 246, 0.3);
			color: #1e293b;
			font-size: 0.85rem;
			padding: 10px 12px;
			outline: none;
			transition: all 0.3s ease;
			font-weight: 600;
		}

		.date-input:focus {
			border-color: #3b82f6;
			box-shadow: 0 0 20px rgba(59, 130, 246, 0.1);
		}

		.date-separator {
			font-size: 0.85rem;
			color: #cbd5e1;
			font-weight: 700;
		}

		.apply-btn {
			border-radius: 12px;
			border: none;
			background: linear-gradient(135deg, #2563eb 0%, #d97706 100%);
			color: #0f172a;
			padding: 10px 18px;
			font-size: 0.85rem;
			font-weight: 800;
			cursor: pointer;
			transition: all 0.3s ease;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			box-shadow: 0 4px 20px rgba(245, 158, 11, 0.2);
		}

		.apply-btn:hover {
			transform: translateY(-2px);
			box-shadow: 0 6px 30px rgba(245, 158, 11, 0.3);
		}

		/* KPI Card Grid */
		.kpi-card-wrapper {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
			gap: 24px;
			margin-bottom: 32px;
		}

		.kpi-card {
			position: relative;
			border-radius: 24px;
			padding: 24px;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			backdrop-filter: blur(10px);
			overflow: hidden;
			cursor: pointer;
			box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
			border: 1.5px solid;
		}

		.kpi-card::before {
			content: '';
			position: absolute;
			top: -60%;
			right: -60%;
			width: 280px;
			height: 280px;
			border-radius: 50%;
			opacity: 0.25;
			filter: blur(80px);
			pointer-events: none;
		}

		.kpi-card:hover {
			transform: translateY(-10px);
			box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
		}

		.kpi-card-inner {
			position: relative;
			z-index: 2;
		}

		.kpi-card-header-section {
			display: flex;
			justify-content: space-between;
			align-items: flex-start;
			gap: 16px;
			margin-bottom: 20px;
		}

		.kpi-card-title-section {
			display: flex;
			align-items: center;
			gap: 10px;
			flex: 1;
		}

		.kpi-card-header-icon {
			width: 28px;
			height: 28px;
			flex-shrink: 0;
		}

		.kpi-card-title {
			font-size: 0.8rem;
			font-weight: 600;
			letter-spacing: 1.2px;
			text-transform: uppercase;
			margin: 0;
			line-height: 1;
		}

		.kpi-card-value-section {
			margin-bottom: 18px;
		}

		.kpi-card-value {
			font-size: 2.8rem;
			font-weight: 900;
			margin: 0;
			line-height: 1.1;
			letter-spacing: -2px;
		}

		.kpi-card-value-sub {
			font-size: 0.75rem;
			margin-top: 4px;
			opacity: 0.7;
		}

		.kpi-card-meta {
			display: grid;
			grid-template-columns: repeat(2, 1fr);
			gap: 16px;
			padding-top: 16px;
			border-top: 1px solid;
			border-top-opacity: 0.25;
		}

		.kpi-meta-item {
			display: flex;
			flex-direction: column;
			gap: 6px;
		}

		.kpi-meta-label {
			font-size: 0.65rem;
			font-weight: 600;
			text-transform: uppercase;
			opacity: 0.65;
			letter-spacing: 0.5px;
		}

		.kpi-meta-value {
			font-size: 1.1rem;
			font-weight: 800;
			margin: 0;
			line-height: 1;
		}

		.kpi-status-badge {
			display: inline-flex;
			align-items: center;
			gap: 6px;
			padding: 8px 14px;
			border-radius: 999px;
			font-size: 0.65rem;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			width: fit-content;
			margin-top: 2px;
		}

		/* Theme Colors */
		.kpi-card-green {
			background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(34, 197, 94, 0.02) 100%);
			border-color: rgba(34, 197, 94, 0.4);
		}

		.kpi-card-green::before { background: #22c55e; }
		.kpi-card-green .kpi-card-title { color: #16a34a; }
		.kpi-card-green .kpi-card-value { color: #22c55e; text-shadow: 0 0 40px rgba(34, 197, 94, 0.35); }
		.kpi-card-green .kpi-card-meta { border-top-color: rgba(34, 197, 94, 0.25); }
		.kpi-card-green .kpi-meta-label { color: #a1a5b4; }
		.kpi-card-green .kpi-meta-value { color: #22c55e; }
		.kpi-card-green .kpi-status-badge { background: rgba(34, 197, 94, 0.2); color: #16a34a; }
		.kpi-card-green .kpi-card-header-icon { color: #22c55e; }

		.kpi-card-blue {
			background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0.02) 100%);
			border-color: rgba(59, 130, 246, 0.4);
		}

		.kpi-card-blue::before { background: #3b82f6; }
		.kpi-card-blue .kpi-card-title { color: #93c5fd; }
		.kpi-card-blue .kpi-card-value { color: #3b82f6; text-shadow: 0 0 40px rgba(59, 130, 246, 0.35); }
		.kpi-card-blue .kpi-card-meta { border-top-color: rgba(59, 130, 246, 0.25); }
		.kpi-card-blue .kpi-meta-label { color: #a1a5b4; }
		.kpi-card-blue .kpi-meta-value { color: #3b82f6; }
		.kpi-card-blue .kpi-card-header-icon { color: #3b82f6; }

		.kpi-card-amber {
			background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(59, 130, 246, 0.02) 100%);
			border-color: rgba(59, 130, 246, 0.4);
		}

		.kpi-card-amber::before { background: #3b82f6; }
		.kpi-card-amber .kpi-card-title { color: #fcd34d; }
		.kpi-card-amber .kpi-card-value { color: #3b82f6; text-shadow: 0 0 40px rgba(59, 130, 246, 0.35); }
		.kpi-card-amber .kpi-card-meta { border-top-color: rgba(59, 130, 246, 0.25); }
		.kpi-card-amber .kpi-meta-label { color: #a1a5b4; }
		.kpi-card-amber .kpi-meta-value { color: #3b82f6; }
		.kpi-card-amber .kpi-card-header-icon { color: #3b82f6; }

		.kpi-card-purple {
			background: linear-gradient(135deg, rgba(168, 85, 247, 0.1) 0%, rgba(168, 85, 247, 0.02) 100%);
			border-color: rgba(168, 85, 247, 0.4);
		}

		.kpi-card-purple::before { background: #a855f7; }
		.kpi-card-purple .kpi-card-title { color: #d8b4fe; }
		.kpi-card-purple .kpi-card-value { color: #a855f7; text-shadow: 0 0 40px rgba(168, 85, 247, 0.35); }
		.kpi-card-purple .kpi-card-meta { border-top-color: rgba(168, 85, 247, 0.25); }
		.kpi-card-purple .kpi-meta-label { color: #a1a5b4; }
		.kpi-card-purple .kpi-meta-value { color: #a855f7; }
		.kpi-card-purple .kpi-card-header-icon { color: #a855f7; }

		/* Secondary Grid */
		.secondary-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
			gap: 24px;
			margin-bottom: 32px;
		}

		.secondary-card {
			position: relative;
			border-radius: 24px;
			padding: 28px;
			background: linear-gradient(135deg, rgba(30, 41, 59, 0.5) 0%, rgba(15, 23, 42, 0.8) 100%);
			border: 1.5px solid rgba(59, 130, 246, 0.4);
			backdrop-filter: blur(15px);
			overflow: hidden;
			box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
		}

		.secondary-card::before {
			content: '';
			position: absolute;
			top: -40%;
			right: -20%;
			width: 250px;
			height: 250px;
			border-radius: 50%;
			background: rgba(59, 130, 246, 0.1);
			filter: blur(70px);
			pointer-events: none;
		}

		.secondary-card:hover {
			transform: translateY(-8px);
			border-color: rgba(51, 65, 85, 0.6);
			box-shadow: 0 15px 50px rgba(0, 0, 0, 0.4);
		}

		.card-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 20px;
			position: relative;
			z-index: 2;
		}

		.card-header-title {
			display: flex;
			flex-direction: column;
			gap: 4px;
		}

		.card-title {
			font-size: 1rem;
			font-weight: 700;
			color: #1e293b;
			margin: 0;
			letter-spacing: 0.5px;
		}

		.card-subtitle {
			font-size: 0.75rem;
			color: #94a3af;
			margin: 0;
		}

		.card-badge {
			background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(59, 130, 246, 0.1) 100%);
			color: #fcd34d;
			padding: 6px 12px;
			border-radius: 999px;
			font-size: 0.7rem;
			font-weight: 600;
			text-transform: uppercase;
			border: 1px solid rgba(59, 130, 246, 0.3);
			letter-spacing: 0.5px;
		}

		.chart-container {
			position: relative;
			z-index: 1;
			height: 320px;
			margin-bottom: 8px;
		}

		.customer-list {
			list-style: none;
			padding: 0;
			margin: 0;
			display: flex;
			flex-direction: column;
			gap: 2px;
		}

		.customer-item {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 14px;
			border-radius: 14px;
			background: rgba(51, 65, 85, 0.2);
			border: 1px solid rgba(51, 65, 85, 0.3);
			transition: all 0.3s ease;
		}

		.customer-item:hover {
			background: rgba(51, 65, 85, 0.35);
			border-color: rgba(51, 65, 85, 0.5);
			transform: translateX(4px);
		}

		.customer-info {
			display: flex;
			align-items: center;
			gap: 12px;
			flex: 1;
			min-width: 0;
		}

		.customer-avatar {
			width: 40px;
			height: 40px;
			border-radius: 10px;
			background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
			display: flex;
			align-items: center;
			justify-content: center;
			font-weight: 700;
			color: white;
			font-size: 0.8rem;
			flex-shrink: 0;
		}

		.customer-details {
			display: flex;
			flex-direction: column;
			gap: 2px;
			min-width: 0;
		}

		.customer-name {
			font-size: 0.85rem;
			font-weight: 600;
			color: #1e293b;
			margin: 0;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.customer-meta {
			font-size: 0.7rem;
			color: #94a3af;
			margin: 0;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.customer-stats {
			display: flex;
			flex-direction: column;
			align-items: flex-end;
			gap: 4px;
			flex-shrink: 0;
		}

		.customer-amount {
			font-size: 0.9rem;
			font-weight: 700;
			color: #16a34a;
			margin: 0;
		}

		.customer-transaction {
			font-size: 0.7rem;
			color: #94a3af;
			margin: 0;
		}

		.empty-state {
			text-align: center;
			padding: 40px 20px;
		}

		.empty-state-title {
			font-size: 0.95rem;
			font-weight: 600;
			color: #1e293b;
			margin: 0 0 8px 0;
		}

		.empty-state-text {
			font-size: 0.8rem;
			color: #94a3af;
			margin: 0;
		}

		/* Responsive */
		@media (max-width: 1024px) {
			.kpi-card-wrapper {
				grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
				gap: 18px;
			}

			.kpi-card {
				padding: 20px;
			}

			.kpi-card-value {
				font-size: 2.3rem;
			}

			.secondary-grid {
				gap: 18px;
			}

			.secondary-card {
				padding: 22px;
			}

			.chart-container {
				height: 280px;
			}
		}

		@media (max-width: 768px) {
			.header-controls {
				flex-direction: column;
				align-items: stretch;
			}

			.period-select, .date-input, .apply-btn {
				width: 100%;
				font-size: 0.9rem !important;
			}

			.custom-date-range {
				flex-direction: column;
			}

			.custom-date-range.active {
				flex-direction: column;
			}

			.date-input {
				width: 100%;
			}

			.kpi-card-wrapper {
				grid-template-columns: repeat(2, 1fr);
				gap: 14px;
			}

			.kpi-card {
				padding: 16px;
			}

			.kpi-card-value {
				font-size: 1.9rem;
			}

			.secondary-grid {
				grid-template-columns: 1fr;
				gap: 14px;
			}

			.chart-container {
				height: 250px;
			}

			.customer-item {
				padding: 12px;
			}
		}

		@media (max-width: 480px) {
			.header-title-block h2 {
				font-size: 1.5rem;
			}

			.kpi-card-wrapper {
				grid-template-columns: 1fr;
				gap: 12px;
			}

			.kpi-card {
				padding: 14px;
			}

			.kpi-card-value {
				font-size: 1.6rem;
			}

			.secondary-card {
				padding: 16px;
			}

			.chart-container {
				height: 220px;
			}

			.customer-info {
				gap: 8px;
			}

			.customer-avatar {
				width: 36px;
				height: 36px;
				font-size: 0.7rem;
			}

			.customer-name {
				font-size: 0.8rem;
			}
		}
	</style>

	<div class="dashboard-header">
		<div class="header-title-block">
			<h2>Ringkasan Penjualan & Stok</h2>
			<p>Gambaran singkat performa penjualan dan ketersediaan stok hari ini.</p>
		</div>
		<div class="header-controls">
			<select name="period" id="db-period-select" class="period-select">
				<option value="today" {{ ($period ?? 'month') === 'today' ? 'selected' : '' }}>Hari Ini</option>
				<option value="week" {{ ($period ?? 'month') === 'week' ? 'selected' : '' }}>Minggu Ini</option>
				<option value="month" {{ ($period ?? 'month') === 'month' ? 'selected' : '' }}>Bulan Ini</option>
				<option value="year" {{ ($period ?? 'month') === 'year' ? 'selected' : '' }}>Tahun Ini</option>
				<option value="all" {{ ($period ?? 'month') === 'all' ? 'selected' : '' }}>Semua Data</option>
				<option value="custom" {{ ($period ?? 'month') === 'custom' ? 'selected' : '' }}>Custom</option>
			</select>

			<div id="db-custom-range" class="custom-date-range">
				<input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="date-input">
				<span class="date-separator">s/d</span>
				<input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="date-input">
			</div>

			<form action="{{ route('dashboard') }}" method="GET" style="display: contents;">
				<select name="period" id="period-hidden" style="display: none;">
					<option value="today" {{ ($period ?? 'month') === 'today' ? 'selected' : '' }}>Hari Ini</option>
					<option value="week" {{ ($period ?? 'month') === 'week' ? 'selected' : '' }}>Minggu Ini</option>
					<option value="month" {{ ($period ?? 'month') === 'month' ? 'selected' : '' }}>Bulan Ini</option>
					<option value="year" {{ ($period ?? 'month') === 'year' ? 'selected' : '' }}>Tahun Ini</option>
					<option value="all" {{ ($period ?? 'month') === 'all' ? 'selected' : '' }}>Semua Data</option>
					<option value="custom" {{ ($period ?? 'month') === 'custom' ? 'selected' : '' }}>Custom</option>
				</select>
				<input type="date" name="start_date" id="start_date_hidden" value="{{ $startDate ?? '' }}" style="display: none;">
				<input type="date" name="end_date" id="end_date_hidden" value="{{ $endDate ?? '' }}" style="display: none;">
				<button type="submit" class="apply-btn">Terapkan</button>
			</form>
		</div>
	</div>

	<section class="kpi-card-wrapper">
		<!-- Card Total Penjualan -->
		<div class="kpi-card kpi-card-green">
			<div class="kpi-card-inner">
				<div class="kpi-card-header-section">
					<div class="kpi-card-title-section">
						<svg class="kpi-card-header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
						</svg>
						<p class="kpi-card-title">Total Penjualan</p>
					</div>
				</div>
				
				<div class="kpi-card-value-section">
					<p class="kpi-card-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
				</div>

				<div class="kpi-card-meta">
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Hari Ini</p>
						<p class="kpi-meta-value">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
					</div>
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Status</p>
						<span class="kpi-status-badge">
							{{ $totalRevenue > 0 ? '✓ Aktif' : '◌ Pending' }}
						</span>
					</div>
				</div>
			</div>
		</div>

		<!-- Card Transaksi Hari Ini -->
		<div class="kpi-card kpi-card-blue">
			<div class="kpi-card-inner">
				<div class="kpi-card-header-section">
					<div class="kpi-card-title-section">
						<svg class="kpi-card-header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="9" cy="21" r="1"></circle>
							<circle cx="20" cy="21" r="1"></circle>
							<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
						</svg>
						<p class="kpi-card-title">Transaksi Hari Ini</p>
					</div>
				</div>

				<div class="kpi-card-value-section">
					<p class="kpi-card-value">{{ $todayTransactions }}</p>
					<p class="kpi-card-value-sub">Total penjualan tercatat</p>
				</div>

				<div class="kpi-card-meta">
					<div class="kpi-meta-item" style="grid-column: 1 / -1;">
						<p class="kpi-meta-label">Performa</p>
						<p class="kpi-meta-value">{{ $todayTransactions }} Transaksi</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Card Total Transaksi -->
		<div class="kpi-card kpi-card-amber">
			<div class="kpi-card-inner">
				<div class="kpi-card-header-section">
					<div class="kpi-card-title-section">
						<svg class="kpi-card-header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<path d="M9 11H3v2h6zm0-4H3v2h6zm6 0v2m6-2v2m0-6v2M9 7H3v2h6z"></path>
							<rect x="3" y="5" width="18" height="14" rx="2" ry="2"></rect>
						</svg>
						<p class="kpi-card-title">Total Transaksi</p>
					</div>
				</div>

				<div class="kpi-card-value-section">
					<p class="kpi-card-value">{{ $totalTransactions }}</p>
				</div>

				<div class="kpi-card-meta">
					<div class="kpi-meta-item">
						<div style="display: flex; align-items: center; gap: 6px;">
							<span style="width: 3px; height: 3px; border-radius: 50%; background: #22c55e;"></span>
							<p class="kpi-meta-label" style="margin: 0;">Penjualan</p>
						</div>
						<p class="kpi-meta-value">{{ $totalSalesTransactions }}</p>
					</div>
					<div class="kpi-meta-item">
						<div style="display: flex; align-items: center; gap: 6px;">
							<span style="width: 3px; height: 3px; border-radius: 50%; background: #ef4444;"></span>
							<p class="kpi-meta-label" style="margin: 0;">Pembelian</p>
						</div>
						<p class="kpi-meta-value" style="color: #ef4444;">{{ $totalTransactions - $totalSalesTransactions }}</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Card Total Stok -->
		<div class="kpi-card kpi-card-purple">
			<div class="kpi-card-inner">
				<div class="kpi-card-header-section">
					<div class="kpi-card-title-section">
						<svg class="kpi-card-header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
							<polyline points="3.27 6.5 12 12.5 20.73 6.5"></polyline>
							<polyline points="12 22.5 12 12.5"></polyline>
						</svg>
						<p class="kpi-card-title">Total Stok</p>
					</div>
				</div>

				<div class="kpi-card-value-section">
					<p class="kpi-card-value">{{ $totalStockUnits }}</p>
					<p class="kpi-card-value-sub">Unit di semua jenis logam</p>
				</div>

				<div class="kpi-card-meta">
					<div class="kpi-meta-item" style="grid-column: 1 / -1;">
						<p class="kpi-meta-label">Inventaris</p>
						<p class="kpi-meta-value">{{ $totalStockUnits }} Unit</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="secondary-grid">
		<!-- Chart Card -->
		<div class="secondary-card">
			<div class="card-header">
				<div class="card-header-title">
					<p class="card-title">Penjualan 7 Hari Terakhir</p>
					<p class="card-subtitle">Total penjualan per hari</p>
				</div>
			</div>
			<div class="chart-container">
				<canvas id="revenueChart"></canvas>
			</div>
		</div>

		<!-- Customer Card -->
		<div class="secondary-card">
			<div class="card-header">
				<div class="card-header-title">
					<p class="card-title">Top Customer</p>
					<p class="card-subtitle">Berdasarkan total belanja</p>
				</div>
				<span class="card-badge">Top 10</span>
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
							<p class="customer-transaction">{{ $customer->total_transactions }} transaksi</p>
						</div>
					</li>
				@empty
					<li style="grid-column: 1 / -1;">
						<div class="empty-state">
							<p class="empty-state-title">Belum ada data customer</p>
							<p class="empty-state-text">Data akan muncul setelah ada transaksi dengan nama customer.</p>
						</div>
					</li>
				@endforelse
			</ul>
		</div>
	</section>
@endsection

{{-- Chart.js untuk grafik penjualan 7 hari terakhir --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
	// Period selector logic
	var periodSelect = document.getElementById('db-period-select');
	var customRange = document.getElementById('db-custom-range');
	
	if (periodSelect && customRange) {
		periodSelect.addEventListener('change', function () {
			if (this.value === 'custom') {
				customRange.classList.add('active');
			} else {
				customRange.classList.remove('active');
			}
			document.getElementById('period-hidden').value = this.value;
		});

		// Check initial state
		if (periodSelect.value === 'custom') {
			customRange.classList.add('active');
		}
	}

	// Sync date inputs
	var dateInputs = document.querySelectorAll('.date-input');
	dateInputs.forEach(function(input) {
		input.addEventListener('change', function() {
			if (input.name === 'start_date') {
				document.getElementById('start_date_hidden').value = this.value;
			} else if (input.name === 'end_date') {
				document.getElementById('end_date_hidden').value = this.value;
			}
		});
	});

	// Chart configuration
	var canvas = document.getElementById('revenueChart');
	if (!canvas) return;

	var ctx = canvas.getContext('2d');

	new Chart(ctx, {
		type: 'line',
		data: {
			labels: {!! json_encode($revenueChartLabels) !!},
			datasets: [{
				label: 'Penjualan',
				data: {!! json_encode($revenueChartValues) !!},
				borderColor: '#3b82f6',
				backgroundColor: 'rgba(59, 130, 246, 0.08)',
				borderWidth: 3,
				pointBackgroundColor: '#3b82f6',
				pointBorderColor: '#020617',
				pointBorderWidth: 2.5,
				pointRadius: 5,
				pointHoverRadius: 7,
				tension: 0.35,
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
						fontColor: '#cbd5f5',
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
						color: 'rgba(148, 163, 184, 0.15)',
						drawBorder: false,
						zeroLineColor: 'rgba(148, 163, 184, 0.15)'
					}
				}],
				xAxes: [{
					ticks: {
						fontColor: '#cbd5f5',
						fontSize: 11,
						padding: 10
					},
					gridLines: {
						display: false,
						drawBorder: false
					}
				}]
			},
			tooltips: {
				backgroundColor: 'rgba(2, 6, 23, 0.95)',
				titleFontColor: '#3b82f6',
				bodyFontColor: '#e5e7eb',
				borderColor: '#3b82f6',
				borderWidth: 1.5,
				cornerRadius: 10,
				displayColors: false,
				xPadding: 16,
				yPadding: 12,
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
});
</script>

