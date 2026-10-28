@extends('layouts.horizon')

<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Nota Pembelian #{{ $transaksiMasuk->reference_no }}</title>
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
			background: #dc2626;
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
			<div class="type-badge">PEMBELIAN</div>
		</div>

		<div class="info-section">
			<div class="info-row">
				<span class="info-label">No Ref:</span>
				<span>{{ $transaksiMasuk->reference_no }}</span>
			</div>
			<div class="info-row">
				<span class="info-label">Tanggal:</span>
				<span>{{ ($transaksiMasuk->tanggal_transaksi ?? $transaksiMasuk->created_at)->format('d/m/Y H:i') }}</span>
			</div>
			<div class="info-row">
				<span class="info-label">Supplier:</span>
				<span>{{ $transaksiMasuk->supplier_name }}</span>
			</div>
			@if($transaksiMasuk->supplier_contact)
			<div class="info-row">
				<span class="info-label">Kontak:</span>
				<span>{{ $transaksiMasuk->supplier_contact }}</span>
			</div>
			@endif
			<div class="info-row">
				<span class="info-label">Status:</span>
				<span>{{ strtoupper($transaksiMasuk->status ?? 'complete') }}</span>
			</div>
		</div>

		<div class="items-table">
			@foreach($transaksiMasuk->items as $item)
				@php
					$serials = json_decode($item->-- hidden nomor_seri -- ?? '[]', true) ?: [];
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
						<span>{{ number_format($item->-- hidden berat --, 2, ',', '.') }} gr x Rp {{ number_format($item->harga_gram, 0, ',', '.') }}</span>
						<span>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</span>
					</div>
				</div>
			@endforeach
		</div>

		<div class="totals">
			<div class="total-row">
				<span>Subtotal:</span>
				<span>Rp {{ number_format($transaksiMasuk->items->sum('total_harga'), 0, ',', '.') }}</span>
			</div>
			<div class="total-row grand">
				<span>TOTAL:</span>
				<span>Rp {{ number_format($transaksiMasuk->total_harga ?? $transaksiMasuk->items->sum('total_harga'), 0, ',', '.') }}</span>
			</div>
			@if($transaksiMasuk->nominal_bayar > 0)
			<div class="total-row">
				<span>Nominal Bayar:</span>
				<span>Rp {{ number_format($transaksiMasuk->nominal_bayar, 0, ',', '.') }}</span>
			</div>
			<div class="total-row" style="color: #22c55e; font-weight: bold;">
				<span>Kembalian:</span>
				<span>Rp {{ number_format($transaksiMasuk->kembalian ?? 0, 0, ',', '.') }}</span>
			</div>
			@endif
			@if($transaksiMasuk->metode_pembayaran)
			<div class="total-row">
				<span>Metode Bayar:</span>
				<span>{{ strtoupper(str_replace('-', ' ', $transaksiMasuk->metode_pembayaran)) }}</span>
			</div>
			@endif
		</div>

		@if($transaksiMasuk->catatan)
			<div class="info-section">
				<div class="info-row">
					<span class="info-label">Catatan:</span>
				</div>
				<div style="margin-top: 5px;">
					{{ $transaksiMasuk->catatan }}
				</div>
			</div>
		@endif

		<div class="footer">
			<p>{{ $profile->store_name ?? config('app.name', 'TOKO') }}</p>
			<p>*** TERIMA KASIH ***</p>
			<p>Nota Pembelian / Purchase Receipt</p>
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
				window.location.replace('{{ route("transaksi.masuk") }}');
			}
		};

		if (!window.matchMedia) {
			setTimeout(function() {
				if (!printDialogClosed) {
					printDialogClosed = true;
					window.location.replace('{{ route("transaksi.masuk") }}');
				}
			}, 1000);
		} else {
			const mediaQueryList = window.matchMedia('print');
			mediaQueryList.addListener(function(mql) {
				if (!mql.matches && !printDialogClosed) {
					printDialogClosed = true;
					setTimeout(function() {
						window.location.replace('{{ route("transaksi.masuk") }}');
					}, 100);
				}
			});
		}

		setTimeout(function() {
			if (!printDialogClosed) {
				printDialogClosed = true;
				window.location.replace('{{ route("transaksi.masuk") }}');
			}
		}, 5000);
	</script>
</body>
</html>
