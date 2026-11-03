@extends('layouts.horizon')

@section('page_title', 'Laporan Produk Terlaris')
@section('page_subtitle', 'Daftar produk paling diminati berdasarkan total unit terjual.')

@section('content')
<style>
	/* Filter Toolbar & Actions (Matching Superadmin Style) */
	.pt-action-bar {
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
</style>

<!-- Toolbar Filter Periode & Aksi (Gaya Superadmin) -->
<div class="pt-action-bar">
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
		<button type="button" id="btn-toggle-pt-filter" class="btn-filter-trigger {{ $isFilterActive ? 'is-active' : '' }}" onclick="togglePtFilter()" title="Buka / Tutup Panel Filter">
			<i data-lucide="filter" class="w-4 h-4"></i>
			<span>Filter</span>
			@if($isFilterActive)
				<span class="inline-block w-2 h-2 rounded-full bg-amber-300 ml-0.5"></span>
			@endif
		</button>
	</div>
</div>

<!-- Collapsible Filter Panel -->
<div id="pt-filter-panel" class="superadmin-filter-panel" style="{{ $isFilterActive ? 'display: block;' : 'display: none;' }}">
	<form method="GET" action="{{ route('laporan.produk-terlaris') }}" id="pt-filter-form">
		<div class="filter-grid-row-bottom">
			<div class="filter-field-group">
				<label for="pt_filter_period" class="filter-field-label">Periode Waktu</label>
				<select name="period" id="pt_filter_period" class="filter-select" onchange="handlePtPeriodChange(this.value)">
					<option value="month" {{ ($period ?? 'month') === 'month' ? 'selected' : '' }}>Bulan Ini</option>
					<option value="today" {{ ($period ?? '') === 'today' ? 'selected' : '' }}>Hari Ini</option>
					<option value="week" {{ ($period ?? '') === 'week' ? 'selected' : '' }}>Minggu Ini</option>
					<option value="year" {{ ($period ?? '') === 'year' ? 'selected' : '' }}>Tahun Ini</option>
					<option value="all" {{ ($period ?? '') === 'all' ? 'selected' : '' }}>Semua Data</option>
					<option value="custom" {{ ($period ?? '') === 'custom' ? 'selected' : '' }}>Rentang Khusus</option>
				</select>
			</div>

			<div class="filter-field-group">
				<label for="pt_filter_start_date" class="filter-field-label">Tanggal Awal</label>
				<input type="date" name="start_date" id="pt_filter_start_date" value="{{ $startDate ?? '' }}" class="filter-input-date" onchange="markPtPeriodAsCustom()">
			</div>

			<div class="filter-field-group">
				<label for="pt_filter_end_date" class="filter-field-label">Tanggal Akhir</label>
				<input type="date" name="end_date" id="pt_filter_end_date" value="{{ $endDate ?? '' }}" class="filter-input-date" onchange="markPtPeriodAsCustom()">
			</div>

			<div class="filter-buttons-group">
				<a href="{{ route('laporan.produk-terlaris') }}" class="btn-filter-reset" title="Kembalikan Filter ke Awal">
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

<section class="hz-secondary-grid" style="margin-top: 24px;">
	<div class="hz-card" style="grid-column: 1 / -1; padding: 24px;">
		<div class="hz-card-header" style="margin-bottom: 16px;">
			<h3 class="hz-card-title">Peringkat Produk Terlaris</h3>
			<p class="hz-card-sub">Data diperbarui otomatis berdasarkan transaksi penjualan kasir.</p>
		</div>
		<div style="overflow-x: auto;">
			<table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
				<thead>
					<tr style="border-bottom: 1px solid #E2E8F0; background: #F8FAFC; color: #475569; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">
						<th style="text-align: left; padding: 14px 12px; font-weight: 600;">Produk</th>
						<th style="text-align: left; padding: 14px 12px; font-weight: 600;">Tipe</th>
						<th style="text-align: right; padding: 14px 12px; font-weight: 600;">Jumlah Terjual</th>
						<th style="text-align: right; padding: 14px 12px; font-weight: 600;">Total Revenue</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($products as $product)
						<tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease;">
							<td style="padding: 14px 12px; font-weight: 600; color: #0F223D;">{{ $product->nama_produk }}</td>
							<td style="padding: 14px 12px; color: #64748B;">
								<span class="hz-pill-info" style="text-transform: capitalize;">{{ $product->tipe }}</span>
							</td>
							<td style="padding: 14px 12px; text-align: right; font-weight: 600; color: #0F223D;">{{ number_format($product->total_qty, 0, ',', '.') }} unit</td>
							<td style="padding: 14px 12px; text-align: right; font-weight: 600; color: #0F223D;">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="4" style="padding: 40px 12px; text-align: center; color: #64748b;">Belum ada data penjualan pada periode ini.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</section>
@endsection

@push('scripts')
<script>
	// Toggle Filter Panel Produk Terlaris
	function togglePtFilter() {
		var panel = document.getElementById('pt-filter-panel');
		var btn = document.getElementById('btn-toggle-pt-filter');
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

	function handlePtPeriodChange(period) {
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
			var startInput = document.getElementById('pt_filter_start_date');
			if (startInput) startInput.focus();
			return;
		}

		var startEl = document.getElementById('pt_filter_start_date');
		var endEl = document.getElementById('pt_filter_end_date');
		if (startEl) startEl.value = formatDate(start);
		if (endEl) endEl.value = formatDate(end);
	}

	function markPtPeriodAsCustom() {
		var periodSelect = document.getElementById('pt_filter_period');
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
