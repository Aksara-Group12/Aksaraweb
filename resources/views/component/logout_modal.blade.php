<!-- Shadcn UI Sign-out Confirmation Modal -->
<div id="shadcn-logout-modal" class="shadcn-modal-backdrop" style="display: none;" onclick="if(event.target === this) closeLogoutModal()">
	<div class="shadcn-modal-card" role="dialog" aria-modal="true" aria-labelledby="shadcn-logout-title">
		<div class="shadcn-modal-header">
			<div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
				<div style="width: 40px; height: 40px; border-radius: 10px; background: #FEF2F2; color: #DC2626; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid #FEE2E2;">
					<i data-lucide="log-out" style="width: 20px; height: 20px;"></i>
				</div>
				<div>
					<h3 id="shadcn-logout-title" class="shadcn-modal-title" style="margin: 0;">Keluar dari Akun</h3>
					<p style="margin: 2px 0 0 0; font-size: 0.78rem; color: #71717A;">Konfirmasi sesi keluar</p>
				</div>
			</div>
			<p class="shadcn-modal-description">
				Apakah Anda yakin ingin keluar? Anda harus masuk kembali dengan kredensial akun Anda untuk mengakses sistem Aksara Developer.
			</p>
		</div>
		<div class="shadcn-modal-footer">
			<button type="button" class="shadcn-modal-btn-cancel" onclick="closeLogoutModal()">
				Batal
			</button>
			<form method="POST" action="{{ route('logout') }}" style="margin: 0; display: inline;">
				@csrf
				<button type="submit" class="shadcn-modal-btn-confirm">
					Keluar Akun
				</button>
			</form>
		</div>
	</div>
</div>

<script>
	function openLogoutModal() {
		var modal = document.getElementById('shadcn-logout-modal');
		if (modal) {
			modal.style.display = 'flex';
			var sMenu = document.getElementById('hz-sidebar-user-menu');
			var hMenu = document.getElementById('hz-user-menu');
			if (sMenu) sMenu.style.display = 'none';
			if (hMenu) hMenu.style.display = 'none';
			if (typeof lucide !== 'undefined') {
				lucide.createIcons();
			}
		}
	}

	function closeLogoutModal() {
		var modal = document.getElementById('shadcn-logout-modal');
		if (modal) {
			modal.style.display = 'none';
		}
	}

	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') {
			closeLogoutModal();
			var sMenu = document.getElementById('hz-sidebar-user-menu');
			var hMenu = document.getElementById('hz-user-menu');
			if (sMenu) sMenu.style.display = 'none';
			if (hMenu) hMenu.style.display = 'none';
		}
	});
</script>

