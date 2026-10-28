@extends('layouts.horizon')

@section('page_title', 'Paket')

@section('content')
@php
	$pakets = $pakets ?? collect();
	$paketCards = method_exists($pakets, 'getCollection') ? $pakets->getCollection() : $pakets;
	$featuredId = $paketCards->sortByDesc('harga')->first()->id ?? null;
@endphp
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Paket Subscribe</h2>
			<p class="hz-top-sub">Kelola paket berlangganan seperti Standart, Premium, dan paket lainnya.</p>
		</div>
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px;">
			<a href="{{ route('paket.create') }}" class="hz-btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; padding: 8px 14px; gap: 8px;">
				<svg width="14" height="14" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M10 4.5V15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
					<path d="M4.5 10H15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
				</svg>
				<span>Tambah Paket</span>
			</a>
			<form method="GET" action="{{ route('paket.index') }}" style="display:flex; align-items:center; gap:6px;">
				<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama paket" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 10px; font-size:0.8rem; color:#1e293b; min-width:220px;">
				<select name="status" onchange="this.form.submit()" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 8px; font-size:0.8rem; color:#1e293b;">
					<option value="">Semua Status</option>
					<option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
					<option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
				</select>
				<select name="per_page" onchange="this.form.submit()" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 8px; font-size:0.8rem; color:#1e293b;">
					<option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
					<option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
					<option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
				</select>
			</form>
		</div>
	</div>

	@if ($message = Session::get('success'))
		<div style="margin-top: 20px; padding: 14px 16px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 10px; color: #16a34a;">
			{{ $message }}
		</div>
	@endif

	@if ($message = Session::get('error'))
		<div style="margin-top: 20px; padding: 14px 16px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 10px; color: #dc2626;">
			{{ $message }}
		</div>
	@endif

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 16px;">
				<div>
					<p class="hz-card-title">Pilihan Paket</p>
					<p class="hz-card-sub">Paket berlangganan yang siap diaktifkan untuk pelanggan.</p>
				</div>
			</div>
			<div class="paket-pricing-grid">
				@forelse ($paketCards as $card)
					@php
						$isFeatured = $featuredId && $card->id === $featuredId;
						$rawFeatures = trim((string) ($card->deskripsi ?? ''));
						$features = collect(preg_split('/\r\n|\r|\n/', $rawFeatures))
							->map(function ($item) {
								return trim($item);
							})
							->filter()
							->values();
						if ($features->isEmpty()) {
							$features = collect([
								'Akses dashboard transaksi',
								'Pengelolaan paket fleksibel',
								'Laporan ringkas',
							]);
						}
					@endphp
					<div class="paket-card {{ $isFeatured ? 'paket-card-highlight' : '' }}">
						@if ($isFeatured)
							<div class="paket-highlight-tag">Paling Populer</div>
						@endif
						<div class="paket-card-head">
							<span class="paket-badge">{{ $card->nama_paket }}</span>
							<p class="paket-price">
								Rp {{ number_format($card->harga ?? 0, 0, ',', '.') }}<span>/bulan</span>
							</p>
							<p class="paket-caption">
								{{ $features->first() ?? 'Paket berlangganan untuk kebutuhan toko.' }}
							</p>
						</div>
						<ul class="paket-feature-list">
							@foreach ($features->take(3) as $feature)
								<li>{{ $feature }}</li>
							@endforeach
						</ul>
						<button class="paket-cta {{ $isFeatured ? 'paket-cta-dark' : '' }}">Aktifkan Paket</button>
					</div>
				@empty
					<div style="padding: 30px 10px; text-align: center; color: #64748b;">
						Belum ada paket untuk ditampilkan.
					</div>
				@endforelse
			</div>
		</div>
	</section>

	<section class="hz-secondary-grid" style="margin-top: 16px;">
		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 12px;">
				<div>
					<p class="hz-card-title">Daftar Paket ({{ $pakets->count() }} paket)</p>
					<p class="hz-card-sub">Ringkasan paket yang tersimpan pada database.</p>
				</div>
			</div>
			<div style="margin-top: 8px; overflow-x: auto;">
				<table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
					<thead>
						<tr style="border-bottom: 1px solid rgba(59, 130, 246, 0.4); color:#9ca3af;">
							<th style="text-align:left; padding:14px 10px;">Nama Paket</th>
							<th style="text-align:left; padding:14px 10px;">Deskripsi</th>
							<th style="text-align:right; padding:14px 10px;">Harga / Bulan</th>
							<th style="text-align:right; padding:14px 10px;">Harga Diskon</th>
							<th style="text-align:center; padding:14px 10px;">Durasi</th>
							<th style="text-align:center; padding:14px 10px;">Status</th>
							<th style="text-align:right; padding:14px 10px;">Aksi</th>
						</tr>
					</thead>
					<tbody>
						@forelse ($pakets as $paket)
							<tr style="border-bottom: 1px solid rgba(15,23,42,0.8);">
								<td style="padding:14px 10px; font-weight: 600; color: #3b82f6;">
									{{ $paket->nama_paket ?? $paket->name ?? '-' }}
								</td>
								<td style="padding:14px 10px; color: #cbd5e1;">
									{{ $paket->deskripsi ?? $paket->description ?? 'Paket berlangganan standar.' }}
								</td>
								<td style="padding:14px 10px; text-align:right; font-weight: 600; color: #3b82f6;">
									{{ isset($paket->harga) ? 'Rp ' . number_format($paket->harga, 0, ',', '.') : '-' }}
								</td>
								<td style="padding:14px 10px; text-align:right;">
									@if ($paket->harga_diskon)
										<div style="color: #22c55e; font-weight: 600;">
											Rp {{ number_format($paket->harga_diskon, 0, ',', '.') }}
										</div>
										<div style="color: #64748b; font-size: 0.8rem; text-decoration: line-through;">
											Rp {{ number_format($paket->harga, 0, ',', '.') }}
										</div>
										<span style="background:rgba(34, 197, 94, 0.2); color:#86efac; padding: 2px 6px; border-radius: 4px; font-size: 0.7rem; display: inline-block; margin-top: 4px;">PROMO</span>
									@else
										<span style="color: #64748b;">-</span>
									@endif
								</td>
								<td style="padding:14px 10px; text-align:center; color: #cbd5e1;">
									{{ $paket->durasi_bulan ?? 1 }} bulan
								</td>
								<td style="padding:14px 10px; text-align:center;">
									@php
										$status = $paket->is_active ?? 1;
									@endphp
									@if ($status)
										<span style="background:rgba(34, 197, 94, 0.2); color:#86efac; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem;">Aktif</span>
									@else
										<span style="background:rgba(239, 68, 68, 0.2); color:#fca5a5; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem;">Nonaktif</span>
									@endif
								</td>
								<td style="padding:14px 10px; text-align:right;">
									<div style="display:flex; justify-content:flex-end; gap:8px;">
										<a href="{{ route('paket.edit', $paket->id) }}" style="background: rgba(59,130,246,0.2); color:#3b82f6; border:1px solid rgba(59,130,246,0.5); padding:6px 10px; border-radius:8px; text-decoration:none; font-size:0.75rem; cursor:pointer; transition: all 0.2s;">
											Edit
										</a>
										<form method="POST" action="{{ route('paket.destroy', $paket->id) }}" onsubmit="return confirm('Yakin hapus paket ini?');" style="display: inline;">
											@csrf
											@method('DELETE')
											<button type="submit" style="background: rgba(239,68,68,0.2); color:#ef4444; border:1px solid rgba(239,68,68,0.5); padding:6px 10px; border-radius:8px; cursor:pointer; font-size:0.75rem;">
												Hapus
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" style="padding: 40px 10px; text-align: center; color: #64748b;">
									Belum ada paket. Tambahkan paket pertama agar bisa ditampilkan di halaman ini.
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
			@if (method_exists($pakets, 'links'))
				<div style="margin-top: 12px; display:flex; justify-content:space-between; align-items:center; font-size:0.8rem; color:#9ca3af;">
					<div>
						Menampilkan {{ $pakets->firstItem() ?? 0 }} - {{ $pakets->lastItem() ?? 0 }} dari {{ $pakets->total() }} paket
					</div>
					<div>
						{{ $pakets->links() }}
					</div>
				</div>
			@endif
		</div>
	</section>

	<style>
		.paket-pricing-grid {
			display: grid;
			gap: 16px;
		}

		@media (min-width: 1024px) {
			.paket-pricing-grid {
				grid-template-columns: repeat(3, minmax(0, 1fr));
			}
		}

		.paket-card {
			border-radius: 18px;
			border: 1px solid rgba(30, 64, 175, 0.35);
			background: rgba(2, 6, 23, 0.85);
			padding: 18px 18px 20px;
			box-shadow: 0 16px 35px rgba(15, 23, 42, 0.7);
			display: flex;
			flex-direction: column;
			gap: 14px;
			position: relative;
			overflow: hidden;
		}

		.paket-card-highlight {
			background: linear-gradient(135deg, rgba(59, 130, 246, 0.95), rgba(245, 158, 11, 0.85));
			color: #020617;
			border-color: rgba(59, 130, 246, 0.6);
		}

		.paket-highlight-tag {
			position: absolute;
			top: 16px;
			right: 16px;
			background: rgba(2, 6, 23, 0.9);
			color: #3b82f6;
			font-size: 0.65rem;
			padding: 4px 10px;
			border-radius: 999px;
			border: 1px solid rgba(2, 6, 23, 0.8);
		}

		.paket-card-highlight .paket-highlight-tag {
			color: #020617;
			background: rgba(255, 255, 255, 0.6);
			border-color: rgba(255, 255, 255, 0.5);
		}

		.paket-card-head {
			display: flex;
			flex-direction: column;
			gap: 8px;
		}

		.paket-badge {
			align-self: flex-start;
			padding: 4px 10px;
			border-radius: 999px;
			font-size: 0.7rem;
			letter-spacing: 0.08em;
			text-transform: uppercase;
			background: rgba(15, 23, 42, 0.85);
			color: #f8fafc;
		}

		.paket-card-highlight .paket-badge {
			background: rgba(2, 6, 23, 0.9);
			color: #3b82f6;
		}

		.paket-price {
			font-size: 1.6rem;
			font-weight: 700;
		}

		.paket-price span {
			font-size: 0.8rem;
			font-weight: 500;
			color: inherit;
		}

		.paket-caption {
			font-size: 0.85rem;
			color: #64748b;
		}

		.paket-card-highlight .paket-caption {
			color: rgba(2, 6, 23, 0.75);
		}

		.paket-feature-list {
			list-style: none;
			padding: 0;
			margin: 0;
			display: grid;
			gap: 8px;
			font-size: 0.85rem;
			color: #cbd5e1;
		}

		.paket-card-highlight .paket-feature-list {
			color: rgba(2, 6, 23, 0.8);
		}

		.paket-feature-list li {
			display: flex;
			align-items: center;
			gap: 8px;
		}

		.paket-feature-list li::before {
			content: '•';
			color: #3b82f6;
			font-size: 1rem;
		}

		.paket-cta {
			margin-top: auto;
			border-radius: 999px;
			border: 1px solid rgba(148, 163, 184, 0.5);
			background: rgba(15, 23, 42, 0.9);
			color: #1e293b;
			padding: 10px 14px;
			font-size: 0.8rem;
			font-weight: 600;
			cursor: pointer;
			transition: transform 0.15s ease, background 0.15s ease;
		}

		.paket-cta:hover {
			transform: translateY(-1px);
			background: rgba(15, 23, 42, 1);
		}

		.paket-cta-dark {
			background: #ffffff;
			color: #3b82f6;
			border-color: rgba(2, 6, 23, 0.75);
		}
	</style>
@endsection
