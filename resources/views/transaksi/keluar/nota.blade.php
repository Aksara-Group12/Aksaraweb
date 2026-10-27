@extends('layouts.horizon')

<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Nota Penjualan #{{ $transaksiKeluar->invoice_no }}</title>
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		html, body {
			height: auto;
			margin: 0;
			padding: 0;
		}

		body {
			font-family: 'Courier New', monospace;
			font-size: 11px;
			line-height: 1.3;
			background: #f5f5f5;
		}

		.receipt {
			width: 72mm;
			max-width: 72mm;
			background: white;
			padding: 10px;
			margin: 20px auto;
			box-shadow: 0 0 10px rgba(0,0,0,0.1);
		}

		.header {
			text-align: center;
			margin-bottom: 8px;
			padding-bottom: 5px;
			border-bottom: 2px dashed #000;
		}

		.header h1 {
			font-size: 16px;
			font-weight: bold;
			margin-bottom: 3px;
		}

		.header p {
			font-size: 10px;
			margin: 1px 0;
		}

		.type-badge {
			display: inline-block;
			background: #16a34a;
			color: white;
			padding: 2px 8px;
			border-radius: 3px;
			font-size: 10px;
			font-weight: bold;
			margin-top: 5px;
		}

		.info-section {
			margin-bottom: 8px;
			padding-bottom: 5px;
			border-bottom: 1px dashed #000;
		}

		.info-row {
			display: flex;
			justify-content: space-between;
			margin: 2px 0;
		}

		.info-label {
			font-weight: bold;
		}

		.items-table {
			width: 100%;
			margin-bottom: 8px;
			border-bottom: 1px dashed #000;
			padding-bottom: 5px;
		}

		.item-row {
			margin: 5px 0;
		}

		.item-name {
			font-weight: bold;
		}

		.item-details {
			display: flex;
			justify-content: space-between;
			margin-top: 1px;
			font-size: 10px;
		}

		.totals {
			margin-bottom: 8px;
		}

		.total-row {
			display: flex;
			justify-content: space-between;
			margin: 3px 0;
		}

		.total-row.grand {
			font-weight: bold;
			font-size: 13px;
			margin-top: 5px;
			padding-top: 5px;
			border-top: 2px solid #000;
		}

		.footer {
			text-align: center;
			margin-top: 8px;
			padding-top: 5px;
			border-top: 2px dashed #000;
		}

		.footer p {
			margin: 2px 0;
			font-size: 10px;
		}

		@media print {
			.hz-header,
			.hz-sidebar,
			.hz-mobile-toggle,
			.hz-top-row,
			.hz-top-actions,
			.hz-main-content {
				display: none !important;
			}

			html, body {
				margin: 0 !important;
				padding: 0 !important;
				background: white !important;
			}

			.hz-layout {
				display: block !important;
				height: auto !important;
				background: white !important;
			}

			.hz-bg,
			.hz-main {
				background: white !important;
				padding: 0 !important;
				height: auto !important;
			}

			.hz-secondary-grid {
				display: none !important;
			}

			body {
				background: white;
				margin: 0;
				padding: 0;
			}

			.receipt {
				width: 72mm;
				max-width: 72mm;
				margin: 0;
				padding: 5px;
				box-shadow: none;
			}

			@page {
				margin: 0;
				size: 72mm 210mm;
			}

			.item-row,
			.info-section,
			.totals,
			.footer {
				page-break-inside: avoid;
				break-inside: avoid;
			}
		}

		@media screen {
			.receipt {
				margin: 20px auto;
			}
		}
	</style>
