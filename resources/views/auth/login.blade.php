<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Login - {{ config('app.name', 'Aksara') }}</title>

		<link rel="preconnect" href="https://fonts.bunny.net">
		<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

		<style>
			* { box-sizing: border-box; margin: 0; padding: 0; }

			body {
				min-height: 100vh;
				font-family: 'Instrument Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
				background: radial-gradient(circle at top, #3b82f6 0, #e0e7ff 55%, #f8f4e8 100%);
				color: #1e293b;
				display: flex;
				align-items: center;
				justify-content: center;
				padding: 24px;
			}

			.auth-shell {
				width: 100%;
				max-width: 430px;
			}

			.auth-card {
				background: rgba(255, 255, 255, 0.95);
				border-radius: 20px;
				border: 1px solid rgba(59, 130, 246, 0.3);
				box-shadow: 0 24px 60px rgba(59, 130, 246, 0.15);
				padding: 22px 22px 24px;
			}

			.auth-header {
				display: flex;
				align-items: center;
				gap: 12px;
				margin-bottom: 20px;
			}

			.logo-badge {
				width: 50px;
				height: 50px;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				overflow: hidden;
				flex-shrink: 0;
			}

			.logo-badge img {
				width: 100%;
				height: 100%;
				object-fit: cover;
			}

			.logo-text-title {
				font-size: 0.9rem;
				font-weight: 600;
				letter-spacing: 0.06em;
				text-transform: uppercase;
			}

			.logo-text-sub {
				margin-top: 2px;
				font-size: 0.7rem;
				color: #64748b;
			}

			.auth-title {
				margin-top: 4px;
				font-size: 1.25rem;
				font-weight: 600;
			}

			.auth-sub {
				margin-top: 4px;
				font-size: 0.8rem;
				color: #64748b;
			}

			.auth-form {
				margin-top: 16px;
				display: flex;
				flex-direction: column;
				gap: 12px;
			}

			.label {
				font-size: 0.75rem;
				color: #94a3b8;
				margin-bottom: 4px;
			}

			.input {
				width: 100%;
				border-radius: 12px;
				border: 1px solid rgba(51, 65, 85, 0.9);
				background: #ffffff;
				padding: 9px 11px;
				color: #1e293b;
				font-size: 0.8rem;
				outline: none;
				transition: border-color 0.15s ease, box-shadow 0.15s ease;
			}

			.input:focus {
				border-color: #3b82f6;
				box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.3);
			}

			.input-error {
				border-color: rgba(239, 68, 68, 0.9);
			}

			.error-text {
				margin-top: 4px;
				font-size: 0.7rem;
				color: #dc2626;
			}

			.alert-error {
				margin-top: 4px;
				margin-bottom: 6px;
				padding: 8px 10px;
				border-radius: 10px;
				border: 1px solid rgba(239, 68, 68, 0.3);
				background: rgba(254, 226, 226, 0.9);
				font-size: 0.75rem;
				color: #991b1b;
			}

			.success-overlay {
				position: fixed;
				inset: 0;
				background: rgba(30, 58, 138, 0.5);
				display: flex;
				align-items: center;
				justify-content: center;
				z-index: 50;
				backdrop-filter: blur(4px);
			}

			.warning-modal {
				width: min(360px, 92vw);
				background: rgba(15, 23, 42, 0.98);
				border-radius: 16px;
				border: 1px solid rgba(59, 130, 246, 0.4);
				box-shadow: 0 24px 60px rgba(15, 23, 42, 0.85);
				padding: 18px;
				text-align: center;
			}

			.warning-title {
				font-size: 0.95rem;
				font-weight: 600;
				color: #fdba74;
			}

			.warning-text {
				margin-top: 6px;
				font-size: 0.75rem;
				color: #e2e8f0;
			}

			.warning-close {
				margin-top: 14px;
				border: none;
				border-radius: 999px;
				background: rgba(59, 130, 246, 0.15);
				color: #fdba74;
				font-size: 0.75rem;
				font-weight: 600;
				padding: 8px 16px;
				cursor: pointer;
			}

			.success-modal {
				width: min(360px, 92vw);
				background: rgba(15, 23, 42, 0.98);
				border-radius: 16px;
				border: 1px solid rgba(34, 197, 94, 0.4);
				box-shadow: 0 24px 60px rgba(15, 23, 42, 0.85);
				padding: 18px;
				text-align: center;
			}

			.success-title {
				font-size: 0.95rem;
				font-weight: 600;
				color: #16a34a;
			}

			.success-text {
				margin-top: 6px;
				font-size: 0.75rem;
				color: #e2e8f0;
			}

			.success-close {
				margin-top: 14px;
				border: none;
				border-radius: 999px;
				background: rgba(34, 197, 94, 0.15);
				color: #16a34a;
				font-size: 0.75rem;
				font-weight: 600;
				padding: 8px 16px;
				cursor: pointer;
			}

			.auth-actions {
				margin-top: 10px;
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 8px;
			}

			.btn-primary {
				border: none;
				border-radius: 999px;
				background: #3b82f6;
				color: #020617;
				font-size: 0.8rem;
				font-weight: 600;
				padding: 9px 18px;
				cursor: pointer;
				box-shadow: 0 18px 40px rgba(59, 130, 246, 0.45);
				transition: background 0.15s ease, transform 0.15s ease, box-shadow 0.15s ease;
			}

			.btn-primary:hover {
				background: #2563eb;
				transform: translateY(-1px);
				box-shadow: 0 20px 48px rgba(59, 130, 246, 0.6);
			}

			.helper-link {
				font-size: 0.75rem;
				color: #64748b;
			}

			.helper-link a {
				color: #3b82f6;
				text-decoration: none;
				font-weight: 500;
			}

			.helper-link a:hover {
				color: #2563eb;
			}

			.remember-row {
				margin-top: 4px;
				display: flex;
				align-items: center;
				gap: 6px;
				font-size: 0.75rem;
				color: #94a3b8;
			}

			.checkbox {
				width: 14px;
				height: 14px;
				border-radius: 4px;
				border: 1px solid rgba(51, 65, 85, 0.9);
				background: #ffffff;
			}

			.password-input-wrapper {
				position: relative;
				display: flex;
				align-items: center;
			}

			.password-toggle {
				position: absolute;
				right: 10px;
				background: none;
				border: none;
				color: #64748b;
				cursor: pointer;
				padding: 4px;
				display: flex;
				align-items: center;
				justify-content: center;
				transition: color 0.15s ease;
			}

			.password-toggle:hover {
				color: #94a3b8;
			}

			.password-input-wrapper .input {
				padding-right: 36px;
			}
		</style>
	</head>
	<body>
		@if (session('login_warning'))
			<div class="success-overlay" id="loginWarningPopup">
				<div class="warning-modal">
					<div class="warning-title">Akun Belum Terdaftar</div>
					<div class="warning-text">{{ session('login_warning') }}</div>
					<button type="button" class="warning-close" onclick="document.getElementById('loginWarningPopup').style.display='none'">OK</button>
				</div>
			</div>
		@endif
		@if (session('success'))
			<div class="success-overlay" id="registerSuccessPopup">
				<div class="success-modal">
					<div class="success-title">Pendaftaran Berhasil</div>
					<div class="success-text">{{ session('success') }}</div>
					<button type="button" class="success-close" onclick="document.getElementById('registerSuccessPopup').style.display='none'">OK</button>
				</div>
			</div>
		@endif
		<div class="auth-shell">
			<div class="auth-card">
				<div class="auth-header">
					<div class="logo-badge">
						<img src="{{ asset('assets/images/GolDay.png') }}" alt="Golday Logo">
					</div>
					<div>
						<p class="logo-text-title">Golday</p>
						<p class="logo-text-sub">Analytics Dashboard</p>
					</div>
				</div>
				<p class="auth-title">Masuk ke Dashboard</p>
				<p class="auth-sub">Gunakan akun yang sudah terdaftar untuk mengelola penjualan dan stok.</p>

				@if ($errors->any())
					<div class="alert-error">
						Terdapat kesalahan pada data yang Anda masukkan. Periksa kembali form di bawah.
					</div>
				@endif

				<form method="POST" action="{{ url('/login') }}" class="auth-form">
					@csrf

					<div>
						<label class="label" for="email">Email</label>
						<input
							id="email"
							type="email"
							name="email"
							value="{{ old('email') }}"
							required
							autocomplete="email"
							class="input @error('email') input-error @enderror"
						>
						@error('email')
							<p class="error-text">{{ $message }}</p>
						@enderror
					</div>

					<div>
						<label class="label" for="password">Password</label>
						<div class="password-input-wrapper">
							<input
								id="password"
								type="password"
								name="password"
								required
								autocomplete="current-password"
								class="input @error('password') input-error @enderror"
							>
							<button type="button" class="password-toggle" onclick="togglePasswordVisibility('password')">
								<svg id="password-eye" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
									<circle cx="12" cy="12" r="3"></circle>
								</svg>
							</button>
						</div>
						@error('password')
							<p class="error-text">{{ $message }}</p>
						@enderror
					</div>

					<div class="remember-row">
						<input id="remember" type="checkbox" name="remember" class="checkbox" {{ old('remember') ? 'checked' : '' }}>
						<label for="remember">Ingat saya di perangkat ini</label>
					</div>

					<div class="auth-actions">
						<button type="submit" class="btn-primary">Masuk</button>
						<p class="helper-link">Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
						<!-- <p class="helper-link">Login sebagai admin? <a href="{{ url('/admin/login') }}">Masuk Admin</a></p> -->
					</div>
				</form>
			</div>
		</div>
	</body>
	<script>
		function togglePasswordVisibility(fieldId) {
			const passwordField = document.getElementById(fieldId);
			const eyeIcon = document.getElementById('password-eye');
			
			if (passwordField.type === 'password') {
				passwordField.type = 'text';
				eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
			} else {
				passwordField.type = 'password';
				eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
			}
		}
	</script>
</html>

