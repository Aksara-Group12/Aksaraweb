<!-- Status Berlangganan (Sidebar) + Paket Pricing Cards (Superadmin Style) -->
@php
	$colorThemes = [
		[
			'card_border_top' => '#2563EB', // Sapphire Blue
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
			'cta_hover_color' => '#FFFFFF',
		],
		[
			'card_border_top' => '#0F223D', // Twilight Navy
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
			'cta_hover_color' => '#FFFFFF',
		],
		[
			'card_border_top' => '#059669', // Emerald Jade
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
			'cta_hover_color' => '#FFFFFF',
		],
		[
			'card_border_top' => '#C5A059', // Imperial Champagne Gold (Paling Populer)
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
			'cta_hover_color' => '#FFFFFF',
		],
		[
			'card_border_top' => '#7C3AED', // Regal Amethyst
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
			'cta_hover_color' => '#FFFFFF',
		],
	];

	// Tentukan featured package (misal harga tertinggi atau diskon)
	$pkgCollection = collect($packages);
	$featuredPkgId = $pkgCollection->sortByDesc('harga')->first()->id ?? null;
@endphp

<style>
	/* Layout Master */
	.sub-pricing-layout {
		display: grid;
		grid-template-columns: 310px 1fr;
		gap: 24px;
		align-items: start;
	}

	@media (max-width: 992px) {
		.sub-pricing-layout {
			grid-template-columns: 1fr;
		}
	}

	/* Card Wrapper (Superadmin Style) */
	.sub-content-card {
		background: #FFFFFF;
		border-radius: 18px;
		border: 1px solid rgba(226, 232, 240, 0.95);
		padding: 24px;
		box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04);
	}

	.sub-card-header {
		margin-bottom: 20px;
		padding-bottom: 14px;
		border-bottom: 1px solid #F1F5F9;
	}

	.sub-section-title {
		font-family: 'Geist', 'Geist Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
		font-size: 1.25rem;
		font-weight: 700;
		color: #0F223D;
		margin: 0;
		line-height: 1.25;
		letter-spacing: -0.02em;
	}

	.sub-section-sub {
		font-size: 0.8rem;
		color: #64748B;
		margin: 4px 0 0;
	}

	/* Status Items */
	.status-block-item {
		border-radius: 14px;
		padding: 16px;
		margin-bottom: 14px;
		transition: all 0.2s ease;
	}

	.status-block-label {
		font-size: 0.7rem;
		font-weight: 700;
		text-transform: uppercase;
		letter-spacing: 0.05em;
		margin: 0 0 6px 0;
	}

	.status-block-val {
		font-family: 'Geist', 'Geist Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
		font-size: 1.5rem;
		font-weight: 700;
		line-height: 1.1;
		margin: 0;
		letter-spacing: -0.02em;
	}

	/* Pricing Grid */
	.paket-pricing-grid {
		display: grid;
		gap: 20px;
		grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
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
		border-color: rgba(197, 160, 89, 0.7);
		box-shadow: 0 0 24px rgba(197, 160, 89, 0.28), 0 4px 16px rgba(15, 34, 61, 0.05);
		transform: translateY(-2px);
	}

	/* Highlight Card (Gold Aksara) */
	.paket-card-highlight {
		border: 1.5px solid #C5A059 !important;
		box-shadow: 0 0 16px rgba(197, 160, 89, 0.25), 0 8px 16px -4px rgba(15, 34, 61, 0.08) !important;
	}

	.paket-card-highlight:hover {
		border-color: #B38E46 !important;
		box-shadow: 0 0 32px rgba(197, 160, 89, 0.55), 0 8px 24px -4px rgba(15, 34, 61, 0.1) !important;
		transform: translateY(-2px);
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

	.paket-card-head {
		display: flex;
		flex-direction: column;
		gap: 8px;
	}

	.paket-price {
		font-family: 'Geist', 'Geist Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
		font-size: 1.75rem;
		font-weight: 700;
		color: #0F223D;
		margin: 4px 0 0;
		line-height: 1.1;
		letter-spacing: -0.025em;
	}

	.paket-price span {
		font-family: inherit;
		font-size: 0.825rem;
		font-weight: 500;
		color: #64748B;
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
		flex-shrink: 0;
	}

	.paket-card-highlight .feature-check-icon {
		stroke-width: 2.5;
	}

	.paket-cta {
		margin-top: auto;
		border-radius: 10px;
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

	/* Modal Dialog (Superadmin Style) */
	.custom-modal-backdrop {
		display: none;
		position: fixed;
		inset: 0;
		background: rgba(15, 34, 61, 0.5);
		backdrop-filter: blur(4px);
		z-index: 1050;
	}

	.custom-modal-dialog {
		display: none;
		position: fixed;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		background: #FFFFFF;
		border-radius: 18px;
		border: 1px solid #E2E8F0;
		box-shadow: 0 25px 50px -12px rgba(15, 34, 61, 0.25);
		z-index: 1051;
		width: 90%;
		max-width: 460px;
		padding: 24px;
		max-height: 90vh;
		overflow-y: auto;
	}
</style>

<div class="sub-pricing-layout">
	<!-- Sidebar Status Berlangganan -->
	<div class="sub-content-card">
		<div class="sub-card-header">
			<h3 class="sub-section-title">Status Berlangganan</h3>
			<p class="sub-section-sub">Informasi paket toko & masa aktif saat ini.</p>
		</div>

		@if ($activeSubscription)
			<div>
				<!-- 1. Paket Aktif -->
				<div class="status-block-item" style="background: #ECFDF5; border: 1px solid #A7F3D0;">
					<p class="status-block-label" style="color: #065F46;">Paket Toko Aktif</p>
					<p class="status-block-val" style="color: #047857;">{{ $activeSubscription->paket_name }}</p>
					<div style="margin-top: 6px;">
						<span style="display: inline-block; padding: 3px 10px; border-radius: 9999px; font-size: 0.72rem; font-weight: 700; background: #D1FAE5; color: #065F46;">
							{{ $activeSubscription->is_trial ? 'Masa Trial' : 'Berlangganan Aktif' }}
						</span>
					</div>
				</div>

				<!-- 2. Tanggal Mulai -->
				<div class="status-block-item" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
					<p class="status-block-label" style="color: #64748B;">Tanggal Mulai</p>
					<p style="font-size: 0.95rem; font-weight: 700; color: #0F223D; margin: 0;">
						{{ $activeSubscription->start_date->format('d M Y') }}
					</p>
				</div>

				<!-- 3. Tanggal Berakhir -->
				<div class="status-block-item" style="background: #FEF2F2; border: 1px solid #FECACA;">
					<p class="status-block-label" style="color: #991B1B;">Tanggal Berakhir</p>
					<p style="font-size: 0.95rem; font-weight: 700; color: #DC2626; margin: 0;">
						{{ $activeSubscription->end_date->format('d M Y') }}
					</p>
				</div>

				<!-- 4. Sisa Hari -->
				<div class="status-block-item" style="background: #FAF5FF; border: 1px solid #DDD6FE;">
					<p class="status-block-label" style="color: #6D28D9;">Sisa Waktu Aktif</p>
					<p class="status-block-val" style="color: #7C3AED;">
						{{ (int) ceil($activeSubscription->getDaysRemaining()) }}
						<span style="font-family: inherit; font-size: 0.9rem; font-weight: 600; color: #6D28D9;">hari</span>
					</p>
				</div>
			</div>
		@else
			<div style="background: #FFFDF8; border: 1px solid #FDE68A; border-radius: 14px; padding: 20px; text-align: center;">
				<i data-lucide="alert-triangle" style="width: 28px; height: 28px; color: #D97706; margin-bottom: 8px;"></i>
				<p style="color: #92400E; font-weight: 700; font-size: 0.9rem; margin: 0 0 4px 0;">Belum Ada Paket Aktif</p>
				<p style="color: #B45309; font-size: 0.775rem; margin: 0;">Pilih salah satu paket di sebelah kanan untuk mulai mengaktifkan fitur toko Anda.</p>
			</div>
		@endif
	</div>

	<!-- Pricing Cards (Pilihan Paket Langganan) -->
	<div class="sub-content-card">
		<div class="sub-card-header">
			<h3 class="sub-section-title">Pilihan Paket Langganan</h3>
			<p class="sub-section-sub">Pilih paket langganan yang sesuai untuk kelancaran operasional dan fitur lengkap bisnis Anda.</p>
		</div>

		<div class="paket-pricing-grid">
			@forelse ($packages as $index => $package)
				@php
					$isFeatured = $featuredPkgId && $package->id === $featuredPkgId;
					$rawFeatures = trim((string) ($package->deskripsi ?? ''));
					$features = collect(preg_split('/\r\n|\r|\n/', $rawFeatures))
						->map(function ($item) { return trim($item); })
						->filter()
						->values();
					if ($features->isEmpty()) {
						$features = collect([
							'Akses POS kasir & transaksi',
							'Manajemen stok & inventori produk',
							'Laporan keuangan & riwayat penjualan',
							'Dukungan multi akun staf/kasir'
						]);
					}

					if ($isFeatured) {
						$theme = $colorThemes[3]; // Imperial Gold
					} else {
						$availableThemes = [0, 1, 2, 4];
						$theme = $colorThemes[$availableThemes[$index % count($availableThemes)]];
					}
				@endphp

				<div class="paket-card {{ $isFeatured ? 'paket-card-highlight' : '' }}" 
					id="package-{{ $package->id }}"
					style="border-top: 4px solid {{ $theme['card_border_top'] }} !important; border-color: {{ $theme['card_border'] }} !important; background: {{ $theme['card_bg'] }} !important;">
					
					<div style="display: flex; justify-content: space-between; align-items: center; gap: 8px; flex-wrap: wrap;">
						<span class="paket-badge" style="background: {{ $theme['badge_bg'] }}; color: {{ $theme['badge_color'] }}; border: 1px solid {{ $theme['badge_border'] }};">
							{{ $package->nama_paket }}
						</span>
						@if ($isFeatured)
							<span class="paket-highlight-tag">
								<i data-lucide="sparkles" style="width: 12px; height: 12px; color: #F59E0B;"></i>
								<span>Paling Populer</span>
							</span>
						@elseif ($package->harga_diskon)
							<span class="paket-highlight-tag" style="background: #047857; color: #FFFFFF; border-color: #A7F3D0;">
								<span>Promo Hemat</span>
							</span>
						@endif
					</div>

					<div class="paket-card-head">
						@if ($package->harga_diskon)
							<p class="paket-price" style="color: {{ $theme['price_color'] }};">
								Rp {{ number_format($package->harga_diskon, 0, ',', '.') }}
								<span>/ {{ $package->durasi_hari }} hari</span>
							</p>
							<p style="font-size: 0.75rem; color: #94A3B8; text-decoration: line-through; margin: -4px 0 0 0;">
								Rp {{ number_format($package->harga, 0, ',', '.') }}
							</p>
						@else
							<p class="paket-price" style="color: {{ $theme['price_color'] }};">
								@if ($package->harga == 0)
									Gratis
								@else
									Rp {{ number_format($package->harga, 0, ',', '.') }}
								@endif
								<span>/ {{ $package->durasi_hari }} hari</span>
							</p>
						@endif
						<p class="paket-caption">
							{{ $features->first() ?? 'Paket berlangganan untuk operasional bisnis.' }}
						</p>
					</div>

					<!-- Fitur Checklist -->
					<ul class="paket-feature-list">
						@foreach ($features->take(4) as $feature)
							<li>
								<i data-lucide="check" class="feature-check-icon" style="color: {{ $theme['check_color'] }};"></i>
								<span>{{ $feature }}</span>
							</li>
						@endforeach
					</ul>

					<!-- Tombol Pilih Paket -->
					<button type="button" 
						class="paket-cta"
						style="background: {{ $theme['cta_bg'] }}; color: {{ $theme['cta_color'] }}; border: 1px solid {{ $theme['cta_border'] }};"
						onmouseover="this.style.background='{{ $theme['cta_hover_bg'] }}'; this.style.color='{{ $theme['cta_hover_color'] }}';"
						onmouseout="this.style.background='{{ $theme['cta_bg'] }}'; this.style.color='{{ $theme['cta_color'] }}';"
						onclick="selectPackage({{ $package->id }}, '{{ $package->nama_paket }}', {{ $package->harga_diskon ?? $package->harga }}, {{ $package->durasi_hari }})">
						<i data-lucide="check-circle" style="width: 14px; height: 14px;"></i>
						<span>Pilih Paket Ini</span>
					</button>
				</div>
			@empty
				<div style="grid-column: 1/-1; text-align: center; padding: 48px 20px; background: #FFFFFF; border: 1px dashed #CBD5E1; border-radius: 16px;">
					<p style="color: #64748B; font-weight: 500; margin: 0;">Belum ada paket berlangganan yang tersedia saat ini.</p>
				</div>
			@endforelse
		</div>
	</div>
</div>

<!-- Modal Konfirmasi Pembayaran (Superadmin Style) -->
<div id="paymentModalBackdrop" class="custom-modal-backdrop"></div>
<div id="paymentModal" class="custom-modal-dialog">
	<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #F1F5F9;">
		<div style="display: flex; align-items: center; gap: 10px;">
			<div style="width: 36px; height: 36px; border-radius: 10px; background: #0F223D; color: #FFFFFF; display: flex; align-items: center; justify-content: center;">
				<i data-lucide="credit-card" style="width: 18px; height: 18px;"></i>
			</div>
			<div>
				<h3 style="font-family: 'Geist', 'Geist Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 1.25rem; font-weight: 700; color: #0F223D; margin: 0; line-height: 1.2; letter-spacing: -0.02em;">
					Konfirmasi Pembayaran
				</h3>
				<span style="font-size: 0.775rem; color: #64748B;">Transfer pembayaran paket berlangganan</span>
			</div>
		</div>
		<button type="button" onclick="closeModal()" style="background: none; border: none; color: #94A3B8; cursor: pointer; padding: 4px;">
			<i data-lucide="x" style="width: 20px; height: 20px;"></i>
		</button>
	</div>

	<form id="paymentForm" enctype="multipart/form-data">
		@csrf

		<!-- Ringkasan Paket Terpilih -->
		<div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 14px; padding: 16px; margin-bottom: 18px;">
			<div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
				<span style="font-size: 0.785rem; color: #64748B; font-weight: 600;">Paket Dipilih</span>
				<span style="font-size: 0.95rem; font-weight: 700; color: #0F223D;" id="modalPackageName"></span>
			</div>
			<div style="margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center;">
				<span style="font-size: 0.785rem; color: #64748B; font-weight: 600;">Total Biaya</span>
				<span style="font-family: 'Geist', 'Geist Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 1.35rem; font-weight: 700; color: #065F46; letter-spacing: -0.02em;" id="modalPrice"></span>
			</div>
			<div style="display: flex; justify-content: space-between; align-items: center;">
				<span style="font-size: 0.785rem; color: #64748B; font-weight: 600;">Masa Aktif</span>
				<span style="font-size: 0.875rem; font-weight: 700; color: #0F223D;" id="modalDuration"></span>
			</div>
		</div>

		<!-- Pilih Rekening Bank -->
		<div style="margin-bottom: 16px;">
			<label style="display: block; font-size: 0.825rem; font-weight: 600; color: #334155; margin-bottom: 6px;">
				Pilih Rekening Bank Tujuan <span style="color: #EF4444;">*</span>
			</label>
			<select name="bank_id" id="bankSelect" style="width: 100%; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; padding: 10px 14px; border-radius: 10px; font-size: 0.85rem; font-family: inherit; outline: none; transition: all 0.15s ease;" required>
				<option value="">-- Pilih Bank --</option>
				@foreach ($banks as $bank)
					<option value="{{ $bank->id }}" data-holder="{{ $bank->account_holder }}" data-account="{{ $bank->account_number }}">
						{{ $bank->bank_name }} - a.n {{ $bank->account_holder }}
					</option>
				@endforeach
			</select>
		</div>

		<!-- Info Rekening -->
		<div id="bankInfo" style="display: none; background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 12px; padding: 14px; margin-bottom: 16px;">
			<p style="font-size: 0.7rem; color: #065F46; font-weight: 700; text-transform: uppercase; margin: 0 0 2px 0;">Pemilik Rekening</p>
			<p style="font-weight: 700; color: #0F223D; margin: 0 0 8px 0; font-size: 0.9rem;" id="accountHolder"></p>
			
			<p style="font-size: 0.7rem; color: #065F46; font-weight: 700; text-transform: uppercase; margin: 0 0 2px 0;">Nomor Rekening</p>
			<div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
				<p style="font-family: monospace; font-size: 1.15rem; font-weight: 800; color: #047857; margin: 0;" id="accountNumber"></p>
				<button type="button" 
					style="font-size: 0.75rem; background: #047857; color: #FFFFFF; border: none; padding: 5px 12px; border-radius: 8px; cursor: pointer; font-weight: 600;"
					onclick="copyToClipboard()">
					Salin
				</button>
			</div>
		</div>

		<!-- Upload Bukti Transaksi -->
		<div style="margin-bottom: 20px;">
			<label style="display: block; font-size: 0.825rem; font-weight: 600; color: #334155; margin-bottom: 6px;">
				Upload Bukti Transfer <span style="color: #EF4444;">*</span>
			</label>
			<div id="dropZone" style="border: 2px dashed #CBD5E1; border-radius: 12px; padding: 20px 14px; text-align: center; cursor: pointer; transition: all 0.2s; background: #FAF8F5;"
				onmouseover="this.style.borderColor='#0F223D'; this.style.background='#F1F5F9';"
				onmouseout="this.style.borderColor='#CBD5E1'; this.style.background='#FAF8F5';">
				<i data-lucide="upload-cloud" style="width: 28px; height: 28px; color: #64748B; margin-bottom: 6px;"></i>
				<p style="color: #0F223D; font-weight: 600; margin: 0 0 4px 0; font-size: 0.85rem;">Klik atau drag file bukti transfer ke sini</p>
				<p style="font-size: 0.75rem; color: #64748B; margin: 0;">JPG, PNG, atau PDF (maks. 5MB)</p>
				<input type="file" 
					name="bukti_transaksi" 
					id="buktiTransaksi" 
					style="display: none;" 
					accept="image/jpeg,image/png,application/pdf"
					required>
			</div>
			<p id="fileInfo" style="font-size: 0.75rem; color: #047857; margin-top: 6px; font-weight: 600;"></p>
		</div>

		<!-- Preview Gambar -->
		<div id="imagePreview" style="display: none; margin-bottom: 16px;">
			<img id="previewImage" style="width: 100%; border-radius: 10px; border: 1px solid #E2E8F0; max-height: 150px; object-fit: cover;" alt="Preview">
			<button type="button" 
				style="margin-top: 6px; font-size: 0.75rem; color: #DC2626; background: none; border: none; cursor: pointer; font-weight: 600;"
				onclick="clearFile()">
				Hapus File
			</button>
		</div>

		<!-- Tombol Aksi Modal -->
		<div style="display: flex; gap: 12px; padding-top: 16px; border-top: 1px solid #F1F5F9;">
			<button type="button" 
				style="flex: 1; background: #FFFFFF; color: #64748B; border: 1px solid #CBD5E1; font-weight: 600; padding: 10px; border-radius: 10px; cursor: pointer; font-size: 0.85rem; font-family: inherit; transition: all 0.15s;"
				onclick="closeModal()">
				Batal
			</button>
			<button type="submit" 
				style="flex: 1; background: #0F223D; color: #FFFFFF; border: none; font-weight: 600; padding: 10px; border-radius: 10px; cursor: pointer; font-size: 0.85rem; font-family: inherit; box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15); transition: all 0.15s;">
				Kirim Bukti Pembayaran
			</button>
		</div>
	</form>
</div>

<script>
	// Inisialisasi ikon Lucide
	function initSubLucide() {
		if (typeof lucide !== 'undefined') {
			lucide.createIcons();
		}
	}

	// Select package
	function selectPackage(paketId, packageName, price, duration) {
		document.getElementById('modalPackageName').textContent = packageName;
		document.getElementById('modalPrice').textContent = price == 0 ? 'Gratis' : 'Rp ' + Number(price).toLocaleString('id-ID');
		document.getElementById('modalDuration').textContent = duration + ' hari';

		const form = document.getElementById('paymentForm');
		form.dataset.paketId = paketId;
		form.dataset.packageName = packageName;
		form.dataset.price = price;
		form.dataset.duration = duration;

		document.getElementById('paymentModalBackdrop').style.display = 'block';
		document.getElementById('paymentModal').style.display = 'block';
		initSubLucide();
	}

	// Close modal
	function closeModal() {
		document.getElementById('paymentModalBackdrop').style.display = 'none';
		document.getElementById('paymentModal').style.display = 'none';
		document.getElementById('paymentForm').reset();
		document.getElementById('bankInfo').style.display = 'none';
		clearFile();
	}

	// Backdrop click
	document.getElementById('paymentModalBackdrop')?.addEventListener('click', closeModal);

	// Bank select change
	document.getElementById('bankSelect')?.addEventListener('change', function() {
		if (this.value) {
			const option = this.options[this.selectedIndex];
			document.getElementById('accountHolder').textContent = option.dataset.holder;
			document.getElementById('accountNumber').textContent = option.dataset.account;
			document.getElementById('bankInfo').style.display = 'block';
		} else {
			document.getElementById('bankInfo').style.display = 'none';
		}
	});

	// Dropzone upload
	const dropZone = document.getElementById('dropZone');
	const buktiTransaksi = document.getElementById('buktiTransaksi');

	dropZone?.addEventListener('click', () => buktiTransaksi.click());

	dropZone?.addEventListener('dragover', (e) => {
		e.preventDefault();
		dropZone.style.borderColor = '#0F223D';
		dropZone.style.background = '#F1F5F9';
	});

	dropZone?.addEventListener('dragleave', () => {
		dropZone.style.borderColor = '#CBD5E1';
		dropZone.style.background = '#FAF8F5';
	});

	dropZone?.addEventListener('drop', (e) => {
		e.preventDefault();
		dropZone.style.borderColor = '#CBD5E1';
		dropZone.style.background = '#FAF8F5';
		buktiTransaksi.files = e.dataTransfer.files;
		handleFileSelect();
	});

	buktiTransaksi?.addEventListener('change', handleFileSelect);

	function handleFileSelect() {
		const file = buktiTransaksi.files[0];
		if (file) {
			document.getElementById('fileInfo').textContent = '✓ ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
			
			if (file.type.startsWith('image/')) {
				const reader = new FileReader();
				reader.onload = (e) => {
					document.getElementById('previewImage').src = e.target.result;
					document.getElementById('imagePreview').style.display = 'block';
				};
				reader.readAsDataURL(file);
			} else {
				document.getElementById('imagePreview').style.display = 'none';
			}
		}
	}

	function clearFile() {
		buktiTransaksi.value = '';
		document.getElementById('fileInfo').textContent = '';
		document.getElementById('imagePreview').style.display = 'none';
	}

	function copyToClipboard() {
		const text = document.getElementById('accountNumber').textContent;
		navigator.clipboard.writeText(text).then(() => {
			alert('Nomor rekening berhasil disalin!');
		});
	}

	// Form submission
	document.getElementById('paymentForm')?.addEventListener('submit', async function(e) {
		e.preventDefault();

		if (!buktiTransaksi.files[0]) {
			alert('Pilih bukti transfer terlebih dahulu');
			return;
		}

		const bankSelect = document.getElementById('bankSelect');
		if (!bankSelect.value) {
			alert('Pilih rekening bank tujuan terlebih dahulu');
			return;
		}

		const formData = new FormData();
		formData.append('paket_id', this.dataset.paketId);
		formData.append('paket_name', this.dataset.packageName);
		formData.append('price', this.dataset.price);
		formData.append('duration_days', this.dataset.duration);
		formData.append('bank_id', bankSelect.value);
		formData.append('bukti_transaksi', buktiTransaksi.files[0]);
		formData.append('_token', document.querySelector('input[name="_token"]').value);

		try {
			const response = await fetch('{{ route('subscribe.store') }}', {
				method: 'POST',
				body: formData,
				headers: {
					'Accept': 'application/json',
					'X-Requested-With': 'XMLHttpRequest'
				}
			});

			const responseText = await response.text();

			let data;
			try {
				data = JSON.parse(responseText);
			} catch (parseError) {
				console.error('JSON parse error:', parseError);
				alert('Error: Server mengembalikan response yang tidak valid.');
				return;
			}

			if (data.success) {
				alert(data.message);
				closeModal();
				location.reload();
			} else {
				if (data.errors) {
					let errorMsg = 'Data tidak valid:\n';
					for (const [key, messages] of Object.entries(data.errors)) {
						errorMsg += '\n' + key + ': ' + messages.join(', ');
					}
					alert(errorMsg);
				} else {
					alert('Error: ' + (data.message || 'Terjadi kesalahan'));
				}
			}
		} catch (error) {
			console.error('Fetch error:', error);
			alert('Terjadi kesalahan jaringan: ' + error.message);
		}
	});

	document.addEventListener('DOMContentLoaded', initSubLucide);
</script>
