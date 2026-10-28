<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>@yield('page_title', 'Dashboard') - {{ config('app.name', 'Golday') }}</title>

		<!-- Fonts -->
		<link rel="preconnect" href="https://fonts.bunny.net">
		<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

		<!-- Simple Horizon-like CSS without Tailwind/Vite -->
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
				font-family: 'Instrument Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
				background-color: #f8f4e8;
				color: #1e293b;
				overflow: hidden;
			}

			a {
				color: inherit;
				text-decoration: none;
			}

			.hz-bg {
				height: 100%;
				background: radial-gradient(circle at top, #3b82f6 0, #e0e7ff 55%, #f8f4e8 100%);
			}

			.hz-layout {
				display: flex;
				height: 100%;
				background: rgba(248, 244, 232, 0.95);
			}

			/* Sidebar */
			.hz-sidebar {
				width: 250px;
				padding: 20px 18px 18px;
				border-right: 1px solid rgba(59, 130, 246, 0.3);
				background: linear-gradient(180deg, #1e3a8a 0, #3b82f6 100%);
				box-shadow: 10px 0 40px rgba(59, 130, 246, 0.25);
				display: flex;
				flex-direction: column;
				gap: 18px;
				position: relative;
				height: 100vh;
				max-height: 100vh;
				overflow-y: auto;
				overflow-x: hidden;
				z-index: 15;
				transition: transform 0.2s ease;
			}

			/* Custom scrollbar for sidebar */
			.hz-sidebar::-webkit-scrollbar {
				width: 6px;
			}

			.hz-sidebar::-webkit-scrollbar-track {
				background: transparent;
			}

			.hz-sidebar::-webkit-scrollbar-thumb {
				background: rgba(255, 255, 255, 0.3);
				border-radius: 3px;
			}

			.hz-sidebar::-webkit-scrollbar-thumb:hover {
				background: rgba(255, 255, 255, 0.5);
			}

			@media (max-width: 768px) {
				.hz-sidebar {
					position: fixed;
					left: 0;
					top: 0;
					height: 100vh;
					transform: translateX(-100%);
					width: 260px;
					z-index: 16;
				}

				.hz-sidebar.hz-sidebar-open {
					transform: translateX(0);
				}

				.hz-sidebar-backdrop {
					position: fixed;
					inset: 0;
					background: rgba(30, 58, 138, 0.5);
					backdrop-filter: blur(4px);
					display: none;
					z-index: 14;
				}

				.hz-sidebar-backdrop.active {
					display: block;
				}
			}

			.hz-sidebar-header {
				display: flex;
				align-items: center;
				gap: 10px;
				padding: 0 6px;
				margin-bottom: 10px;
			}

			.hz-logo-badge {
				width: 36px;
				height: 36px;
				border-radius: 16px;
				background: linear-gradient(135deg, #60a5fa, #3b82f6, #2563eb);
				color: #ffffff;
				font-weight: 700;
				font-size: 1rem;
				display: flex;
				align-items: center;
				justify-content: center;
			}

			.hz-logo-title {
				font-size: 0.9rem;
				font-weight: 600;
				letter-spacing: 0.04em;
			}

			.hz-logo-sub {
				margin-top: 2px;
				font-size: 0.7rem;
				color: #bfdbfe;
			}

			.hz-nav {
				flex: 1;
				display: flex;
				flex-direction: column;
				gap: 18px;
				font-size: 0.8rem;
				min-height: 0;
			}

			.hz-nav-group {
				display: flex;
				flex-direction: column;
				gap: 6px;
			}

			.hz-nav-group-title {
				padding: 0 8px;
				font-size: 0.65rem;
				text-transform: uppercase;
				letter-spacing: 0.16em;
				color: #bfdbfe;
				margin-bottom: 4px;
			}

			.hz-nav-item {
				display: flex;
				align-items: center;
				gap: 10px;
				padding: 8px 10px;
				border-radius: 14px;
				color: #e0e7ff;
				transition: background 0.15s ease, color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
			}

			.hz-nav-subitem {
				padding-left: 28px;
				border-radius: 10px;
				font-size: 0.75rem;
				color: #c7d2fe;
			}

			.hz-nav-item:hover {
				background: rgba(255, 255, 255, 0.15);
				color: #ffffff;
				transform: translateY(-1px);
			}

			.hz-nav-item-active {
				background: rgba(255, 255, 255, 0.25);
				color: #ffffff;
				box-shadow: 0 22px 40px rgba(59, 130, 246, 0.4);
				border: 1px solid rgba(255, 255, 255, 0.3);
			}

			.hz-nav-icon {
				width: 28px;
				height: 28px;
				border-radius: 11px;
				background: rgba(255, 255, 255, 0.15);
				display: flex;
				align-items: center;
				justify-content: center;
				color: #ffffff;
				font-size: 0;
			}

			.hz-nav-label-main {
				font-size: 0.8rem;
				font-weight: 500;
			}

			.hz-nav-label-sub {
				margin-top: 2px;
				font-size: 0.7rem;
				color: #c7d2fe;
			}

			/* Header */
			.hz-header {
				position: sticky;
				top: 0;
				z-index: 10;
				display: flex;
				align-items: center;
				justify-content: space-between;
				padding: 16px 24px 12px;
				border-bottom: 1px solid rgba(59, 130, 246, 0.2);
				background: rgba(255, 255, 255, 0.85);
				backdrop-filter: blur(18px);
			}

			.hz-header-left {
				display: flex;
				flex-direction: column;
				gap: 2px;
			}

			.hz-header-label {
				font-size: 0.65rem;
				text-transform: uppercase;
				letter-spacing: 0.16em;
				color: #64748b;
			}

			.hz-header-title {
				font-size: 1.35rem;
				font-weight: 700;
				letter-spacing: -0.02em;
				color: #1e3a8a;
			}

			.hz-header-right {
				display: flex;
				align-items: center;
				gap: 12px;
			}

			.hz-mobile-toggle {
				width: 38px;
				height: 38px;
				border-radius: 12px;
				border: 1px solid rgba(59, 130, 246, 0.4);
				background: rgba(59, 130, 246, 0.1);
				color: #1e3a8a;
				display: none;
				align-items: center;
				justify-content: center;
				cursor: pointer;
			}

			@media (max-width: 768px) {
				.hz-header {
					padding-left: 16px;
					padding-right: 16px;
				}

				.hz-mobile-toggle {
					display: inline-flex;
				}
			}

			.hz-header-search {
				display: flex;
				align-items: center;
				padding: 4px 10px;
				border-radius: 999px;
				border: 1px solid rgba(59, 130, 246, 0.3);
				background: rgba(255, 255, 255, 0.8);
				max-width: 260px;
			}

			.hz-header-search-input {
				border: none;
				outline: none;
				margin-left: 6px;
				width: 170px;
				background: transparent;
				color: #1e3a8a;
				font-size: 0.72rem;
			}

			@media (max-width: 768px) {
				.hz-header-search {
					display: none;
				}
			}

			.hz-icon-button {
				width: 34px;
				height: 34px;
				border-radius: 999px;
				border: 1px solid rgba(59, 130, 246, 0.3);
				background: rgba(255, 255, 255, 0.8);
				color: #1e3a8a;
				display: flex;
				align-items: center;
				justify-content: center;
				position: relative;
				cursor: pointer;
			}

			.hz-notification-dot {
				position: absolute;
				width: 8px;
				height: 8px;
				border-radius: 999px;
				background: #22c55e;
				top: 4px;
				right: 4px;
				box-shadow: 0 0 0 2px #ffffff;
			}

			.hz-user-chip {
				display: flex;
				align-items: center;
				gap: 8px;
				border-radius: 999px;
				padding: 4px 10px 4px 4px;
				border: 1px solid rgba(59, 130, 246, 0.3);
				background: rgba(255, 255, 255, 0.8);
			}

			.hz-avatar {
				width: 28px;
				height: 28px;
				border-radius: 999px;
				background: linear-gradient(135deg, #60a5fa, #3b82f6, #2563eb);
			}

			.hz-user-text {
				font-size: 0.72rem;
				line-height: 1.2;
			}

			.hz-user-name {
				font-weight: 500;
				color: #1e3a8a;
			}

			.hz-user-sub {
				font-size: 0.68rem;
				color: #64748b;
			}

			.hz-main {
				flex: 1;
				display: flex;
				flex-direction: column;
				min-width: 0;
				height: 100vh;
				min-height: 0;
				overflow: hidden;
			}

			.hz-main-content {
				flex: 1;
				padding: 24px 16px 32px;
				margin: 0 auto;
				overflow-y: auto;
				min-height: 0;
				width: 100%;
				margin: 0;
			}

			@media (min-width: 1024px) {
				.hz-main-content {
					padding: 32px 32px 40px;
				}
			}

			/* Top section */
			.hz-top-row {
				display: flex;
				flex-direction: column;
				gap: 16px;
			}

			@media (min-width: 768px) {
				.hz-top-row {
					flex-direction: row;
					align-items: flex-end;
					justify-content: space-between;
				}
			}

			.hz-top-title {
				font-size: 1.25rem;
				font-weight: 600;
				letter-spacing: -0.01em;
			}

			.hz-top-sub {
				margin-top: 4px;
				font-size: 0.875rem;
				color: #64748b;
			}

			.hz-top-actions {
				display: flex;
				flex-wrap: wrap;
				align-items: center;
				gap: 12px;
			}

			.hz-btn-outline,
			.hz-btn-primary {
				display: inline-flex;
				align-items: center;
				gap: 8px;
				border-radius: 999px;
				font-size: 0.75rem;
				font-weight: 500;
				padding: 8px 14px;
				border: 1px solid transparent;
				cursor: pointer;
				background: none;
				color: inherit;
				transition: background 0.15s ease, border-color 0.15s ease, transform 0.15s ease;
			}

			.hz-btn-outline {
				border-color: rgba(59, 130, 246, 0.4);
				background: rgba(255, 255, 255, 0.8);
				color: #1e3a8a;
			}

			.hz-btn-outline:hover {
				background: rgba(255, 255, 255, 1);
			}

			.hz-btn-primary {
				background: #3b82f6;
				border-color: #3b82f6;
				color: #ffffff;
				box-shadow: 0 18px 45px rgba(59, 130, 246, 0.35);
			}

			.hz-btn-primary:hover {
				background: #2563eb;
				border-color: #2563eb;
				transform: translateY(-1px);
			}

			.hz-icon-pill {
				display: inline-flex;
				align-items: center;
				justify-content: center;
				width: 20px;
				height: 20px;
				border-radius: 999px;
			}

			/* KPI cards */
			.hz-kpi-grid {
				margin-top: 24px;
				display: grid;
				gap: 16px;
			}

			@media (min-width: 768px) {
				.hz-kpi-grid {
					grid-template-columns: repeat(2, minmax(0, 1fr));
				}
			}

			@media (min-width: 1280px) {
				.hz-kpi-grid {
					grid-template-columns: repeat(4, minmax(0, 1fr));
				}
			}

			.hz-card {
				border-radius: 18px;
				border: 1px solid rgba(59, 130, 246, 0.2);
				background: rgba(255, 255, 255, 0.9);
				padding: 18px 20px;
				box-shadow: 0 18px 40px rgba(59, 130, 246, 0.1);
			}

			.hz-card-muted {
				border-color: rgba(59, 130, 246, 0.15);
				background: rgba(255, 255, 255, 0.7);
			}

			.hz-card-primary {
				position: relative;
				overflow: hidden;
				background: linear-gradient(135deg, #3b82f6, #2563eb, #1d4ed8);
				color: #ffffff;
				border-color: rgba(59, 130, 246, 0.4);
			}

			.hz-card-title {
				font-size: 0.7rem;
				text-transform: uppercase;
				letter-spacing: 0.16em;
				color: #64748b;
			}

			.hz-card-value {
				margin-top: 8px;
				font-size: 1.6rem;
				font-weight: 600;
				color: #1e3a8a;
			}

			.hz-card-sub {
				margin-top: 4px;
				font-size: 0.75rem;
				color: #64748b;
			}

			.hz-pill-success {
				margin-top: 4px;
				display: inline-flex;
				align-items: center;
				gap: 4px;
				padding: 2px 8px;
				border-radius: 999px;
				background: rgba(22, 163, 74, 0.15);
				color: #166534;
				font-size: 0.7rem;
			}

			.hz-pill-dot {
				width: 8px;
				height: 8px;
				border-radius: 999px;
				background: #22c55e;
			}

			.hz-progress-track {
				margin-top: 10px;
				display: flex;
				height: 10px;
				border-radius: 999px;
				background: #e0e7ff;
				overflow: hidden;
			}

			.hz-progress-fill-1 { background: #3b82f6; flex: 3; }
			.hz-progress-fill-2 { background: #2563eb; flex: 1; }
			.hz-progress-fill-3 { background: #1d4ed8; flex: 1; }

			/* Conversion mini bars */
			.hz-mini-bars {
				margin-top: 14px;
				display: flex;
				align-items: flex-end;
				gap: 6px;
				height: 70px;
			}

			.hz-mini-bar-track {
				flex: 1;
				border-radius: 999px;
				background: #e0e7ff;
				overflow: hidden;
			}

			.hz-mini-bar-fill {
				width: 100%;
				border-radius: 999px;
				background: #3b82f6;
			}

			/* Timeline & list grid */
			.hz-secondary-grid {
				margin-top: 20px;
				display: grid;
				gap: 16px;
			}

			@media (min-width: 1024px) {
				.hz-secondary-grid {
					grid-template-columns: 2fr 1fr;
				}
			}

			.hz-card-chart,
			.hz-card-list {
				border-radius: 18px;
				border: 1px solid rgba(59, 130, 246, 0.2);
				background: rgba(255, 255, 255, 0.9);
				padding: 20px;
				box-shadow: 0 18px 40px rgba(59, 130, 246, 0.1);
			}

			.hz-card-header {
				display: flex;
				align-items: center;
				justify-content: space-between;
			}

			.hz-chip {
				border-radius: 999px;
				padding: 3px 10px;
				font-size: 0.65rem;
				background: rgba(22, 163, 74, 0.15);
				color: #166534;
				font-weight: 500;
			}

			.hz-chart-shell {
				margin-top: 16px;
				height: 190px;
				border-radius: 16px;
				padding: 14px;
				background: linear-gradient(to bottom, rgba(59, 130, 246, 0.15), #f8f4e8);
			}

			.hz-chart-bars {
				display: flex;
				align-items: flex-end;
				gap: 10px;
				height: 100%;
			}

			.hz-chart-col {
				flex: 1;
				text-align: center;
				font-size: 0.68rem;
				color: #64748b;
			}

			.hz-chart-bar-track {
				height: 130px;
				margin: 0 auto;
				border-radius: 999px;
				background: #e0e7ff;
				overflow: hidden;
				display: flex;
				align-items: flex-end;
			}

			.hz-chart-bar-fill {
				width: 100%;
				border-radius: 999px;
				background: linear-gradient(to top, #3b82f6, #60a5fa, #93c5fd);
			}

			.hz-chart-label {
				margin-top: 6px;
				color: #475569;
			}

			/* Customers list */
			.hz-list {
				margin-top: 16px;
				list-style: none;
				display: flex;
				flex-direction: column;
				gap: 12px;
			}

			.hz-list-item {
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 12px;
			}

			.hz-list-left {
				display: flex;
				align-items: center;
				gap: 10px;
			}

			.hz-list-avatar {
				width: 32px;
				height: 32px;
				border-radius: 999px;
				background: linear-gradient(135deg, #3b82f6, #60a5fa);
			}

			.hz-list-avatar-emerald { background: linear-gradient(135deg, #22c55e, #4ade80); }
			.hz-list-avatar-amber { background: linear-gradient(135deg, #f97316, #fb923c); }

			.hz-list-title {
				font-size: 0.85rem;
				font-weight: 500;
				color: #1e3a8a;
			}

			.hz-list-sub {
				font-size: 0.7rem;
				color: #64748b;
			}

			.hz-list-value {
				font-size: 0.75rem;
				font-weight: 600;
				color: #1e3a8a;
			}

			.hz-list-change {
				margin-top: 2px;
				font-size: 0.7rem;
				color: #16a34a;
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