</head>
<body>
	<div class="receipt">
		<div class="header">
			<h1>{{ $profile->store_name ?? config('app.name', 'TOKO') }}</h1>
			<div class="type-badge">PENJUALAN</div>
		</div>

		<div class="info-section">
			<div class="info-row">
				<span class="info-label">No Invoice:</span>
				<span>{{ $transaksiKeluar->invoice_no }}</span>
			</div>
			<div class="info-row">
				<span class="info-label">Tanggal:</span>
				<span>{{ ($transaksiKeluar->tanggal_transaksi ?? $transaksiKeluar->created_at)->format('d/m/Y H:i') }}</span>
			</div>
			<div class="info-row">
				<span class="info-label">Customer:</span>
				<span>{{ $transaksiKeluar->customer_name }}</span>
			</div>
			@if($transaksiKeluar->customer_contact)
			<div class="info-row">
				<span class="info-label">Kontak:</span>
				<span>{{ $transaksiKeluar->customer_contact }}</span>
			</div>
			@endif
			<div class="info-row">
				<span class="info-label">Status:</span>
				<span>{{ strtoupper($transaksiKeluar->status ?? 'complete') }}</span>
			</div>
		</div>

		<div class="items-table">
			@foreach($transaksiKeluar->items as $item)
				@php
					$serials = json_decode($item->nomor_seri ?? '[]', true) ?: [];
					$qty = max(count($serials), 1);
					$unitPrice = $qty > 0 ? $item->total_harga / $qty : $item->total_harga;
				@endphp
				<div class="item-row">
					<div class="item-name">
						{{ $item->product->nama_produk ?? 'Produk' }}
						@if($item->product && $item->product->brand)
							- {{ $item->product->brand }}
						@endif
					</div>
					@if(count($serials))
						<div class="item-details">
							<span>No Seri:</span>
							<span>{{ implode(', ', $serials) }}</span>
						</div>
					@endif
					<div class="item-details">
						<span>{{ $qty }} x Rp {{ number_format($unitPrice, 0, ',', '.') }}</span>
						<span>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</span>
					</div>
				</div>
			@endforeach
		</div>

		@php
			$totalBiayaTambahan = $transaksiKeluar->biayaTambahan->sum('nominal');
		@endphp

		<div class="totals">
			<div class="total-row">
				<span>Subtotal:</span>
				<span>Rp {{ number_format($transaksiKeluar->items->sum('total_harga'), 0, ',', '.') }}</span>
			</div>
			@if($totalBiayaTambahan > 0)
			<div class="total-row">
				<span>Biaya Tambahan:</span>
				<span>Rp {{ number_format($totalBiayaTambahan, 0, ',', '.') }}</span>
			</div>
			@endif
			<div class="total-row grand">
				<span>TOTAL:</span>
				<span>Rp {{ number_format($transaksiKeluar->total_harga ?? ($transaksiKeluar->items->sum('total_harga') + $totalBiayaTambahan), 0, ',', '.') }}</span>
			</div>
			@if($transaksiKeluar->nominal_bayar > 0)
			<div class="total-row">
				<span>Nominal Bayar:</span>
				<span>Rp {{ number_format($transaksiKeluar->nominal_bayar, 0, ',', '.') }}</span>
			</div>
			<div class="total-row" style="color: #22c55e; font-weight: bold;">
				<span>Kembalian:</span>
				<span>Rp {{ number_format($transaksiKeluar->kembalian ?? 0, 0, ',', '.') }}</span>
			</div>
			@endif
			@if($transaksiKeluar->metode_pembayaran)
			<div class="total-row">
				<span>Metode Bayar:</span>
				<span>{{ strtoupper(str_replace('-', ' ', $transaksiKeluar->metode_pembayaran)) }}</span>
			</div>
			@endif
		</div>

		@if($transaksiKeluar->catatan)
			<div class="info-section">
				<div class="info-row">
					<span class="info-label">Catatan:</span>
				</div>
				<div style="margin-top: 5px;">
					{{ $transaksiKeluar->catatan }}
				</div>
			</div>
		@endif

		@if($totalBiayaTambahan > 0)
			<div class="info-section">
				<div class="info-row">
					<span class="info-label">Rincian Biaya Tambahan:</span>
				</div>
				<div style="margin-top: 5px; font-size:10px;">
					@foreach($transaksiKeluar->biayaTambahan as $b)
						<div class="info-row">
							<span>
								{{ $b->nama }}@if($b->keterangan) - {{ $b->keterangan }}@endif
							</span>
							<span>Rp {{ number_format($b->nominal, 0, ',', '.') }}</span>
						</div>
					@endforeach
				</div>
			</div>
		@endif

		<div class="footer">
			<p>{{ $profile->store_name ?? config('app.name', 'TOKO') }}</p>
			<p>*** TERIMA KASIH ***</p>
			<p>Nota Penjualan / Sales Receipt</p>
			<p>Diinput oleh: {{ $user->name ?? '-' }}</p>
			<p>Dicetak: {{ now()->format('d/m/Y H:i:s') }}</p>
		</div>
	</div>

	<script>
		let printDialogClosed = false;

		window.onload = function() {
			window.print();
		};

		window.onafterprint = function() {
			if (!printDialogClosed) {
				printDialogClosed = true;
				window.location.replace('{{ route("transaksi.keluar") }}');
			}
		};

		if (!window.matchMedia) {
			setTimeout(function() {
				if (!printDialogClosed) {
					printDialogClosed = true;
					window.location.replace('{{ route("transaksi.keluar") }}');
				}
			}, 1000);
		} else {
			const mediaQueryList = window.matchMedia('print');
			mediaQueryList.addListener(function(mql) {
				if (!mql.matches && !printDialogClosed) {
					printDialogClosed = true;
					setTimeout(function() {
						window.location.replace('{{ route("transaksi.keluar") }}');
					}, 100);
				}
			});
		}

		setTimeout(function() {
			if (!printDialogClosed) {
				printDialogClosed = true;
				window.location.replace('{{ route("transaksi.keluar") }}');
			}
		}, 5000);
	</script>
</body>
</html>
