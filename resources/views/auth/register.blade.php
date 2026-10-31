<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Daftar - {{ config('app.name', 'Aksara Developer') }}</title>
		<link rel="icon" type="image/png" href="{{ asset('assets/images/aksara-logo.png') }}">
		<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

		<!-- Fonts: Newsreader & Inter -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400&display=swap" rel="stylesheet">

		<style>
			* { box-sizing: border-box; margin: 0; padding: 0; }

			body {
				min-height: 100vh;
				font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
				background-color: #FAF8F5;
				background-image: 
					radial-gradient(circle at 50% 0%, rgba(197, 160, 89, 0.1) 0%, transparent 50%),
					radial-gradient(rgba(15, 34, 61, 0.05) 1px, transparent 1px);
				background-size: 100% 100%, 24px 24px;
				color: #0F223D;
				display: flex;
				align-items: center;
				justify-content: center;
				padding: 24px;
				-webkit-font-smoothing: antialiased;
			}

			.auth-shell {
				width: 100%;
				max-width: 480px;
			}

			.auth-card {
				background: #FFFFFF;
				border-radius: 20px;
				border: 1px solid rgba(226, 232, 240, 0.95);
				box-shadow: 0 10px 30px -5px rgba(15, 34, 61, 0.06), 0 4px 6px -2px rgba(15, 34, 61, 0.03);
				padding: 32px 30px 34px;
				transition: border-color 0.2s ease;
			}

			.auth-card:hover {
				border-color: rgba(197, 160, 89, 0.4);
			}

			.auth-header {
				display: flex;
				align-items: center;
				margin-bottom: 24px;
				padding-bottom: 20px;
				border-bottom: 1px solid #F1F5F9;
			}

			.brand-link {
				display: flex;
				align-items: center;
				gap: 12px;
				text-decoration: none;
			}

			.logo-badge {
				height: 38px;
				width: auto;
				display: flex;
				align-items: center;
				justify-content: center;
				flex-shrink: 0;
			}

			.logo-badge img {
				height: 38px;
				width: auto;
				object-fit: contain;
			}

			.brand-text-wrap {
				padding-left: 10px;
				border-left: 1px solid #E2E8F0;
			}

			.logo-text-title {
				font-size: 1rem;
				font-weight: 700;
				color: #0F223D;
				line-height: 1.2;
			}

			.logo-text-title span {
				color: #C5A059;
			}

			.logo-text-sub {
				margin-top: 1px;
				font-size: 0.7rem;
				color: #64748B;
				font-weight: 500;
				letter-spacing: 0.02em;
			}

			.auth-title {
				font-family: 'Newsreader', Georgia, serif;
				font-size: 1.75rem;
				font-weight: 400;
				color: #0F223D;
				letter-spacing: -0.02em;
				line-height: 1.2;
			}

			.auth-sub {
				margin-top: 6px;
				font-size: 0.85rem;
				color: #64748B;
				line-height: 1.45;
			}

			.auth-form {
				margin-top: 22px;
				display: flex;
				flex-direction: column;
				gap: 16px;
			}

			.label {
				display: block;
				font-size: 0.8rem;
				font-weight: 600;
				color: #334155;
				margin-bottom: 6px;
			}

			.input {
				width: 100%;
				border-radius: 10px;
				border: 1px solid #CBD5E1;
				background: #FFFFFF;
				padding: 11px 13px;
				color: #0F223D;
				font-family: 'Inter', sans-serif;
				font-size: 0.875rem;
				outline: none;
				transition: border-color 0.15s ease, box-shadow 0.15s ease;
			}

			.input:focus {
				border-color: #0F223D;
				box-shadow: 0 0 0 3px rgba(15, 34, 61, 0.08);
			}

			.input-error {
				border-color: #EF4444;
			}

			.error-text {
				margin-top: 5px;
				font-size: 0.75rem;
				color: #DC2626;
			}

			.alert-error {
				margin-bottom: 14px;
				padding: 10px 14px;
				border-radius: 10px;
				border: 1px solid #FECACA;
				background: #FEF2F2;
				font-size: 0.8rem;
				color: #991B1B;
				line-height: 1.4;
			}

			.auth-actions {
				margin-top: 6px;
				display: flex;
				flex-direction: column;
				gap: 14px;
			}

			.btn-primary {
				width: 100%;
				border: none;
				border-radius: 10px;
				background: #0F223D;
				color: #FFFFFF;
				font-family: 'Inter', sans-serif;
				font-size: 0.875rem;
				font-weight: 600;
				padding: 12px 20px;
				cursor: pointer;
				box-shadow: 0 4px 12px rgba(15, 34, 61, 0.15);
				transition: all 0.2s ease;
			}

			.btn-primary:hover {
				background: #162C50;
				box-shadow: 0 6px 18px rgba(15, 34, 61, 0.2);
				transform: translateY(-1px);
			}

			.helper-link {
				font-size: 0.8rem;
				color: #64748B;
				text-align: center;
			}

			.helper-link a {
				color: #C5A059;
				text-decoration: none;
				font-weight: 600;
			}

			.helper-link a:hover {
				color: #9E7E36;
				text-decoration: underline;
			}

			.password-input-wrapper {
				position: relative;
				display: flex;
				align-items: center;
			}

			.password-toggle {
				position: absolute;
				right: 12px;
				background: none;
				border: none;
				color: #64748B;
				cursor: pointer;
				padding: 4px;
				display: flex;
				align-items: center;
				justify-content: center;
				transition: color 0.15s ease;
			}

			.password-toggle:hover {
				color: #0F223D;
			}

			.password-input-wrapper .input {
				padding-right: 42px;
			}
		</style>
	</head>
	<body>
		<div class="auth-shell">
			<div class="auth-card">
				<div class="auth-header">
					<a href="{{ url('/') }}" class="brand-link">
						<div class="logo-badge">
							<img src="{{ asset('assets/images/aksara-logo.png') }}" alt="Aksara Logo">
						</div>
						<div class="brand-text-wrap">
							<p class="logo-text-title">Aksara <span>Developer</span></p>
							<p class="logo-text-sub">Sistem Kasir & Bisnis UMKM</p>
						</div>
					</a>
				</div>
				<h1 class="auth-title">Buat Akun Baru</h1>
				<p class="auth-sub">Daftarkan akun toko baru untuk mengakses dashboard kasir, stok, dan laporan keuangan.</p>

				@if ($errors->any())
					<div class="alert-error" style="margin-top: 14px;">
						Terdapat kesalahan pada data yang Anda masukkan. Periksa kembali form di bawah.
					</div>
				@endif

				<form method="POST" action="{{ url('/register') }}" class="auth-form">
					@csrf

					<div>
						<label class="label" for="name">Nama Pemilik / Nama Toko</label>
						<input
							id="name"
							type="text"
							name="name"
							value="{{ old('name') }}"
							required
							autocomplete="name"
							placeholder="Contoh: Toko Surya Gemilang"
							class="input @error('name') input-error @enderror"
						>
						@error('name')
							<p class="error-text">{{ $message }}</p>
						@enderror
					</div>

					<div>
						<label class="label" for="email">Alamat Email</label>
						<input
							id="email"
							type="email"
							name="email"
							value="{{ old('email') }}"
							required
							autocomplete="email"
							placeholder="nama@tokoanda.com"
							class="input @error('email') input-error @enderror"
						>
						@error('email')
							<p class="error-text">{{ $message }}</p>
						@enderror
					</div>

					<div>
						<label class="label" for="password">Kata Sandi</label>
						<div class="password-input-wrapper">
							<input
								id="password"
								type="password"
								name="password"
								required
								autocomplete="new-password"
								placeholder="Minimal 8 karakter"
								class="input @error('password') input-error @enderror"
							>
							<button type="button" class="password-toggle" onclick="togglePasswordVisibility('password')" aria-label="Tampilkan kata sandi">
								<svg id="password-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
									<circle cx="12" cy="12" r="3"></circle>
								</svg>
							</button>
						</div>
						@error('password')
							<p class="error-text">{{ $message }}</p>
						@enderror
					</div>

					<div>
						<label class="label" for="password_confirmation">Konfirmasi Kata Sandi</label>
						<div class="password-input-wrapper">
							<input
								id="password_confirmation"
								type="password"
								name="password_confirmation"
								required
								autocomplete="new-password"
								placeholder="Ulangi kata sandi"
								class="input"
							>
							<button type="button" class="password-toggle" onclick="togglePasswordVisibility('password_confirmation')" aria-label="Tampilkan konfirmasi kata sandi">
								<svg id="password_confirmation-eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
									<circle cx="12" cy="12" r="3"></circle>
								</svg>
							</button>
						</div>
					</div>

					<div class="auth-actions">
						<button type="submit" class="btn-primary">Daftar Akun Sekarang</button>
						<p class="helper-link">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
					</div>
				</form>
			</div>
		</div>
	</body>
	<script>
		function togglePasswordVisibility(fieldId) {
			const passwordField = document.getElementById(fieldId);
			const eyeIcon = document.getElementById(fieldId + '-eye');
			
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

