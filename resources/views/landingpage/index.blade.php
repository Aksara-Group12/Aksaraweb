<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Aksara Developer - Sistem kasir, manajemen stok, data pelanggan, dan keuangan terpadu untuk bisnis dan UMKM Indonesia.">
	<title>Aksara Developer - Sistem Kasir, Stok, Data Pelanggan & Keuangan UMKM</title>
	<link rel="icon" type="image/png" href="{{ asset('assets/images/aksara-logo.png') }}">
	<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
	
	<!-- Tailwind CSS -->
	<script src="https://cdn.tailwindcss.com"></script>
	
	<!-- Lucide Icons (Shadcn Standard) -->
	<script src="https://unpkg.com/lucide@latest"></script>

	<!-- Fonts: Newsreader (Editorial Serif) & Plus Jakarta Sans (Modern Clean Sans) -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;0,6..72,700;1,6..72,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

	<style>
		/* Global Base Font: Plus Jakarta Sans */
		body, button, input, p, span, a, nav, div, li {
			font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
		}

		body {
			-webkit-font-smoothing: antialiased;
			-moz-osx-font-smoothing: grayscale;
			background-color: #FAF8F5;
			color: #0F223D;
		}

		/* All Headlines & Titles: Newsreader */
		h1, h2, h3, h4, .font-serif-title, .title-serif {
			font-family: 'Newsreader', Georgia, serif !important;
			letter-spacing: -0.02em;
		}

		/* Eyebrow Tagline */
		.font-tagline {
			font-family: 'Plus Jakarta Sans', sans-serif;
			letter-spacing: 0.16em;
		}

		/* Shadcn-like subtle borders and cards */
		.ui-card {
			background: #FFFFFF;
			border: 1px solid rgba(226, 232, 240, 0.9);
			box-shadow: 0 1px 3px 0 rgba(15, 34, 61, 0.04), 0 1px 2px -1px rgba(15, 34, 61, 0.04);
			transition: all 0.2s ease-in-out;
		}
		.ui-card:hover {
			border-color: rgba(197, 160, 89, 0.45);
			box-shadow: 0 10px 25px -5px rgba(15, 34, 61, 0.07), 0 8px 10px -6px rgba(15, 34, 61, 0.04);
		}

		.bg-pattern {
			background-image: radial-gradient(rgba(15, 34, 61, 0.05) 1px, transparent 1px);
			background-size: 24px 24px;
		}

		/* Native Scroll Reveal Animations (Tailwind Transitions + IntersectionObserver) */
		.reveal-on-scroll {
			opacity: 0;
			transform: translateY(28px);
			transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
			will-change: opacity, transform;
		}

		.reveal-on-scroll.is-revealed {
			opacity: 1;
			transform: translateY(0);
		}

		.reveal-scale {
			opacity: 0;
			transform: scale(0.96) translateY(20px);
			transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
			will-change: opacity, transform;
		}

		.reveal-scale.is-revealed {
			opacity: 1;
			transform: scale(1) translateY(0);
		}

		/* Staggered Delay Utilities */
		.delay-75  { transition-delay: 75ms; }
		.delay-150 { transition-delay: 150ms; }
		.delay-200 { transition-delay: 200ms; }
		.delay-250 { transition-delay: 250ms; }
		.delay-300 { transition-delay: 300ms; }
		.delay-350 { transition-delay: 350ms; }
		.delay-400 { transition-delay: 400ms; }
		.delay-500 { transition-delay: 500ms; }
	</style>

	<script>
		tailwind.config = {
			theme: {
				extend: {
					fontFamily: {
						sans: ['"Plus Jakarta Sans"', 'sans-serif'],
						serif: ['"Newsreader"', 'Georgia', 'serif'],
					},
					colors: {
						brand: {
							navy: '#0F223D',
							royal: '#162C50',
							accent: '#1E3A68',
							gold: '#C5A059',
							goldLight: '#E8CA85',
							goldDark: '#9E7E36',
							cream: '#FAF8F5',
							card: '#FFFFFF',
							muted: '#64748B',
							border: '#E2E8F0',
						}
					}
				}
			}
		}
	</script>
