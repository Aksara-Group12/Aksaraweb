<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Sistem kasir, manajemen stok, CRM, dan keuangan terpadu untuk UMKM Retail, F&B, dan Jasa. Kelola bisnis dengan mudah, akurat, dan cepat.">
	<title>Aksara - Sistem Kasir, Stok, CRM & Keuangan UMKM</title>
	<link rel="icon" type="image/png" href="{{ asset('assets/images/aksara-logo.png') }}">
	<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
	<script src="https://cdn.tailwindcss.com"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<style>
		* { font-family: 'Plus Jakarta Sans', sans-serif; }

		:root {
			--blue-500: #3b82f6;
			--blue-600: #2563eb;
			--cream-900: #f8f4e8;
			--cream-800: #f0ead6;
			--cream-700: #e8e0c8;
			--ink-100: #1e293b;
		}

		@keyframes aurora {
			0% { transform: translateX(-20%) translateY(-10%) rotate(0deg); opacity: 0.7; }
			50% { transform: translateX(10%) translateY(0%) rotate(6deg); opacity: 1; }
			100% { transform: translateX(-20%) translateY(-10%) rotate(0deg); opacity: 0.7; }
		}

		@keyframes floaty {
			0%, 100% { transform: translateY(0px); }
			50% { transform: translateY(-16px); }
		}

		@keyframes shimmer {
			0% { background-position: 0% 50%; }
			50% { background-position: 100% 50%; }
			100% { background-position: 0% 50%; }
		}

		.glass {
			backdrop-filter: blur(18px) saturate(160%);
			-webkit-backdrop-filter: blur(18px) saturate(160%);
		}

		.hover-lift {
			transition: all 0.3s ease;
		}

		.hover-lift:hover {
			transform: translateY(-8px);
			box-shadow: 0 18px 50px rgba(59, 130, 246, 0.25);
		}

		.metal-grid {
			background-image: linear-gradient(rgba(59, 130, 246, 0.12) 1px, transparent 1px),
				linear-gradient(90deg, rgba(59, 130, 246, 0.12) 1px, transparent 1px);
			background-size: 60px 60px;
		}

		.btn-whatsapp-wrap {
			position: fixed;
			bottom: 32px;
			right: 32px;
			z-index: 40;
		}

		.btn-whatsapp-wrap a {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 64px;
			height: 64px;
			border-radius: 50%;
			background-color: #25d366;
			border: 4px solid white;
			box-shadow: 0 20px 60px rgba(37, 211, 102, 0.3);
			transition: all 0.3s ease;
			text-decoration: none;
			color: white;
		}

		.btn-whatsapp-wrap a:hover {
			background-color: #1eaa4f;
			box-shadow: 0 25px 70px rgba(37, 211, 102, 0.5);
			transform: scale(1.1);
		}

		.btn-whatsapp-wrap i {
			font-size: 32px;
			color: white;
			transition: all 0.3s ease;
		}

		.btn-whatsapp-wrap a:hover i {
			transform: scale(1.1);
		}

		.whatsapp-icon {
			width: 32px;
			height: 32px;
			color: white;
			transition: all 0.3s ease;
		}

		.btn-whatsapp-wrap a:hover .whatsapp-icon {
			transform: scale(1.1);
		}
	</style>
