@extends('layouts.horizon')

@section('page_title', 'Dashboard Superadmin')

@section('header_content')
	<div>
		<h1 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.35rem; font-weight: 500; color: #0F223D; margin: 0; line-height: 1.2;">
			Dashboard Superadmin
		</h1>
		<p style="font-size: 0.8rem; color: #64748B; margin: 2px 0 0 0;">
			Pantau performa sistem, aktivitas pengguna, dan arus transaksi keuangan.
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

		/* KPI Card Grid: 4 Kolom Rapi & Selaras */
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

		/* Shadcn UI Unified Icon Badge (Selaras Warm Gold Aksara) */
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

		/* Secondary Grid */
		.secondary-grid {
			display: grid;
			grid-template-columns: 1fr;
			gap: 24px;
			margin-bottom: 32px;
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

		.customer-list {
			list-style: none;
			padding: 0;
			margin: 0;
			display: flex;
			flex-direction: column;
			gap: 10px;
		}

		.customer-item {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 12px 16px;
			border-radius: 12px;
			background: #FAF8F5;
			border: 1px solid #E2E8F0;
			transition: all 0.15s ease;
		}

		.customer-item:hover {
			background: #FFFFFF;
			border-color: rgba(197, 160, 89, 0.4);
			box-shadow: 0 4px 12px rgba(15, 34, 61, 0.04);
			transform: translateX(2px);
		}

		.customer-info {
			display: flex;
			align-items: center;
			gap: 12px;
			flex: 1;
			min-width: 0;
		}

		.customer-avatar {
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

		.customer-details {
			display: flex;
			flex-direction: column;
			gap: 2px;
			min-width: 0;
		}

		.customer-name {
			font-size: 0.875rem;
			font-weight: 600;
			color: #0F223D;
			margin: 0;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.customer-meta {
			font-size: 0.75rem;
			color: #64748B;
			margin: 0;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.customer-stats {
			display: flex;
			flex-direction: column;
			align-items: flex-end;
			gap: 3px;
			flex-shrink: 0;
		}

		.customer-amount {
			font-size: 0.95rem;
			font-weight: 700;
			color: #0F223D;
			margin: 0;
		}

		.customer-transaction {
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

		/* Responsive Grid: Rapi di Semua Resolusi */
		@media (max-width: 1200px) {
			.kpi-card-wrapper {
				grid-template-columns: repeat(2, 1fr);
				gap: 16px;
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

	<section class="kpi-card-wrapper">
		<!-- Card 1: Total User -->
		<div class="kpi-card">
			<div class="kpi-card-inner">
				<div class="kpi-card-header-section">
					<div class="kpi-card-title-section">
						<div class="kpi-icon-badge">
							<i data-lucide="users"></i>
						</div>
						<p class="kpi-card-title">Total User</p>
					</div>
				</div>

				<div class="kpi-card-value-section">
					<p class="kpi-card-value">{{ $totalUsers }}</p>
					<p class="kpi-card-value-sub">Seluruh Pengguna Terdaftar</p>
				</div>

				<div class="kpi-card-meta">
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">User Aktif</p>
						<p class="kpi-meta-value">{{ $totalActiveUsers }}</p>
					</div>
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Hari Ini</p>
						<p class="kpi-meta-value">+{{ $todayNewUsers }}</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Card 2: User Berlangganan -->
		<div class="kpi-card">
			<div class="kpi-card-inner">
				<div class="kpi-card-header-section">
					<div class="kpi-card-title-section">
						<div class="kpi-icon-badge">
							<i data-lucide="user-check"></i>
						</div>
						<p class="kpi-card-title">User Berlangganan</p>
					</div>
				</div>

				<div class="kpi-card-value-section">
					<p class="kpi-card-value">{{ $totalSubscribedUsers }}</p>
					<p class="kpi-card-value-sub">Pengguna Paket Premium Aktif</p>
				</div>

				<div class="kpi-card-meta">
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Status</p>
						<span class="kpi-status-badge {{ $totalSubscribedUsers > 0 ? 'kpi-status-active' : 'kpi-status-wait' }}">
							<span class="status-indicator-dot"></span>
							<span>{{ $totalSubscribedUsers > 0 ? 'Aktif' : 'Menunggu' }}</span>
						</span>
					</div>
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Rasio Konversi</p>
						<p class="kpi-meta-value">{{ $totalActiveUsers > 0 ? number_format(($totalSubscribedUsers / $totalActiveUsers) * 100, 1) : 0 }}%</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Card 3: Pendapatan Subscription -->
		<div class="kpi-card">
			<div class="kpi-card-inner">
				<div class="kpi-card-header-section">
					<div class="kpi-card-title-section">
						<div class="kpi-icon-badge">
							<i data-lucide="credit-card"></i>
						</div>
						<p class="kpi-card-title">Pendapatan Langganan</p>
					</div>
				</div>

				<div class="kpi-card-value-section">
					<p class="kpi-card-value" style="font-size: 1.85rem;">Rp {{ number_format($subscriptionRevenue, 0, ',', '.') }}</p>
					<p class="kpi-card-value-sub">{{ $startDate }} s/d {{ $endDate }}</p>
				</div>

				<div class="kpi-card-meta">
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Hari Ini</p>
						<p class="kpi-meta-value" style="font-size: 0.85rem;">Rp {{ number_format($todaySubscriptionRevenue, 0, ',', '.') }}</p>
					</div>
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Rata-rata/User</p>
						<p class="kpi-meta-value" style="font-size: 0.85rem;">Rp {{ $totalSubscribedUsers > 0 ? number_format($subscriptionRevenue / $totalSubscribedUsers, 0, ',', '.') : 0 }}</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Card 4: Net Income (Profit/Loss) -->
		<div class="kpi-card">
			<div class="kpi-card-inner">
				<div class="kpi-card-header-section">
					<div class="kpi-card-title-section">
						<div class="kpi-icon-badge">
							<i data-lucide="wallet"></i>
						</div>
						<p class="kpi-card-title">Laba Bersih Sistem</p>
					</div>
				</div>

				<div class="kpi-card-value-section">
					<p class="kpi-card-value" style="font-size: 1.85rem; color: {{ $netIncome > 0 ? '#059669' : ($netIncome < 0 ? '#DC2626' : '#0F223D') }}">
						{{ $netIncome > 0 ? '+' : ($netIncome < 0 ? '-' : '') }}Rp {{ number_format(abs($netIncome), 0, ',', '.') }}
					</p>
					<p class="kpi-card-value-sub">{{ $netIncome > 0 ? 'Surplus / Untung' : ($netIncome < 0 ? 'Defisit / Rugi' : 'Imbang / Netral') }}</p>
				</div>

				<div class="kpi-card-meta">
					<div class="kpi-meta-item">
						<p class="kpi-meta-label">Hari Ini</p>
						<p class="kpi-meta-value" style="font-size: 0.85rem; color: {{ $todayNetIncome > 0 ? '#059669' : ($todayNetIncome < 0 ? '#DC2626' : '#0F223D') }}">
							{{ $todayNetIncome > 0 ? '+' : ($todayNetIncome < 0 ? '-' : '') }}Rp {{ number_format(abs($todayNetIncome), 0, ',', '.') }}
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
					<p class="card-title">Pengguna Transaksi Teratas</p>
					<p class="card-subtitle">Daftar pengguna dengan nilai volume penjualan kasir terbesar</p>
				</div>
				<span class="card-badge">
					<i data-lucide="award" style="width: 14px; height: 14px;"></i>
					<span>Top 5 Pengguna</span>
				</span>
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
							<p class="customer-transaction">{{ $user->total_transaksi_keluar ?? 0 }} Transaksi Penjualan</p>
						</div>
					</li>
				@empty
					<li>
						<div class="empty-state">
							<p class="empty-state-title">Belum ada data transaksi</p>
							<p class="empty-state-text">Data performa pengguna akan tampil otomatis setelah ada transaksi penjualan di periode ini.</p>
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
		});
	</script>
@endsection
