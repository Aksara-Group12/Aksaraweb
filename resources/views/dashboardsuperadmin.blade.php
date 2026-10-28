@extends('layouts.horizon')

@section('page_title', 'Dashboard Superadmin')

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
			background: linear-gradient(135deg, #e5e7eb 0%, #9ca3af 100%);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			background-clip: text;
		}

		.header-title-block p {
			font-size: 0.95rem;
			color: #a1a5b4;
			margin: 0;
		}

		.header-controls {
			display: flex;
			align-items: center;
			gap: 12px;
			flex-wrap: wrap;
		}

		.period-select {
			border-radius: 12px;
			background: rgba(255, 255, 255, 0.95) 0%, rgba(15, 23, 42, 0.8) 100%);
			border: 1.5px solid rgba(59, 130, 246, 0.6);
			color: #1e293b;
			font-size: 0.8rem;
			padding: 10px 14px;
			outline: none;
			cursor: pointer;
			transition: all 0.3s ease;
			font-weight: 500;
		}

		.period-select:hover {
			border-color: rgba(59, 130, 246, 0.6);
			box-shadow: 0 0 20px rgba(59, 130, 246, 0.1);
		}

		.period-select:focus {
			border-color: #3b82f6;
			background: rgba(255, 255, 255, 1) 0%, rgba(15, 23, 42, 0.95) 100%);
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
			background: rgba(255, 255, 255, 0.95) 0%, rgba(15, 23, 42, 0.8) 100%);
			border-radius: 12px;
			border: 1.5px solid rgba(59, 130, 246, 0.6);
			color: #1e293b;
			font-size: 0.8rem;
			padding: 10px 12px;
			outline: none;
			transition: all 0.3s ease;
		}

		.date-input:focus {
			border-color: #3b82f6;
			box-shadow: 0 0 20px rgba(59, 130, 246, 0.1);
		}

		.date-separator {
			font-size: 0.8rem;
			color: #94a3af;
			font-weight: 600;
		}

		.apply-btn {
			border-radius: 12px;
			border: none;
			background: linear-gradient(135deg, #2563eb 0%, #d97706 100%);
			color: #111827;
			padding: 10px 18px;
			font-size: 0.8rem;
			font-weight: 700;
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
			<h2>Dashboard Superadmin</h2>
			<p>Pantau Performa Sistem, User, & Keuangan</p>
		</div>
		<div class="header-controls">
			<select name="period" id="db-period-select" class="period-select" onchange="changePeriod(this.value)">
				<option value="today" {{ ($period ?? 'month') === 'today' ? 'selected' : '' }}>Hari Ini</option>
				<option value="week" {{ ($period ?? 'month') === 'week' ? 'selected' : '' }}>Minggu Ini</option>
				<option value="month" {{ ($period ?? 'month') === 'month' ? 'selected' : '' }}>Bulan Ini</option>
				<option value="year" {{ ($period ?? 'month') === 'year' ? 'selected' : '' }}>Tahun Ini</option>
				<option value="all" {{ ($period ?? 'month') === 'all' ? 'selected' : '' }}>Semua Data</option>
				<option value="custom" {{ ($period ?? 'month') === 'custom' ? 'selected' : '' }}>Custom</option>
			</select>

			<div id="db-custom-range" class="custom-date-range {{ ($period ?? 'month') === 'custom' ? 'active' : '' }}">
				<input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="date-input" id="start_date_input">
				<span class="date-separator">s/d</span>
				<input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="date-input" id="end_date_input">
				<button type="button" class="apply-btn" onclick="applyCustomDate()">Terapkan</button>
			</div>
		</div>
	</div>

	<section class="kpi-card-wrapper">
		<!-- Card Total User -->
		<div class="kpi-card kpi-card-blue">
			<div class="kpi-card-inner">
				<div class="kpi-card-header-section">
					<div class="kpi-card-title-section">
						<svg class="kpi-card-header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
							<circle cx="9" cy="7" r="4"></circle>
							<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
							<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
						</svg>
						<p class="kpi-card-title">Total User</p>
					</div>
				</div>

				<div class="kpi-card-value-section">
					<p class="kpi-card-value">{{ $totalUsers }}</p>
					<p class="kpi-card-value-sub">Seluruh Pengguna Sistem</p>
				</div>

				<div class="kpi-card-meta">
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">User Aktif</p>
						<p class="kpi-meta-value">{{ $totalActiveUsers }}</p>
					</div>
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Hari Ini</p>
						<p class="kpi-meta-value">{{ $todayNewUsers }}</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Card Total User Berlangganan -->
		<div class="kpi-card kpi-card-green">
			<div class="kpi-card-inner">
				<div class="kpi-card-header-section">
					<div class="kpi-card-title-section">
						<svg class="kpi-card-header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
							<circle cx="12" cy="7" r="4"></circle>
							<path d="M9 12h6"></path>
						</svg>
						<p class="kpi-card-title">User Berlangganan</p>
					</div>
				</div>

				<div class="kpi-card-value-section">
					<p class="kpi-card-value">{{ $totalSubscribedUsers }}</p>
					<p class="kpi-card-value-sub">Pengguna Aktif Premium</p>
				</div>

				<div class="kpi-card-meta">
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Status</p>
						<span class="kpi-status-badge">
							{{ $totalSubscribedUsers > 0 ? '✓ Aktif' : '◌ Tunggu' }}
						</span>
					</div>
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Dari Total</p>
						<p class="kpi-meta-value">{{ $totalActiveUsers > 0 ? number_format(($totalSubscribedUsers / $totalActiveUsers) * 100, 1) : 0 }}%</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Card Pendapatan Subscription -->
		<div class="kpi-card kpi-card-amber">
			<div class="kpi-card-inner">
				<div class="kpi-card-header-section">
					<div class="kpi-card-title-section">
						<svg class="kpi-card-header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
						</svg>
						<p class="kpi-card-title">Pendapatan Subscription</p>
					</div>
				</div>

				<div class="kpi-card-value-section">
					<p class="kpi-card-value" style="font-size: 1.8rem;">Rp {{ number_format($subscriptionRevenue, 0, ',', '.') }}</p>
					<p class="kpi-card-value-sub">{{ $startDate }} s/d {{ $endDate }}</p>
				</div>

				<div class="kpi-card-meta">
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Hari Ini</p>
						<p class="kpi-meta-value" style="font-size: 0.85rem;">Rp {{ number_format($todaySubscriptionRevenue, 0, ',', '.') }}</p>
					</div>
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Avg/User</p>
						<p class="kpi-meta-value" style="font-size: 0.85rem;">Rp {{ $totalSubscribedUsers > 0 ? number_format($subscriptionRevenue / $totalSubscribedUsers, 0, ',', '.') : 0 }}</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Card Net Income (Profit/Loss) -->
		<div class="kpi-card kpi-card-purple">
			<div class="kpi-card-inner">
				<div class="kpi-card-header-section">
					<div class="kpi-card-title-section">
						<svg class="kpi-card-header-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
							<polyline points="13 2 13 9 20 9"></polyline>
							<line x1="12" y1="11" x2="12" y2="17"></line>
							<line x1="9" y1="14" x2="15" y2="14"></line>
						</svg>
						<p class="kpi-card-title">Net Income</p>
					</div>
				</div>

				<div class="kpi-card-value-section">
					<p class="kpi-card-value" style="font-size: 1.8rem; color: {{ $netIncome >= 0 ? '#22c55e' : '#ef4444' }}">
						{{ $netIncome >= 0 ? '+' : '-' }}Rp {{ number_format(abs($netIncome), 0, ',', '.') }}
					</p>
					<p class="kpi-card-value-sub">{{ $netIncome >= 0 ? 'Untung' : 'Rugi' }}</p>
				</div>

				<div class="kpi-card-meta">
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Hari Ini</p>
						<p class="kpi-meta-value" style="font-size: 0.85rem; color: {{ $todayNetIncome >= 0 ? '#22c55e' : '#ef4444' }}">
							{{ $todayNetIncome >= 0 ? '+' : '-' }}Rp {{ number_format(abs($todayNetIncome), 0, ',', '.') }}
						</p>
					</div>
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Margin</p>
						<p class="kpi-meta-value" style="font-size: 0.85rem;">{{ $totalKeluar > 0 ? number_format(($netIncome / $totalKeluar) * 100, 1) : 0 }}%</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="secondary-grid">
		<!-- Top Users Card -->
		<div class="secondary-card">
			<div class="card-header">
				<div class="card-header-title">
					<p class="card-title">Top Users (Transaksi Tertinggi)</p>
					<p class="card-subtitle">Pengguna dengan penjualan terbanyak</p>
				</div>
				<span class="card-badge">Top 5</span>
			</div>
			<ul class="customer-list">
				@forelse($topUsers as $user)
					<li class="customer-item">
						<div class="customer-info">
							<div class="customer-avatar">
								{{ strtoupper(substr($user->name, 0, 1)) }}
							</div>
							<div class="customer-details">
								<p class="customer-name">{{ $user->name }}</p>
								<p class="customer-meta">{{ $user->email }}</p>
							</div>
						</div>
						<div class="customer-stats">
							<p class="customer-amount">Rp {{ number_format($user->total_revenue ?? 0, 0, ',', '.') }}</p>
							<p class="customer-transaction">{{ $user->total_transaksi_keluar ?? 0 }} Transaksi</p>
						</div>
					</li>
				@empty
					<li style="grid-column: 1 / -1;">
						<div class="empty-state">
							<p class="empty-state-title">Belum ada data</p>
							<p class="empty-state-text">Data pengguna akan muncul setelah ada transaksi di periode ini.</p>
						</div>
					</li>
				@endforelse
			</ul>
		</div>
	</section>

	<script>
		function changePeriod(period) {
			if (period === 'custom') {
				document.getElementById('db-custom-range').classList.add('active');
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
				alert('Silakan pilih tanggal awal dan akhir');
				return;
			}

			const url = new URL(window.location);
			url.searchParams.set('period', 'custom');
			url.searchParams.set('start_date', startDate);
			url.searchParams.set('end_date', endDate);
			window.location = url.toString();
		}
	</script>
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
</script>

