@extends('layouts.horizon')

@section('page_title', 'Manajemen Paket')
@section('page_subtitle', 'Kelola paket berlangganan merchant seperti Standard, Premium, dan Enterprise.')

@section('content')
@php
	$pakets = $pakets ?? collect();
	$paketCards = method_exists($pakets, 'getCollection') ? $pakets->getCollection() : $pakets;
	$featuredId = $paketCards->sortByDesc('harga')->first()->id ?? null;
@endphp

<style>
	.page-header-row {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 24px;
		gap: 16px;
		flex-wrap: wrap;
	}

	.page-title-block h2 {
		font-family: 'Newsreader', Georgia, serif;
		font-size: 2.2rem;
		font-weight: 500;
		color: #0F223D;
		margin: 0 0 4px 0;
		line-height: 1.2;
	}

	.page-title-block p {
		font-size: 0.925rem;
		color: #64748B;
		margin: 0;
	}

	.header-actions-wrap {
		display: flex;
		align-items: center;
		gap: 10px;
		flex-wrap: wrap;
	}

	.btn-add-primary {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 9px 16px;
		border-radius: 10px;
		background: #0F223D;
		color: #FFFFFF;
		font-family: inherit;
		font-size: 0.825rem;
		font-weight: 600;
		text-decoration: none;
		transition: all 0.15s ease;
		box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15);
	}

	.btn-add-primary:hover {
		background: #162C50;
		transform: translateY(-1px);
		box-shadow: 0 4px 12px rgba(15, 34, 61, 0.2);
	}

	.filter-form-wrap {
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.search-input-wrap {
		position: relative;
		display: inline-flex;
		align-items: center;
	}

	.search-input-wrap svg,
	.search-input-wrap i {
		position: absolute;
		left: 12px;
		top: 50%;
		transform: translateY(-50%);
		width: 15px;
		height: 15px;
		color: #94A3B8;
		pointer-events: none;
		z-index: 5;
	}

	.search-input {
		border-radius: 10px;
		border: 1px solid #CBD5E1;
		background: #FFFFFF;
		padding: 8px 12px 8px 36px !important;
		font-family: inherit;
		font-size: 0.825rem;
		color: #0F223D;
		min-width: 200px;
		outline: none;
		transition: all 0.15s ease;
		box-shadow: 0 1px 2px rgba(15, 34, 61, 0.04);
	}

	.search-input:focus {
		border-color: #0F223D;
		box-shadow: 0 0 0 3px rgba(15, 34, 61, 0.08);
	}

	.select-filter {
		border-radius: 10px;
		border: 1px solid #CBD5E1;
		background: #FFFFFF;
		padding: 8px 10px;
		font-family: inherit;
		font-size: 0.825rem;
		color: #0F223D;
		outline: none;
		cursor: pointer;
		transition: all 0.15s ease;
		box-shadow: 0 1px 2px rgba(15, 34, 61, 0.04);
	}

	.select-filter:focus {
		border-color: #0F223D;
	}

	/* Alerts */
	.flash-alert {
		margin-bottom: 20px;
		padding: 12px 16px;
		border-radius: 10px;
		font-size: 0.85rem;
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.flash-success {
		background: #ECFDF5;
		border: 1px solid #A7F3D0;
		color: #065F46;
	}

	.flash-error {
		background: #FEF2F2;
		border: 1px solid #FECACA;
		color: #991B1B;
	}

	/* Section Cards */
	.content-card {
		background: #FFFFFF;
		border-radius: 18px;
		border: 1px solid rgba(226, 232, 240, 0.95);
		padding: 24px;
		box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04);
		margin-bottom: 24px;
	}

	.content-card-header {
		margin-bottom: 20px;
		padding-bottom: 14px;
		border-bottom: 1px solid #F1F5F9;
	}

	.section-title {
		font-family: 'Newsreader', Georgia, serif;
		font-size: 1.35rem;
		font-weight: 600;
		color: #0F223D;
		margin: 0;
	}

	.section-sub {
		font-size: 0.8rem;
		color: #64748B;
		margin: 3px 0 0;
	}

	/* Pricing Grid */
	.paket-pricing-grid {
		display: grid;
		gap: 20px;
		grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
	}

	/* Standard Package Card */
	.paket-card {
		position: relative;
		border-radius: 18px;
		border: 1px solid rgba(226, 232, 240, 0.95);
		background: #FFFFFF;
		padding: 24px;
		box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04);
		display: flex;
		flex-direction: column;
		gap: 16px;
		overflow: hidden;
		transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
	}

	.paket-card:hover {
		transform: translateY(-4px);
		border-color: rgba(197, 160, 89, 0.4);
		box-shadow: 0 12px 24px -4px rgba(15, 34, 61, 0.08);
	}

	/* FEATURED CARD: Gradasi Emas Mewah (Aksara Luxury Gold) */
	.paket-card-highlight {
		border: 1.5px solid #C5A059 !important;
		box-shadow: 0 16px 36px -8px rgba(197, 160, 89, 0.35), 0 8px 16px -4px rgba(15, 34, 61, 0.08) !important;
	}

	.paket-card-highlight:hover {
		transform: translateY(-4px);
		box-shadow: 0 20px 40px -6px rgba(197, 160, 89, 0.45), 0 10px 20px -4px rgba(15, 34, 61, 0.12) !important;
	}

	.paket-highlight-tag {
		background: #0F223D;
		color: #FDE68A;
		font-size: 0.68rem;
		font-weight: 700;
		padding: 4px 10px;
		border-radius: 9999px;
		letter-spacing: 0.04em;
		display: inline-flex;
		align-items: center;
		gap: 5px;
		box-shadow: 0 2px 6px rgba(15, 34, 61, 0.2);
		border: 1px solid rgba(197, 160, 89, 0.5);
		white-space: nowrap;
		flex-shrink: 0;
	}

	.paket-card-head {
		display: flex;
		flex-direction: column;
		gap: 8px;
	}

	.paket-badge {
		align-self: flex-start;
		padding: 4px 10px;
		border-radius: 9999px;
		font-size: 0.7rem;
		font-weight: 700;
		letter-spacing: 0.06em;
		text-transform: uppercase;
		white-space: normal;
		line-height: 1.3;
	}

	.paket-price {
		font-size: 1.85rem;
		font-weight: 800;
		color: #0F223D;
		margin: 4px 0 0;
		line-height: 1.1;
		letter-spacing: -0.02em;
	}

	.paket-price span {
		font-size: 0.825rem;
		font-weight: 500;
		color: #64748B;
	}

	.paket-card-highlight .paket-price span {
		color: rgba(15, 34, 61, 0.75);
	}

	.paket-caption {
		font-size: 0.8rem;
		color: #64748B;
		margin: 0;
		line-height: 1.4;
	}

	.paket-card-highlight .paket-caption {
		color: rgba(15, 34, 61, 0.85);
		font-weight: 500;
	}

	.paket-feature-list {
		list-style: none;
		padding: 14px 0;
		margin: 0;
		display: flex;
		flex-direction: column;
		gap: 10px;
		border-top: 1px solid #F1F5F9;
		border-bottom: 1px solid #F1F5F9;
		font-size: 0.825rem;
		color: #334155;
	}

	.paket-card-highlight .paket-feature-list {
		border-color: rgba(15, 34, 61, 0.15);
		color: #0F223D;
		font-weight: 500;
	}

	.paket-feature-list li {
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.feature-check-icon {
		width: 15px;
		height: 15px;
		color: #059669;
		flex-shrink: 0;
	}

	.paket-card-highlight .feature-check-icon {
		color: #0F223D;
		stroke-width: 2.5;
	}

	.paket-cta {
		margin-top: auto;
		border-radius: 10px;
		border: 1px solid #0F223D;
		background: #0F223D;
		color: #FFFFFF;
		padding: 10px 14px;
		font-family: inherit;
		font-size: 0.825rem;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.15s ease;
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 6px;
		box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15);
	}

	.paket-cta:hover {
		background: #162C50;
		border-color: #162C50;
		transform: translateY(-1px);
		box-shadow: 0 4px 12px rgba(15, 34, 61, 0.2);
	}

	/* Table Styling */
	.custom-table {
		width: 100%;
		border-collapse: separate;
		border-spacing: 0;
		font-size: 0.85rem;
	}

	.custom-table th {
		background: #F8FAFC;
		padding: 12px 14px;
		font-size: 0.72rem;
		font-weight: 700;
		color: #64748B;
		text-transform: uppercase;
		letter-spacing: 0.05em;
		border-bottom: 1px solid #E2E8F0;
	}

	.custom-table th:first-child { border-top-left-radius: 10px; }
	.custom-table th:last-child { border-top-right-radius: 10px; }

	.custom-table td {
		padding: 14px;
		border-bottom: 1px solid #F1F5F9;
		color: #334155;
		vertical-align: middle;
	}

	.custom-table tr:hover td {
		background: #FAF8F5;
	}

	.badge-status-active {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		padding: 3px 8px;
		border-radius: 6px;
		font-size: 0.72rem;
		font-weight: 600;
		background: #ECFDF5;
		color: #065F46;
		border: 1px solid #A7F3D0;
	}

	.badge-status-inactive {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		padding: 3px 8px;
		border-radius: 6px;
		font-size: 0.72rem;
		font-weight: 600;
		background: #FEF2F2;
		color: #991B1B;
		border: 1px solid #FECACA;
	}

	.btn-table-edit {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 6px 10px;
		border-radius: 8px;
		background: #FFFFFF;
		color: #0F223D;
		border: 1px solid #CBD5E1;
		font-family: inherit;
		font-size: 0.78rem;
		font-weight: 600;
		text-decoration: none;
		transition: all 0.15s ease;
	}

	.btn-table-edit:hover {
		background: #F8FAFC;
		border-color: #94A3B8;
	}

	.btn-table-delete {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		padding: 6px 10px;
		border-radius: 8px;
		background: #FEF2F2;
		color: #DC2626;
		border: 1px solid #FECACA;
		font-family: inherit;
		font-size: 0.78rem;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.15s ease;
	}

	.btn-table-delete:hover {
		background: #FEE2E2;
		border-color: #F87171;
	}

	/* Modal Confirmation */
	.custom-modal-backdrop {
		display: none;
		position: fixed;
		inset: 0;
		background: rgba(15, 34, 61, 0.5);
		backdrop-filter: blur(4px);
		z-index: 100;
	}

	.custom-modal-dialog {
		display: none;
		position: fixed;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		background: #FFFFFF;
		border-radius: 16px;
		border: 1px solid #E2E8F0;
		box-shadow: 0 25px 50px -12px rgba(15, 34, 61, 0.25);
		z-index: 101;
		width: 90%;
		max-width: 460px;
		padding: 24px;
	}
