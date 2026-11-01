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

		/* Top Action & Filter Bar */
		.shadcn-action-bar {
			display: flex;
			justify-content: flex-start;
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
		<!-- Top Action & Filter Bar (Gaya Shadcn UI) -->
		<div class="shadcn-action-bar">
			<form action="{{ route('dashboard') }}" method="GET" class="shadcn-filter-group">
				<select name="period" id="db-period-select" class="shadcn-period-select">
					<option value="today" {{ ($period ?? 'month') === 'today' ? 'selected' : '' }}>Hari Ini</option>
					<option value="week" {{ ($period ?? 'month') === 'week' ? 'selected' : '' }}>Minggu Ini</option>
					<option value="month" {{ ($period ?? 'month') === 'month' ? 'selected' : '' }}>Bulan Ini</option>
					<option value="year" {{ ($period ?? 'month') === 'year' ? 'selected' : '' }}>Tahun Ini</option>
					<option value="all" {{ ($period ?? 'month') === 'all' ? 'selected' : '' }}>Semua Data</option>
					<option value="custom" {{ ($period ?? 'month') === 'custom' ? 'selected' : '' }}>Rentang Khusus</option>
				</select>

				<div id="db-custom-range" class="shadcn-custom-box {{ ($period ?? 'month') === 'custom' ? 'active' : '' }}" style="margin-bottom: 0; padding: 4px 8px;">
					<input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="shadcn-date-input">
					<span style="font-size: 0.8rem; color: #71717A;">s/d</span>
					<input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="shadcn-date-input">
				</div>

				<button type="submit" class="btn-shadcn-black">
					<i data-lucide="filter" style="width: 14px; height: 14px;"></i>
					<span>Terapkan</span>
				</button>
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
	</script>
@endsection
