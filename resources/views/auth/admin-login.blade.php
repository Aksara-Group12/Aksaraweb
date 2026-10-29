<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Masuk Admin - {{ config('app.name', 'Aksara Developer') }}</title>
		<link rel="icon" type="image/png" href="{{ asset('assets/images/aksara-logo.png') }}">
		<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

		<!-- Fonts: Newsreader & Plus Jakarta Sans (Sesuai Landing Page) -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

		<style>
			* { box-sizing: border-box; margin: 0; padding: 0; }

			body {
				min-height: 100vh;
				font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
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
				max-width: 440px;
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
				font-size: 0.68rem;
				font-weight: 500;
				color: #64748B;
				letter-spacing: 0.02em;
			}

			.admin-badge {
				display: inline-block;
				margin-bottom: 8px;
				font-size: 0.7rem;
				font-weight: 700;
				letter-spacing: 0.06em;
				text-transform: uppercase;
				color: #C5A059;
				background: rgba(197, 160, 89, 0.12);
				padding: 3px 10px;
				border-radius: 9999px;
				border: 1px solid rgba(197, 160, 89, 0.25);
			}

			.auth-title {
				font-family: 'Newsreader', Georgia, serif;
				font-size: 1.75rem;
				font-weight: 500;
				color: #0F223D;
				line-height: 1.2;
				letter-spacing: -0.01em;
			}

			.auth-sub {
				margin-top: 6px;
				font-size: 0.85rem;
				color: #64748B;
				line-height: 1.5;
			}

			.auth-form {
				margin-top: 24px;
				display: flex;
				flex-direction: column;
				gap: 16px;
			}

			.form-group {
				display: flex;
				flex-direction: column;
				gap: 6px;
			}

			.label {
				font-size: 0.8rem;
				font-weight: 600;
				color: #334155;
			}

			.input-wrapper {
				position: relative;
				display: flex;
				align-items: center;
			}

			.input {
				width: 100%;
				border-radius: 10px;
				border: 1px solid #CBD5E1;
				background: #FFFFFF;
				padding: 10px 14px;
				color: #0F223D;
				font-family: inherit;
				font-size: 0.875rem;
				outline: none;
				transition: all 0.15s ease;
			}

			.input:focus {
				border-color: #0F223D;
				box-shadow: 0 0 0 3px rgba(15, 34, 61, 0.08);
			}

			.input-error {
				border-color: #EF4444;
			}

			.input-error:focus {
				box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
			}

			.error-text {
				font-size: 0.75rem;
				color: #DC2626;
				margin-top: 2px;
			}

			.alert-error {
				margin-top: 14px;
				padding: 10px 14px;
				border-radius: 10px;
				border: 1px solid rgba(239, 68, 68, 0.2);
				background: #FEF2F2;
				font-size: 0.8rem;
				color: #991B1B;
				display: flex;
				align-items: center;
				gap: 8px;
			}

			.auth-actions {
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 8px;
				margin-top: 2px;
			}

			.remember-wrap {
				display: flex;
				align-items: center;
				gap: 8px;
				cursor: pointer;
				font-size: 0.8rem;
				color: #64748B;
				user-select: none;
			}

			.remember-wrap input[type="checkbox"] {
				accent-color: #0F223D;
				width: 15px;
				height: 15px;
				cursor: pointer;
				border-radius: 4px;
			}

			.auth-link {
				font-size: 0.8rem;
				font-weight: 600;
				color: #C5A059;
				text-decoration: none;
				transition: color 0.15s ease;
			}

			.auth-link:hover {
				color: #9E7E36;
				text-decoration: underline;
			}

			.auth-button {
				width: 100%;
				margin-top: 6px;
				border-radius: 10px;
				border: none;
				background: #0F223D;
				color: #FFFFFF;
				font-family: inherit;
				font-weight: 600;
				padding: 11px 16px;
				cursor: pointer;
				font-size: 0.875rem;
				transition: all 0.15s ease;
				display: flex;
				align-items: center;
				justify-content: center;
				gap: 8px;
				box-shadow: 0 4px 12px rgba(15, 34, 61, 0.15);
			}

			.auth-button:hover {
				background: #162C50;
				transform: translateY(-1px);
				box-shadow: 0 6px 16px rgba(15, 34, 61, 0.2);
			}

			.auth-button:active {
				transform: translateY(0);
			}

			.auth-footer {
				margin-top: 20px;
				text-align: center;
				font-size: 0.8rem;
				color: #64748B;
				padding-top: 16px;
				border-top: 1px solid #F1F5F9;
			}

			.auth-footer a {
				color: #0F223D;
				font-weight: 600;
				text-decoration: none;
			}

			.auth-footer a:hover {
				text-decoration: underline;
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
							<div class="logo-text-title">Aksara <span>Developer</span></div>
							<div class="logo-text-sub">Portal Akses Pengelola</div>
						</div>
					</a>
				</div>

				<div class="admin-badge">Admin Akses</div>
				<div class="auth-title">Masuk Administrator</div>
				<div class="auth-sub">Akses khusus manajemen sistem dan data kasir merchant.</div>

				@if ($errors->any())
					<div class="alert-error">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
						<span>{{ $errors->first() }}</span>
					</div>
				@endif

				<form class="auth-form" method="POST" action="{{ url('/admin/login') }}">
					@csrf
					<div class="form-group">
						<label class="label">Alamat Email</label>
						<div class="input-wrapper">
							<input type="email" name="email" value="{{ old('email') }}" class="input {{ $errors->has('email') ? 'input-error' : '' }}" placeholder="admin@aksarapos.com" required autofocus>
						</div>
						@error('email')
							<div class="error-text">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group">
						<label class="label">Kata Sandi</label>
						<div class="input-wrapper">
							<input type="password" name="password" class="input {{ $errors->has('password') ? 'input-error' : '' }}" placeholder="••••••••" required>
						</div>
						@error('password')
							<div class="error-text">{{ $message }}</div>
						@enderror
					</div>

					<div class="auth-actions">
						<label class="remember-wrap">
							<input type="checkbox" name="remember">
							<span>Ingat saya</span>
						</label>
						<a class="auth-link" href="{{ route('login') }}">Masuk sebagai Pemilik Bisnis &rarr;</a>
					</div>

					<button type="submit" class="auth-button">
						<span>Masuk ke Konsol Admin</span>
					</button>
				</form>

				<div class="auth-footer">
					Kembali ke <a href="{{ url('/') }}">Beranda Aksara Developer</a>
				</div>
			</div>
		</div>
	</body>
</html>