</style>

<div class="hz-top-row" style="display:flex; justify-content:flex-end; align-items:center; margin-bottom: 20px;">
	<div class="header-actions-wrap" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
		<a href="{{ route('paket.create') }}" class="btn-add-primary">
			<i data-lucide="plus" style="width: 15px; height: 15px;"></i>
			<span>Tambah Paket Baru</span>
		</a>
		<form method="GET" action="{{ route('paket.index') }}" class="filter-form-wrap">
			<div class="search-input-wrap">
				<i data-lucide="search"></i>
				<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama paket..." class="search-input">
			</div>
			<select name="status" onchange="this.form.submit()" class="select-filter">
				<option value="">Semua Status</option>
				<option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
				<option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
			</select>
			<select name="per_page" onchange="this.form.submit()" class="select-filter">
				<option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10 / hal</option>
				<option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 / hal</option>
				<option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50 / hal</option>
			</select>
		</form>
	</div>
</div>

@if ($message = Session::get('success'))
	<div class="flash-alert flash-success">
		<i data-lucide="check-circle-2" style="width: 16px; height: 16px;"></i>
		<span>{{ $message }}</span>
	</div>
@endif

@if ($message = Session::get('error'))
	<div class="flash-alert flash-error">
		<i data-lucide="alert-circle" style="width: 16px; height: 16px;"></i>
		<span>{{ $message }}</span>
	</div>
