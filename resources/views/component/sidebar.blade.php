<aside class="hz-sidebar" id="hz-sidebar">
	@php
		$user = auth()->user();
		$isSuperadmin = $user && $user->role === 'superadmin';
		$isOwner = $user && $user->role === 'owner';
		$ownerSubscriptionActive = $isOwner ? (bool) \App\Models\Subscribe::getActiveSubscription($user->id) : true;
		$paketType = $isOwner ? \App\Models\Subscribe::getPackageType($user->id) : 1; // 1, 2, or 3
		$profile = auth()->user()?->profile ?? \App\Models\Profile::where('user_id', auth()->id())->first();
		$storeName = $profile?->store_name ?? 'Aksara';
		$displayName = session('admin_name') ?? $user?->name ?? 'Pengguna';
		$nameParts = explode(' ', trim($displayName));
		$initials = strtoupper(substr($nameParts[0] ?? 'U', 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : substr($nameParts[0] ?? '', 1, 1)));
		$hasAvatar = !empty($profile?->logo_path) && (file_exists(public_path('storage/' . $profile->logo_path)) || \Illuminate\Support\Facades\Storage::disk('public')->exists($profile->logo_path));
		$roleLabel = session()->has('admin_id') 
			? 'Kasir' 
			: ($isSuperadmin 
				? 'Superadmin' 
				: ($isOwner 
					? 'Owner' 
					: ($user?->role === 'kasir' ? 'Kasir' : ($user?->role ? ucfirst($user->role) : 'Pengguna'))));
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
			<p class="hz-logo-sub" style="color: #64748B; font-size: 0.72rem; margin: 2px 0 0 0;">{{ $isSuperadmin ? 'Superadmin Konsol' : ($storeName !== 'Aksara' ? $storeName : 'Panel Manajemen Toko') }}</p>
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
				<a href="{{ session()->has('admin_id') ? 'javascript:void(0)' : ($ownerSubscriptionActive ? route('admin.index') : 'javascript:void(0)') }}" 
				   @if(session()->has('admin_id')) onclick="showEmployeeDeniedModal()" 
				   @elseif(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif 
				   class="hz-nav-item {{ request()->routeIs('admin.*') ? 'hz-nav-item-active' : '' }}">
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
				<a href="{{ session()->has('admin_id') ? 'javascript:void(0)' : ($ownerSubscriptionActive ? route('expenses.index') : 'javascript:void(0)') }}" 
				   @if(session()->has('admin_id')) onclick="showEmployeeDeniedModal()" 
				   @elseif(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif 
				   class="hz-nav-item {{ request()->routeIs('expenses.*') ? 'hz-nav-item-active' : '' }}">
					<i data-lucide="receipt" class="hz-nav-lucide"></i>
					<span class="hz-nav-label">Expenses</span>
				</a>
				<a href="{{ $ownerSubscriptionActive ? route('transaksi.log') : 'javascript:void(0)' }}" class="hz-nav-item {{ request()->routeIs('transaksi.log') ? 'hz-nav-item-active' : '' }}" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
					<i data-lucide="file-text" class="hz-nav-lucide"></i>
					<span class="hz-nav-label">Riwayat Transaksi</span>
				</a>
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

	<!-- Sidebar Footer User Profile (Shadcn Style - nav-user) -->
	<div class="hz-sidebar-footer" style="margin-top: auto; padding-top: 12px; border-top: 1px solid #F1F5F9; flex-shrink: 0; position: relative; z-index: 30;">
		<button type="button" id="hz-sidebar-user-trigger" title="Menu Akun & Profil" style="width: 100%; display: flex; align-items: center; justify-content: space-between; padding: 8px 10px; border-radius: 10px; border: none; background: transparent; cursor: pointer; text-align: left; transition: background 0.15s ease;" onmouseover="this.style.background='#F4F4F5'" onmouseout="this.style.background='transparent'">
			<div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
				<div style="width: 36px; height: 36px; border-radius: 9999px; background: #F4F4F5; color: #09090B; border: 1px solid #E4E4E7; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; overflow: hidden; flex-shrink: 0;">
					@if($hasAvatar)
						<img src="{{ asset('storage/' . $profile->logo_path) }}" alt="{{ $displayName }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 9999px; display: block;">
					@else
						{{ $initials }}
					@endif
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
					@if($hasAvatar)
						<img src="{{ asset('storage/' . $profile->logo_path) }}" alt="{{ $displayName }}" style="border-radius: 9999px;">
					@else
						{{ $initials }}
					@endif
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
