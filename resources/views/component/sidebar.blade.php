<aside class="hz-sidebar" id="hz-sidebar">
	@php
		$user = auth()->user();
		$isSuperadmin = $user && $user->role === 'superadmin';
		$isOwner = $user && $user->role === 'owner';
		$ownerSubscriptionActive = $isOwner ? (bool) \App\Models\Subscribe::getActiveSubscription($user->id) : true;
		$storeName = 'Aksara';
		$displayName = session('admin_name') ?? $user?->name ?? 'Pengguna';
		$nameParts = explode(' ', trim($displayName));
		$initials = strtoupper(substr($nameParts[0] ?? 'U', 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : substr($nameParts[0] ?? '', 1, 1)));
		$roleLabel = session()->has('admin_id') 
			? 'Kasir' 
			: ($isSuperadmin 
				? 'Superadmin' 
				: ($isOwner 
					? 'Owner' 
					: ($user?->role === 'kasir' ? 'Kasir' : ($user?->role ? ucfirst($user->role) : 'Pengguna'))));

		// Helper function untuk cek permission
		$hasPermission = function($permission) use ($user) {
			if (!$user) return false;
			if ($user->role === 'owner' || $user->role === 'superadmin') return true;
			if (method_exists($user, 'hasPermission')) {
				return $user->hasPermission($permission);
			}
			return true;
		};
	@endphp
	<div class="hz-sidebar-header">
		<div class="hz-logo-badge" style="background: transparent; border: none; padding: 0; box-shadow: none;">
			<img 
				src="{{ asset('assets/images/aksara-logo.png') }}" 
				alt="Aksara Developer" 
				style="width: 34px; height: 34px; object-fit: contain;"
			>
		</div>
		<div class="hz-logo-text">
			<p class="hz-logo-title" style="color: #0F223D; font-size: 0.95rem; font-weight: 700; line-height: 1.2; margin: 0;">Aksara <span style="color: #C5A059;">Developer</span></p>
			<p class="hz-logo-sub" style="color: #64748B; font-size: 0.72rem; margin: 2px 0 0 0;">{{ $isSuperadmin ? 'Superadmin Konsol' : 'Panel Manajemen Toko' }}</p>
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
				<a href="{{ $ownerSubscriptionActive ? route('close-order.index') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('close-order.index') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
					<i data-lucide="lock" class="hz-nav-lucide"></i>
					<span class="hz-nav-label">Export Close Order</span>
				</a>
			</div>
		@endif
	</nav>

	<!-- Sidebar Footer User Profile (Shadcn Style - nav-user) -->
	<div class="hz-sidebar-footer" style="margin-top: auto; padding-top: 12px; border-top: 1px solid #F1F5F9; flex-shrink: 0; position: relative; z-index: 30;">
		<button type="button" id="hz-sidebar-user-trigger" title="Menu Akun & Profil" style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 8px 10px; border-radius: 10px; border: none; background: transparent; cursor: pointer; text-align: left; transition: background 0.15s ease;" onmouseover="this.style.background='#F4F4F5'" onmouseout="this.style.background='transparent'">
			<div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
				<div style="width: 36px; height: 36px; border-radius: 9999px; background: #F4F4F5; color: #09090B; border: 1px solid #E4E4E7; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; overflow: hidden; flex-shrink: 0;">
					{{ $initials }}
				</div>
				<div style="overflow: hidden; line-height: 1.25;">
					<p style="margin: 0; font-size: 0.85rem; font-weight: 600; color: #09090B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 135px;">
						{{ $displayName }}
					</p>
					<p style="margin: 2px 0 0 0; font-size: 0.73rem; color: #71717A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 135px;">
						{{ $roleLabel }}
					</p>
				</div>
			</div>
			<i data-lucide="chevrons-up-down" style="width: 15px; height: 15px; color: #71717A; flex-shrink: 0;"></i>
		</button>

		<!-- Dropdown Menu Kiri Bawah (Persis Shadcn nav-user) -->
		<div id="hz-sidebar-user-menu" class="shadcn-dropdown-menu" style="display: none; position: absolute; bottom: calc(100% + 8px); left: 0; right: 0; width: 100%; z-index: 60;">
			<div class="shadcn-dropdown-header-user">
				<div class="shadcn-dropdown-avatar" style="border-radius: 9999px;">
					{{ $initials }}
				</div>
				<div class="shadcn-dropdown-user-text">
					<p class="shadcn-dropdown-user-name">{{ $displayName }}</p>
					<p class="shadcn-dropdown-user-email">{{ $roleLabel }}</p>
				</div>
			</div>

			<div class="shadcn-dropdown-sep"></div>

			<a href="{{ route('profile.index') }}" class="shadcn-dropdown-item">
				<div class="shadcn-dropdown-item-content">
					<i data-lucide="user" style="width: 16px; height: 16px; color: #71717A;"></i>
					<span>Edit Profil</span>
				</div>
			</a>

			<div class="shadcn-dropdown-sep"></div>

			<button type="button" class="shadcn-dropdown-item shadcn-dropdown-item-destructive" onclick="openLogoutModal()">
				<div class="shadcn-dropdown-item-content">
					<i data-lucide="log-out" style="width: 16px; height: 16px; color: #DC2626;"></i>
					<span>Keluar</span>
				</div>
			</button>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			var sTrigger = document.getElementById('hz-sidebar-user-trigger');
			var sMenu = document.getElementById('hz-sidebar-user-menu');
			if (!sTrigger || !sMenu) return;

			sTrigger.addEventListener('click', function (e) {
				e.stopPropagation();
				var hMenu = document.getElementById('hz-user-menu');
				if (hMenu) hMenu.style.display = 'none';

				var isHidden = sMenu.style.display === 'none' || sMenu.style.display === '';
				sMenu.style.display = isHidden ? 'block' : 'none';
				if (isHidden && typeof lucide !== 'undefined') {
					lucide.createIcons();
				}
			});

			document.addEventListener('click', function (e) {
				if (!sMenu.contains(e.target) && !sTrigger.contains(e.target)) {
					sMenu.style.display = 'none';
				}
			});
		});
	</script>
</aside>