@endif

{{-- Section 1: Pilihan Paket Pricing Cards --}}
<div class="content-card">
	<div class="content-card-header">
		<h3 class="section-title">Pilihan Paket Langganan</h3>
		<p class="section-sub">Tampilan kartu paket langganan yang aktif dan siap dipilih oleh pemilik toko.</p>
	</div>

	@php
		$colorThemes = [
			[
				'card_border_top' => '#2563EB', // Sapphire Blue (Paket 1 / Kasir + Keuangan)
				'card_bg' => 'linear-gradient(180deg, #F0F6FF 0%, #FFFFFF 140px)',
				'card_border' => '#BFDBFE',
				'badge_bg' => '#EFF6FF',
				'badge_color' => '#1D4ED8',
				'badge_border' => '#BFDBFE',
				'price_color' => '#1E3A8A',
				'check_color' => '#2563EB',
				'cta_bg' => '#EFF6FF',
				'cta_color' => '#1D4ED8',
				'cta_border' => '#BFDBFE',
				'cta_hover_bg' => '#1D4ED8',
			],
			[
				'card_border_top' => '#0F223D', // Twilight Navy (Premium)
				'card_bg' => 'linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 140px)',
				'card_border' => '#CBD5E1',
				'badge_bg' => '#0F223D',
				'badge_color' => '#FFFFFF',
				'badge_border' => '#0F223D',
				'price_color' => '#0F223D',
				'check_color' => '#0F223D',
				'cta_bg' => '#0F223D',
				'cta_color' => '#FFFFFF',
				'cta_border' => '#0F223D',
				'cta_hover_bg' => '#1E3A8A',
			],
			[
				'card_border_top' => '#059669', // Emerald Jade (Paket 2 / Kasir + Keuangan + Stok)
				'card_bg' => 'linear-gradient(180deg, #ECFDF5 0%, #FFFFFF 140px)',
				'card_border' => '#A7F3D0',
				'badge_bg' => '#ECFDF5',
				'badge_color' => '#047857',
				'badge_border' => '#A7F3D0',
				'price_color' => '#065F46',
				'check_color' => '#059669',
				'cta_bg' => '#ECFDF5',
				'cta_color' => '#047857',
				'cta_border' => '#A7F3D0',
				'cta_hover_bg' => '#047857',
			],
			[
				'card_border_top' => '#C5A059', // Imperial Champagne Gold (Paket 3 / Paling Populer)
				'card_bg' => 'linear-gradient(145deg, #FFFDF8 0%, #FFF9EC 50%, #FAF0DA 100%)',
				'card_border' => '#E0B46A',
				'badge_bg' => '#FEF3C7',
				'badge_color' => '#92400E',
				'badge_border' => '#FDE68A',
				'price_color' => '#78350F',
				'check_color' => '#D97706',
				'cta_bg' => '#0F223D',
				'cta_color' => '#FFFFFF',
				'cta_border' => '#0F223D',
				'cta_hover_bg' => '#162C50',
			],
			[
				'card_border_top' => '#7C3AED', // Regal Amethyst (Trial / Tambahan)
				'card_bg' => 'linear-gradient(180deg, #FAF5FF 0%, #FFFFFF 140px)',
				'card_border' => '#DDD6FE',
				'badge_bg' => '#F5F3FF',
				'badge_color' => '#6D28D9',
				'badge_border' => '#DDD6FE',
				'price_color' => '#4C1D95',
				'check_color' => '#7C3AED',
				'cta_bg' => '#F5F3FF',
				'cta_color' => '#6D28D9',
				'cta_border' => '#DDD6FE',
				'cta_hover_bg' => '#6D28D9',
			],
			[
				'card_border_top' => '#475569', // Pearl Slate
				'card_bg' => 'linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 140px)',
				'card_border' => '#CBD5E1',
				'badge_bg' => '#F1F5F9',
				'badge_color' => '#334155',
				'badge_border' => '#CBD5E1',
				'price_color' => '#0F223D',
				'check_color' => '#475569',
				'cta_bg' => '#FFFFFF',
				'cta_color' => '#334155',
				'cta_border' => '#CBD5E1',
				'cta_hover_bg' => '#334155',
			],
		];
	@endphp

	<div class="paket-pricing-grid">
		@forelse ($paketCards as $index => $card)
			@php
				$isFeatured = $featuredId && $card->id === $featuredId;
				$rawFeatures = trim((string) ($card->deskripsi ?? ''));
				$features = collect(preg_split('/\r\n|\r|\n/', $rawFeatures))
					->map(function ($item) { return trim($item); })
					->filter()
					->values();
				if ($features->isEmpty()) {
					$features = collect([
						'Akses dashboard transaksi kasir',
						'Kontrol stok & inventori produk',
						'Laporan penjualan otomatis',
					]);
				}

				if ($isFeatured) {
					$theme = $colorThemes[3]; // Imperial Gold untuk Featured
				} else {
					$availableThemes = [0, 1, 2, 4, 5];
					$theme = $colorThemes[$availableThemes[$index % count($availableThemes)]];
				}
			@endphp
			<div class="paket-card {{ $isFeatured ? 'paket-card-highlight' : '' }}" 
				style="border-top: 4px solid {{ $theme['card_border_top'] }} !important; border-color: {{ $theme['card_border'] }} !important; background: {{ $theme['card_bg'] }} !important;">
				
				<div class="paket-card-top-bar" style="display: flex; justify-content: space-between; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 6px;">
					<span class="paket-badge" style="background: {{ $theme['badge_bg'] }}; color: {{ $theme['badge_color'] }}; border: 1px solid {{ $theme['badge_border'] }};">
						{{ $card->nama_paket }}
					</span>
					@if ($isFeatured)
						<span class="paket-highlight-tag">
							<i data-lucide="sparkles" style="width: 12px; height: 12px; color: #F59E0B;"></i>
							<span>Paling Populer</span>
						</span>
					@endif
				</div>

				<div class="paket-card-head">
					<p class="paket-price" style="color: {{ $theme['price_color'] }};">
						Rp {{ number_format($card->harga ?? 0, 0, ',', '.') }}<span>/bulan</span>
					</p>
					<p class="paket-caption">
						{{ $features->first() ?? 'Paket berlangganan untuk operasional bisnis.' }}
					</p>
				</div>

				<ul class="paket-feature-list">
					@foreach ($features->take(4) as $feature)
						<li>
							<i data-lucide="check" class="feature-check-icon" style="color: {{ $theme['check_color'] }};"></i>
							<span>{{ $feature }}</span>
						</li>
					@endforeach
				</ul>

				<button type="button" class="paket-cta">
					<span>Aktifkan Paket</span>
				</button>
			</div>
		@empty
			<div style="padding: 32px 14px; text-align: center; color: #94A3B8; grid-column: 1 / -1;">
				<i data-lucide="package-x" style="width: 32px; height: 32px; margin: 0 auto 8px; color: #CBD5E1; display: block;"></i>
				<span style="font-size: 0.9rem;">Belum ada paket langganan yang dibuat.</span>
			</div>
		@endforelse
	</div>
