<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<title>Laporan Pengeluaran</title>
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
	<h1>Laporan Pengeluaran</h1>
	<p class="small">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>

	<table class="table">
		<thead>
			<tr>
				<th>Tanggal</th>
				<th>Kategori</th>
				<th>Keterangan</th>
				<th>Nominal</th>
				<th>Metode</th>
			</tr>
		</thead>
		<tbody>
		@foreach($expenses as $expense)
			<tr>
				<td>
					{{ $expense->tanggal ? $expense->tanggal->format('d/m/Y') : ($expense->created_at ? $expense->created_at->format('d/m/Y') : '') }}
				</td>
				<td>{{ $expense->kategori ?? '-' }}</td>
				<td>{{ $expense->keterangan ?? '-' }}</td>
				<td>Rp {{ number_format($expense->nominal, 0, ',', '.') }}</td>
				<td>{{ $expense->metode_pembayaran === 'cash' ? 'Cash' : 'Non-Cash' }}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</body>
</html>
