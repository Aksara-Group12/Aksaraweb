<!-- Subscription Expired Modal -->
<div id="subscriptionExpiredModal" class="subscription-expired-overlay" style="display: none;">
	<div class="subscription-expired-modal">
		<div class="modal-icon-wrapper">
			<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
				<path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
			</svg>
		</div>
		<div class="modal-content">
			<h2 class="modal-title">Masa Berlangganan Habis</h2>
			<p class="modal-subtitle">Akses ke fitur utama telah dibatasi</p>
			<p class="modal-description">
				Periode trial atau berlangganan Anda telah berakhir. Untuk melanjutkan menggunakan semua fitur aplikasi, silakan perbarui paket berlangganan Anda.
			</p>
		</div>
		<div class="modal-actions">
			<a href="{{ route('profile.index') }}" class="btn-subscribe">
				Perbarui Berlangganan
			</a>
			<a href="{{ route('dashboard') }}" class="btn-dashboard">
				Kembali ke Dashboard
			</a>
			<button type="button" class="btn-dismiss" onclick="dismissSubscriptionModal()">
				Tutup
			</button>
		</div>
	</div>
</div>

<style>
	.subscription-expired-overlay {
		position: fixed;
		inset: 0;
		background: rgba(2, 6, 23, 0.8);
		display: flex;
		align-items: center;
		justify-content: center;
		z-index: 100;
		backdrop-filter: blur(6px);
		animation: fadeIn 0.3s ease-out;
	}

	@keyframes fadeIn {
		from {
			opacity: 0;
		}
		to {
			opacity: 1;
		}
	}

	.subscription-expired-modal {
		width: min(420px, 92vw);
		background: linear-gradient(135deg, rgba(15, 23, 42, 0.98) 0%, rgba(15, 23, 42, 0.95) 100%);
		border-radius: 24px;
		border: 2px solid rgba(251, 146, 60, 0.4);
		box-shadow: 0 24px 60px rgba(15, 23, 42, 0.85), 0 0 80px rgba(251, 146, 60, 0.15);
		padding: 32px 24px;
		text-align: center;
		animation: slideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
	}

	@keyframes slideIn {
		from {
			transform: translateY(-30px);
			opacity: 0;
		}
		to {
			transform: translateY(0);
			opacity: 1;
		}
	}

	.modal-icon-wrapper {
		display: flex;
		align-items: center;
		justify-content: center;
		width: 80px;
		height: 80px;
		margin: 0 auto 20px;
		border-radius: 50%;
		background: linear-gradient(135deg, rgba(251, 146, 60, 0.2) 0%, rgba(245, 158, 11, 0.1) 100%);
		color: #fb923c;
		border: 2px solid rgba(251, 146, 60, 0.3);
	}

	.modal-icon-wrapper svg {
		width: 100%;
		height: 100%;
		padding: 16px;
	}

	.modal-content {
		margin-bottom: 28px;
	}

	.modal-title {
		font-size: 1.4rem;
		font-weight: 700;
		color: #fbbf24;
		margin: 0 0 6px 0;
		letter-spacing: -0.5px;
	}

	.modal-subtitle {
		font-size: 0.85rem;
		color: #fb923c;
		margin: 0 0 16px 0;
		font-weight: 500;
	}

	.modal-description {
		font-size: 0.85rem;
		color: #cbd5f5;
		line-height: 1.6;
		margin: 0;
	}

	.modal-actions {
		display: flex;
		flex-direction: column;
		gap: 10px;
		margin-top: 28px;
	}

	.btn-subscribe {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 12px 22px;
		border-radius: 999px;
		background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
		color: #020617;
		font-size: 0.85rem;
		font-weight: 700;
		border: none;
		cursor: pointer;
		text-decoration: none;
		transition: all 0.3s ease;
		box-shadow: 0 12px 40px rgba(251, 191, 36, 0.4);
		letter-spacing: 0.3px;
	}

	.btn-subscribe:hover {
		transform: translateY(-2px);
		box-shadow: 0 16px 50px rgba(251, 191, 36, 0.5);
		background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
	}

	.btn-subscribe:active {
		transform: translateY(0);
	}

	.btn-dashboard {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 11px 20px;
		border-radius: 999px;
		background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(59, 130, 246, 0.1) 100%);
		color: #93c5fd;
		font-size: 0.83rem;
		font-weight: 600;
		border: 1.5px solid rgba(59, 130, 246, 0.5);
		cursor: pointer;
		text-decoration: none;
		transition: all 0.3s ease;
		letter-spacing: 0.3px;
	}

	.btn-dashboard:hover {
		background: linear-gradient(135deg, rgba(59, 130, 246, 0.3) 0%, rgba(59, 130, 246, 0.2) 100%);
		border-color: rgba(59, 130, 246, 0.7);
		color: #bfdbfe;
		transform: translateY(-1px);
	}

	.btn-dashboard:active {
		transform: translateY(0);
	}

	.btn-dismiss {
		padding: 10px 20px;
		border-radius: 999px;
		background: rgba(51, 65, 85, 0.3);
		color: #cbd5f5;
		font-size: 0.8rem;
		font-weight: 500;
		border: 1px solid rgba(51, 65, 85, 0.5);
		cursor: pointer;
		transition: all 0.3s ease;
	}

	.btn-dismiss:hover {
		background: rgba(51, 65, 85, 0.5);
		border-color: rgba(51, 65, 85, 0.7);
		color: #e5e7eb;
	}

	.btn-dismiss:active {
		transform: scale(0.98);
	}

	/* Responsive */
	@media (max-width: 480px) {
		.subscription-expired-modal {
			padding: 24px 18px;
		}

		.modal-title {
			font-size: 1.2rem;
		}

		.modal-icon-wrapper {
			width: 70px;
			height: 70px;
			margin-bottom: 16px;
		}

		.modal-actions {
			gap: 8px;
			margin-top: 20px;
		}

		.btn-subscribe {
			padding: 11px 18px;
			font-size: 0.8rem;
		}

		.btn-dashboard {
			padding: 10px 16px;
			font-size: 0.75rem;
		}

		.btn-dismiss {
			padding: 9px 16px;
			font-size: 0.75rem;
		}
	}
</style>

<script>
	function dismissSubscriptionModal() {
		const modal = document.getElementById('subscriptionExpiredModal');
		if (modal) {
			modal.style.display = 'none';
		}
	}

	function showSubscriptionModal() {
		const modal = document.getElementById('subscriptionExpiredModal');
		
		if (modal) {
			modal.style.display = 'flex';
		}
	}

	// Auto-show modal saat halaman load jika ada error message
	document.addEventListener('DOMContentLoaded', function () {
		const errorMessage = document.body.getAttribute('data-subscription-error');
		if (errorMessage) {
			setTimeout(() => {
				showSubscriptionModal();
			}, 100);
		}
	});
</script>
