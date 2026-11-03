@extends('layouts.horizon')

@section('page_title', 'Dashboard')
@section('page_subtitle', 'Pantau performa sistem, aktivitas pengguna, dan arus transaksi keuangan.')

@section('content')
	<style>
		.shadcn-container {
			max-width: 100%;
			padding: 4px 0 24px 0;
			font-family: 'Geist', 'Geist Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
		}

		/* Top Action & Filter Bar */
		.shadcn-action-bar {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 22px;
			flex-wrap: wrap;
			gap: 12px;
		}

		/* Filter Action Bar (Reference Gambar 2) */
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
			font-family: inherit;
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
			font-family: inherit;
			text-decoration: none;
		}

		.btn-outline-action:hover {
			background: #F4F4F5;
			border-color: #D4D4D8;
		}

		/* Collapsible Filter Panel (Reference Gambar 3) */
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

		.filter-grid-row {
			display: grid;
			grid-template-columns: repeat(3, 1fr);
			gap: 20px;
			margin-bottom: 18px;
		}

		.filter-grid-row-bottom {
			display: grid;
			grid-template-columns: 1fr 1fr 1.2fr;
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
			font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
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

		/* 4 Top KPI Cards Grid */
		.shadcn-cards-grid {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			gap: 16px;
			margin-bottom: 22px;
		}

		.shadcn-card {
			background: #FFFFFF;
			border: 1px solid #E4E4E7;
			border-radius: 12px;
			padding: 20px 22px;
			box-shadow: 0 1px 3px rgba(0,0,0,0.02);
			transition: border-color 0.2s ease, box-shadow 0.2s ease;
		}

		.shadcn-card:hover {
			border-color: rgba(197, 160, 89, 0.55);
			box-shadow: 0 0 16px rgba(197, 160, 89, 0.18), 0 2px 8px rgba(15, 34, 61, 0.04);
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

		/* 2 Column Main Section (Overview & Recent Sales) */
		.shadcn-main-grid {
			display: grid;
			grid-template-columns: 4fr 3fr;
			gap: 16px;
			margin-bottom: 22px;
		}

		.shadcn-panel-card {
			background: #FFFFFF;
			border: 1px solid #E4E4E7;
			border-radius: 12px;
			padding: 24px;
			box-shadow: 0 1px 3px rgba(0,0,0,0.02);
			transition: border-color 0.2s ease, box-shadow 0.2s ease;
		}

		.shadcn-panel-card:hover {
			border-color: rgba(197, 160, 89, 0.45);
			box-shadow: 0 0 16px rgba(197, 160, 89, 0.12), 0 2px 8px rgba(15, 34, 61, 0.03);
		}

		.shadcn-panel-header {
			margin-bottom: 20px;
		}

		.shadcn-panel-title {
			font-size: 1rem;
			font-weight: 600;
			color: #09090B;
			margin: 0;
		}

		.shadcn-panel-subtitle {
			font-size: 0.825rem;
			color: #71717A;
			margin: 4px 0 0 0;
		}

		/* Recent Sales / Merchant List */
		.shadcn-sales-list {
			display: flex;
			flex-direction: column;
			gap: 16px;
		}

		.shadcn-sale-item {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 12px;
		}

		.shadcn-sale-left {
			display: flex;
			align-items: center;
			gap: 12px;
			min-width: 0;
		}

		.shadcn-sale-avatar {
			width: 38px;
			height: 38px;
			border-radius: 9999px;
			background: #F4F4F5;
			color: #09090B;
			display: flex;
			align-items: center;
			justify-content: center;
			font-weight: 600;
			font-size: 0.8rem;
			flex-shrink: 0;
			border: 1px solid #E4E4E7;
		}

		.shadcn-sale-meta {
			min-width: 0;
		}

		.shadcn-sale-name {
			font-size: 0.875rem;
			font-weight: 600;
			color: #09090B;
			margin: 0;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.shadcn-sale-email {
			font-size: 0.775rem;
			color: #71717A;
			margin: 2px 0 0 0;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.shadcn-sale-amount {
			font-size: 0.95rem;
			font-weight: 600;
			color: #09090B;
			white-space: nowrap;
			text-align: right;
		}

		/* Chart Container */
		.shadcn-chart-box {
			position: relative;
			width: 100%;
			height: 290px;
		}

		/* Responsive Breakdown */
		@media (max-width: 1100px) {
			.shadcn-cards-grid {
				grid-template-columns: repeat(2, 1fr);
			}
			.shadcn-main-grid {
				grid-template-columns: 1fr;
			}
		}

		@media (max-width: 640px) {
			.shadcn-cards-grid {
				grid-template-columns: 1fr;
			}
			.shadcn-action-bar {
				flex-direction: column;
				align-items: stretch;
			}
			.shadcn-filter-group {
				width: 100%;
			}
			.shadcn-period-select {
				flex: 1;
			}
		}
	</style>

	<div class="shadcn-container">
		<!-- Top Action & Filter Header Bar (Reference Gambar 2) -->
		<div class="shadcn-action-bar">
			<div class="flex items-center gap-3">
				@if($isFilterActive)
					<div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-full text-xs font-medium text-blue-800">
						<span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
						<span>Filter Aktif: <strong>{{ $period === 'custom' ? 'Rentang Khusus' : ($period === 'month' ? 'Bulan Ini' : ($period === 'today' ? 'Hari Ini' : ($period === 'week' ? 'Minggu Ini' : ($period === 'year' ? 'Tahun Ini' : 'Semua Data')))) }}</strong></span>
						@if($paketId && $paketId !== 'all')
							<span class="text-blue-400">•</span>
							<span>Paket: <strong>{{ $daftarPaket->firstWhere('id', $paketId)?->nama_paket ?? 'Khusus' }}</strong></span>
						@endif
						@if($statusLangganan && $statusLangganan !== 'all')
							<span class="text-blue-400">•</span>
							<span>Status: <strong>{{ ucfirst($statusLangganan) }}</strong></span>
						@endif
					</div>
				@endif
			</div>

			<div class="filter-action-wrapper">
				<button type="button" class="btn-outline-action" onclick="openDownloadReportModal()" title="Unduh Laporan Pemesanan">
					<i data-lucide="download" class="w-4 h-4"></i>
					<span>Unduh Laporan</span>
				</button>
				<button type="button" id="btn-toggle-filter" class="btn-filter-trigger {{ $isFilterActive ? 'is-active' : '' }}" onclick="toggleSuperadminFilter()" title="Buka / Tutup Panel Filter">
					<i data-lucide="filter" class="w-4 h-4"></i>
					<span>Filter</span>
					@if($isFilterActive)
						<span class="inline-block w-2 h-2 rounded-full bg-amber-300 ml-0.5"></span>
					@endif
				</button>
			</div>
		</div>

		<!-- Collapsible Filter Panel (Reference Gambar 3) -->
		<div id="superadmin-filter-panel" class="superadmin-filter-panel" style="{{ $isFilterActive ? 'display: block;' : 'display: none;' }}">
			<form method="GET" action="{{ route('dashboard-superadmin') }}" id="superadmin-filter-form">
				<!-- Baris 1: 3 Dropdown (Status, Paket, Periode) -->
				<div class="filter-grid-row">
					<div class="filter-field-group">
						<label for="filter_status" class="filter-field-label">Status Langganan</label>
						<select name="status_langganan" id="filter_status" class="filter-select">
							<option value="all" {{ ($statusLangganan ?? 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
							<option value="active" {{ ($statusLangganan ?? '') === 'active' ? 'selected' : '' }}>Berlangganan Aktif</option>
							<option value="trial" {{ ($statusLangganan ?? '') === 'trial' ? 'selected' : '' }}>Uji Coba (Trial)</option>
							<option value="expired" {{ ($statusLangganan ?? '') === 'expired' ? 'selected' : '' }}>Kedaluwarsa</option>
						</select>
					</div>

					<div class="filter-field-group">
						<label for="filter_paket" class="filter-field-label">Paket Langganan</label>
						<select name="paket_id" id="filter_paket" class="filter-select">
							<option value="all" {{ ($paketId ?? 'all') === 'all' ? 'selected' : '' }}>Semua Paket</option>
							@foreach($daftarPaket as $p)
								<option value="{{ $p->id }}" {{ ($paketId ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nama_paket }}</option>
							@endforeach
						</select>
					</div>

					<div class="filter-field-group">
						<label for="filter_period" class="filter-field-label">Periode Waktu</label>
						<select name="period" id="filter_period" class="filter-select" onchange="handleFilterPeriodChange(this.value)">
							<option value="month" {{ ($period ?? 'month') === 'month' ? 'selected' : '' }}>Bulan Ini</option>
							<option value="today" {{ ($period ?? '') === 'today' ? 'selected' : '' }}>Hari Ini</option>
							<option value="week" {{ ($period ?? '') === 'week' ? 'selected' : '' }}>Minggu Ini</option>
							<option value="year" {{ ($period ?? '') === 'year' ? 'selected' : '' }}>Tahun Ini</option>
							<option value="all" {{ ($period ?? '') === 'all' ? 'selected' : '' }}>Semua Data</option>
							<option value="custom" {{ ($period ?? '') === 'custom' ? 'selected' : '' }}>Rentang Khusus</option>
						</select>
					</div>
				</div>

				<!-- Baris 2: Tanggal Awal, Tanggal Akhir, dan Tombol Aksi -->
				<div class="filter-grid-row-bottom">
					<div class="filter-field-group">
						<label for="filter_start_date" class="filter-field-label">Tanggal Awal</label>
						<input type="date" name="start_date" id="filter_start_date" value="{{ $startDate ?? '' }}" class="filter-input-date" onchange="markPeriodAsCustom()">
					</div>

					<div class="filter-field-group">
						<label for="filter_end_date" class="filter-field-label">Tanggal Akhir</label>
						<input type="date" name="end_date" id="filter_end_date" value="{{ $endDate ?? '' }}" class="filter-input-date" onchange="markPeriodAsCustom()">
					</div>

					<div class="filter-buttons-group">
						<a href="{{ route('dashboard-superadmin') }}" class="btn-filter-reset" title="Kembalikan Filter ke Awal">
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

		<!-- 4 Kartu Metrik Utama (Gaya Shadcn UI) -->
		<div class="shadcn-cards-grid">
			<!-- Card 1: Total Pendapatan Langganan -->
			<div class="shadcn-card">
				<div class="shadcn-card-top">
					<span class="shadcn-card-label">Total Pendapatan</span>
					<div class="shadcn-card-icon">
						<i data-lucide="dollar-sign"></i>
					</div>
				</div>
				<div class="shadcn-card-value">Rp {{ number_format($subscriptionRevenue, 0, ',', '.') }}</div>
				<p class="shadcn-card-subtext">
					+{{ $todaySubscriptionRevenue > 0 ? 'Rp ' . number_format($todaySubscriptionRevenue, 0, ',', '.') : 'Rp 0' }} hari ini
				</p>
			</div>

			<!-- Card 2: Merchant Berlangganan -->
			<div class="shadcn-card">
				<div class="shadcn-card-top">
					<span class="shadcn-card-label">Merchant Berlangganan</span>
					<div class="shadcn-card-icon">
						<i data-lucide="users"></i>
					</div>
				</div>
				<div class="shadcn-card-value">+{{ $totalSubscribedUsers }}</div>
				<p class="shadcn-card-subtext">
					{{ $totalActiveUsers > 0 ? number_format(($totalSubscribedUsers / $totalActiveUsers) * 100, 1) : 0 }}% dari total pengguna aktif
				</p>
			</div>

			<!-- Card 3: Penjualan Kasir -->
			<div class="shadcn-card">
				<div class="shadcn-card-top">
					<span class="shadcn-card-label">Penjualan Kasir (Sistem)</span>
					<div class="shadcn-card-icon">
						<i data-lucide="credit-card"></i>
					</div>
				</div>
				<div class="shadcn-card-value">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</div>
				<p class="shadcn-card-subtext">
					Total volume transaksi penjualan selesai
				</p>
			</div>

			<!-- Card 4: Pengguna Aktif -->
			<div class="shadcn-card">
				<div class="shadcn-card-top">
					<span class="shadcn-card-label">Pengguna Aktif</span>
					<div class="shadcn-card-icon">
						<i data-lucide="activity"></i>
					</div>
				</div>
				<div class="shadcn-card-value">+{{ $totalActiveUsers }}</div>
				<p class="shadcn-card-subtext">
					+{{ $todayNewUsers }} akun baru hari ini
				</p>
			</div>
		</div>

		<!-- Bagian Utama: Grafik Ikhtisar & Merchant Teratas -->
		<div class="shadcn-main-grid">
			<!-- Kolom Kiri: Grafik Ikhtisar Penjualan -->
			<div class="shadcn-panel-card">
				<div class="shadcn-panel-header">
					<h3 class="shadcn-panel-title">Ikhtisar Penjualan</h3>
					<p class="shadcn-panel-subtitle">Pergerakan arus transaksi sistem 7 hari terakhir</p>
				</div>
				<div class="shadcn-chart-box">
					<canvas id="shadcnOverviewChart"></canvas>
				</div>
			</div>

			<!-- Kolom Kanan: Pengguna Transaksi Teratas -->
			<div class="shadcn-panel-card">
				<div class="shadcn-panel-header">
					<h3 class="shadcn-panel-title">Pengguna Transaksi Teratas</h3>
					<p class="shadcn-panel-subtitle">
						Daftar {{ $topUsers->count() }} merchant dengan nilai transaksi terbesar di periode ini.
					</p>
				</div>

				<div class="shadcn-sales-list">
					@forelse($topUsers as $user)
						@php
							$nameParts = explode(' ', trim($user->name));
							$initials = strtoupper(substr($nameParts[0] ?? 'U', 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
						@endphp
						<div class="shadcn-sale-item">
							<div class="shadcn-sale-left">
								<div class="shadcn-sale-avatar">
									{{ $initials ?: 'UM' }}
								</div>
								<div class="shadcn-sale-meta">
									<p class="shadcn-sale-name">{{ $user->name }}</p>
									<p class="shadcn-sale-email">{{ $user->email }}</p>
								</div>
							</div>
							<div class="shadcn-sale-amount">
								+Rp {{ number_format($user->total_revenue ?? 0, 0, ',', '.') }}
							</div>
						</div>
					@empty
						<div style="text-align: center; padding: 40px 10px; color: #71717A;">
							<i data-lucide="inbox" style="width: 32px; height: 32px; margin: 0 auto 8px auto; opacity: 0.5;"></i>
							<p style="font-size: 0.875rem; margin: 0;">Belum ada riwayat transaksi penjualan.</p>
						</div>
					@endforelse
				</div>
			</div>
		</div>

		<!-- Rincian Finansial Tambahan (Arus Kas & Laba Bersih) -->
		<div class="shadcn-panel-card" style="margin-top: 16px;">
			<div class="shadcn-panel-header">
				<h3 class="shadcn-panel-title">Ringkasan Arus Kas Sistem</h3>
				<p class="shadcn-panel-subtitle">Total pembelian barang, penjualan, dan laba operasional sistem.</p>
			</div>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
				<div style="background: #F4F4F5; border-radius: 10px; padding: 18px;">
					<p style="font-size: 0.75rem; color: #71717A; margin: 0 0 6px 0; text-transform: uppercase; font-weight: 600;">Arus Kas Masuk (Pembelian)</p>
					<p style="font-size: 1.35rem; font-weight: 700; color: #09090B; margin: 0;">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
				</div>
				<div style="background: #F4F4F5; border-radius: 10px; padding: 18px;">
					<p style="font-size: 0.75rem; color: #71717A; margin: 0 0 6px 0; text-transform: uppercase; font-weight: 600;">Arus Kas Keluar (Penjualan)</p>
					<p style="font-size: 1.35rem; font-weight: 700; color: #09090B; margin: 0;">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
				</div>
				<div style="background: #F4F4F5; border-radius: 10px; padding: 18px;">
					<p style="font-size: 0.75rem; color: #71717A; margin: 0 0 6px 0; text-transform: uppercase; font-weight: 600;">Laba Bersih Sistem</p>
					<p style="font-size: 1.35rem; font-weight: 700; color: {{ $netIncome >= 0 ? '#16A34A' : '#DC2626' }}; margin: 0;">
						{{ $netIncome >= 0 ? '+' : '' }}Rp {{ number_format($netIncome, 0, ',', '.') }}
					</p>
				</div>
			</div>
		</div>

		<!-- Modal Dialog Pilih Format Unduh Laporan (Shadcn Style) -->
		<div id="download-report-modal" class="shadcn-modal-backdrop" style="display: none;" onclick="if(event.target === this) closeDownloadReportModal()">
			<div class="shadcn-modal-card" style="max-width: 440px;" role="dialog" aria-modal="true" aria-labelledby="download-report-title">
				<div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px;">
					<div>
						<h3 id="download-report-title" class="shadcn-modal-title" style="margin: 0; font-size: 1.15rem;">Unduh Laporan Pemesanan</h3>
						<p class="shadcn-modal-description" style="margin: 4px 0 0 0; font-size: 0.825rem;">
							Pilih format file laporan pemesanan yang ingin diunduh:
						</p>
					</div>
					<button type="button" onclick="closeDownloadReportModal()" style="border: none; background: transparent; color: #71717A; cursor: pointer; padding: 4px; border-radius: 6px; display: flex; align-items: center; justify-content: center;" onmouseover="this.style.background='#F4F4F5'" onmouseout="this.style.background='transparent'">
						<i data-lucide="x" style="width: 18px; height: 18px;"></i>
					</button>
				</div>

				<div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">
					<!-- Opsi PDF -->
					<a href="{{ route('dashboard-superadmin.export-pdf', ['scope' => 'period', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
					   onclick="closeDownloadReportModal()"
					   style="display: flex; align-items: center; gap: 14px; padding: 14px 16px; border: 1px solid #E4E4E7; border-radius: 10px; background: #FFFFFF; text-decoration: none; transition: border-color 0.2s ease, box-shadow 0.2s ease;"
					   onmouseover="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 14px rgba(197, 160, 89, 0.25)'"
					   onmouseout="this.style.borderColor='#E4E4E7'; this.style.boxShadow='none'">
						<div style="width: 42px; height: 42px; border-radius: 10px; background: #FEF2F2; color: #DC2626; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid #FEE2E2;">
							<i data-lucide="file-text" style="width: 22px; height: 22px;"></i>
						</div>
						<div style="flex: 1; min-width: 0;">
							<div style="display: flex; align-items: center; justify-content: space-between;">
								<span style="font-weight: 600; font-size: 0.9rem; color: #09090B;">Dokumen PDF (.pdf)</span>
								<span style="font-size: 0.72rem; font-weight: 600; padding: 2px 7px; border-radius: 4px; background: #F4F4F5; color: #71717A; border: 1px solid #E4E4E7;">Siap Cetak</span>
							</div>
							<p style="margin: 3px 0 0 0; font-size: 0.78rem; color: #71717A;">
								Format dokumen resmi dengan kop surat, tabel rapi, dan pengesahan.
							</p>
						</div>
					</a>

					<!-- Opsi Excel / CSV -->
					<a href="{{ route('dashboard-superadmin.export-excel', ['scope' => 'period', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
					   onclick="closeDownloadReportModal()"
					   style="display: flex; align-items: center; gap: 14px; padding: 14px 16px; border: 1px solid #E4E4E7; border-radius: 10px; background: #FFFFFF; text-decoration: none; transition: border-color 0.2s ease, box-shadow 0.2s ease;"
					   onmouseover="this.style.borderColor='#059669'; this.style.boxShadow='0 0 14px rgba(5, 150, 105, 0.22)'"
					   onmouseout="this.style.borderColor='#E4E4E7'; this.style.boxShadow='none'">
						<div style="width: 42px; height: 42px; border-radius: 10px; background: #ECFDF5; color: #059669; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid #D1FAE5;">
							<i data-lucide="file-spreadsheet" style="width: 22px; height: 22px;"></i>
						</div>
						<div style="flex: 1; min-width: 0;">
							<div style="display: flex; align-items: center; justify-content: space-between;">
								<span style="font-weight: 600; font-size: 0.9rem; color: #09090B;">Spreadsheet Excel (.csv)</span>
								<span style="font-size: 0.72rem; font-weight: 600; padding: 2px 7px; border-radius: 4px; background: #F4F4F5; color: #71717A; border: 1px solid #E4E4E7;">Data Mentah</span>
							</div>
							<p style="margin: 3px 0 0 0; font-size: 0.78rem; color: #71717A;">
								Format tabel data untuk analisis angka, filter, dan rekap spreadsheet.
							</p>
						</div>
					</a>
				</div>

				<div style="display: flex; justify-content: flex-end;">
					<button type="button" class="shadcn-modal-btn-cancel" onclick="closeDownloadReportModal()" style="width: 100%;">
						Batal
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Chart.js CDN -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

	<script>
		// Modal Pilih Format Unduh Laporan
		function openDownloadReportModal() {
			var modal = document.getElementById('download-report-modal');
			if (modal) {
				modal.style.display = 'flex';
				if (typeof lucide !== 'undefined') {
					lucide.createIcons();
				}
			}
		}

		function closeDownloadReportModal() {
			var modal = document.getElementById('download-report-modal');
			if (modal) {
				modal.style.display = 'none';
			}
		}

		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape') {
				closeDownloadReportModal();
			}
		});

		// Toggle Buka/Tutup Filter Panel (Reference Gambar 2 & 3)
		function toggleSuperadminFilter() {
			var panel = document.getElementById('superadmin-filter-panel');
			var btn = document.getElementById('btn-toggle-filter');
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

		// Handle Sinkronisasi Dropdown Periode ke Tanggal
		function handleFilterPeriodChange(period) {
			const today = new Date();
			const pad = (n) => String(n).padStart(2, '0');
			const formatDate = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;

			let start = new Date();
			let end = new Date();

			if (period === 'today') {
				// start and end are today
			} else if (period === 'week') {
				const day = today.getDay();
				const diff = today.getDate() - day + (day === 0 ? -6 : 1); // Senin
				start = new Date(today.setDate(diff));
				end = new Date(start);
				end.setDate(start.getDate() + 6); // Minggu
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
				var startInput = document.getElementById('filter_start_date');
				if (startInput) startInput.focus();
				return;
			}

			var startEl = document.getElementById('filter_start_date');
			var endEl = document.getElementById('filter_end_date');
			if (startEl) startEl.value = formatDate(start);
			if (endEl) endEl.value = formatDate(end);
		}

		function markPeriodAsCustom() {
			var periodSelect = document.getElementById('filter_period');
			if (periodSelect) {
				periodSelect.value = 'custom';
			}
		}

		// Initialize Chart.js Shadcn Bar Chart
		function initShadcnOverviewChart() {
			if (typeof lucide !== 'undefined') {
				lucide.createIcons();
			}

			if (typeof Chart === 'undefined') return;

			const ctx = document.getElementById('shadcnOverviewChart');
			if (ctx) {
				const labels = {!! json_encode($chartsLabels ?? ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']) !!};
				const dataKeluar = {!! json_encode($chartsKeluar ?? [0, 0, 0, 0, 0, 0, 0]) !!};

				new Chart(ctx, {
					type: 'bar',
					data: {
						labels: labels,
						datasets: [{
							label: 'Penjualan (Rp)',
							data: dataKeluar,
							backgroundColor: '#09090B',
							hoverBackgroundColor: '#27272A',
							borderRadius: 4,
							borderSkipped: false,
							maxBarThickness: 42
						}]
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						legend: {
							display: false
						},
						tooltips: {
							backgroundColor: '#09090B',
							titleFontFamily: "'Inter', sans-serif",
							bodyFontFamily: "'Inter', sans-serif",
							titleFontSize: 12,
							bodyFontSize: 12,
							cornerRadius: 8,
							xPadding: 12,
							yPadding: 10,
							callbacks: {
								label: function(tooltipItem) {
									return 'Rp ' + Number(tooltipItem.yLabel).toLocaleString('id-ID');
								}
							}
						},
						scales: {
							xAxes: [{
								gridLines: {
									display: false,
									drawBorder: false
								},
								ticks: {
									fontFamily: "'Inter', sans-serif",
									fontSize: 12,
									fontColor: '#71717A'
								}
							}],
							yAxes: [{
								gridLines: {
									color: '#F4F4F5',
									drawBorder: false,
									zeroLineColor: '#F4F4F5'
								},
								ticks: {
									fontFamily: "'Inter', sans-serif",
									fontSize: 11,
									fontColor: '#71717A',
									beginAtZero: true,
									callback: function(value) {
										if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
										if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'k';
										return 'Rp ' + value;
									}
								}
							}]
						}
					}
				});
			}
		}

		initShadcnOverviewChart();
		document.addEventListener('DOMContentLoaded', initShadcnOverviewChart);
	</script>
@endsection
