<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<title>{{ $documentTitle ?? 'Laporan Pemesanan Sistem Aksara Developer' }}</title>
	<style>
		@page {
			margin: 25px 30px;
		}

		body {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 9px;
			color: #1e293b;
			line-height: 1.4;
			margin: 0;
			padding: 0;
		}

		/* Header Table */
		.header-table {
			width: 100%;
			border-collapse: collapse;
			border-bottom: 2px solid #0F223D;
			padding-bottom: 12px;
			margin-bottom: 16px;
		}

		.header-logo {
			width: 55px;
			vertical-align: middle;
		}

		.header-title-box {
			vertical-align: middle;
			padding-left: 12px;
		}

		.brand-name {
			font-size: 16px;
			font-weight: bold;
			color: #0F223D;
			margin: 0 0 2px 0;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.brand-name span {
			color: #C5A059;
		}

		.brand-desc {
			font-size: 8.5px;
			color: #64748B;
			margin: 0 0 4px 0;
		}

		.doc-title {
			font-size: 13px;
			font-weight: bold;
			color: #0F223D;
			margin: 4px 0 0 0;
			text-transform: uppercase;
			letter-spacing: 0.3px;
		}

		.header-meta-box {
			text-align: right;
			vertical-align: middle;
			font-size: 8.5px;
			color: #475569;
			line-height: 1.5;
		}

		/* KPI Summary Cards Table */
		.summary-table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 16px;
		}

		.summary-card {
			width: 25%;
			padding: 8px 10px;
			background: #F8FAFC;
			border: 1px solid #E2E8F0;
			border-radius: 6px;
			text-align: left;
		}

		.summary-label {
			font-size: 7.5px;
			text-transform: uppercase;
			color: #64748B;
			font-weight: bold;
			letter-spacing: 0.5px;
			margin-bottom: 2px;
		}

		.summary-value {
			font-size: 12px;
			font-weight: bold;
			color: #0F223D;
		}

		/* Section Title */
		.section-header {
			font-size: 10.5px;
			font-weight: bold;
			color: #0F223D;
			border-bottom: 1px solid #CBD5E1;
			padding-bottom: 4px;
			margin: 14px 0 8px 0;
			text-transform: uppercase;
			letter-spacing: 0.4px;
		}

		/* Data Table */
		.data-table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 18px;
		}

		.data-table th {
			background-color: #0F223D;
			color: #FFFFFF;
			font-size: 8px;
			font-weight: bold;
			text-align: left;
			padding: 6px 5px;
			border: 1px solid #0F223D;
			text-transform: uppercase;
			letter-spacing: 0.3px;
		}

		.data-table td {
			padding: 5px;
			border: 1px solid #E2E8F0;
			font-size: 8.5px;
			vertical-align: middle;
		}

		.data-table tr:nth-child(even) {
			background-color: #F8FAFC;
		}

		.text-center {
			text-align: center;
		}

		.text-right {
			text-align: right;
		}

		/* Badges */
		.badge {
			display: inline-block;
			padding: 2px 6px;
			border-radius: 4px;
			font-size: 7.5px;
			font-weight: bold;
			text-align: center;
		}

		.badge-success {
			background-color: #DEF7EC;
			color: #03543F;
			border: 1px solid #BCF0DA;
		}

		.badge-warning {
			background-color: #FEF08A;
			color: #713F12;
			border: 1px solid #FDE047;
		}

		.badge-danger {
			background-color: #FDE8E8;
			color: #9B1C1C;
			border: 1px solid #FBD5D5;
		}

		.badge-info {
			background-color: #E1EFFE;
			color: #1E429F;
			border: 1px solid #C3DDFD;
		}

		/* Total Footer Row */
		.total-row td {
			background-color: #F1F5F9;
			font-weight: bold;
			font-size: 9px;
			border-top: 2px solid #0F223D;
		}

		/* Sign-off area */
		.footer-table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 25px;
		}

		.footer-note {
			font-size: 7.5px;
			color: #64748B;
			line-height: 1.4;
			vertical-align: top;
			width: 60%;
		}

		.footer-sign {
			text-align: center;
			vertical-align: top;
			width: 40%;
			font-size: 8.5px;
		}

		.sign-space {
			height: 45px;
		}

		.sign-name {
			font-weight: bold;
			color: #0F223D;
			text-decoration: underline;
		}
	</style>
