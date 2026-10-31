@extends('layouts.horizon')

@section('page_title', 'Dashboard')
@section('page_subtitle', 'Pantau performa sistem, aktivitas pengguna, dan arus transaksi keuangan.')

@section('content')
	<style>
		.shadcn-container {
			max-width: 100%;
			padding: 4px 0 24px 0;
			font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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

		.shadcn-filter-group {
			display: flex;
			align-items: center;
			gap: 8px;
			flex-wrap: wrap;
		}

		.shadcn-period-select {
			background: #FFFFFF;
			border: 1px solid #E4E4E7;
			border-radius: 8px;
			color: #09090B;
			padding: 8px 14px;
			font-size: 0.825rem;
			font-weight: 500;
			outline: none;
			cursor: pointer;
			font-family: inherit;
			box-shadow: 0 1px 2px rgba(0,0,0,0.03);
			transition: all 0.15s ease;
		}

		.shadcn-period-select:focus {
			border-color: #09090B;
			box-shadow: 0 0 0 2px rgba(9, 9, 11, 0.1);
		}

		.btn-shadcn-black {
			background: #09090B;
			color: #FFFFFF;
			border: none;
			border-radius: 8px;
			padding: 8px 16px;
			font-size: 0.825rem;
			font-weight: 600;
			cursor: pointer;
			display: inline-flex;
			align-items: center;
			gap: 8px;
			transition: all 0.15s ease;
			box-shadow: 0 1px 3px rgba(0,0,0,0.12);
			font-family: inherit;
			text-decoration: none;
		}

		.btn-shadcn-black:hover {
			background: #27272A;
			box-shadow: 0 0 14px rgba(9, 9, 11, 0.25);
		}

		/* Custom Date Filter Box */
		.shadcn-custom-box {
			display: none;
			align-items: center;
			gap: 8px;
			margin-bottom: 16px;
			padding: 10px 14px;
			background: #FFFFFF;
			border: 1px solid #E4E4E7;
			border-radius: 8px;
			width: fit-content;
		}

		.shadcn-custom-box.active {
			display: flex;
		}

		.shadcn-date-input {
			border: 1px solid #E4E4E7;
			border-radius: 6px;
			padding: 6px 10px;
			font-size: 0.8rem;
			color: #09090B;
			outline: none;
			font-family: inherit;
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
		<!-- Top Action & Filter Bar -->
		<div class="shadcn-action-bar">
			<div class="shadcn-filter-group">
				<select name="period" id="shadcn-period-select" class="shadcn-period-select" onchange="handlePeriodChange(this.value)">
					<option value="today" {{ ($period ?? 'month') === 'today' ? 'selected' : '' }}>Hari Ini</option>
					<option value="week" {{ ($period ?? 'month') === 'week' ? 'selected' : '' }}>Minggu Ini</option>
					<option value="month" {{ ($period ?? 'month') === 'month' ? 'selected' : '' }}>Bulan Ini</option>
					<option value="year" {{ ($period ?? 'month') === 'year' ? 'selected' : '' }}>Tahun Ini</option>
					<option value="all" {{ ($period ?? 'month') === 'all' ? 'selected' : '' }}>Semua Data</option>
					<option value="custom" {{ ($period ?? 'month') === 'custom' ? 'selected' : '' }}>Rentang Khusus</option>
				</select>
			</div>
			<div>
				<button type="button" class="btn-shadcn-black" onclick="openDownloadReportModal()" title="Unduh Laporan Pemesanan">
					<i data-lucide="download"></i>
					<span>Unduh Laporan</span>
				</button>
			</div>
		</div>

		<!-- Kotak Filter Rentang Khusus -->
		<div id="shadcn-custom-range" class="shadcn-custom-box {{ ($period ?? 'month') === 'custom' ? 'active' : '' }}">
			<input type="date" id="shadcn_start_date" value="{{ $startDate ?? '' }}" class="shadcn-date-input">
			<span style="font-size: 0.8rem; color: #71717A;">s/d</span>
			<input type="date" id="shadcn_end_date" value="{{ $endDate ?? '' }}" class="shadcn-date-input">
			<button type="button" class="btn-shadcn-black" style="padding: 6px 12px; font-size: 0.78rem;" onclick="applyShadcnCustomDate()">
				Terapkan Filter
			</button>
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
					<a href="{{ route('dashboard-superadmin.export-pdf', ($period === 'custom' && !empty($startDate) && !empty($endDate)) ? ['scope' => 'period', 'start_date' => $startDate, 'end_date' => $endDate] : []) }}" 
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
					<a href="{{ route('dashboard-superadmin.export-excel', ($period === 'custom' && !empty($startDate) && !empty($endDate)) ? ['scope' => 'period', 'start_date' => $startDate, 'end_date' => $endDate] : []) }}" 
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

		// Handle Period Change
		function handlePeriodChange(period) {
			if (period === 'custom') {
				document.getElementById('shadcn-custom-range').classList.add('active');
			} else {
				document.getElementById('shadcn-custom-range').classList.remove('active');
				const url = new URL(window.location);
				url.searchParams.set('period', period);
				window.location = url.toString();
			}
		}

		// Apply Custom Date Range
		function applyShadcnCustomDate() {
			const start = document.getElementById('shadcn_start_date').value;
			const end = document.getElementById('shadcn_end_date').value;
			if (!start || !end) {
				alert('Silakan pilih tanggal awal dan tanggal akhir.');
				return;
			}
			const url = new URL(window.location);
			url.searchParams.set('period', 'custom');
			url.searchParams.set('start_date', start);
			url.searchParams.set('end_date', end);
			window.location = url.toString();
		}

		// Initialize Chart.js Shadcn Bar Chart
		document.addEventListener('DOMContentLoaded', function () {
			if (typeof lucide !== 'undefined') {
				lucide.createIcons();
			}

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
		});
	</script>
@endsection
