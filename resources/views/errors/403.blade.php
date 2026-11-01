<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Akses Ditolak | Aksara Developer</title>
	<link rel="icon" type="image/png" href="{{ asset('assets/images/aksara-logo.png') }}">

	<!-- Fonts: Newsreader & Plus Jakarta Sans -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,500;6..72,600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

	<style>
		* {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		body {
			font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
			background-color: #FAF8F5;
			background-image: radial-gradient(rgba(15, 34, 61, 0.05) 1px, transparent 1px);
			background-size: 24px 24px;
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 20px;
			color: #0F223D;
		}

		/* Modal Pop-up Card */
		.popup-card {
			background: #FFFFFF;
			border-radius: 24px;
			border: 1px solid rgba(226, 232, 240, 0.95);
			box-shadow: 0 25px 60px -15px rgba(15, 34, 61, 0.18), 0 0 0 1px rgba(15, 34, 61, 0.04);
			max-width: 480px;
			width: 100%;
			padding: 36px 30px;
			text-align: center;
			animation: popIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
			position: relative;
		}

		@keyframes popIn {
			from {
				opacity: 0;
				transform: scale(0.92) translateY(12px);
			}
			to {
				opacity: 1;
				transform: scale(1) translateY(0);
			}
		}

		.icon-wrap {
			width: 68px;
			height: 68px;
			border-radius: 50%;
			background: #FEF2F2;
			border: 1.5px solid #FECACA;
			display: flex;
			align-items: center;
			justify-content: center;
			margin: 0 auto 20px;
			color: #DC2626;
			box-shadow: 0 8px 20px -4px rgba(220, 38, 38, 0.18);
		}

		.badge-code {
			display: inline-block;
			padding: 3px 10px;
			border-radius: 999px;
			background: rgba(220, 38, 38, 0.08);
			color: #DC2626;
			font-size: 0.75rem;
			font-weight: 700;
			letter-spacing: 0.05em;
			text-transform: uppercase;
			margin-bottom: 8px;
		}

		h1 {
			font-family: 'Newsreader', Georgia, serif;
			font-size: 1.85rem;
			font-weight: 600;
			color: #0F223D;
			line-height: 1.2;
			margin-bottom: 10px;
		}

		p {
			font-size: 0.925rem;
			color: #64748B;
			line-height: 1.6;
			margin-bottom: 28px;
		}

		.actions-wrap {
			display: flex;
			gap: 12px;
			justify-content: center;
			flex-wrap: wrap;
		}

		.btn {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 8px;
			padding: 12px 22px;
			border-radius: 12px;
			font-size: 0.875rem;
			font-weight: 600;
			cursor: pointer;
			text-decoration: none;
			transition: all 0.18s ease;
			font-family: inherit;
		}

		.btn-back {
			background: #FAF8F5;
			color: #334155;
			border: 1px solid #CBD5E1;
		}

		.btn-back:hover {
			background: #F1F5F9;
			border-color: #94A3B8;
		}

		.btn-primary {
			background: #0F223D;
			color: #FFFFFF;
			border: 1px solid #0F223D;
			box-shadow: 0 4px 14px rgba(15, 34, 61, 0.18);
		}

		.btn-primary:hover {
			background: #162C50;
			border-color: #162C50;
			box-shadow: 0 6px 20px rgba(15, 34, 61, 0.25);
		}
	</style>
</head>
<body>

	<div class="popup-card">
		<!-- Icon Gembok / Shield Alert -->
		<div class="icon-wrap">
			<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
				<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
				<path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
			</svg>
		</div>

		<span class="badge-code">Error 403 • Terbatas</span>
		<h1>Akses Ditolak</h1>

		<p>
			{{ $exception->getMessage() ?: 'Anda tidak memiliki wewenang atau hak akses untuk melihat halaman ini.' }}
		</p>

		@php
			$user = auth()->user();
			$homeUrl = $user?->role === 'superadmin' 
				? route('dashboard-superadmin') 
				: ($user?->role === 'owner' 
					? route('dashboard') 
					: ($user ? route('transaksi.pos') : url('/')));
			$homeLabel = $user?->role === 'superadmin' 
				? 'Dashboard Superadmin' 
				: ($user?->role === 'owner' 
					? 'Dashboard Toko' 
					: ($user ? 'POS Kasir' : 'Beranda'));
		@endphp

		<div class="actions-wrap">
			<button type="button" class="btn btn-back" onclick="if(window.history.length > 1){ window.history.back(); } else { window.location.href='{{ $homeUrl }}'; }">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
					<line x1="19" y1="12" x2="5" y2="12"></line>
					<polyline points="12 19 5 12 12 5"></polyline>
				</svg>
				Kembali
			</button>
			<a href="{{ $homeUrl }}" class="btn btn-primary">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
					<polyline points="9 22 9 12 15 12 15 22"></polyline>
				</svg>
				{{ $homeLabel }}
			</a>
		</div>
	</div>

</body>
</html>

