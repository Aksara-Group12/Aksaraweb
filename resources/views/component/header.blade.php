<header class="hz-header" style="display:flex; align-items:center; justify-content:space-between; padding: 14px 32px; background: #FFFFFF; border-bottom: 1px solid #E2E8F0; min-height: 72px;">
	<div class="hz-header-left" style="display: flex; align-items: center; gap: 14px;">
		<button type="button" class="hz-mobile-toggle" id="hz-sidebar-toggle" aria-label="Buka menu navigasi">
			<i data-lucide="menu" style="width: 18px; height: 18px;"></i>
		</button>
		@hasSection('header_content')
			@yield('header_content')
		@else
			<div class="hz-header-default-title">
				<h1 style="font-family: 'Newsreader', Georgia, serif !important; font-size: 1.45rem; font-weight: 500; color: #0F223D; margin: 0; line-height: 1.2; letter-spacing: -0.01em;">
					@yield('page_title', 'Dashboard')
				</h1>
				@hasSection('page_subtitle')
					<p style="font-family: 'Inter', sans-serif !important; font-size: 0.8rem; color: #64748B; margin: 3px 0 0 0;">
						@yield('page_subtitle')
					</p>
				@endif
			</div>
		@endif
	</div>

	<div class="hz-header-right" style="margin-left:auto; display: flex; align-items: center; gap: 12px; position: relative;">
		@php
			$currentUser = auth()->user();
			$isSuperadmin = $currentUser && $currentUser->role === 'superadmin';
			$isOwner = $currentUser && $currentUser->role === 'owner';
			$paketType = $isOwner ? \App\Models\Subscribe::getPackageType($currentUser->id) : 1;
			$currentProfile = $currentUser ? ($currentUser->profile ?? \App\Models\Profile::where('user_id', $currentUser->id)->first()) : null;
			$logoPath = $currentProfile?->logo_path;
			$displayName = session('admin_name') ?? $currentUser?->name ?? 'Pengguna';
			$nameParts = explode(' ', trim($displayName));
			$initials = strtoupper(substr($nameParts[0] ?? 'U', 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : substr($nameParts[0] ?? '', 1, 1)));
			$hasAvatar = !empty($logoPath) && (file_exists(public_path('storage/' . $logoPath)) || \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath));
			$roleLabel = session()->has('admin_id') 
				? 'Kasir' 
				: ($isSuperadmin 
					? 'Superadmin' 
					: ($isOwner 
						? 'Owner' 
						: ($currentUser?->role === 'kasir' ? 'Kasir' : ($currentUser?->role ? ucfirst($currentUser->role) : 'Pengguna'))));
		@endphp


		<!-- Bulatan Foto Profil / Avatar (Shadcn Navbar Style) -->
		<div id="hz-user-trigger" style="cursor: pointer; position: relative;" title="{{ $displayName }}">
			<div class="hz-avatar" style="overflow: hidden; width: 34px; height: 34px; border-radius: 9999px; border: 1px solid #E4E4E7; display: flex; align-items: center; justify-content: center; background: #F4F4F5; color: #09090B; font-weight: 600; font-size: 0.8rem; flex-shrink: 0; box-shadow: 0 1px 2px rgba(0,0,0,0.04); transition: box-shadow 0.2s ease, border-color 0.2s ease;" onmouseover="this.style.boxShadow='0 0 12px rgba(197, 160, 89, 0.45)'; this.style.borderColor='rgba(197, 160, 89, 0.7)'" onmouseout="this.style.boxShadow='0 1px 2px rgba(0,0,0,0.04)'; this.style.borderColor='#E4E4E7'">
				@if($hasAvatar)
					<img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $displayName }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 9999px; display: block;">
				@else
					{{ $initials }}
				@endif
			</div>
		</div>

		<!-- Dropdown Menu Kanan Atas (Persis Shadcn Profile Dropdown) -->
		<div id="hz-user-menu" class="shadcn-dropdown-menu" style="display: none; position: absolute; right: 0; top: calc(100% + 8px); width: 240px; z-index: 60;">
			<div class="shadcn-dropdown-header-user">
				<div class="shadcn-dropdown-avatar" style="border-radius: 9999px;">
					@if($hasAvatar)
						<img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $displayName }}" style="border-radius: 9999px;">
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
			var trigger = document.getElementById('hz-user-trigger');
			var menu = document.getElementById('hz-user-menu');
			if (!trigger || !menu) return;

			trigger.addEventListener('click', function (e) {
				e.stopPropagation();
				var sMenu = document.getElementById('hz-sidebar-user-menu');
				if (sMenu) sMenu.style.display = 'none';

				var isHidden = menu.style.display === 'none' || menu.style.display === '';
				menu.style.display = isHidden ? 'block' : 'none';
				if (isHidden && typeof lucide !== 'undefined') {
					lucide.createIcons();
				}
			});

			document.addEventListener('click', function (e) {
				if (!menu.contains(e.target) && !trigger.contains(e.target)) {
					menu.style.display = 'none';
				}
			});
		});
	</script>
</header>
