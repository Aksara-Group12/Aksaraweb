<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Verifikasi Email - {{ config('app.name', 'Golday') }}</title>

		<link rel="preconnect" href="https://fonts.bunny.net">
		<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

		<style>
			* { box-sizing: border-box; margin: 0; padding: 0; }

			body {
				min-height: 100vh;
				font-family: 'Instrument Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
				background: radial-gradient(circle at top, #4f46e5 0, #020617 55%, #000000 100%);
				color: #1e293b;
				display: flex;
				align-items: center;
				justify-content: center;
				padding: 24px;
			}

			.container {
				width: 100%;
				max-width: 500px;
			}

			.card {
				background: rgba(15, 23, 42, 0.96);
				border-radius: 20px;
				border: 1px solid rgba(30, 64, 175, 0.5);
				box-shadow: 0 24px 60px rgba(15, 23, 42, 0.85);
				padding: 40px 30px;
				text-align: center;
			}

			.icon {
				font-size: 48px;
				margin-bottom: 20px;
			}

			h1 {
				font-size: 1.75rem;
				margin-bottom: 12px;
				color: #3b82f6;
			}

			.subtitle {
				font-size: 0.95rem;
				color: #64748b;
				margin-bottom: 24px;
				line-height: 1.6;
			}

			.message-box {
				background: rgba(59, 130, 246, 0.1);
				border: 1px solid rgba(59, 130, 246, 0.3);
				border-radius: 10px;
				padding: 16px;
				margin-bottom: 24px;
				color: #3b82f6;
				font-size: 0.9rem;
			}

			.form-group {
				margin-bottom: 16px;
			}

			label {
				display: block;
				font-size: 0.85rem;
				color: #64748b;
				margin-bottom: 6px;
				text-align: left;
			}

			input {
				width: 100%;
				padding: 10px 12px;
				border-radius: 8px;
				border: 1px solid rgba(51, 65, 85, 0.9);
				background: #ffffff;
				color: #1e293b;
				font-size: 0.9rem;
				transition: border-color 0.15s ease;
			}

			input:focus {
				outline: none;
				border-color: #3b82f6;
				box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.7);
			}

			.btn-group {
				display: flex;
				gap: 12px;
				margin-top: 28px;
			}

			button, .btn-link {
				flex: 1;
				padding: 12px 16px;
				border-radius: 10px;
				font-weight: 600;
				font-size: 0.9rem;
				border: none;
				cursor: pointer;
				transition: all 0.15s ease;
			}

			.btn-primary {
				background: #3b82f6;
				color: #020617;
				box-shadow: 0 18px 40px rgba(59, 130, 246, 0.45);
			}

			.btn-primary:hover {
				background: #2563eb;
				transform: translateY(-1px);
				box-shadow: 0 20px 48px rgba(59, 130, 246, 0.6);
			}

			.btn-link {
				background: rgba(148,163,184,0.1);
				color: #64748b;
				text-decoration: none;
				display: inline-flex;
				align-items: center;
				justify-content: center;
			}

			.btn-link:hover {
				background: rgba(148,163,184,0.2);
				color: #cbd5e1;
			}

			.info-text {
				font-size: 0.8rem;
				color: #6b7280;
				margin-top: 16px;
				line-height: 1.5;
			}

			a {
				color: #3b82f6;
				text-decoration: none;
			}

			a:hover {
				color: #2563eb;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="card">
				<div class="icon">✉️</div>
				<h1>Verifikasi Email Diperlukan</h1>
				<p class="subtitle">
					Akun Anda sudah dibuat, namun kami perlu memverifikasi email Anda sebelum Anda dapat login.
				</p>

				@if (session('success'))
					<div class="message-box">
						{{ session('success') }}
					</div>
				@endif

				@if (session('error'))
					<div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 10px; padding: 16px; margin-bottom: 24px; color: #ef4444; font-size: 0.9rem;">
						{{ session('error') }}
					</div>
				@endif

				<div style="background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(148, 163, 184, 0.2); border-radius: 10px; padding: 20px; margin-bottom: 24px; text-align: left;">
					<p style="margin-bottom: 12px; color: #cbd5e1;"><strong>📧 Apa yang perlu Anda lakukan:</strong></p>
					<ol style="color: #64748b; padding-left: 20px;">
						<li style="margin-bottom: 8px;">Cek email Anda (termasuk folder Spam/Junk)</li>
						<li style="margin-bottom: 8px;">Klik link "Verifikasi Email" di email dari kami</li>
						<li>Kembali ke halaman login dan masuk dengan akun Anda</li>
					</ol>
				</div>

				<form method="POST" action="{{ route('verification.resend') }}" style="margin-bottom: 24px;">
					@csrf
					<div class="form-group">
						<label for="email">Email Anda:</label>
						<input 
							type="email" 
							id="email" 
							name="email" 
							required 
							placeholder="Masukkan email yang didaftarkan"
							style="margin-bottom: 12px;">
						@error('email')
							<span style="color: #ef4444; font-size: 0.8rem;">{{ $message }}</span>
						@enderror
					</div>

					<div class="btn-group">
						<button type="submit" class="btn-primary">
							Kirim Ulang Link
						</button>
						<a href="{{ route('login') }}" class="btn-link">
							Kembali ke Login
						</a>
					</div>
				</form>

				<div class="info-text">
					💡 <strong>Tips:</strong> Jika email tidak diterima, cek folder Spam atau Junk. Atau minta kami untuk mengirim link verifikasi ulang.
				</div>
			</div>
		</div>
	</body>
</html>