</div>

{{-- Section 2: Tabel Daftar Paket --}}
<div class="content-card">
	<div class="content-card-header">
		<h3 class="section-title">Daftar Paket ({{ $pakets->count() }} paket)</h3>
		<p class="section-sub">Ringkasan konfigurasi paket langganan tersimpan dalam basis data sistem.</p>
	</div>

	<div style="overflow-x: auto;">
		<table class="custom-table">
			<thead>
				<tr>
					<th style="text-align: left;">Nama Paket</th>
					<th style="text-align: left;">Deskripsi</th>
					<th style="text-align: right;">Harga / Bulan</th>
					<th style="text-align: right;">Harga Diskon</th>
					<th style="text-align: center;">Durasi</th>
					<th style="text-align: center;">Status</th>
					<th style="text-align: right;">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($pakets as $paket)
					<tr>
						<td style="font-weight: 700; color: #0F223D;">
							{{ $paket->nama_paket ?? $paket->name ?? '-' }}
						</td>
						<td style="color: #64748B; font-size: 0.825rem; max-width: 250px;">
							{{ $paket->deskripsi ?? $paket->description ?? 'Paket berlangganan standar.' }}
						</td>
						<td style="text-align: right; font-weight: 700; color: #0F223D;">
							{{ isset($paket->harga) ? 'Rp ' . number_format($paket->harga, 0, ',', '.') : '-' }}
						</td>
						<td style="text-align: right;">
							@if ($paket->harga_diskon)
								<div style="color: #059669; font-weight: 700;">
									Rp {{ number_format($paket->harga_diskon, 0, ',', '.') }}
								</div>
								<div style="color: #94A3B8; font-size: 0.75rem; text-decoration: line-through;">
									Rp {{ number_format($paket->harga, 0, ',', '.') }}
								</div>
							@else
								<span style="color: #94A3B8;">-</span>
							@endif
						</td>
						<td style="text-align: center; color: #64748B;">
							{{ $paket->durasi_bulan ?? 1 }} bulan
						</td>
						<td style="text-align: center;">
							@php($status = $paket->is_active ?? 1)
							@if ($status)
								<span class="badge-status-active">Aktif</span>
							@else
								<span class="badge-status-inactive">Nonaktif</span>
							@endif
						</td>
						<td style="text-align: right;">
							<div style="display: flex; justify-content: flex-end; gap: 6px;">
								<a href="{{ route('paket.edit', $paket->id) }}" class="btn-table-edit">
									<i data-lucide="edit-3" style="width: 13px; height: 13px;"></i>
									<span>Edit</span>
								</a>
								<button type="button" class="btn-table-delete" onclick="openDeletePaketModal('{{ route('paket.destroy', $paket->id) }}', '{{ addslashes($paket->nama_paket ?? $paket->name) }}')">
									<i data-lucide="trash-2" style="width: 13px; height: 13px;"></i>
									<span>Hapus</span>
								</button>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="7" style="padding: 32px 14px; text-align: center; color: #94A3B8;">
							Belum ada paket. Silakan tambahkan paket pertama Anda.
						</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>

	@if (method_exists($pakets, 'links'))
		<div style="margin-top: 16px; padding-top: 14px; border-top: 1px solid #F1F5F9; display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; color: #64748B;">
			<div>
				Menampilkan <strong>{{ $pakets->firstItem() ?? 0 }} - {{ $pakets->lastItem() ?? 0 }}</strong> dari total <strong>{{ $pakets->total() }}</strong> paket
			</div>
			<div>
				{{ $pakets->links() }}
			</div>
		</div>
	@endif
