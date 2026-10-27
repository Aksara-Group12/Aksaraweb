<header class="hz-header">
	<div class="hz-header-left">
		<button type="button" class="hz-mobile-toggle" id="hz-sidebar-toggle" aria-label="Toggle menu">
			<svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M3 5H17" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
				<path d="M3 10H17" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
				<path d="M3 15H17" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
			</svg>
		</button>
	</div>
	<div class="hz-header-right" style="margin-left:auto; gap: 10px; position: relative;">
		<div id="hz-user-trigger" class="hz-user-chip" style="cursor: pointer;">
			<div class="hz-avatar"></div>
			<div class="hz-user-text">
				<p class="hz-user-name">Hi, {{ auth()->user()->name ?? 'Admin' }}</p>
				<!-- <p class="hz-user-sub">Golday Dashboard</p> -->
			</div>
			<svg width="12" height="12" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M6 8L10 12L14 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
		</div>
		<div id="hz-user-menu" style="display:none; position:absolute; right:0; top:48px; background: rgba(15,23,42,0.98); border-radius: 14px; border:1px solid rgba(30,64,175,0.6); box-shadow: 0 18px 40px rgba(15,23,42,0.85); min-width: 180px; padding: 6px; font-size:0.8rem; z-index:20;">
			<a href="{{ route('profile.index') }}" style="display:flex; align-items:center; gap:8px; padding:8px 10px; border-radius:10px; color:#e5e7eb; text-decoration:none;">
				<span class="hz-nav-icon" style="width:22px; height:22px; border-radius:9px; background: rgba(15,23,42,0.95); font-size:0.75rem;">P</span>
				<span>Profil Toko</span>
			</a>
			<form method="POST" action="{{ route('logout') }}">
				@csrf
				<button type="submit" style="width:100%; text-align:left; display:flex; align-items:center; gap:8px; padding:8px 10px; border-radius:10px; border:none; background:none; color:#fecaca; cursor:pointer; font-size:0.8rem;">
					<span class="hz-nav-icon" style="width:22px; height:22px; border-radius:9px; background: rgba(127,29,29,0.9); font-size:0.75rem;">⏏</span>
					<span>Keluar</span>
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