</head>
<body>

	<!-- Header Kop Laporan -->
	<table class="header-table">
		<tr>
			<td class="header-logo">
				@php
					$logoFile = public_path('assets/images/aksara-logo.png');
					$logoData = file_exists($logoFile) ? base64_encode(file_get_contents($logoFile)) : null;
				@endphp
				@if($logoData)
					<img src="data:image/png;base64,{{ $logoData }}" width="48" height="48" alt="Logo Aksara">
				@endif
			</td>
			<td class="header-title-box">
				<div class="brand-name">Aksara <span>Developer</span></div>
				<div class="brand-desc">Platform Manajemen Toko, Kasir POS & Solusi Bisnis Digital</div>
				<div class="doc-title">Laporan Lengkap Pemesanan Sistem</div>
			</td>
			<td class="header-meta-box">
				<strong>Periode Data:</strong> {{ $periodTitle ?? 'Semua Riwayat (Dari Awal Hingga Akhir)' }}<br>
				<strong>Tanggal Cetak:</strong> {{ now()->translatedFormat('d/m/Y H:i') }} WIB<br>
				<strong>Dicetak Oleh:</strong> {{ auth()->user()->name ?? 'Superadmin' }} (Superadmin)<br>
				<strong>Total Record:</strong> {{ $totalSubscriptions }} Pemesanan
			</td>
		</tr>
	</table>

	<!-- Kotak Ringkasan Metrik (KPI) -->
	<table class="summary-table">
		<tr>
			<td class="summary-card" style="padding-right: 8px;">
				<div class="summary-label">Total Seluruh Pemesanan</div>
				<div class="summary-value">{{ number_format($totalSubscriptions, 0, ',', '.') }} Pesanan</div>
			</td>
			<td class="summary-card" style="padding-right: 8px;">
				<div class="summary-label">Total Pendapatan Pemesanan</div>
				<div class="summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
			</td>
			<td class="summary-card" style="padding-right: 8px;">
				<div class="summary-label">Pemesanan Paket Aktif</div>
				<div class="summary-value" style="color: #047857;">{{ number_format($totalActive, 0, ',', '.') }} Aktif</div>
			</td>
			<td class="summary-card">
				<div class="summary-label">Pemesanan Menunggu Approval</div>
				<div class="summary-value" style="color: #B45309;">{{ number_format($totalPending, 0, ',', '.') }} Pending</div>
			</td>
		</tr>
	</table>

	<!-- Section: Riwayat Pemesanan Berlangganan Paket -->
	<div class="section-header">
		Daftar Pemesanan Paket Berlangganan (Dari Awal Hingga Akhir)
	</div>

	<table class="data-table">
		<thead>
			<tr>
				<th style="width: 25px;" class="text-center">No</th>
				<th style="width: 70px;">ID Pesanan</th>
				<th style="width: 80px;">Tanggal Pesan</th>
				<th style="width: 120px;">Pemesan / Akun</th>
				<th style="width: 120px;">Nama Toko / Brand</th>
				<th style="width: 90px;">Paket Dipesan</th>
				<th style="width: 60px;" class="text-center">Durasi</th>
				<th style="width: 75px;">Bank Tujuan</th>
				<th style="width: 95px;" class="text-right">Biaya / Harga</th>
				<th style="width: 75px;" class="text-center">Status Langganan</th>
				<th style="width: 75px;" class="text-center">Verifikasi Admin</th>
			</tr>
		</thead>
		<tbody>
			@forelse($subscriptions as $index => $sub)
				<tr>
					<td class="text-center">{{ $index + 1 }}</td>
					<td style="font-weight: bold; font-family: monospace;">#SUB-{{ str_pad($sub->id, 5, '0', STR_PAD_LEFT) }}</td>
					<td>{{ $sub->created_at ? $sub->created_at->format('d/m/Y H:i') : ($sub->start_date ? \Carbon\Carbon::parse($sub->start_date)->format('d/m/Y') : '-') }}</td>
					<td>
						<strong>{{ $sub->user->name ?? 'Pengguna' }}</strong><br>
						<span style="font-size: 7.5px; color: #64748B;">{{ $sub->user->email ?? '-' }}</span>
					</td>
					<td>{{ $sub->user?->profile?->store_name ?? 'Aksara Store' }}</td>
					<td>
						<strong>{{ $sub->paket_name ?? ($sub->paket->nama_paket ?? 'Paket') }}</strong>
						@if($sub->is_trial)
							<span style="font-size: 7.5px; color: #64748B;">(Trial)</span>
						@endif
					</td>
					<td class="text-center">{{ $sub->duration_days ? $sub->duration_days . ' Hari' : '-' }}</td>
					<td>{{ $sub->bank->nama_bank ?? ($sub->bank_id ? 'Bank #' . $sub->bank_id : 'Sistem / Otomatis') }}</td>
					<td class="text-right" style="font-weight: bold;">
						Rp {{ number_format($sub->price ?? 0, 0, ',', '.') }}
					</td>
					<td class="text-center">
						@if($sub->status === 'active')
							<span class="badge badge-success">Aktif</span>
						@elseif($sub->status === 'expired')
							<span class="badge badge-warning">Kedaluwarsa</span>
						@elseif($sub->status === 'cancelled')
							<span class="badge badge-danger">Dibatalkan</span>
						@else
							<span class="badge badge-info">{{ ucfirst($sub->status) }}</span>
						@endif
					</td>
					<td class="text-center">
						@if($sub->approval_status === 'approved')
							<span class="badge badge-success">Disetujui</span>
						@elseif($sub->approval_status === 'pending')
							<span class="badge badge-warning">Menunggu</span>
						@elseif($sub->approval_status === 'rejected')
							<span class="badge badge-danger">Ditolak</span>
						@else
							<span class="badge badge-info">-</span>
						@endif
					</td>
				</tr>
			@empty
				<tr>
					<td colspan="11" class="text-center" style="padding: 16px; color: #64748B;">
						Tidak ada riwayat data pemesanan langganan.
					</td>
				</tr>
			@endforelse
		</tbody>
		<tfoot>
			<tr class="total-row">
				<td colspan="8" class="text-right" style="padding: 7px 10px;">TOTAL KESELURUHAN NILAI PEMESANAN:</td>
				<td class="text-right" style="padding: 7px 5px; color: #0F223D;">
					Rp {{ number_format($totalRevenue, 0, ',', '.') }}
				</td>
				<td colspan="2" class="text-center">
					{{ $totalSubscriptions }} Pemesanan Tercatat
				</td>
			</tr>
		</tfoot>
	</table>

	@if(isset($transaksiKeluar) && $transaksiKeluar->count() > 0)
		<!-- Section Tambahan: Ringkasan Transaksi Penjualan Sistem -->
		<div class="section-header" style="margin-top: 18px;">
			Ringkasan Transaksi Penjualan Kasir (Sistem)
		</div>

		<table class="data-table">
			<thead>
				<tr>
					<th style="width: 25px;" class="text-center">No</th>
					<th style="width: 90px;">No. Invoice</th>
					<th style="width: 80px;">Tanggal</th>
					<th style="width: 140px;">Merchant / Kasir</th>
					<th style="width: 120px;">Pelanggan</th>
					<th style="width: 90px;">Metode Bayar</th>
					<th style="width: 95px;" class="text-right">Total Transaksi</th>
					<th style="width: 75px;" class="text-center">Status</th>
				</tr>
			</thead>
			<tbody>
				@foreach($transaksiKeluar as $idx => $tk)
					<tr>
						<td class="text-center">{{ $idx + 1 }}</td>
						<td style="font-family: monospace; font-weight: bold;">{{ $tk->invoice_no }}</td>
						<td>{{ ($tk->tanggal_transaksi ?: $tk->created_at)?->format('d/m/Y H:i') }}</td>
						<td>{{ $tk->admin?->nama ?? 'Kasir Utama' }}</td>
						<td>{{ $tk->customer_name ?? 'Pelanggan Umum' }}</td>
						<td>{{ strtoupper($tk->metode_pembayaran ?? 'CASH') }}</td>
						<td class="text-right" style="font-weight: bold;">Rp {{ number_format($tk->total_harga ?? 0, 0, ',', '.') }}</td>
						<td class="text-center">
							<span class="badge badge-success">{{ ucfirst($tk->status ?? 'complete') }}</span>
						</td>
					</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr class="total-row">
					<td colspan="6" class="text-right" style="padding: 6px 10px;">TOTAL VOLUME PENJUALAN KASIR:</td>
					<td class="text-right" style="color: #0F223D;">Rp {{ number_format($totalPenjualanKasir ?? 0, 0, ',', '.') }}</td>
					<td class="text-center">{{ $transaksiKeluar->count() }} Transaksi</td>
				</tr>
			</tfoot>
		</table>
	@endif

	<!-- Pengesahan / Footer Sign -->
	<table class="footer-table">
		<tr>
			<td class="footer-note">
				<strong>Catatan Laporan:</strong><br>
				1. Laporan ini merupakan dokumen rekapitulasi data resmi yang digenerate secara otomatis oleh sistem Aksara Developer.<br>
				2. Data mencakup seluruh riwayat pemesanan paket langganan dan transaksi sistem dari awal operasional hingga saat ini.<br>
				3. Seluruh nominal transaksi disajikan dalam mata uang Rupiah (IDR).
			</td>
			<td class="footer-sign">
				Dikeluarkan di: Indonesia<br>
				Pada tanggal: {{ now()->translatedFormat('d F Y') }}<br>
				<strong>Mengetahui & Menyetujui,</strong>
				<div class="sign-space"></div>
				<div class="sign-name">{{ auth()->user()->name ?? 'Administrator' }}</div>
				<div style="font-size: 7.5px; color: #64748B;">Superadmin Konsol Aksara Developer</div>
			</td>
		</tr>
	</table>

</body>
</html>