</div>

{{-- Modal Konfirmasi Hapus Paket --}}
<div id="deletePaketBackdrop" class="custom-modal-backdrop" onclick="closeDeletePaketModal()"></div>
<div id="deletePaketModal" class="custom-modal-dialog">
	<div style="width: 44px; height: 44px; border-radius: 12px; background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; display: flex; align-items: center; justify-content: center; margin-bottom: 14px;">
		<i data-lucide="alert-triangle" style="width: 22px; height: 22px;"></i>
	</div>
	<h4 style="font-family: 'Newsreader', serif; font-size: 1.35rem; font-weight: 600; color: #0F223D; margin: 0 0 6px;">Konfirmasi Hapus Paket</h4>
	<p style="font-size: 0.85rem; color: #64748B; line-height: 1.5; margin: 0 0 20px;" id="deletePaketDescription">
		Apakah Anda yakin ingin menghapus paket ini?
	</p>

	<form id="deletePaketForm" method="POST" action="" style="display: none;">
		@csrf
		@method('DELETE')
	</form>

	<div style="display: flex; justify-content: flex-end; gap: 10px; padding-top: 14px; border-top: 1px solid #F1F5F9;">
		<button type="button" onclick="closeDeletePaketModal()" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #334155; font-family: inherit; font-size: 0.825rem; font-weight: 600; cursor: pointer;">
			Batal
		</button>
		<button type="button" onclick="executeDeletePaket()" style="padding: 8px 16px; border-radius: 8px; border: none; background: #DC2626; color: #FFFFFF; font-family: inherit; font-size: 0.825rem; font-weight: 600; cursor: pointer;">
			Ya, Hapus Paket
		</button>
	</div>
</div>

<script>
	let targetDeletePaketUrl = '';

	function openDeletePaketModal(actionUrl, paketName) {
		targetDeletePaketUrl = actionUrl;
		document.getElementById('deletePaketDescription').innerHTML = `Apakah Anda yakin ingin menghapus paket <strong>"${paketName}"</strong>? Tindakan ini tidak dapat dibatalkan.`;
		document.getElementById('deletePaketBackdrop').style.display = 'block';
		document.getElementById('deletePaketModal').style.display = 'block';
		if (typeof lucide !== 'undefined') lucide.createIcons();
	}

	function closeDeletePaketModal() {
		document.getElementById('deletePaketBackdrop').style.display = 'none';
		document.getElementById('deletePaketModal').style.display = 'none';
	}

	function executeDeletePaket() {
		const form = document.getElementById('deletePaketForm');
		form.action = targetDeletePaketUrl;
		form.submit();
	}

	document.addEventListener('DOMContentLoaded', function() {
		if (typeof lucide !== 'undefined') {
			lucide.createIcons();
		}
	});
</script>
@endsection
