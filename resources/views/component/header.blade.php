<header class="hz-header">
	<div class="hz-header-left">
		<button type="button" class="hz-mobile-toggle" id="hz-sidebar-toggle" aria-label="Buka menu navigasi">
			<i data-lucide="menu" style="width: 18px; height: 18px;"></i>
		</button>
	</div>
	<div class="hz-header-right" style="margin-left:auto; gap: 12px; position: relative;">
		<div id="hz-user-trigger" class="hz-user-chip" style="cursor: pointer;" title="Menu Akun">
			<div class="hz-avatar">
				{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
			</div>
			<div class="hz-user-text">
				<p class="hz-user-name">Hai, {{ auth()->user()->name ?? 'Pengguna' }}</p>
			</div>
			<i data-lucide="chevron-down" style="width: 14px; height: 14px; color: #64748B;"></i>
		</div>
		<div id="hz-user-menu" style="display:none; position:absolute; right:0; top:46px; background: #FFFFFF; border-radius: 12px; border:1px solid #E2E8F0; box-shadow: 0 10px 25px -5px rgba(15, 34, 61, 0.12), 0 8px 10px -6px rgba(15, 34, 61, 0.08); min-width: 190px; padding: 6px; font-size:0.825rem; z-index:50;">
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
