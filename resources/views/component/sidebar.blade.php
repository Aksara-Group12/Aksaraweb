<aside class="hz-sidebar" id="hz-sidebar">
	@php
		$user = auth()->user();
		$isSuperadmin = $user && $user->role === 'superadmin';
		$isOwner = $user && $user->role === 'owner';
		$ownerSubscriptionActive = $isOwner ? (bool) \App\Models\Subscribe::getActiveSubscription($user->id) : true;
		$paketType = $isOwner ? \App\Models\Subscribe::getPackageType($user->id) : 1; // 1, 2, or 3
		$profile = auth()->user()->profile ?? \App\Models\Profile::where('user_id', auth()->id())->first();
		$storeName = $profile?->store_name ?? 'Aksara';
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

		<div class="hz-nav-group">
			<p class="hz-nav-group-title">Manajemen</p>
			@if($isSuperadmin)
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
			@elseif($isOwner && !session()->has('admin_id'))
				<a href="{{ $ownerSubscriptionActive ? route('produk.index') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('produk.index') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
					<i data-lucide="box" class="hz-nav-lucide"></i>
					<span class="hz-nav-label">Semua Produk</span>
				</a>
				<a href="{{ $ownerSubscriptionActive ? route('produkstok.index') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('produkstok.*') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
					<i data-lucide="boxes" class="hz-nav-lucide"></i>
					<span class="hz-nav-label">Manajemen Stok</span>
				</a>
				@if($paketType >= 2)
					<a href="{{ $ownerSubscriptionActive ? route('stock-adjustment.index') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('stock-adjustment.*') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="clipboard-pen-line" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Stock Adjustment</span>
					</a>
					<a href="{{ $ownerSubscriptionActive ? route('produk.log') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('produk.log') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="history" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Log Produk</span>
					</a>
				@endif
				@if($paketType >= 3)
					<a href="{{ $ownerSubscriptionActive ? route('pelanggan.index') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('pelanggan.*') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="user-check" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Data Pelanggan</span>
					</a>
					<a href="{{ $ownerSubscriptionActive ? route('supplier.index') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('supplier.*') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="truck" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Data Supplier</span>
					</a>
				@endif
				<a href="{{ $ownerSubscriptionActive ? route('admin.index') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('admin.*') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
					<i data-lucide="shield-check" class="hz-nav-lucide"></i>
					<span class="hz-nav-label">Manajemen Admin</span>
				</a>
			@endif
		</div>

		@if($isOwner)
			<div class="hz-nav-group">
				<p class="hz-nav-group-title">Transaksi</p>
				@if(!session()->has('admin_id') && $paketType >= 2)
					<a href="{{ $ownerSubscriptionActive ? route('transaksi.masuk') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('transaksi.masuk') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="arrow-down-left" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Transaksi Masuk</span>
					</a>
				@endif
				<a href="{{ $ownerSubscriptionActive ? route('transaksi.pos') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('transaksi.pos') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
					<i data-lucide="shopping-cart" class="hz-nav-lucide"></i>
					<span class="hz-nav-label">POS Kasir</span>
				</a>
				<a href="{{ $ownerSubscriptionActive ? route('shift.index') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('shift.*') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
					<i data-lucide="clock" class="hz-nav-lucide"></i>
					<span class="hz-nav-label">Shift Kasir</span>
				</a>
				@if(!session()->has('admin_id'))
					<a href="{{ $ownerSubscriptionActive ? route('transaksi.keluar') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('transaksi.keluar') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="arrow-up-right" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Riwayat Penjualan</span>
					</a>
				@endif
				<a href="{{ $ownerSubscriptionActive ? route('expenses.index') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('expenses.*') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
					<i data-lucide="receipt" class="hz-nav-lucide"></i>
					<span class="hz-nav-label">Expenses</span>
				</a>
				@if(!session()->has('admin_id'))
					<a href="{{ $ownerSubscriptionActive ? route('transaksi.log') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('transaksi.log') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="file-text" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Log Transaksi</span>
					</a>
				@endif
			</div>

			@if(!session()->has('admin_id'))
				<div class="hz-nav-group">
					<p class="hz-nav-group-title">Ringkasan Keuangan</p>
					<a href="{{ $ownerSubscriptionActive ? route('journal.index') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('journal.*') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="book-open" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Jurnal Finance</span>
					</a>
					<a href="{{ $ownerSubscriptionActive ? route('invoice.index') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('invoice.*') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="file-spreadsheet" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Invoice / AR</span>
					</a>
					<a href="{{ $ownerSubscriptionActive ? route('laporan.laba-rugi') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('laporan.laba-rugi') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="trending-up" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Laba Rugi</span>
					</a>
					<a href="{{ $ownerSubscriptionActive ? route('laporan.keuangan') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('laporan.keuangan') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<i data-lucide="pie-chart" class="hz-nav-lucide"></i>
						<span class="hz-nav-label">Ringkasan Keuangan</span>
					</a>
				</div>
			@endif
		@endif
	</nav>
</aside>
