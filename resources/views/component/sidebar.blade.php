<aside class="hz-sidebar" id="hz-sidebar">
	@php
		$user = auth()->user();
		$isSuperadmin = $user && $user->role === 'superadmin';
		$isOwner = $user && $user->role === 'owner';
		$ownerSubscriptionActive = $isOwner ? (bool) \App\Models\Subscribe::getActiveSubscription($user->id) : true;
		// $paketType = $isOwner ? \App\Models\Subscribe::getPackageType($user->id) : 1; // DISABLED - semua fitur terbuka
		$profile = auth()->user()->profile ?? \App\Models\Profile::where('user_id', auth()->id())->first();
		$storeName = $profile?->store_name ?? 'Aksara';

		// Helper function untuk cek permission
		$hasPermission = function($permission) use ($user) {
			if ($user->role === 'owner') return true;
			return $user->hasPermission($permission);
		};
	@endphp
	<div class="hz-sidebar-header">
		<div class="hz-logo-badge">
			@if(!$isSuperadmin && $profile && $profile->logo_path)
				@php
					$logoPath = asset('storage/' . $profile->logo_path);
				@endphp
				<img 
					src="{{ $logoPath }}" 
					alt="{{ $storeName }}" 
					onerror="this.src='{{ asset('assets/images/aksara-logo.png') }}';"
				>
			@else
				<img 
					src="{{ asset('assets/images/aksara-logo.png') }}" 
					alt="Aksara Logo" 
				>
			@endif
		</div>
		<div class="hz-logo-text">
			@if($isSuperadmin)
				<p class="hz-logo-title">Aksara <span>Developer</span></p>
				<p class="hz-logo-sub">Superadmin Konsol</p>
			@else
				<p class="hz-logo-title">{{ $storeName }}</p>
				<p class="hz-logo-sub">Panel Manajemen Toko</p>
			@endif
		</div>
	</div>
	<nav class="hz-nav">
		<div class="hz-nav-group">
			<p class="hz-nav-group-title">Menu Utama</p>
			@if($isSuperadmin)
				<a href="{{ route('dashboard-superadmin') }}" class="hz-nav-item {{ request()->routeIs('dashboard-superadmin') ? 'hz-nav-item-active' : '' }}">
					<i data-lucide="layout-dashboard" class="hz-nav-lucide"></i>
					<span class="hz-nav-label">Dashboard</span>
				</a>
			@elseif($isOwner)
				<a href="{{ route('dashboard') }}" class="hz-nav-item {{ request()->routeIs('dashboard') ? 'hz-nav-item-active' : '' }}">
					<i data-lucide="layout-dashboard" class="hz-nav-lucide"></i>
					<span class="hz-nav-label">Dashboard</span>
				</a>
			@endif
		</div>

		@if($isSuperadmin)
			<div class="hz-nav-group">
				<p class="hz-nav-group-title">Superadmin</p>
				<a href="{{ route('manajemen-user.index') }}" class="hz-nav-item {{ request()->routeIs('manajemen-user.*') ? 'hz-nav-item-active' : '' }}">
					<i data-lucide="users" class="hz-nav-lucide"></i>
					<span class="hz-nav-label">Manajemen User</span>
				</a>
				<a href="{{ route('paket.index') }}" class="hz-nav-item {{ request()->routeIs('paket.*') ? 'hz-nav-item-active' : '' }}">
					<i data-lucide="package" class="hz-nav-lucide"></i>
					<span class="hz-nav-label">Manajemen Paket</span>
				</a>
				<a href="{{ route('bank.index') }}" class="hz-nav-item {{ request()->routeIs('bank.*') ? 'hz-nav-item-active' : '' }}">
					<i data-lucide="credit-card" class="hz-nav-lucide"></i>
					<span class="hz-nav-label">Rekening Bank</span>
				</a>
			</div>
		@endif

		@if($isOwner || $hasPermission('produk'))
			<div class="hz-nav-group">
				<p class="hz-nav-group-title">Manajemen</p>
				@if($hasPermission('produk'))
					<a href="{{ $ownerSubscriptionActive ? route('produk.index') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('produk.index') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="box" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Produk/Menu</span>
					</a>
				@endif
				@if($hasPermission('data_master'))
					<a href="{{ $ownerSubscriptionActive ? route('data-master.index') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('data-master.index') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="settings" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Data Master</span>
					</a>
				@endif
			</div>
		@endif

		@if($isOwner || $hasPermission('pos') || $hasPermission('riwayat_penjualan') || $hasPermission('pengeluaran'))
			<div class="hz-nav-group">
				<p class="hz-nav-group-title">Transaksi</p>
				@if($hasPermission('pos'))
					<a href="{{ $ownerSubscriptionActive ? route('transaksi.pos') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('transaksi.pos') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="shopping-cart" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">POS Kasir</span>
					</a>
				@endif
				@if($hasPermission('riwayat_penjualan') && !session()->has('admin_id'))
					<a href="{{ $ownerSubscriptionActive ? route('transaksi.keluar') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('transaksi.keluar') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="arrow-up-right" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Riwayat Penjualan</span>
					</a>
				@endif
				@if($hasPermission('pengeluaran'))
					<a href="{{ $ownerSubscriptionActive ? route('pengeluaran.index') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('pengeluaran.*') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="receipt" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Pengeluaran</span>
					</a>
				@endif
			</div>
		@endif

			@if($hasPermission('laporan') && !session()->has('admin_id'))
				<div class="hz-nav-group">
					<p class="hz-nav-group-title">Laporan</p>
					<a href="{{ $ownerSubscriptionActive ? route('laporan.keuangan') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('laporan.keuangan') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="pie-chart" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Ringkasan Keuangan</span>
					</a>
					<a href="{{ $ownerSubscriptionActive ? route('laporan.produk-terlaris') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('laporan.produk-terlaris') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="trending-up" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Produk Terlaris</span>
					</a>
				</div>
			@endif
		@endif
	</nav>
</aside>
