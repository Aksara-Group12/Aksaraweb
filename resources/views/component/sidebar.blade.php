<aside class="hz-sidebar" id="hz-sidebar">
	@php
		$user = auth()->user();
		$isOwner = $user && $user->role === 'owner';
		$ownerSubscriptionActive = $isOwner ? (bool) \App\Models\Subscribe::getActiveSubscription($user->id) : true;
		$profile = auth()->user()->profile ?? \App\Models\Profile::where('user_id', auth()->id())->first();
		$storeName = $profile?->store_name ?? config('app.name', 'GolDay');
		$storeInitial = mb_strtoupper(mb_substr($storeName, 0, 1));
	@endphp
	<div class="hz-sidebar-header">
		<div class="hz-logo-badge">
			@if($profile && $profile->logo_path)
				@php
					$logoPath = asset('storage/' . $profile->logo_path);
					// Debug: Log the path
					// echo "<!-- Logo Path: $logoPath -->";
				@endphp
				<img 
					src="{{ $logoPath }}" 
					alt="{{ $storeName }}" 
					style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;"
					onerror="this.style.display='none'; this.parentElement.innerHTML='{{ $storeInitial }}';"
				>
			@else
				<span style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; font-weight: 700; font-size: 20px;">{{ $storeInitial }}</span>
			@endif
		</div>
		<div class="hz-logo-text">
			<p class="hz-logo-title">{{ $storeName }}</p>
			<p class="hz-logo-sub">Panel Manajemen Toko</p>
		</div>
	</div>
	<nav class="hz-nav">
		<div class="hz-nav-group">
			<p class="hz-nav-group-title">Menu</p>
			@if(auth()->user() && auth()->user()->role === 'superadmin')
				<a href="{{ route('dashboard-superadmin') }}" class="hz-nav-item hz-nav-item-active">
					<span class="hz-nav-icon" style="background: rgba(251, 191, 36, 0.95); color:#111827;">
						<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M4 9.25L10 3.5L16 9.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M5.75 8.75V14.5C5.75 15.33 6.42 16 7.25 16H12.75C13.58 16 14.25 15.33 14.25 14.5V8.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</span>
					<div>
						<p class="hz-nav-label-main">Dashboard Superadmin</p>
						<p class="hz-nav-label-sub">Kelola sistem &amp; keuangan</p>
					</div>
				</a>
			@elseif(auth()->user() && auth()->user()->role === 'owner')
				<a href="{{ route('dashboard') }}" class="hz-nav-item hz-nav-item-active">
					<span class="hz-nav-icon" style="background: rgba(79, 70, 229, 0.95); color:#ffffff;">
						<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M4 9.25L10 3.5L16 9.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M5.75 8.75V14.5C5.75 15.33 6.42 16 7.25 16H12.75C13.58 16 14.25 15.33 14.25 14.5V8.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</span>
					<div>
						<p class="hz-nav-label-main">Dashboard</p>
						<p class="hz-nav-label-sub">Ringkasan &amp; insight</p>
					</div>
				</a>
			@endif
			<!-- <a href="#" class="hz-nav-item">
				<span class="hz-nav-icon">
					<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M10 10C11.73 10 13.13 8.6 13.13 6.88C13.13 5.15 11.73 3.75 10 3.75C8.27 3.75 6.88 5.15 6.88 6.88C6.88 8.6 8.27 10 10 10Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
						<path d="M5 16.25C5.55 14.57 7.58 13.25 10 13.25C12.42 13.25 14.45 14.57 15 16.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</span>
				<span class="hz-nav-label-main">Profil</span>
			</a> -->
		</div>
		<div class="hz-nav-group">
			<p class="hz-nav-group-title">Manajemen</p>
			@if(auth()->user() && auth()->user()->role === 'owner')
				<a href="{{ $ownerSubscriptionActive ? route('produk.index') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
					<span class="hz-nav-icon">
						<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M4.5 5.5L10 3L15.5 5.5V9.5C15.5 13.09 13.17 15.83 10 17C6.83 15.83 4.5 13.09 4.5 9.5V5.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</span>
					<span class="hz-nav-label-main">Semua Produk</span>
				</a>
				<a href="{{ $ownerSubscriptionActive ? route('produkstok.index') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
					<span class="hz-nav-icon">
						<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle cx="10" cy="10" r="7" stroke="currentColor" stroke-width="1.5" fill="none" />
							<path d="M7 10h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
							<path d="M10 7v6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
						</svg>
					</span>
					<span class="hz-nav-label-main">Manajemen Stok</span>
				</a>
				@if(!session()->has('admin_id'))
					<a href="{{ $ownerSubscriptionActive ? route('stock-adjustment.index') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<span class="hz-nav-icon">
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
						<span class="hz-nav-label-main">Stock Adjustment</span>
					</a>
					<a href="{{ $ownerSubscriptionActive ? route('produk.log') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<span class="hz-nav-icon">
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="1.5" fill="none" />
								<path d="M10 6V10L13 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
						</span>
						<span class="hz-nav-label-main">Log Produk</span>
					</a>
					<a href="{{ $ownerSubscriptionActive ? route('pelanggan.index') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<span class="hz-nav-icon">
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M12 2C7.58 2 4 5.42 5.58 5.42 10C5.42 14.42 7.58 18 12 18C16.42 18 18.58 14.42 18.58 10C18.58 5.58 16.42 2 12 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M12 6V8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M9 11H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
						<span class="hz-nav-label-main">Data Pelanggan</span>
					</a>
					<a href="{{ $ownerSubscriptionActive ? route('supplier.index') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<span class="hz-nav-icon">
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M5 4h14M12 4v6m0 0l-3 4m3-4v6m-2 2h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
						<span class="hz-nav-label-main">Data Supplier</span>
					</a>
				@endif
				<a href="{{ $ownerSubscriptionActive ? route('admin.index') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
					<span class="hz-nav-icon">
						<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M7 8C8.65685 8 10 6.65685 10 5C10 3.34315 8.65685 2 7 2C5.34315 2 4 3.34315 4 5C4 6.65685 5.34315 8 7 8Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M13 8C14.6569 8 16 6.65685 16 5C16 3.34315 14.6569 2 13 2C11.3431 2 10 3.34315 10 5C10 6.65685 11.3431 8 13 8Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M7 18C8.65685 18 10 16.6569 10 15C10 13.3431 8.65685 12 7 12C5.34315 12 4 13.3431 4 15C4 16.6569 5.34315 18 7 18Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M1 15L3 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M11 15L19 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</span>
					<span class="hz-nav-label-main">Manajemen Admin</span>
				</a>
			@endif
			@if(auth()->user() && auth()->user()->role === 'superadmin')
				<a href="{{ route('manajemen-user.index') }}" class="hz-nav-item hz-nav-subitem">
					<span class="hz-nav-label-main">Manajemen User</span>
				</a>
				<a href="{{ route('paket.index') }}" class="hz-nav-item hz-nav-subitem">
					<span class="hz-nav-label-main">Manajemen Paket</span>
				</a>
				<a href="{{ route('bank.index') }}" class="hz-nav-item hz-nav-subitem">
					<span class="hz-nav-label-main">Rekening</span>
				</a>
			@endif
		</div>
		@if(auth()->user() && auth()->user()->role === 'owner')
			<div class="hz-nav-group">
				<p class="hz-nav-group-title">Transaksi</p>
				@if(!session()->has('admin_id'))
					<a href="{{ $ownerSubscriptionActive ? route('transaksi.masuk') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<span class="hz-nav-icon">
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M10 4V14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								<path d="M6 10L10 14L14 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								<path d="M3 17H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
						</span>
						<span class="hz-nav-label-main">Transaksi Masuk</span>
					</a>
					<a href="{{ $ownerSubscriptionActive ? route('transaksi.pos') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<span class="hz-nav-icon">
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
						<span class="hz-nav-label-main">POS Kasir</span>
					</a>
					<a href="{{ $ownerSubscriptionActive ? route('shift.index') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<span class="hz-nav-icon">
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M12 8V4H8V8H4V12H8V16H12V12H16V8H12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M2 12H18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
						<span class="hz-nav-label-main">Shift Kasir</span>
					</a>
				@endif
				<a href="{{ $ownerSubscriptionActive ? route('transaksi.keluar') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
					<span class="hz-nav-icon">
						<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M10 14V4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M14 8L10 4L6 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M3 17H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</span>
					<span class="hz-nav-label-main">Keluar</span>
				</a>
				<a href="{{ $ownerSubscriptionActive ? route('expenses.index') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
					<span class="hz-nav-icon">
						<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M3 4H17C18.1 4 19 4.9 19 6V16C19 17.1 18.1 18 17 18H3C1.9 18 1 17.1 1 16V6C1 4.9 1.9 4 3 4Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M1 8H19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							<circle cx="5" cy="12" r="1.5" fill="currentColor" />
						</svg>
					</span>
					<span class="hz-nav-label-main">Expenses</span>
				</a>
				@if(!session()->has('admin_id'))
					<a href="{{ $ownerSubscriptionActive ? route('transaksi.log') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<span class="hz-nav-icon">
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M10 3C6.13 3 3 6.13 3 10C3 13.87 6.13 17 10 17C13.87 17 17 13.87 17 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
								<path d="M10 6V10L13 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
							</svg>
						</span>
						<span class="hz-nav-label-main">Log Transaksi</span>
					</a>
				@endif
			</div>
			@if(!session()->has('admin_id'))
				<div class="hz-nav-group">
					<p class="hz-nav-group-title">Ringkasan Keuangan</p>
					<a href="{{ $ownerSubscriptionActive ? route('journal.index') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<span class="hz-nav-icon">
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M3 16V8C3 6.9 3.9 6 5 6H8V3C8 2.45 8.45 2 9 2C9.55 2 10 2.45 10 3V6H13V4C13 3.45 13.45 3 14 3C14.55 3 15 3.45 15 4V6H17C18.1 6 19 6.9 19 8V16C19 17.1 18.1 18 17 18H5C3.9 18 3 17.1 3 16Z" stroke="currentColor" stroke-width="1" fill="none" stroke-linecap="round" stroke-linejoin="round" />
								<path d="M7 10H5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
								<path d="M13 10H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
								<path d="M10 13H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
							</svg>
						</span>
						<span class="hz-nav-label-main">Jurnal Finance</span>
					</a>
					<a href="{{ $ownerSubscriptionActive ? route('invoice.index') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<span class="hz-nav-icon">
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
						<span class="hz-nav-label-main">Invoice / AR</span>
					</a>
					<a href="{{ $ownerSubscriptionActive ? route('laporan.laba-rugi') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<span class="hz-nav-icon">
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M3 16V8C3 6.9 3.9 6 5 6H8V3C8 2.45 8.45 2 9 2C9.55 2 10 2.45 10 3V6H13V4C13 3.45 13.45 3 14 3C14.55 3 15 3.45 15 4V6H17C18.1 6 19 6.9 19 8V16C19 17.1 18.1 18 17 18H5C3.9 18 3 17.1 3 16Z" stroke="currentColor" stroke-width="1" fill="none" stroke-linecap="round" stroke-linejoin="round" />
								<path d="M7 10H5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
								<path d="M13 10H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
								<path d="M10 13H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
							</svg>
						</span>
						<span class="hz-nav-label-main">Laba Rugi</span>
					</a>
					<a href="{{ $ownerSubscriptionActive ? route('laporan.keuangan') : 'javascript:void(0)' }}" class="hz-nav-item" @if(!$ownerSubscriptionActive) onclick="showSubscriptionModal()" @endif>
						<span class="hz-nav-icon">
							<svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M3 16V8C3 6.9 3.9 6 5 6H8V3C8 2.45 8.45 2 9 2C9.55 2 10 2.45 10 3V6H13V4C13 3.45 13.45 3 14 3C14.55 3 15 3.45 15 4V6H17C18.1 6 19 6.9 19 8V16C19 17.1 18.1 18 17 18H5C3.9 18 3 17.1 3 16Z" stroke="currentColor" stroke-width="1" fill="none" stroke-linecap="round" stroke-linejoin="round" />
								<path d="M7 10H5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
								<path d="M13 10H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
								<path d="M10 13H8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
							</svg>
						</span>
						<span class="hz-nav-label-main">Ringkasan Keuangan</span>
					</a>
				</div>
			@endif
		@endif
	</nav>
</aside>
