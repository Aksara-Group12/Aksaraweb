<!-- Employee Access Denied Modal -->
<div id="employeeAccessDeniedModal" style="display: {{ session('employee_access_denied') ? 'flex' : 'none' }}; position: fixed; inset: 0; background: rgba(15, 34, 61, 0.45); backdrop-filter: blur(6px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
	<div style="background: #FFFFFF; border-radius: 20px; max-width: 440px; width: 100%; padding: 28px; box-shadow: 0 25px 50px -12px rgba(15, 34, 61, 0.25); border: 1px solid #E2E8F0; text-align: center; animation: hzModalPop 0.25s ease-out;">
		<div style="width: 60px; height: 60px; border-radius: 50%; background: #FEF2F2; border: 1px solid #FECACA; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; color: #DC2626;">
			<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
				<path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
			</svg>
		</div>
		<h3 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.5rem; font-weight: 600; color: #0F223D; margin: 0 0 8px 0;">Akses Ditolak</h3>
		<p style="font-size: 0.9rem; color: #64748B; margin: 0 0 24px 0; line-height: 1.5;" id="employeeAccessDeniedMsg">
			{{ session('employee_access_denied', 'Akses ditolak. Karyawan hanya dapat mengakses POS, Shift, dan Riwayat Transaksi.') }}
		</p>
		<div style="display: flex; gap: 10px; justify-content: center;">
			<a href="{{ route('transaksi.pos') }}" style="flex: 1; padding: 11px 18px; border-radius: 10px; background: #0F223D; color: #FFFFFF; font-weight: 600; font-size: 0.875rem; text-decoration: none; text-align: center; box-shadow: 0 2px 6px rgba(15,34,61,0.15);">
				Ke POS Kasir
			</a>
			<button type="button" onclick="closeEmployeeDeniedModal()" style="padding: 11px 18px; border-radius: 10px; background: #FAF8F5; border: 1px solid #CBD5E1; color: #475569; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
				Tutup
			</button>
		</div>
	</div>
</div>

<style>
@keyframes hzModalPop {
	from {
		opacity: 0;
		transform: scale(0.95);
	}
	to {
		opacity: 1;
		transform: scale(1);
	}
}
</style>

<script>
function showEmployeeDeniedModal(msg) {
	const modal = document.getElementById('employeeAccessDeniedModal');
	if (msg) {
		const txt = document.getElementById('employeeAccessDeniedMsg');
		if (txt) txt.textContent = msg;
	}
	if (modal) modal.style.display = 'flex';
}

function closeEmployeeDeniedModal() {
	const modal = document.getElementById('employeeAccessDeniedModal');
	if (modal) modal.style.display = 'none';
}
</script>

