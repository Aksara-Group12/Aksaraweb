<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>@yield('page_title', 'Dashboard') - {{ config('app.name', 'Aksara Developer') }}</title>
		<link rel="icon" type="image/png" href="{{ asset('assets/images/aksara-logo.png') }}">
		<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

		<!-- Fonts: Newsreader & Plus Jakarta Sans (Sesuai Landing Page Aksara Developer) -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

		<!-- Lucide Icons (Shadcn Standard) -->
		<script src="https://unpkg.com/lucide@latest"></script>

		<style>
			html,
			body {
				height: 100%;
			}

			* {
				box-sizing: border-box;
				margin: 0;
				padding: 0;
			}

			body.hz-body {
				height: 100vh;
				font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
				background-color: #FAF8F5;
				color: #0F223D;
				overflow: hidden;
				-webkit-font-smoothing: antialiased;
			}

			a {
				color: inherit;
				text-decoration: none;
			}

			.hz-bg {
				height: 100%;
				background-color: #FAF8F5;
				background-image: 
					radial-gradient(circle at 50% 0%, rgba(197, 160, 89, 0.08) 0%, transparent 40%),
					radial-gradient(rgba(15, 34, 61, 0.04) 1px, transparent 1px);
				background-size: 100% 100%, 24px 24px;
			}

			.hz-layout {
				display: flex;
				height: 100%;
				background: transparent;
			}

			/* Sidebar Deep Navy (Aksara Luxury Theme) */
			.hz-sidebar {
				width: 260px;
				padding: 22px 16px 20px;
				border-right: 1px solid rgba(255, 255, 255, 0.08);
				background: #0F223D;
				background-image: linear-gradient(180deg, #0F223D 0%, #162C50 100%);
				box-shadow: 4px 0 24px rgba(15, 34, 61, 0.12);
				display: flex;
				flex-direction: column;
				gap: 20px;
				position: relative;
				height: 100vh;
				max-height: 100vh;
				overflow-y: auto;
				overflow-x: hidden;
				z-index: 15;
				transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
			}

			/* Custom scrollbar for sidebar */
			.hz-sidebar::-webkit-scrollbar {
				width: 5px;
			}

			.hz-sidebar::-webkit-scrollbar-track {
				background: transparent;
			}

			.hz-sidebar::-webkit-scrollbar-thumb {
				background: rgba(255, 255, 255, 0.15);
				border-radius: 999px;
			}

			.hz-sidebar::-webkit-scrollbar-thumb:hover {
				background: rgba(255, 255, 255, 0.25);
			}

			@media (max-width: 768px) {
				.hz-sidebar {
					position: fixed;
					left: 0;
					top: 0;
					height: 100vh;
					transform: translateX(-100%);
					width: 270px;
					z-index: 50;
				}

				.hz-sidebar.hz-sidebar-open {
					transform: translateX(0);
				}

				.hz-sidebar-backdrop {
					position: fixed;
					inset: 0;
					background: rgba(15, 34, 61, 0.6);
					backdrop-filter: blur(4px);
					display: none;
					z-index: 45;
				}

				.hz-sidebar-backdrop.active {
					display: block;
				}
			}

			.hz-sidebar-header {
				display: flex;
				align-items: center;
				gap: 12px;
				padding: 0 4px 16px;
				border-bottom: 1px solid rgba(255, 255, 255, 0.08);
			}

			.hz-logo-badge {
				width: 38px;
				height: 38px;
				border-radius: 10px;
				background: rgba(255, 255, 255, 0.06);
				border: 1px solid rgba(255, 255, 255, 0.12);
				display: flex;
				align-items: center;
				justify-content: center;
				flex-shrink: 0;
				padding: 4px;
			}

			.hz-logo-badge img {
				width: 100%;
				height: 100%;
				object-fit: contain;
			}

			.hz-logo-text {
				display: flex;
				flex-direction: column;
				min-width: 0;
			}

			.hz-logo-title {
				font-size: 0.95rem;
				font-weight: 700;
				color: #FFFFFF;
				letter-spacing: -0.01em;
				line-height: 1.2;
				white-space: nowrap;
				overflow: hidden;
				text-overflow: ellipsis;
			}

			.hz-logo-title span {
				color: #C5A059;
			}

			.hz-logo-sub {
				margin-top: 2px;
				font-size: 0.68rem;
				font-weight: 500;
				color: #94A3B8;
				letter-spacing: 0.02em;
			}

			.hz-nav {
				flex: 1;
				display: flex;
				flex-direction: column;
				gap: 18px;
				font-size: 0.825rem;
				min-height: 0;
			}

			.hz-nav-group {
				display: flex;
				flex-direction: column;
				gap: 3px;
			}

			.hz-nav-group-title {
				padding: 0 10px;
				font-size: 0.65rem;
				text-transform: uppercase;
				letter-spacing: 0.12em;
				font-weight: 700;
				color: #64748B;
				margin-bottom: 4px;
			}

			/* Shadcn UI Aligned Navigation Items */
			.hz-nav-item {
				display: flex;
				align-items: center;
				gap: 12px;
				padding: 9px 12px;
				border-radius: 10px;
				color: #94A3B8;
				font-size: 0.825rem;
				font-weight: 500;
				border: 1px solid transparent;
				transition: all 0.15s ease;
				text-decoration: none;
			}

			.hz-nav-item:hover {
				background: rgba(255, 255, 255, 0.06);
				color: #FFFFFF;
				border-color: rgba(255, 255, 255, 0.08);
			}

			.hz-nav-item:hover .hz-nav-lucide {
				color: #C5A059;
			}

			.hz-nav-item-active {
				background: rgba(197, 160, 89, 0.15) !important;
				color: #FFFFFF !important;
				border-color: rgba(197, 160, 89, 0.35) !important;
				box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
				font-weight: 600;
			}

			.hz-nav-item-active .hz-nav-lucide {
				color: #C5A059 !important;
			}

			.hz-nav-lucide {
				width: 17px;
				height: 17px;
				color: #94A3B8;
				flex-shrink: 0;
				stroke-width: 2;
				transition: color 0.15s ease;
			}

			.hz-nav-label {
				white-space: nowrap;
				overflow: hidden;
				text-overflow: ellipsis;
			}

			/* Main Content Area */
			.hz-main {
				flex: 1;
				display: flex;
				flex-direction: column;
				min-width: 0;
				height: 100vh;
				overflow: hidden;
			}

			/* Header */
			.hz-header {
				position: sticky;
				top: 0;
				z-index: 10;
				display: flex;
				align-items: center;
				justify-content: space-between;
				padding: 16px 32px;
				border-bottom: 1px solid rgba(226, 232, 240, 0.85);
				background: rgba(250, 248, 245, 0.88);
				backdrop-filter: blur(12px);
			}

			.hz-header-left {
				display: flex;
				align-items: center;
				gap: 12px;
			}

			.hz-mobile-toggle {
				width: 36px;
				height: 36px;
				border-radius: 10px;
				border: 1px solid #CBD5E1;
				background: #FFFFFF;
				color: #0F223D;
				display: none;
				align-items: center;
				justify-content: center;
				cursor: pointer;
				transition: all 0.15s ease;
			}

			.hz-mobile-toggle:hover {
				background: #F1F5F9;
			}

			@media (max-width: 768px) {
				.hz-header {
					padding: 14px 18px;
				}

				.hz-mobile-toggle {
					display: inline-flex;
				}
			}

			.hz-header-right {
				display: flex;
				align-items: center;
				gap: 12px;
			}

			.hz-user-chip {
				display: flex;
				align-items: center;
				gap: 10px;
				border-radius: 9999px;
				padding: 5px 14px 5px 6px;
				border: 1px solid #E2E8F0;
				background: #FFFFFF;
				box-shadow: 0 2px 6px rgba(15, 34, 61, 0.04);
				transition: all 0.15s ease;
			}

			.hz-user-chip:hover {
				border-color: rgba(197, 160, 89, 0.5);
				box-shadow: 0 4px 12px rgba(15, 34, 61, 0.06);
			}

			.hz-avatar {
				width: 28px;
				height: 28px;
				border-radius: 9999px;
				background: #0F223D;
				color: #C5A059;
				font-size: 0.75rem;
				font-weight: 700;
				display: flex;
				align-items: center;
				justify-content: center;
			}

			.hz-user-text {
				font-size: 0.8rem;
				line-height: 1.2;
			}

			.hz-user-name {
				font-weight: 600;
				color: #0F223D;
			}

			.hz-main-content {
				flex: 1;
				overflow-y: auto;
				padding: 32px;
			}

			/* Global Horizon Page Components */
			.hz-top-row {
				display: flex;
				justify-content: space-between;
				align-items: flex-start;
				margin-bottom: 24px;
				gap: 16px;
				flex-wrap: wrap;
			}

			.hz-top-title {
				font-family: 'Newsreader', Georgia, serif;
				font-size: 2.2rem;
				font-weight: 500;
				color: #0F223D;
				margin: 0 0 4px 0;
				line-height: 1.2;
				letter-spacing: -0.01em;
			}

			.hz-top-sub {
				font-size: 0.925rem;
				color: #64748B;
				margin: 0;
			}

			.hz-card {
				background: #FFFFFF;
				border-radius: 18px;
				border: 1px solid rgba(226, 232, 240, 0.95);
				padding: 22px 24px;
				box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04);
			}

			.hz-card-title {
				font-family: 'Newsreader', Georgia, serif;
				font-size: 1.25rem;
				font-weight: 600;
				color: #0F223D;
				margin: 0;
			}

			.hz-card-sub {
				font-size: 0.8rem;
				color: #64748B;
				margin: 0;
			}

			@media (max-width: 768px) {
				.hz-main-content {
					padding: 20px 16px;
				}
			}
		</style>
	</head>
	<body class="hz-body" @if(session('subscription_modal') && auth()->check() && auth()->user()->role === 'owner') data-subscription-error="true" @endif>
		<div class="hz-bg">
			<div class="hz-layout">
				@include('component.sidebar')
				<div class="hz-sidebar-backdrop" id="hz-sidebar-backdrop"></div>

				<div class="hz-main">
					@include('component.header')

					<main class="hz-main-content">
						@yield('content')
					</main>
				</div>
			</div>
		</div>

		@if(auth()->check() && auth()->user()->role === 'owner')
			@include('component.subscription_expired_modal')
		@endif
		
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				// Inisialisasi ikon Lucide (Shadcn Standard)
				if (typeof lucide !== 'undefined') {
					lucide.createIcons();
				}

				var toggle = document.getElementById('hz-sidebar-toggle');
				var sidebar = document.getElementById('hz-sidebar');
				var backdrop = document.getElementById('hz-sidebar-backdrop');
				if (!toggle || !sidebar || !backdrop) return;

				function closeSidebar() {
					sidebar.classList.remove('hz-sidebar-open');
					backdrop.classList.remove('active');
				}

				toggle.addEventListener('click', function () {
					var isOpen = sidebar.classList.toggle('hz-sidebar-open');
					backdrop.classList.toggle('active', isOpen);
				});

				backdrop.addEventListener('click', closeSidebar);

				window.addEventListener('resize', function () {
					if (window.innerWidth > 768) {
						closeSidebar();
					}
				});
			});
		</script>
	</body>
</html>