</head>
<body class="bg-[#FAF8F5] text-brand-navy antialiased selection:bg-brand-gold/20 selection:text-brand-navy">

	<!-- ==================== 1. TOP NAVBAR (Aksara Developer Clean Header) ==================== -->
	<header class="sticky top-0 z-50 w-full border-b border-brand-border bg-[#FAF8F5]/90 backdrop-blur-md">
		<div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
			<!-- Brand Logo -->
			<a href="{{ url('/') }}" class="flex items-center gap-3.5 group">
				<img src="{{ asset('assets/images/aksara-logo.png') }}" alt="Aksara Logo" class="h-10 w-auto object-contain transition-transform group-hover:scale-105">
				<div class="pl-3 border-l border-brand-border">
					<div class="text-brand-navy font-bold text-base leading-tight">
						Aksara <span class="text-brand-gold">Developer</span>
					</div>
					<div class="text-[11px] text-brand-muted font-medium tracking-wide">
						Sistem Kasir & Bisnis UMKM
					</div>
				</div>
			</a>

			<!-- Nav Navigation (Desktop) - 100% Bahasa Indonesia -->
			<nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-700">
				<a href="#fitur" class="hover:text-brand-gold transition-colors">Fitur Kasir</a>
				<a href="#solusi" class="hover:text-brand-gold transition-colors">Solusi Bisnis</a>
				<a href="#alur" class="hover:text-brand-gold transition-colors">Alur Kerja</a>
				<a href="#harga" class="hover:text-brand-gold transition-colors">Paket Harga</a>
				<a href="#faq" class="hover:text-brand-gold transition-colors">Tanya Jawab</a>
			</nav>

			<!-- Action Buttons (Shadcn style) -->
			<div class="flex items-center gap-3">
				{{-- <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg text-sm font-semibold text-brand-navy hover:bg-slate-200/60 h-10 px-4 transition-colors">
					Masuk
				</a> --}}
				<a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-lg text-sm font-semibold text-white bg-brand-navy hover:bg-brand-royal border border-brand-navy/30 h-10 px-5 shadow-sm transition-all hover:shadow">
					Coba Gratis
				</a>
			</div>
		</div>
	</header>

	<!-- ==================== 2. HOME / HERO SECTION (Full Viewport Height & Editorial Typography) ==================== -->
	<section class="relative min-h-[calc(100vh-5rem)] flex flex-col justify-center items-center px-6 lg:px-8 bg-pattern overflow-hidden text-center py-10 sm:py-16">
		<div class="max-w-4xl mx-auto space-y-7 my-auto">
			
			<!-- Eyebrow Tagline (Center) -->
			<div class="reveal-on-scroll">
				<div class="inline-flex items-center gap-2 rounded-full border border-brand-gold/30 bg-white px-4 py-1.5 text-xs font-semibold text-brand-navy shadow-xs">
					<span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
					<span class="font-tagline uppercase text-[11px] text-brand-goldDark font-bold">Aksara Sistem Kasir & Bisnis</span>
				</div>
			</div>

			<!-- Big Centered Editorial Headline (Newsreader Serif 3 Baris Pas) -->
			<h1 class="reveal-on-scroll delay-100 font-serif-title text-4xl sm:text-6xl lg:text-[4.75rem] font-normal leading-[1.1] tracking-tight text-brand-navy max-w-4xl mx-auto">
				Sistem Kasir Terpadu<br class="hidden sm:inline">
				untuk Semua yang<br class="hidden sm:inline">
				Bisnismu Butuhkan.
			</h1>

			<!-- Subheadline (Center) -->
			<p class="reveal-on-scroll delay-150 text-base sm:text-lg text-brand-muted max-w-2xl mx-auto leading-relaxed font-normal">
				Aksara Developer siap membantu mendigitalkan operasional tokomu menjadi sistem kasir cepat, inventori akurat, database pelanggan, dan laporan laba rugi otomatis.
			</p>

			<!-- Centered CTA Buttons -->
			<div class="reveal-on-scroll delay-200 pt-2 flex flex-col sm:flex-row items-center justify-center gap-4">
				<a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-full bg-brand-navy hover:bg-brand-royal text-white text-sm font-semibold px-8 py-3.5 shadow-sm transition-all hover:scale-[1.02]">
					Mulai Coba Gratis 7 Hari
					<i data-lucide="arrow-right" class="w-4 h-4"></i>
				</a>
				<a href="https://wa.me/628131355613?text=Halo%20Aksara%20Developer%2C%20saya%20tertarik%20dengan%20sistem%20kasir%20dan%20CRM%20UMKM" target="_blank" rel="noopener" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-full bg-white border border-slate-300 hover:border-brand-gold/50 hover:bg-slate-50 text-brand-navy text-sm font-semibold px-8 py-3.5 shadow-sm transition-all">
					<i data-lucide="message-circle" class="w-4 h-4 text-emerald-600"></i>
					Chat Aja Dulu (+62 813-1355-613)
				</a>
			</div>
		</div>
	</section>

	<!-- ==================== 3. SOCIAL PROOF / METRICS BAR ==================== -->
	<section class="border-y border-brand-border bg-white py-10 px-6">
		<div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
			<div class="reveal-on-scroll delay-75">
				<div class="text-3xl font-extrabold text-brand-navy tracking-tight">3x</div>
				<div class="text-xs sm:text-sm font-medium text-brand-muted mt-1">Transaksi Lebih Cepat</div>
			</div>
			<div class="reveal-on-scroll delay-150">
				<div class="text-3xl font-extrabold text-brand-navy tracking-tight">100%</div>
				<div class="text-xs sm:text-sm font-medium text-brand-muted mt-1">Transparan & Waktu Nyata</div>
			</div>
			<div class="reveal-on-scroll delay-200">
				<div class="text-3xl font-extrabold text-brand-navy tracking-tight">0%</div>
				<div class="text-xs sm:text-sm font-medium text-brand-muted mt-1">Selisih Stok Opname</div>
			</div>
			<div class="reveal-on-scroll delay-300">
				<div class="text-3xl font-extrabold text-brand-navy tracking-tight">24/7</div>
				<div class="text-xs sm:text-sm font-medium text-brand-muted mt-1">Dukungan Pendampingan</div>
			</div>
		</div>
	</section>

	<!-- ==================== 4. FITUR UTAMA (Shadcn UI Tabs Architecture) ==================== -->
	<section id="fitur" class="py-20 px-6 lg:px-8 max-w-7xl mx-auto">
		<div class="text-center max-w-2xl mx-auto mb-12 reveal-on-scroll">
			<span class="text-xs font-bold uppercase tracking-wider text-brand-gold">Modul Terpadu</span>
			<h2 class="text-3xl sm:text-4xl font-normal text-brand-navy tracking-tight mt-2">
				Kelola Semua Operasional dalam Satu Sistem
			</h2>
			<p class="text-sm sm:text-base text-brand-muted mt-3">
				Kombinasi fitur kasir, inventori barang, hubungan pelanggan, dan pembukuan keuangan yang menyatu tanpa perlu banyak aplikasi terpisah.
			</p>
		</div>

		<!-- Shadcn Tab Header Switcher -->
		<div class="flex justify-center mb-10 reveal-on-scroll delay-100">
			<div class="inline-flex p-1 bg-slate-200/70 rounded-xl max-w-full overflow-x-auto gap-1">
				<button onclick="switchTab('tab-pos', this)" class="tab-trigger inline-flex items-center gap-2 rounded-lg px-4 py-2 text-xs sm:text-sm font-semibold transition-all bg-white text-brand-navy shadow-sm">
					<i data-lucide="receipt" class="w-4 h-4"></i>
					Kasir Penjualan
				</button>
				<button onclick="switchTab('tab-stok', this)" class="tab-trigger inline-flex items-center gap-2 rounded-lg px-4 py-2 text-xs sm:text-sm font-semibold transition-all text-slate-600 hover:text-brand-navy">
					<i data-lucide="boxes" class="w-4 h-4"></i>
					Stok & Opname
				</button>
				<button onclick="switchTab('tab-crm', this)" class="tab-trigger inline-flex items-center gap-2 rounded-lg px-4 py-2 text-xs sm:text-sm font-semibold transition-all text-slate-600 hover:text-brand-navy">
					<i data-lucide="users" class="w-4 h-4"></i>
					Data Pelanggan
				</button>
				<button onclick="switchTab('tab-keuangan', this)" class="tab-trigger inline-flex items-center gap-2 rounded-lg px-4 py-2 text-xs sm:text-sm font-semibold transition-all text-slate-600 hover:text-brand-navy">
					<i data-lucide="bar-chart-3" class="w-4 h-4"></i>
					Laporan Keuangan
				</button>
			</div>
		</div>

		<!-- Tab Content 1: POS -->
		<div id="tab-pos" class="tab-content block reveal-scale delay-150">
			<div class="ui-card rounded-2xl p-8 lg:p-12 grid lg:grid-cols-2 gap-10 items-center">
				<div class="space-y-5">
					<div class="w-10 h-10 rounded-lg bg-brand-navy/5 flex items-center justify-center text-brand-navy">
						<i data-lucide="receipt" class="w-5 h-5 text-brand-gold"></i>
					</div>
					<h3 class="text-2xl sm:text-3xl font-normal text-brand-navy">
						Transaksi Kasir Responsif & Cetak Struk Instan
					</h3>
					<p class="text-sm text-brand-muted leading-relaxed">
						Proses antrean pembeli dengan tampilan kasir yang bersih. Mendukung pemindaian barcode, beragam metode pembayaran, cetak ke printer struk USB/Bluetooth, hingga pembagian sesi kasir.
					</p>
					<div class="space-y-3 pt-2">
						<div class="flex items-start gap-3">
							<i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5"></i>
							<span class="text-sm text-slate-700">Pembayaran Fleksibel: Tunai, Transfer Bank, QRIS, & Kasbon Piutang.</span>
						</div>
						<div class="flex items-start gap-3">
							<i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5"></i>
							<span class="text-sm text-slate-700">Manajemen Sesi Kasir dengan rekapitulasi uang di laci kas otomatis.</span>
						</div>
						<div class="flex items-start gap-3">
							<i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5"></i>
							<span class="text-sm text-slate-700">Fitur Pembatalan & Koreksi transaksi terlindungi izin khusus Pemilik Toko.</span>
						</div>
					</div>
				</div>
				<div class="bg-[#FAF8F5] p-6 rounded-xl border border-slate-200 space-y-3">
					<div class="flex items-center justify-between pb-3 border-b border-slate-200 text-xs font-semibold text-slate-600">
						<span>Ringkasan Nota Penjualan</span>
						<span>#TRX-88210</span>
					</div>
					<div class="space-y-2 text-xs">
						<div class="flex justify-between p-2.5 bg-white rounded-lg border border-slate-200/60">
							<span>Kopi Susu Gula Aren (2x)</span>
							<span class="font-bold">Rp 36.000</span>
						</div>
						<div class="flex justify-between p-2.5 bg-white rounded-lg border border-slate-200/60">
							<span>Croissant Mentega (1x)</span>
							<span class="font-bold">Rp 24.000</span>
						</div>
						<div class="flex justify-between p-3 bg-brand-navy text-white rounded-lg font-bold">
							<span>Total Tagihan</span>
							<span class="text-brand-goldLight">Rp 60.000</span>
						</div>
					</div>
					<p class="text-center text-[11px] text-slate-400">Nota siap dicetak langsung via printer struk</p>
				</div>
			</div>
		</div>

		<!-- Tab Content 2: STOK -->
		<div id="tab-stok" class="tab-content hidden">
			<div class="ui-card rounded-2xl p-8 lg:p-12 grid lg:grid-cols-2 gap-10 items-center">
				<div class="space-y-5">
					<div class="w-10 h-10 rounded-lg bg-brand-navy/5 flex items-center justify-center text-brand-navy">
						<i data-lucide="boxes" class="w-5 h-5 text-brand-gold"></i>
					</div>
					<h3 class="text-2xl sm:text-3xl font-normal text-brand-navy">
						Kontrol Stok Waktu Nyata & Penyesuaian Fisik Presisi
					</h3>
					<p class="text-sm text-brand-muted leading-relaxed">
						Stok berkurang otomatis saat terjadi penjualan dan bertambah saat pasokan baru masuk. Dapatkan notifikasi peringatan saat stok menipis agar tidak kehilangan potensi penjualan.
					</p>
					<div class="space-y-3 pt-2">
						<div class="flex items-start gap-3">
							<i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5"></i>
							<span class="text-sm text-slate-700">Peringatan Stok Minimum otomatis saat persediaan barang mulai habis.</span>
						</div>
						<div class="flex items-start gap-3">
							<i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5"></i>
							<span class="text-sm text-slate-700">Fitur Penyesuaian Stok untuk mencocokkan selisih stok fisik vs sistem.</span>
						</div>
						<div class="flex items-start gap-3">
							<i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5"></i>
							<span class="text-sm text-slate-700">Dukungan pelacakan Nomor Seri unik untuk tiap barang bernilai tinggi.</span>
						</div>
					</div>
				</div>
				<div class="bg-[#FAF8F5] p-6 rounded-xl border border-slate-200 space-y-3">
					<div class="p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-center justify-between text-xs">
						<div class="flex items-center gap-2">
							<i data-lucide="alert-triangle" class="w-4 h-4 text-amber-600"></i>
							<span class="font-semibold text-amber-900">Perhatian: Sisa Stok Menipis</span>
						</div>
						<span class="font-bold text-amber-700">Sisa 3 Buah</span>
					</div>
					<div class="bg-white p-3.5 rounded-lg border border-slate-200/80 space-y-2 text-xs">
						<div class="flex justify-between text-slate-600">
							<span>Barang Masuk (Pemasok Utama)</span>
							<span class="font-semibold text-emerald-600">+50 Buah</span>
						</div>
						<div class="flex justify-between text-slate-600">
							<span>Penjualan Kasir Hari Ini</span>
							<span class="font-semibold text-rose-600">-12 Buah</span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Tab Content 3: CRM -->
		<div id="tab-crm" class="tab-content hidden">
			<div class="ui-card rounded-2xl p-8 lg:p-12 grid lg:grid-cols-2 gap-10 items-center">
				<div class="space-y-5">
					<div class="w-10 h-10 rounded-lg bg-brand-navy/5 flex items-center justify-center text-brand-navy">
						<i data-lucide="users" class="w-5 h-5 text-brand-gold"></i>
					</div>
					<h3 class="text-2xl sm:text-3xl font-normal text-brand-navy">
						Database Pelanggan Terpusat & Kontrol Piutang
					</h3>
					<p class="text-sm text-brand-muted leading-relaxed">
						Catat kontak pelanggan setia, pantau produk yang paling disukai pembeli, dan lacak tanggal jatuh tempo piutang/kasbon secara teratur tanpa resiko terlupakan.
					</p>
					<div class="space-y-3 pt-2">
						<div class="flex items-start gap-3">
							<i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5"></i>
							<span class="text-sm text-slate-700">Database kontak pelanggan lengkap dengan riwayat total transaksi.</span>
						</div>
						<div class="flex items-start gap-3">
							<i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5"></i>
							<span class="text-sm text-slate-700">Pencatatan nota tagihan dan pengingat tanggal jatuh tempo kasbon.</span>
						</div>
						<div class="flex items-start gap-3">
							<i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5"></i>
							<span class="text-sm text-slate-700">Manajemen data pemasok untuk kelancaran rantai pasokan toko.</span>
						</div>
					</div>
				</div>
				<div class="bg-[#FAF8F5] p-6 rounded-xl border border-slate-200 space-y-3 text-xs">
					<div class="p-3.5 bg-white rounded-lg border border-slate-200/80 flex items-center justify-between">
						<div>
							<p class="font-bold text-slate-800">Toko Surya Gemilang (Langganan)</p>
							<p class="text-[11px] text-slate-500">Total Pembelian: Rp 24.500.000</p>
						</div>
						<span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-100 text-emerald-800">Lunas</span>
					</div>
					<div class="p-3.5 bg-white rounded-lg border border-slate-200/80 flex items-center justify-between">
						<div>
							<p class="font-bold text-slate-800">Bpk. Ridwan (Tempo 14 Hari)</p>
							<p class="text-[11px] text-amber-700">Jatuh Tempo: 2 Hari Lagi</p>
						</div>
						<span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-100 text-amber-800">Rp 1.800.000</span>
					</div>
				</div>
			</div>
		</div>

		<!-- Tab Content 4: KEUANGAN -->
		<div id="tab-keuangan" class="tab-content hidden">
			<div class="ui-card rounded-2xl p-8 lg:p-12 grid lg:grid-cols-2 gap-10 items-center">
				<div class="space-y-5">
					<div class="w-10 h-10 rounded-lg bg-brand-navy/5 flex items-center justify-center text-brand-navy">
						<i data-lucide="bar-chart-3" class="w-5 h-5 text-brand-gold"></i>
					</div>
					<h3 class="text-2xl sm:text-3xl font-normal text-brand-navy">
						Laporan Laba Rugi Otomatis Tanpa Jurnal Manual
					</h3>
					<p class="text-sm text-brand-muted leading-relaxed">
						Setiap transaksi langsung terhubung ke pencatatan finansial. Hitung laba kotor, beban operasional, dan laba bersih secara otomatis tanpa perlu repot menghitung manual.
					</p>
					<div class="space-y-3 pt-2">
						<div class="flex items-start gap-3">
							<i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5"></i>
							<span class="text-sm text-slate-700">Laba Bersih dihitung otomatis dari Pendapatan dikurangi HPP & Beban.</span>
						</div>
						<div class="flex items-start gap-3">
							<i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5"></i>
							<span class="text-sm text-slate-700">Pencatatan pengeluaran operasional (sewa ruko, listrik, gaji staf).</span>
						</div>
						<div class="flex items-start gap-3">
							<i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5"></i>
							<span class="text-sm text-slate-700">Buku kas dan arus kas yang siap diunduh ke Excel atau PDF kapan saja.</span>
						</div>
					</div>
				</div>
				<div class="bg-[#FAF8F5] p-6 rounded-xl border border-slate-200 space-y-3">
					<div class="bg-brand-navy text-white p-4 rounded-lg">
						<p class="text-[11px] text-brand-goldLight">Perkiraan Laba Bersih Bulan Ini</p>
						<p class="text-xl font-bold mt-0.5">Rp 28.450.000</p>
					</div>
					<div class="grid grid-cols-2 gap-2 text-xs">
						<div class="p-3 bg-white rounded-lg border border-slate-200/80">
							<p class="text-slate-500">Pendapatan Kotor</p>
							<p class="font-bold text-emerald-600 mt-0.5">Rp 82.500.000</p>
						</div>
						<div class="p-3 bg-white rounded-lg border border-slate-200/80">
							<p class="text-slate-500">Beban Operasional</p>
							<p class="font-bold text-rose-600 mt-0.5">Rp 14.200.000</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- ==================== 5. SOLUSI INDUSTRI (Clean Cards) ==================== -->
	<section id="solusi" class="py-20 px-6 lg:px-8 border-y border-brand-border bg-white">
		<div class="max-w-7xl mx-auto">
			<div class="text-center max-w-2xl mx-auto mb-14 reveal-on-scroll">
				<span class="text-xs font-bold uppercase tracking-wider text-brand-gold">Fleksibilitas Sistem</span>
				<h2 class="text-3xl sm:text-4xl font-normal text-brand-navy tracking-tight mt-2">
					Dirancang untuk Berbagai Macam Sektor Usaha
				</h2>
				<p class="text-sm sm:text-base text-brand-muted mt-3">
					Mudah disesuaikan dengan alur transaksi dan karakteristik bisnis toko Anda.
				</p>
			</div>

			<div class="grid md:grid-cols-3 gap-8">
				<!-- Sektor 1: Retail -->
				<div class="ui-card rounded-2xl p-7 space-y-4 reveal-on-scroll delay-100">
					<div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-brand-navy">
						<i data-lucide="store" class="w-6 h-6 text-brand-gold"></i>
					</div>
					<h3 class="text-xl font-normal text-brand-navy">Toko Retail & Kelontong</h3>
					<p class="text-sm text-brand-muted leading-relaxed">
						Minimarket, toko pakaian, aksesoris, apotek, dan kebutuhan harian. Cepat memindai barcode ribuan produk dan mengontrol stok varian barang dengan efisien.
					</p>
					<div class="text-xs font-semibold text-brand-navy flex items-center gap-1 pt-2">
						<span>Cepat Scan & Banyak Varian</span>
						<i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
					</div>
				</div>

				<!-- Sektor 2: F&B -->
				<div class="ui-card rounded-2xl p-7 space-y-4 reveal-on-scroll delay-200">
					<div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-brand-navy">
						<i data-lucide="utensils" class="w-6 h-6 text-brand-gold"></i>
					</div>
					<h3 class="text-xl font-normal text-brand-navy">Kuliner, Kafe & Restoran</h3>
					<p class="text-sm text-brand-muted leading-relaxed">
						Kedai kopi, kafe, warung makan, toko roti, hingga usaha katering. Kirim pesanan terpisah ke dapur/bar dan dukung kasir cepat di jam sibuk tanpa antrean menumpuk.
					</p>
					<div class="text-xs font-semibold text-brand-navy flex items-center gap-1 pt-2">
						<span>Bebas Antre & Cetak Cepat</span>
						<i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
					</div>
				</div>

				<!-- Sektor 3: Jasa -->
				<div class="ui-card rounded-2xl p-7 space-y-4 reveal-on-scroll delay-300">
					<div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-brand-navy">
						<i data-lucide="wrench" class="w-6 h-6 text-brand-gold"></i>
					</div>
					<h3 class="text-xl font-normal text-brand-navy">Usaha Jasa & Servis</h3>
					<p class="text-sm text-brand-muted leading-relaxed">
						Salon kecantikan, laundry kiloan, bengkel kendaraan, barbershop, dan klinik. Pencatatan nama pelanggan, tipe pengerjaan jasa, serta tanda terima servis yang transparan.
					</p>
					<div class="text-xs font-semibold text-brand-navy flex items-center gap-1 pt-2">
						<span>Pencatatan Servis Rapi</span>
						<i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- ==================== 6. ALUR KERJA (3 Poin Alur Harian Kasir) ==================== -->
	<section id="alur" class="py-20 px-6 lg:px-8 max-w-6xl mx-auto">
		<div class="text-center mb-14 reveal-on-scroll">
			<div class="inline-flex items-center gap-2 rounded-full border border-brand-navy/20 bg-brand-navy text-white px-5 py-1.5 text-sm font-bold shadow-sm mb-3">
				Alur Kerja Harian
			</div>
			<h2 class="text-3xl sm:text-4xl font-normal text-brand-navy tracking-tight">
				Alur Praktis Operasional Bisnismu
			</h2>
			<p class="text-sm sm:text-base text-brand-muted mt-2">
				Satu alur terintegrasi mulai dari pencatatan kasir, update inventori, hingga laporan keuangan.
			</p>
		</div>

		<!-- 3 Step Process Grid (Sesuai 3 Poin Awal) -->
		<div class="grid md:grid-cols-3 gap-6">
			<!-- Poin 1 -->
			<div class="ui-card rounded-2xl p-7 bg-white space-y-4 reveal-on-scroll delay-100">
				<div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-brand-navy">
					<i data-lucide="receipt" class="w-6 h-6 text-brand-gold"></i>
				</div>
				<span class="text-[11px] font-bold text-brand-gold uppercase tracking-wider">Langkah 1</span>
				<h3 class="text-xl font-normal text-brand-navy">
					Buka Shift & Transaksi Penjualan
				</h3>
				<p class="text-sm text-brand-muted leading-relaxed">
					Buka shift kasir dan catat transaksi penjualan (cash/tempo).
				</p>
			</div>

			<!-- Poin 2 -->
			<div class="ui-card rounded-2xl p-7 bg-white space-y-4 reveal-on-scroll delay-200">
				<div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-brand-navy">
					<i data-lucide="boxes" class="w-6 h-6 text-brand-gold"></i>
				</div>
				<span class="text-[11px] font-bold text-brand-gold uppercase tracking-wider">Langkah 2</span>
				<h3 class="text-xl font-normal text-brand-navy">
					Sinkronisasi Stok & Piutang
				</h3>
				<p class="text-sm text-brand-muted leading-relaxed">
					Stok otomatis berkurang/bertambah dan piutang tercatat jika tempo.
				</p>
			</div>

			<!-- Poin 3 -->
			<div class="ui-card rounded-2xl p-7 bg-white space-y-4 reveal-on-scroll delay-300">
				<div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-brand-navy">
					<i data-lucide="trending-up" class="w-6 h-6 text-brand-gold"></i>
				</div>
				<span class="text-[11px] font-bold text-brand-gold uppercase tracking-wider">Langkah 3</span>
				<h3 class="text-xl font-normal text-brand-navy">
					Laporan Laba Rugi Otomatis
				</h3>
				<p class="text-sm text-brand-muted leading-relaxed">
					Laporan laba rugi langsung terbentuk dengan HPP dan margin.
				</p>
			</div>
		</div>

		<!-- Clean Callout Banner -->
		<div class="mt-10 rounded-2xl bg-white border border-brand-border p-8 text-center space-y-3 shadow-sm reveal-scale delay-200">
			<h3 class="text-2xl font-normal text-brand-navy">Konsultasikan Kebutuhan Sistem Tokomu Sekarang</h3>
			<p class="text-sm text-brand-muted">
				Hubungi kami langsung via WhatsApp
			</p>
			<div class="pt-2">
				<a href="https://wa.me/628131355613?text=Halo%20Aksara%20Developer%2C%20saya%20mau%20konsultasi%20paket%20sistem" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg bg-brand-navy hover:bg-brand-royal text-white text-sm font-semibold px-6 py-2.5 transition">
					<i data-lucide="message-circle" class="w-4 h-4 text-emerald-400"></i>
					Chat Aja Dulu
				</a>
			</div>
		</div>
	</section>

	<!-- ==================== 7. PAKET HARGA (Dinamis dari Database) ==================== -->
	<section id="harga" class="py-20 px-6 lg:px-8 border-t border-brand-border bg-white">
		<div class="max-w-6xl mx-auto">
			<div class="text-center max-w-2xl mx-auto mb-14 reveal-on-scroll">
				<span class="text-xs font-bold uppercase tracking-wider text-brand-gold">Pilihan Berlangganan</span>
				<h2 class="text-3xl sm:text-4xl font-normal text-brand-navy tracking-tight mt-2">
					Investasi Jelas & Terjangkau
				</h2>
				<p class="text-sm sm:text-base text-brand-muted mt-2">
					Mulai tanpa beban. Pilih paket yang sesuai dengan kapasitas operasional tokomu.
				</p>
			</div>

			<!-- Dynamic Packages from DB -->
			<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
				@forelse($packages as $paket)
					<div class="ui-card rounded-2xl p-7 bg-white flex flex-col justify-between relative reveal-on-scroll delay-{{ ($loop->index % 3 + 1) * 100 }} @if($loop->first) border-brand-gold shadow-md @endif">
						@if($loop->first)
							<div class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-brand-navy text-brand-goldLight px-3 py-0.5 text-[10px] font-bold uppercase tracking-wider border border-brand-gold/40">
								Paket Pilihan
							</div>
						@endif

						<div>
							<h3 class="text-2xl font-normal text-brand-navy">{{ $paket->nama_paket }}</h3>
							<p class="text-xs text-brand-muted mt-1 leading-relaxed">{{ $paket->deskripsi ?? 'Akses lengkap ke seluruh modul kasir dan operasional UMKM.' }}</p>

							<!-- Price Display -->
							<div class="my-6 p-4 rounded-xl bg-[#FAF8F5] border border-slate-200">
								@if ($paket->harga_diskon)
									<div class="text-xs text-slate-400 line-through">Rp {{ number_format($paket->harga, 0, ',', '.') }}</div>
									<div class="text-2xl font-black text-emerald-700">
										Rp {{ number_format($paket->harga_diskon, 0, ',', '.') }}
									</div>
									<span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded mt-1 inline-block">Harga Promo</span>
								@else
									<div class="text-2xl font-black text-brand-navy">
										Rp {{ number_format($paket->harga, 0, ',', '.') }}
									</div>
								@endif
								<div class="text-[11px] text-slate-500 mt-1">
									Masa aktif: {{ $paket->durasi_bulan > 0 ? $paket->durasi_bulan . ' Bulan' : 'Akses Uji Coba' }}
								</div>
							</div>

							<!-- Features List in Package -->
							<div class="space-y-2.5 text-xs text-slate-700 mb-8 font-medium">
								<div class="flex items-center gap-2">
									<i data-lucide="check" class="w-4 h-4 text-emerald-600 stroke-[2.5]"></i>
									<span>Akses Lengkap Kasir Penjualan</span>
								</div>
								<div class="flex items-center gap-2">
									<i data-lucide="check" class="w-4 h-4 text-emerald-600 stroke-[2.5]"></i>
									<span>Manajemen Stok & Riwayat Masuk-Keluar</span>
								</div>
								<div class="flex items-center gap-2">
									<i data-lucide="check" class="w-4 h-4 text-emerald-600 stroke-[2.5]"></i>
									<span>Database Pelanggan & Catatan Piutang</span>
								</div>
								<div class="flex items-center gap-2">
									<i data-lucide="check" class="w-4 h-4 text-emerald-600 stroke-[2.5]"></i>
									<span>Laporan Finansial Laba Rugi Otomatis</span>
								</div>
								<div class="flex items-center gap-2">
									<i data-lucide="check" class="w-4 h-4 text-emerald-600 stroke-[2.5]"></i>
									<span>Bantuan Teknis & Panduan Awal</span>
								</div>
							</div>
						</div>

						<a href="{{ route('register') }}" class="w-full inline-flex items-center justify-center rounded-lg text-sm font-semibold py-3 transition @if($loop->first) bg-brand-navy hover:bg-brand-royal text-white @else bg-slate-100 hover:bg-slate-200 text-brand-navy @endif">
							Pilih Paket Ini
						</a>
					</div>
				@empty
					<div class="col-span-full text-center text-brand-muted py-12">
						Belum ada paket aktif yang tersedia.
					</div>
				@endforelse
			</div>
		</div>
	</section>

	<!-- ==================== 8. TANYA JAWAB (Clean Accordion) ==================== -->
	<section id="faq" class="py-20 px-6 lg:px-8 max-w-4xl mx-auto">
		<div class="text-center mb-12 reveal-on-scroll">
			<span class="text-xs font-bold uppercase tracking-wider text-brand-gold">Pertanyaan Umum</span>
			<h2 class="text-3xl font-normal text-brand-navy tracking-tight mt-1">
				Pertanyaan yang Sering Diajukan
			</h2>
			<p class="text-sm text-brand-muted mt-2">
				Hal-hal yang paling sering ditanyakan seputar pengoperasian sistem kasir Aksara.
			</p>
		</div>

		<div class="space-y-3">
			<div class="ui-card rounded-xl p-5 bg-white reveal-on-scroll delay-100">
				<h4 class="font-normal text-base text-brand-navy">Apakah butuh perangkat keras khusus untuk menggunakannya?</h4>
				<p class="text-xs text-brand-muted mt-2 leading-relaxed">
					Tidak wajib. Aksara berjalan secara online berbasis peramban web modern, sehingga bisa langsung dibuka lewat smartphone Android/iPhone, tablet, maupun laptop/komputer yang sudah Anda miliki. Anda cukup menyambungkan printer struk Bluetooth jika ingin mencetak nota fisik.
				</p>
			</div>

			<div class="ui-card rounded-xl p-5 bg-white reveal-on-scroll delay-200">
				<h4 class="font-normal text-base text-brand-navy">Bagaimana jika staf kasir saya belum terbiasa dengan komputer?</h4>
				<p class="text-xs text-brand-muted mt-2 leading-relaxed">
					Tampilan kasir Aksara dirancang sangat sederhana, bersih, dan mudah dipahami dalam sekali lihat. Tim kami juga menyediakan panduan penggunaan dan siap membantu melatih staf toko Anda sampai benar-benar lancar.
				</p>
			</div>

			<div class="ui-card rounded-xl p-5 bg-white reveal-on-scroll delay-300">
				<h4 class="font-normal text-base text-brand-navy">Apakah data transaksi penjualan dan keuangan saya aman?</h4>
				<p class="text-xs text-brand-muted mt-2 leading-relaxed">
					Sangat aman. Setiap akun memiliki proteksi hak akses terpisah (Pemilik Toko vs Staf Kasir). Staf kasir hanya bisa memproses transaksi dan tidak dapat mengintip laporan laba bersih, harga modal (HPP), atau data sensitif pemilik toko.
				</p>
			</div>
		</div>
	</section>

	<!-- ==================== 9. CTA BANNER ==================== -->
	<section class="py-16 px-6 lg:px-8 bg-brand-navy text-white">
		<div class="max-w-4xl mx-auto text-center space-y-5 reveal-scale">
			<h2 class="text-3xl sm:text-4xl font-normal tracking-tight">
				Siap Mengembangkan Usahamu Bersama Aksara?
			</h2>
			<p class="text-sm sm:text-base text-slate-300 max-w-xl mx-auto">
				Tinggalkan pencatatan manual yang rawan salah hitung. Nikmati kemudahan tata kelola kasir, stok, dan arus kas yang rapi mulai hari ini.
			</p>
			<div class="pt-3 flex flex-col sm:flex-row items-center justify-center gap-3">
				<a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-brand-gold hover:bg-brand-goldLight text-brand-navy font-bold text-sm px-6 py-3.5 transition">
					Mulai Sekarang Gratis
				</a>
				<a href="https://wa.me/628131355613?text=Halo%20Aksara%20Developer%2C%20saya%20mau%20tanya%20sistem" target="_blank" rel="noopener" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-white/10 hover:bg-white/15 text-white border border-white/20 text-sm font-semibold px-6 py-2.5 transition">
					<i data-lucide="message-circle" class="w-4 h-4 text-emerald-400"></i>
					Hubungi WhatsApp
				</a>
			</div>
		</div>
	</section>

	<!-- ==================== 10. FOOTER ==================== -->
	<footer class="bg-white border-t border-brand-border py-10 px-6 text-xs text-brand-muted">
		<div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
			<div class="flex items-center gap-3">
				<img src="{{ asset('assets/images/aksara-logo.png') }}" alt="Aksara Logo" class="h-8 w-auto object-contain">
				<div>
					<p class="font-bold text-brand-navy text-sm">Aksara Developer</p>
					<p class="text-[11px] text-brand-muted">Sistem Kasir, Stok, Data Pelanggan & Keuangan Terpadu UMKM</p>
				</div>
			</div>
			<div class="text-center md:text-right space-y-1">
				<p>© 2026 Aksara Developer. Seluruh hak cipta dilindungi undang-undang.</p>
				<p>Konsultasi & Layanan: <a href="https://wa.me/628131355613" class="text-brand-navy font-semibold hover:underline">+62 813-1355-613</a></p>
			</div>
		</div>
	</footer>

	<!-- ==================== FLOATING WHATSAPP BUTTON ==================== -->
	<a href="https://wa.me/628131355613?text=Halo%20Aksara%20Developer%2C%20saya%20tertarik%20dengan%20sistem%20kasir%20dan%20CRM%20UMKM" target="_blank" rel="noopener" class="fixed bottom-6 right-6 z-50 inline-flex items-center gap-2 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 text-xs font-bold shadow-lg transition-transform hover:scale-105 border border-white" aria-label="Konsultasi WhatsApp">
		<i data-lucide="message-circle" class="w-4 h-4"></i>
		<span>Chat Aja Dulu</span>
	</a>

	<!-- ==================== SCRIPT: TABS, LUCIDE & SCROLL ANIMATION ==================== -->
	<script>
		// Inisialisasi Ikon Lucide
		lucide.createIcons();

		// Native Intersection Observer for Scroll Animations
		document.addEventListener('DOMContentLoaded', function() {
			var observerOptions = {
				root: null,
				rootMargin: '0px 0px -40px 0px',
				threshold: 0.08
			};

			var revealElements = document.querySelectorAll('.reveal-on-scroll, .reveal-scale');

			var observer = new IntersectionObserver(function(entries, observerInstance) {
				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						entry.target.classList.add('is-revealed');
						observerInstance.unobserve(entry.target);
					}
				});
			}, observerOptions);

			revealElements.forEach(function(el) {
				observer.observe(el);
			});
		});

		// Pengalih Tab Modul
		function switchTab(tabId, el) {
			document.querySelectorAll('.tab-content').forEach(function(pane) {
				pane.classList.add('hidden');
				pane.classList.remove('block');
				pane.classList.remove('is-revealed');
			});

			document.querySelectorAll('.tab-trigger').forEach(function(btn) {
				btn.classList.remove('bg-white', 'text-brand-navy', 'shadow-sm');
				btn.classList.add('text-slate-600');
			});

			var activePane = document.getElementById(tabId);
			if (activePane) {
				activePane.classList.remove('hidden');
				activePane.classList.add('block');
				// Re-trigger visual reveal
				setTimeout(function() {
					activePane.classList.add('is-revealed');
				}, 20);
			}

			if (el) {
				el.classList.add('bg-white', 'text-brand-navy', 'shadow-sm');
				el.classList.remove('text-slate-600');
			}
		}
	</script>
</body>
</html>
