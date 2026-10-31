<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>@yield('page_title', 'Dashboard') - {{ config('app.name', 'Aksara Developer') }}</title>
		<link rel="icon" type="image/png" href="{{ asset('assets/images/aksara-logo.png') }}">
		<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

		<!-- Fonts: Inter (UI Sans) & Newsreader (Luxury Editorial Serif) -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,200..800;1,6..72,200..800&display=swap" rel="stylesheet">

		<!-- Lucide Icons (Shadcn Standard) -->
		<script src="https://unpkg.com/lucide@latest"></script>

		<style>
			@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
			@import url('https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

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
				font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
				background-color: #FAF8F5;
				color: #0F223D;
				overflow: hidden;
				-webkit-font-smoothing: antialiased;
			}

			/* Headings globally use Newsreader */
			h1, h2, h3, .hz-card-title, .hz-top-title {
				font-family: 'Newsreader', Georgia, serif;
			}

			/* Body & UI elements globally use Inter */
			p, span, a, button, input, select, textarea, table, th, td, label {
				font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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

			/* Sidebar Clean White (Aksara Luxury White Theme) */
			.hz-sidebar {
				width: 260px;
				padding: 22px 16px 20px;
				border-right: 1px solid #E2E8F0;
				background: #FFFFFF;
				box-shadow: 2px 0 16px rgba(15, 34, 61, 0.03);
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
				background: rgba(15, 34, 61, 0.12);
				border-radius: 999px;
			}

			.hz-sidebar::-webkit-scrollbar-thumb:hover {
				background: rgba(15, 34, 61, 0.22);
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
					box-shadow: 4px 0 24px rgba(15, 34, 61, 0.15);
				}

				.hz-sidebar.hz-sidebar-open {
					transform: translateX(0);
				}

				.hz-sidebar-backdrop {
					position: fixed;
					inset: 0;
					background: rgba(15, 34, 61, 0.4);
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
				border-bottom: 1px solid #F1F5F9;
			}

			.hz-logo-badge {
				width: 36px;
				height: 36px;
				display: flex;
				align-items: center;
				justify-content: center;
				flex-shrink: 0;
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
				color: #0F223D;
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
				color: #64748B;
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
				color: #94A3B8;
				margin-bottom: 4px;
			}

			/* Shadcn UI Aligned Navigation Items (Clean White Theme) */
			.hz-nav-item {
				display: flex;
				align-items: center;
				gap: 12px;
				padding: 9px 12px;
				border-radius: 10px;
				color: #475569;
				font-size: 0.825rem;
				font-weight: 500;
				border: 1px solid transparent;
				transition: all 0.15s ease;
				text-decoration: none;
			}

			.hz-nav-item:hover {
				background: #FAF8F5;
				color: #0F223D;
				border-color: rgba(197, 160, 89, 0.3);
			}

			.hz-nav-item:hover .hz-nav-lucide {
				color: #C5A059;
			}

			.hz-nav-item-active {
				background: rgba(197, 160, 89, 0.12) !important;
				color: #0F223D !important;
				border-color: rgba(197, 160, 89, 0.35) !important;
				box-shadow: 0 1px 3px rgba(197, 160, 89, 0.08);
				font-weight: 600;
			}

			.hz-nav-item-active .hz-nav-lucide {
				color: #C5A059 !important;
			}

			.hz-nav-lucide {
				width: 17px;
				height: 17px;
				color: #64748B;
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

			/* Header Pure White */
			.hz-header {
				position: sticky;
				top: 0;
				z-index: 10;
				display: flex;
				align-items: center;
				justify-content: space-between;
				padding: 16px 32px;
				border-bottom: 1px solid #E2E8F0;
				background: #FFFFFF;
				box-shadow: 0 1px 4px rgba(15, 34, 61, 0.03);
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
				background: #FAF8F5;
				box-shadow: 0 1px 3px rgba(15, 34, 61, 0.04);
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

			/* Global Horizon Form & Button Standards (Superadmin Match) */
			.hz-btn, .hz-btn-primary {
				background: #0F223D !important;
				color: #FFFFFF !important;
				border: none !important;
				border-radius: 10px !important;
				padding: 9px 18px !important;
				font-weight: 600 !important;
				font-size: 0.85rem !important;
				display: inline-flex !important;
				align-items: center !important;
				gap: 6px !important;
				text-decoration: none !important;
				box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15) !important;
				cursor: pointer !important;
				transition: all 0.15s ease !important;
			}

			.hz-btn:hover, .hz-btn-primary:hover {
				background: #1B355A !important;
				color: #FFFFFF !important;
				transform: translateY(-1px);
			}

			.hz-btn-secondary {
				background: #FFFFFF !important;
				color: #475569 !important;
				border: 1px solid #CBD5E1 !important;
				box-shadow: none !important;
			}

			.hz-btn-secondary:hover {
				background: #F8FAFC !important;
				color: #0F223D !important;
			}

			/* Global Form Controls in Top Actions */
			.hz-top-actions input,
			.hz-top-actions select,
			.filter-form input,
			.filter-form select {
				border-radius: 10px !important;
				border: 1px solid #CBD5E1 !important;
				background: #FFFFFF !important;
				color: #0F223D !important;
				font-size: 0.85rem !important;
				padding: 8px 12px !important;
				outline: none !important;
				box-shadow: 0 1px 2px rgba(15, 34, 61, 0.04) !important;
				font-family: inherit !important;
			}

			.search-input-wrap,
			.hz-search-wrap {
				position: relative !important;
				display: inline-flex !important;
				align-items: center !important;
			}

			.search-input-wrap i,
			.search-input-wrap svg,
			.hz-search-wrap i,
			.hz-search-wrap svg {
				position: absolute !important;
				left: 12px !important;
				top: 50% !important;
				transform: translateY(-50%) !important;
				width: 15px !important;
				height: 15px !important;
				color: #94A3B8 !important;
				pointer-events: none !important;
				z-index: 5 !important;
			}

			.search-input-wrap input,
			.hz-search-wrap input,
			.hz-top-actions input.search-input,
			.hz-top-actions input[name="search"],
			.filter-form input.search-input,
			.filter-form input[name="search"] {
				padding-left: 38px !important;
			}

			.hz-top-actions input:focus,
			.hz-top-actions select:focus {
				border-color: #0F223D !important;
				box-shadow: 0 0 0 3px rgba(15, 34, 61, 0.08) !important;
			}

			.hz-top-actions button[type="submit"] {
				border-radius: 10px !important;
				border: none !important;
				background: #0F223D !important;
				color: #FFFFFF !important;
				font-size: 0.85rem !important;
				font-weight: 600 !important;
				padding: 8px 16px !important;
				cursor: pointer !important;
				box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15) !important;
			}

			/* Global Tables inside hz-card */
			.hz-card table {
				width: 100% !important;
				border-collapse: separate !important;
				border-spacing: 0 !important;
			}

			.hz-card table thead tr th {
				background: #F8FAFC !important;
				color: #64748B !important;
				font-size: 0.75rem !important;
				font-weight: 700 !important;
				text-transform: uppercase !important;
				letter-spacing: 0.05em !important;
				border-bottom: 1px solid #E2E8F0 !important;
				border-top: none !important;
				padding: 12px 14px !important;
			}

			.hz-card table thead tr {
				border-bottom: none !important;
			}

			.hz-card table tbody tr {
				border-bottom: 1px solid #F1F5F9 !important;
				transition: background 0.15s ease !important;
			}

			.hz-card table tbody tr:hover td {
				background: #FAF8F5 !important;
			}

			.hz-card table tbody tr td {
				padding: 14px 12px !important;
				border-bottom: 1px solid #F1F5F9 !important;
				color: #334155 !important;
				font-size: 0.875rem !important;
				vertical-align: middle !important;
			}

			/* Code & Text Colors in tables */
			.hz-card table td[style*="color: #3b82f6"],
			.hz-card table td[style*="color:#3b82f6"] {
				color: #0F223D !important;
				font-weight: 600 !important;
			}

			.hz-card table td[style*="color: #cbd5e1"],
			.hz-card table td[style*="color:#cbd5e1"] {
				color: #64748B !important;
			}

			/* Dropdown actions */
			.hz-dropdown, .actions-dropdown {
				background: #FFFFFF !important;
				border: 1px solid #E2E8F0 !important;
				border-radius: 12px !important;
				box-shadow: 0 10px 25px -5px rgba(15, 34, 61, 0.12), 0 8px 10px -6px rgba(15, 34, 61, 0.08) !important;
				overflow: hidden !important;
			}

			.hz-dropdown a, .hz-dropdown button,
			.actions-dropdown a, .actions-dropdown button {
				color: #0F223D !important;
				font-size: 0.85rem !important;
				padding: 9px 14px !important;
				transition: background 0.15s ease !important;
			}

			.hz-dropdown a:hover, .hz-dropdown button:hover,
			.actions-dropdown a:hover, .actions-dropdown button:hover {
				background: #FAF8F5 !important;
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

		@include('component.employee_access_denied_modal')
		
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
