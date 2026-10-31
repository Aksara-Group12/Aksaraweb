<header class="hz-header" style="display:flex; align-items:center; justify-content:space-between; padding: 14px 32px; background: #FFFFFF; border-bottom: 1px solid #E2E8F0; min-height: 72px;">
	<div class="hz-header-left" style="display: flex; align-items: center; gap: 14px;">
		<button type="button" class="hz-mobile-toggle" id="hz-sidebar-toggle" aria-label="Buka menu navigasi">
			<i data-lucide="menu" style="width: 18px; height: 18px;"></i>
		</button>
		@hasSection('header_content')
			@yield('header_content')
		@else
			<div class="hz-header-default-title">
				<h1 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.35rem; font-weight: 500; color: #0F223D; margin: 0; line-height: 1.2;">
					@yield('page_title', 'Dashboard')
				</h1>
				@hasSection('page_subtitle')
					<p style="font-size: 0.8rem; color: #64748B; margin: 2px 0 0 0;">
						@yield('page_subtitle')
					</p>
				@endif
			</div>
		@endif
	</div>
	<div class="hz-header-right" style="margin-left:auto; gap: 12px; position: relative;">
		@php
			$currentUser = auth()->user();
			$currentProfile = $currentUser ? ($currentUser->profile ?? \App\Models\Profile::where('user_id', $currentUser->id)->first()) : null;
			$logoPath = $currentProfile?->logo_path;
			$displayName = session('admin_name') ?? $currentUser?->name ?? 'Pengguna';
			$hasAvatar = !empty($logoPath) && (file_exists(public_path('storage/' . $logoPath)) || \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath));
		@endphp
		<div id="hz-user-trigger" class="hz-user-chip" style="cursor: pointer;" title="Menu Akun">
			<div class="hz-avatar" style="overflow: hidden; width: 32px; height: 32px; border-radius: 9999px; border: 1.5px solid rgba(197, 160, 89, 0.4); display: flex; align-items: center; justify-content: center; background: #0F223D; color: #C5A059; font-weight: 700; flex-shrink: 0;">
				@if($hasAvatar)
					<img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $displayName }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 9999px; display: block;">
				@else
					{{ strtoupper(substr($displayName, 0, 1)) }}
				@endif
			</div>
			<div class="hz-user-text">
				<p class="hz-user-name">Hai, {{ $displayName }}</p>
			</div>
			<i data-lucide="chevron-down" style="width: 14px; height: 14px; color: #64748B;"></i>
		</div>
		<div id="hz-user-menu" style="display:none; position:absolute; right:0; top:46px; background: #FFFFFF; border-radius: 12px; border:1px solid #E2E8F0; box-shadow: 0 10px 25px -5px rgba(15, 34, 61, 0.12), 0 8px 10px -6px rgba(15, 34, 61, 0.08); min-width: 220px; padding: 6px; font-size:0.825rem; z-index:50;">
			<div style="display:flex; align-items:center; gap:10px; padding:8px 10px; margin-bottom: 4px; border-bottom: 1px solid #F1F5F9;">
				<div class="hz-avatar-menu" style="width: 36px; height: 36px; border-radius: 9999px; background: #0F223D; color: #C5A059; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; overflow: hidden; flex-shrink: 0; border: 1.5px solid rgba(197, 160, 89, 0.4);">
					@if($hasAvatar)
						<img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $displayName }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 9999px; display: block;">
					@else
						{{ strtoupper(substr($displayName, 0, 1)) }}
					@endif
				</div>
				<div style="overflow: hidden; line-height: 1.25;">
					<p style="margin: 0; font-weight: 600; color: #0F223D; font-size: 0.85rem; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">{{ $displayName }}</p>
					<p style="margin: 2px 0 0 0; color: #64748B; font-size: 0.72rem; text-transform: capitalize;">{{ $currentUser?->role === 'superadmin' ? 'Superadmin' : ($currentUser?->role === 'owner' ? 'Pemilik Toko' : 'Kasir / Admin') }}</p>
				</div>
			</div>
			<a href="{{ route('profile.index') }}" style="display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:8px; color:#0F223D; text-decoration:none; font-weight:500; transition: background 0.15s ease;" onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
				<i data-lucide="{{ auth()->user()?->role === 'superadmin' ? 'user-cog' : 'store' }}" style="width: 16px; height: 16px; color: #C5A059;"></i>
				<span>{{ auth()->user()?->role === 'superadmin' ? 'Edit Profil' : 'Profil Toko' }}</span>
			</a>
			<div style="height: 1px; background: #F1F5F9; margin: 4px 6px;"></div>
			<form method="POST" action="{{ route('logout') }}">
				@csrf
				<button type="submit" style="width:100%; text-align:left; display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:8px; border:none; background:none; color:#DC2626; cursor:pointer; font-size:0.825rem; font-family: inherit; font-weight: 500; transition: background 0.15s ease;" onmouseover="this.style.background='#FEF2F2'" onmouseout="this.style.background='transparent'">
					<i data-lucide="log-out" style="width: 16px; height: 16px; color: #DC2626;"></i>
					<span>Keluar Akun</span>
				</button>
			</form>
		</div>
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			var trigger = document.getElementById('hz-user-trigger');
			var menu = document.getElementById('hz-user-menu');
			if (!trigger || !menu) return;

			function toggleMenu() {
				menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
			}

			trigger.addEventListener('click', function (e) {
				e.stopPropagation();
				toggleMenu();
			});

			document.addEventListener('click', function (e) {
				if (!menu.contains(e.target) && !trigger.contains(e.target)) {
					menu.style.display = 'none';
				}
			});
		});
	</script>
</header>