</head>
<body class="bg-[var(--cream-900)] text-[var(--ink-100)] overflow-x-hidden">
	<div class="absolute inset-0 overflow-hidden pointer-events-none -z-10">
		<div class="absolute -top-40 -left-40 w-[700px] h-[700px] bg-gradient-to-br from-[#3b82f6]/40 to-[#60a5fa]/20 rounded-full blur-3xl animate-[aurora_18s_ease-in-out_infinite]"></div>
		<div class="absolute top-1/3 right-[-200px] w-[600px] h-[600px] bg-gradient-to-br from-[#93c5fd]/25 to-[#bfdbfe]/10 rounded-full blur-3xl animate-[aurora_22s_ease-in-out_infinite]"></div>
		<div class="absolute inset-0 metal-grid opacity-30"></div>
	</div>

	<nav class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[94%] max-w-6xl">
		<div class="glass bg-blue-50/5 border border-blue-200/10 rounded-3xl shadow-2xl shadow-black/20">
			<div class="flex items-center justify-between h-auto px-6 py-3">
				<a href="{{ url('/') }}" class="flex items-center gap-3 group">
					<img src="{{ asset('assets/images/aksara-logo.png') }}" alt="Aksara Logo" class="h-12 w-auto object-contain transition-transform group-hover:scale-105">
					<div class="hidden sm:block pl-2 border-l border-blue-200/20">
						<h2 class="text-blue-900 font-bold text-base leading-tight"><span class="text-[#3b82f6]">Aksara Developer</span></h2>
					</div>
				</a>
				<div class="hidden md:flex items-center gap-8 text-sm">
					<a href="#fitur" class="text-blue-900/80 hover:text-[#3b82f6] transition">Fitur</a>
					<a href="#alur" class="text-blue-900/80 hover:text-[#3b82f6] transition">Alur</a>
					<a href="#harga" class="text-blue-900/80 hover:text-[#3b82f6] transition">Harga</a>
					<a href="#kontak" class="text-blue-900/80 hover:text-[#3b82f6] transition">Kontak</a>
				</div>
				<div class="flex items-center gap-3">
					<a href="{{ route('login') }}" class="border border-blue-900/40 text-blue-900 px-4 py-2 rounded-full text-sm font-semibold hover:bg-blue-900 hover:text-white transition">Login</a>
					<a href="{{ route('register') }}" class="bg-[#3b82f6] text-white px-5 py-2.5 rounded-full text-sm font-bold hover:bg-[#2563eb] transition">Coba Gratis</a>
				</div>
			</div>
		</div>
	</nav>

	<section class="relative min-h-screen flex items-center px-6 lg:px-8 pt-32">
		<div class="max-w-6xl mx-auto grid lg:grid-cols-2 gap-14 items-center">
			<div class="space-y-6">
				<div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass bg-blue-50/5 border border-blue-200/10 text-xs tracking-wide">
					<span class="w-2 h-2 bg-[#3b82f6] rounded-full"></span>
					Sistem kasir, stok, CRM & keuangan terpadu UMKM
				</div>
				<h1 class="text-4xl md:text-6xl font-extrabold leading-tight">
					Kelola <span class="text-[#3b82f6]">bisnis UMKM</span> dengan kasir, stok, CRM, dan keuangan terpadu.
				</h1>
				<p class="text-blue-900/70 text-lg">
					Aksara membantu Retail, F&B, dan Jasa mencatat transaksi harian, kontrol stok, pencatatan piutang, dan laporan laba rugi otomatis dengan mudah.
				</p>
				<div class="flex flex-col sm:flex-row gap-4">
				<a href="{{ route('register') }}" class="bg-[#3b82f6] text-white px-8 py-4 rounded-2xl font-bold text-base hover:bg-[#2563eb] transition">Mulai Gratis 7 Hari</a>
					<a href="#harga" class="glass bg-blue-50/5 border border-blue-200/10 text-blue-900 px-8 py-4 rounded-2xl font-semibold text-base hover:bg-blue-50/10 transition">Lihat Paket</a>
				</div>
				<div class="grid grid-cols-3 gap-4 pt-6">
					<div class="glass bg-blue-50/5 border border-blue-200/10 rounded-2xl p-4 hover-lift">
						<div class="text-2xl font-bold text-[#3b82f6]">{{ $stats['users'] }}</div>
						<div class="text-xs text-blue-900/60">Toko Aktif</div>
					</div>
					<!-- <div class="glass bg-blue-50/5 border border-blue-200/10 rounded-2xl p-4 hover-lift">
						<div class="text-2xl font-bold text-[#3b82f6]">{{ 200 + $stats['subscriptions'] }}</div>
						<div class="text-xs text-blue-900/60">Langganan</div>
					</div> -->
					<div class="glass bg-blue-50/5 border border-blue-200/10 rounded-2xl p-4 hover-lift">
						<div class="text-2xl font-bold text-[#3b82f6]">{{$stats['packages'] }}</div>
						<div class="text-xs text-blue-900/60">Paket Tersedia</div>
					</div>
				</div>
			</div>
			<div class="relative">
				<div class="glass bg-blue-50/5 border border-blue-200/10 rounded-3xl p-8 space-y-8 shadow-2xl">
					<div>
						<h3 class="text-2xl font-bold text-[#3b82f6] mb-4">Visi</h3>
						<p class="text-blue-900/80 leading-relaxed">Transformasi UMKM Indonesia dari pencatatan manual menuju tata kelola bisnis berbasis digital yang transparan dan efisien.</p>
					</div>
					<div class="border-t border-blue-200/10"></div>
					<div>
						<h3 class="text-2xl font-bold text-[#3b82f6] mb-6">Misi</h3>
						<ul class="space-y-4">
							<li class="flex gap-3">
								<span class="text-[#3b82f6] font-bold mt-1">•</span>
								<div>
									<p class="font-semibold text-blue-900 mb-1">Kasir POS serbaguna</p>
									<p class="text-blue-900/70 text-sm">Transaksi cepat tunai/non-tunai/tempo dengan pencatatan yang akurat dan cetak nota instan.</p>
								</div>
							</li>
							<li class="flex gap-3">
								<span class="text-[#3b82f6] font-bold mt-1">•</span>
								<div>
									<p class="font-semibold text-blue-900 mb-1">Kontrol stok & opname presisi</p>
									<p class="text-blue-900/70 text-sm">Alert stok menipis, tracking pergerakan stok, dan penyesuaian selisih stok fisik vs sistem.</p>
								</div>
							</li>
							<li class="flex gap-3">
								<span class="text-[#3b82f6] font-bold mt-1">•</span>
								<div>
									<p class="font-semibold text-blue-900 mb-1">Pengelolaan piutang pelanggan & pengeluaran operasional</p>
									<p class="text-blue-900/70 text-sm">Master pelanggan, pencatatan cicilan invoice, dan kontrol biaya operasional dengan rapi.</p>
								</div>
							</li>
							<li class="flex gap-3">
								<span class="text-[#3b82f6] font-bold mt-1">•</span>
								<div>
									<p class="font-semibold text-blue-900 mb-1">Laporan laba rugi otomatis</p>
									<p class="text-blue-900/70 text-sm">Kalkulasi HPP, margin bersih, dan breakdown pendapatan vs beban operasional secara real-time.</p>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="absolute -bottom-10 -right-8 w-28 h-28 bg-gradient-to-br from-[#3b82f6] to-[#2563eb] rounded-full blur-2xl opacity-70 animate-[floaty_5s_ease-in-out_infinite]"></div>
			</div>
		</div>
	</section>

	<section id="fitur" class="relative py-24 px-6 lg:px-8">
		<div class="max-w-6xl mx-auto">
			<div class="text-center mb-14">
				<h2 class="text-4xl md:text-5xl font-bold text-blue-900">Fitur Inti Aksara</h2>
				<p class="text-blue-900/60 mt-4 max-w-2xl mx-auto">Dirancang khusus untuk UMKM Retail, F&B, dan Jasa: kasir, stok, CRM, dan keuangan terpadu.</p>
			</div>
			<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
				<div class="glass bg-blue-50/5 border border-blue-200/10 rounded-3xl p-6 hover-lift">
					<h3 class="text-xl font-semibold mb-3 text-blue-900">POS Kasir Cepat</h3>
					<p class="text-blue-900/60">Transaksi instan, cetak nota, dan dukungan pembayaran tempo (Invoice/AR).</p>
				</div>
				<div class="glass bg-blue-50/5 border border-blue-200/10 rounded-3xl p-6 hover-lift">
					<h3 class="text-xl font-semibold mb-3 text-blue-900">Manajemen Stok & Opname</h3>
					<p class="text-blue-900/60">Alert stok menipis dan penyesuaian selisih stok fisik vs sistem dengan nilai kerugian.</p>
				</div>
				<div class="glass bg-blue-50/5 border border-blue-200/10 rounded-3xl p-6 hover-lift">
					<h3 class="text-xl font-semibold mb-3 text-blue-900">CRM & Invoice Piutang</h3>
					<p class="text-blue-900/60">Master pelanggan dan pencatatan cicilan tagihan (Account Receivable).</p>
				</div>
				<div class="glass bg-blue-50/5 border border-blue-200/10 rounded-3xl p-6 hover-lift">
					<h3 class="text-xl font-semibold mb-3 text-blue-900">Pembelian & Supplier</h3>
					<p class="text-blue-900/60">Transaksi masuk dengan update stok otomatis dan master supplier.</p>
				</div>
				<div class="glass bg-blue-50/5 border border-blue-200/10 rounded-3xl p-6 hover-lift">
					<h3 class="text-xl font-semibold mb-3 text-blue-900">Shift Kasir & Rekonsiliasi</h3>
					<p class="text-blue-900/60">Pengendalian kas dengan modal awal/akhir dan rekonsiliasi kas harian.</p>
				</div>
				<div class="glass bg-blue-50/5 border border-blue-200/10 rounded-3xl p-6 hover-lift">
					<h3 class="text-xl font-semibold mb-3 text-blue-900">Laporan Keuangan & Laba Rugi</h3>
					<p class="text-blue-900/60">Kalkulasi HPP, beban operasional, dan laba bersih secara otomatis.</p>
				</div>
			</div>
		</div>
	</section>

	<section id="alur" class="relative py-24 px-6 lg:px-8 bg-[var(--cream-800)]">
		<div class="max-w-6xl mx-auto grid lg:grid-cols-2 gap-12 items-center">
			<div>
				<h2 class="text-4xl md:text-5xl font-bold mb-6 text-blue-900">Alur Kerja Harian</h2>
				<p class="text-blue-900/60 mb-8">Satu dashboard untuk kasir, stok, CRM, dan laporan keuangan UMKM.</p>
				<ol class="space-y-4">
					<li class="glass bg-blue-50/5 border border-blue-200/10 rounded-2xl p-4 text-blue-900">1. Buka shift kasir dan catat transaksi penjualan (cash/tempo).</li>
					<li class="glass bg-blue-50/5 border border-blue-200/10 rounded-2xl p-4 text-blue-900">2. Stok otomatis berkurang/bertambah dan piutang tercatat jika tempo.</li>
					<li class="glass bg-blue-50/5 border border-blue-200/10 rounded-2xl p-4 text-blue-900">3. Laporan laba rugi langsung terbentuk dengan HPP dan margin.</li>
				</ol>
			</div>
			<div class="glass bg-blue-50/5 border border-blue-200/10 rounded-3xl p-6">
				<div class="text-sm text-blue-900/60 mb-2">Dashboard Snapshot</div>
				<div class="grid grid-cols-2 gap-4">
					<div class="bg-[var(--cream-700)] rounded-2xl p-4">
						<p class="text-xs text-blue-900/50">Omset Penjualan</p>
						<p class="text-xl font-bold text-blue-900">Rp 145Jt</p>
					</div>
					<div class="bg-[var(--cream-700)] rounded-2xl p-4">
						<p class="text-xs text-blue-900/50">Total Produk/SKU</p>
						<p class="text-xl font-bold text-blue-900">284</p>
					</div>
					<div class="bg-[var(--cream-700)] rounded-2xl p-4">
						<p class="text-xs text-blue-900/50">Piutang/Invoice</p>
						<p class="text-xl font-bold text-blue-900">Rp 32Jt</p>
					</div>
					<div class="bg-[var(--cream-700)] rounded-2xl p-4">
						<p class="text-xs text-blue-900/50">Laba Bersih</p>
						<p class="text-xl font-bold text-blue-900">Rp 18Jt</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section id="harga" class="relative py-24 px-6 lg:px-8">
		<div class="max-w-6xl mx-auto">
			<div class="text-center mb-14">
				<h2 class="text-4xl md:text-5xl font-bold text-blue-900">Paket Berlangganan</h2>
				<p class="text-blue-900/60 mt-4">Harga otomatis mengikuti paket di sistem Anda.</p>
			</div>
			<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
				@forelse($packages as $paket)
					<div class="glass bg-blue-50/5 border border-blue-200/10 rounded-3xl p-6 hover-lift">
						<h3 class="text-xl font-bold mb-2 text-blue-900">{{ $paket->nama_paket }}</h3>
						<p class="text-blue-900/60 text-sm mb-4">{{ $paket->deskripsi }}</p>
						@if ($paket->harga_diskon)
							<div class="flex items-center gap-3 mb-2">
								<div class="text-sm text-blue-900/50 line-through">Rp {{ number_format($paket->harga, 0, ',', '.') }}</div>
								<div class="text-3xl font-bold text-[#22c55e]">Rp {{ number_format($paket->harga_diskon, 0, ',', '.') }}</div>
							</div>
							<div class="text-xs text-[#22c55e] font-semibold mb-6">✓ Harga Spesial / Promo</div>
						@else
							<div class="text-3xl font-bold text-[#3b82f6] mb-2">Rp {{ number_format($paket->harga, 0, ',', '.') }}</div>
						@endif
						<div class="text-xs text-blue-900/50 mb-6">Per {{ $paket->durasi_bulan }} bulan</div>
						<a href="{{ route('register') }}" class="block text-center bg-[#3b82f6] text-white py-3 rounded-2xl font-semibold hover:bg-[#2563eb] transition">Pilih Paket</a>
					</div>
				@empty
					<div class="col-span-full text-center text-blue-900/60">Belum ada paket aktif.</div>
				@endforelse
			</div>
		</div>
	</section>

	<section id="kontak" class="relative py-20 px-6 lg:px-8 bg-[var(--cream-800)]">
		<div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10 items-center">
			<div>
				<h2 class="text-3xl md:text-4xl font-bold mb-4 text-blue-900">Siap Naik Level?</h2>
				<p class="text-blue-900/60">Konsultasi gratis untuk setup sistem kasir UMKM Anda.</p>
			</div>
			<div class="flex flex-col sm:flex-row gap-4">
				<a href="{{ route('register') }}" class="bg-[#3b82f6] text-white px-6 py-4 rounded-2xl font-bold text-center">Mulai Gratis 7 Hari</a>
				<a href="{{ route('login') }}" class="glass bg-blue-50/5 border border-blue-200/10 text-blue-900 px-6 py-4 rounded-2xl font-semibold text-center">Masuk Dashboard</a>
			</div>
		</div>
	</section>

	<footer class="py-10 text-center text-blue-900/40 text-sm">
		© 2026 Aksara. Sistem kasir, stok, CRM & keuangan UMKM untuk Indonesia.
	</footer>

	<div class="btn-whatsapp-wrap">
		<a href="https://wa.me/6288231321669?text=Halo%20Aksara%2C%20saya%20tertarik%20dengan%20sistem%20kasir%20UMKM" target="_blank" rel="noopener noreferrer" aria-label="Chat WhatsApp">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="whatsapp-icon">
				<path d="M414.73,97.1A222.14,222.14,0,0,0,256.94,32C134,32,33.92,131.58,33.87,254A220.61,220.61,0,0,0,63.65,365L32,480l118.25-30.87a223.63,223.63,0,0,0,106.6,27h.09c122.93,0,223-99.59,223.06-222A220.18,220.18,0,0,0,414.73,97.1ZM256.94,438.66h-.08a185.75,185.75,0,0,1-94.36-25.72l-6.77-4L85.56,427.26l18.73-68.09-4.41-7A183.46,183.46,0,0,1,71.53,254c0-101.73,83.21-184.5,185.48-184.5A185,185,0,0,1,442.34,254.14C442.3,355.88,359.13,438.66,256.94,438.66ZM358.63,300.47c-5.57-2.78-33-16.2-38.08-18.05s-8.83-2.78-12.54,2.78-14.4,18-17.65,21.75-6.5,4.16-12.07,1.38-23.54-8.63-44.83-27.53c-16.57-14.71-27.75-32.87-31-38.42s-.35-8.56,2.44-11.32c2.51-2.49,5.57-6.48,8.36-9.72s3.72-5.56,5.57-9.26.93-6.94-.46-9.71-12.54-30.08-17.18-41.19c-4.53-10.82-9.12-9.35-12.54-9.52-3.25-.16-7-.2-10.69-.2a20.53,20.53,0,0,0-14.86,6.94c-5.11,5.56-19.51,19-19.51,46.28s20,53.68,22.76,57.38,39.3,59.73,95.21,83.76a323.11,323.11,0,0,0,31.78,11.68c13.35,4.22,25.5,3.63,35.1,2.2,10.71-1.59,33-13.42,37.63-26.38s4.64-24.06,3.25-26.37S364.21,303.24,358.63,300.47Z" fill="currentColor"/>
			</svg>
		</a>
	</div>
</body>
</html>
