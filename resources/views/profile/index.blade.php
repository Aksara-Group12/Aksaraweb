@extends('layouts.horizon')

@section('page_title', 'Profil & Berlangganan')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Profil Toko & Berlangganan</h2>
			<p class="hz-top-sub">Kelola informasi profil dan paket berlangganan Anda.</p>
		</div>
		<div class="hz-top-actions">
			@if($profile)
				<a href="{{ route('profile.edit', $profile) }}" class="hz-btn-outline">
					<span class="hz-icon-pill" style="background: rgba(148, 163, 184, 0.15);">
						✎
					</span>
					<span>Ubah profil</span>
				</a>
			@else
				<a href="{{ route('profile.create') }}" class="hz-btn-primary">
					<span class="hz-icon-pill" style="background: rgba(59, 130, 246, 0.2);">
						+
					</span>
					<span>Atur profil toko</span>
				</a>
			@endif
		</div>
	</div>

	<!-- Tab Navigation -->
	<div style="margin-top: 24px; padding-bottom: 12px; border-bottom: 1px solid rgba(148, 163, 184, 0.2); display: flex; gap: 24px;">
		<button type="button" 
			id="tab-profil-btn" 
			class="tab-nav-btn tab-nav-active"
			style="padding: 8px 0; font-weight: 500; color: #60a5fa; border-bottom: 2px solid #60a5fa; background: none; border: none; cursor: pointer; transition: all 0.3s ease;"
			onclick="switchTab('profil')">
			<span style="margin-right: 6px;">👤</span>Profil Toko
		</button>
		@if(auth()->user()->role === 'owner')
			<button type="button" 
				id="tab-berlangganan-btn" 
				class="tab-nav-btn"
				style="padding: 8px 0; font-weight: 500; color: #64748b; border-bottom: 2px solid transparent; background: none; border: none; cursor: pointer; transition: all 0.3s ease;"
				onclick="switchTab('berlangganan')">
				<span style="margin-right: 6px;">💳</span>Berlangganan
			</button>
		@endif
	</div>

	@if (session('success'))
		<div style="margin-top: 16px; padding: 10px 12px; border-radius: 10px; border:1px solid rgba(34,197,94,0.4); background: rgba(22,163,74,0.15); color:#bbf7d0; font-size:0.8rem;">
			{{ session('success') }}
		</div>
	@endif

	<!-- Tab Content: Profil -->
	<div id="content-profil" class="tab-content" style="margin-top: 24px;">
		<section class="hz-secondary-grid">
			<div class="hz-card" style="grid-column: 1 / -1;">
				@if(!$profile)
					<p class="hz-card-title">Belum ada profil toko</p>
					<p class="hz-card-sub" style="margin-top: 8px;">Lengkapi profil toko untuk menampilkan identitas pada dashboard dan nota transaksi.</p>
					<div style="margin-top: 16px;">
						<a href="{{ route('profile.create') }}" class="hz-btn-primary">Atur profil sekarang</a>
					</div>
				@else
					<div style="display:flex; flex-wrap:wrap; gap: 20px;">
						<div style="flex:1 1 260px;">
							<p class="hz-card-title">Identitas Toko</p>
							<div style="margin-top: 10px; font-size:0.85rem; color:#1e293b;">
								<p><strong>Nama Toko:</strong> {{ $profile->store_name }}</p>
								<p style="margin-top:6px;"><strong>Nama Pemilik:</strong> {{ $profile->owner_name ?? '-' }}</p>
								<p style="margin-top:6px;"><strong>Telepon / WA:</strong> {{ $profile->phone ?? '-' }}</p>
								<p style="margin-top:6px;"><strong>Email:</strong> {{ $profile->email ?? '-' }}</p>
								<p style="margin-top:6px;"><strong>Alamat:</strong> {{ $profile->address ?? '-' }}</p>
								<p style="margin-top:6px;"><strong>Kota / Kab:</strong> {{ $profile->city ?? '-' }} {{ $profile->postal_code ? '(' . $profile->postal_code . ')' : '' }}</p>
							</div>
						</div>
					</div>
				@endif
			</div>
		</section>
	</div>

	<!-- Tab Content: Berlangganan -->
	@if(auth()->user()->role === 'owner')
		<div id="content-berlangganan" class="tab-content" style="margin-top: 24px; display: none;">
			@include('subscribe.content')
		</div>
	@endif

	<style>
		.tab-nav-btn {
			transition: all 0.3s ease;
		}
		.tab-nav-btn:hover {
			color: #60a5fa;
		}
		.tab-nav-btn.tab-nav-active {
			color: #60a5fa;
			border-bottom: 2px solid #60a5fa;
		}
	</style>

	<script>
		function switchTab(tab) {
			// Hide all tabs
			document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
			document.querySelectorAll('.tab-nav-btn').forEach(el => el.classList.remove('tab-nav-active'));

			// Show selected tab
			document.getElementById('content-' + tab).style.display = 'block';
			document.getElementById('tab-' + tab + '-btn').classList.add('tab-nav-active');
		}
	</script>
@endsection

