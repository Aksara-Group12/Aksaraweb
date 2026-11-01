@php
	$user = auth()->user();
	$deniedMsg = session('access_denied') ?? session('employee_access_denied') ?? session('error_modal');
	$hasDenied = !empty($deniedMsg);
	$dashboardUrl = $user?->role === 'superadmin' 
		? route('dashboard-superadmin') 
		: ($user?->role === 'owner' 
			? route('dashboard') 
			: route('transaksi.pos'));
	$dashboardLabel = $user?->role === 'superadmin' 
		? 'Ke Dashboard Superadmin' 
		: ($user?->role === 'owner' 
			? 'Ke Dashboard' 
			: 'Ke POS Kasir');
@endphp

<!-- Access Denied Modal (Universal Popup) -->
<div id="employeeAccessDeniedModal" style="display: {{ $hasDenied ? 'flex' : 'none' }}; position: fixed; inset: 0; background: rgba(15, 34, 61, 0.5); backdrop-filter: blur(6px); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
	<div style="background: #FFFFFF; border-radius: 20px; max-width: 440px; width: 100%; padding: 28px 24px; box-shadow: 0 25px 50px -12px rgba(15, 34, 61, 0.25); border: 1px solid #E2E8F0; text-align: center; animation: hzModalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
		
		<!-- Icon Lock / Shield Alert -->
		<div style="width: 62px; height: 62px; border-radius: 50%; background: #FEF2F2; border: 1px solid #FECACA; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; color: #DC2626; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.12);">
			<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
				<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
				<path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
			</svg>
		</div>

		<!-- Title -->
		<h3 id="accessDeniedTitle" style="font-family: {{ (auth()->check() && auth()->user()->role === 'superadmin') ? "'Geist', 'Geist Sans', system-ui, -apple-system, sans-serif" : "'Newsreader', Georgia, serif" }}; font-size: 1.5rem; font-weight: 600; color: #0F223D; margin: 0 0 8px 0; line-height: 1.2; letter-spacing: -0.015em;">
			Akses Ditolak
		</h3>

		<!-- Message -->
		<p id="employeeAccessDeniedMsg" style="font-size: 0.885rem; color: #64748B; margin: 0 0 24px 0; line-height: 1.55;">
			{{ $deniedMsg ?: 'Anda tidak memiliki hak akses untuk membuka halaman atau fitur ini.' }}
		</p>

		<!-- Actions -->
		<div style="display: flex; gap: 10px; justify-content: center;">
			<button type="button" onclick="closeAccessDeniedModal()" style="flex: 1; padding: 11px 18px; border-radius: 10px; background: #FAF8F5; border: 1px solid #CBD5E1; color: #475569; font-weight: 600; font-size: 0.875rem; cursor: pointer; transition: background 0.15s ease;" onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='#FAF8F5'">
				Tutup
			</button>
			<a href="{{ $dashboardUrl }}" style="flex: 1.2; padding: 11px 18px; border-radius: 10px; background: #0F223D; color: #FFFFFF; font-weight: 600; font-size: 0.875rem; text-decoration: none; text-align: center; box-shadow: 0 2px 8px rgba(15,34,61,0.18); transition: background 0.15s ease;" onmouseover="this.style.background='#162C50'" onmouseout="this.style.background='#0F223D'">
				{{ $dashboardLabel }}
			</a>
		</div>
	</div>
</div>

<style>
@keyframes hzModalPop {
	from {
		opacity: 0;
		transform: scale(0.92) translateY(8px);
	}
	to {
		opacity: 1;
		transform: scale(1) translateY(0);
	}
}
</style>

<script>
window.showAccessDeniedModal = function(title, msg) {
	const modal = document.getElementById('employeeAccessDeniedModal');
	if (modal) {
		const tElem = document.getElementById('accessDeniedTitle');
		const mElem = document.getElementById('employeeAccessDeniedMsg');
		if (tElem && title) tElem.textContent = title;
		if (mElem && msg) mElem.textContent = msg;
		modal.style.display = 'flex';
	}
};

window.closeAccessDeniedModal = function() {
	const modal = document.getElementById('employeeAccessDeniedModal');
	if (modal) modal.style.display = 'none';
};

// Aliases for compatibility
window.showEmployeeDeniedModal = function(msg) {
	window.showAccessDeniedModal('Akses Ditolak', msg);
};
window.closeEmployeeDeniedModal = function() {
	window.closeAccessDeniedModal();
};
</script>
