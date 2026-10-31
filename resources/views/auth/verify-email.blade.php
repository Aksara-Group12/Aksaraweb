<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Verifikasi Email - {{ config('app.name', 'Aksara Developer') }}</title>
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
				padding: 36px 32px;
				text-align: center;
			}

			.icon-wrap {
				width: 56px;
				height: 56px;
				margin: 0 auto 20px;
				border-radius: 16px;
				background: rgba(197, 160, 89, 0.12);
				color: #C5A059;
				display: flex;
				align-items: center;
				justify-content: center;
			}

			.auth-title {
				font-family: 'Newsreader', Georgia, serif;
				font-size: 1.75rem;
				font-weight: 500;
				color: #0F223D;
				line-height: 1.25;
			}

			.auth-sub {
				margin-top: 10px;
				font-size: 0.875rem;
				color: #64748B;
				line-height: 1.6;
			}

			.status-box {
				margin-top: 18px;
				padding: 12px 16px;
				border-radius: 10px;
				background: #ECFDF5;
				border: 1px solid #A7F3D0;
				color: #065F46;
				font-size: 0.85rem;
			}

			.btn-primary {
				margin-top: 24px;
				width: 100%;
				border-radius: 10px;
				border: none;
				background: #0F223D;
				color: #FFFFFF;
				font-family: inherit;
				font-weight: 600;
				padding: 11px 18px;
				cursor: pointer;
				font-size: 0.875rem;
				transition: all 0.15s ease;
			}

			.btn-primary:hover {
				background: #162C50;
			}

			.btn-link {
				margin-top: 14px;
				background: none;
				border: none;
				color: #64748B;
				font-family: inherit;
				font-size: 0.825rem;
				cursor: pointer;
				text-decoration: underline;
			}

			.btn-link:hover {
				color: #0F223D;
			}
		</style>
	</head>
	<body>
		<div class="auth-shell">
			<div class="auth-card">
				<div class="icon-wrap">
					<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
						<polyline points="22,6 12,13 2,6"/>
					</svg>
				</div>

				<h1 class="auth-title">Verifikasi Alamat Email</h1>
				<p class="auth-sub">
					Terima kasih telah mendaftar di Aksara Developer! Sebelum memulai, silakan periksa kotak masuk email Anda dan klik tautan verifikasi yang kami kirimkan.
				</p>

				@if (session('status') == 'verification-link-sent')
					<div class="status-box">
						Tautan verifikasi baru telah berhasil dikirim ke alamat email yang Anda daftarkan.
					</div>
				@endif

				<form method="POST" action="{{ route('verification.send') }}">
					@csrf
					<button type="submit" class="btn-primary">
						Kirim Ulang Email Verifikasi
					</button>
				</form>

				<form method="POST" action="{{ route('logout') }}">
					@csrf
					<button type="submit" class="btn-link">
						Keluar / Ganti Akun
					</button>
				</form>
			</div>
		</div>
	</body>
</html>
