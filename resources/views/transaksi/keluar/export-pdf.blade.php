<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<title>Laporan Transaksi Keluar</title>
	<style>
		body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
		h1 { font-size: 16px; margin-bottom: 10px; }
		.table { width: 100%; border-collapse: collapse; margin-top: 10px; }
		.table th, .table td { border: 1px solid #ddd; padding: 6px 4px; }
		.table th { background: #f3f4f6; font-weight: 600; }
		.small { font-size: 10px; color: #6b7280; }
	</style>
</head>
<body>
	<h1>Laporan Transaksi Keluar</h1>
	<p class="small">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>

	<table class="table">
		<thead>
			<tr>
				<th>Tanggal</th>
				<th>No. Invoice</th>
				<th>Pelanggan</th>
				<th>Kota/Kabupaten</th>
				<th>Total</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
		@foreach($transaksi as $t)
			<tr>
				<td>{{ ($t->tanggal_transaksi ?: $t->created_at)?->format('d/m/Y') }}</td>
				<td>{{ $t->invoice_no }}</td>
				<td>{{ $t->customer_name }}</td>
				<td>{{ $t->lokasi ?? '-' }}</td>
				<td>Rp {{ number_format($t->total_harga ?? 0, 0, ',', '.') }}</td>
				<td>{{ ucfirst($t->status ?? 'complete') }}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</body>
</html>
