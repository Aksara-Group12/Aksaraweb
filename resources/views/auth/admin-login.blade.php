<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Login Admin - {{ config('app.name', 'Aksara') }}</title>

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
				box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.7);
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
				background: rgba(15, 23, 42, 0.9);
				font-size: 0.75rem;
				color: #991b1b;
			}

			.auth-actions {
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 8px;
				margin-top: 4px;
			}

			.auth-button {
				width: 100%;
				border-radius: 12px;
				border: none;
				background: linear-gradient(135deg, #3b82f6, #2563eb);
				color: #020617;
				font-weight: 600;
				padding: 10px 14px;
				cursor: pointer;
				font-size: 0.85rem;
			}

			.auth-link {
				font-size: 0.75rem;
				color: #93c5fd;
				text-decoration: none;
			}

			.auth-footer {
				margin-top: 14px;
				text-align: center;
				font-size: 0.75rem;
				color: #94a3b8;
			}
		</style>
	</head>
	<body>
		<div class="auth-shell">
			<div class="auth-card">
				<div class="auth-header">
					<div class="logo-badge">
						<img src="{{ asset('images/logo.png') }}" alt="Logo">
					</div>
					<div>
						<div class="logo-text-title">{{ config('app.name', 'Golday') }}</div>
						<div class="logo-text-sub">Admin Access</div>
					</div>
				</div>

				<div class="auth-title">Login Admin</div>
				<div class="auth-sub">Masuk sebagai admin sesuai owner Anda.</div>

				@if ($errors->any())
					<div class="alert-error">
						{{ $errors->first() }}
					</div>
				@endif

				<form class="auth-form" method="POST" action="{{ url('/admin/login') }}">
					@csrf
					<div>
						<label class="label">Email</label>
						<input type="email" name="email" value="{{ old('email') }}" class="input {{ $errors->has('email') ? 'input-error' : '' }}" required>
						@error('email')
							<div class="error-text">{{ $message }}</div>
						@enderror
					</div>

					<div>
						<label class="label">Password</label>
						<input type="password" name="password" class="input {{ $errors->has('password') ? 'input-error' : '' }}" required>
						@error('password')
							<div class="error-text">{{ $message }}</div>
						@enderror
					</div>

					<div class="auth-actions">
						<label style="font-size: 0.75rem; color: #94a3b8; display: flex; align-items: center; gap: 6px;">
							<input type="checkbox" name="remember" style="cursor:pointer;">
							Ingat saya
						</label>
						<a class="auth-link" href="{{ route('login') }}">Login Owner</a>
					</div>

					<button type="submit" class="auth-button">Masuk</button>
				</form>

				<div class="auth-footer">Gunakan email admin yang sudah diverifikasi.</div>
			</div>
		</div>
	</body>
</html>
