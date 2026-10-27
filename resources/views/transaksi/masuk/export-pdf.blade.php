<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<title>Laporan Transaksi Masuk</title>
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
	<h1>Laporan Transaksi Masuk</h1>
	<p class="small">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>

	<table class="table">
		<thead>
			<tr>
				<th>Tanggal</th>
				<th>No. Referensi</th>
				<th>Supplier</th>
				<th>Kota/Kabupaten</th>
				<th>Total Harga</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
		@foreach($transaksi as $t)
			<tr>
				<td>{{ $t->created_at ? $t->created_at->format('d/m/Y') : '' }}</td>
				<td>{{ $t->reference_no }}</td>
				<td>{{ $t->supplier_name }}</td>
				<td>{{ $t->lokasi ?? '-' }}</td>
				<td>Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
				<td>
					@if ($t->status === 'complete')
						Selesai
					@elseif ($t->status === 'pending')
						Pending
					@else
						Dibatalkan
					@endif
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</body>
</html>
