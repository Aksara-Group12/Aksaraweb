<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.5;
        }
        .container {
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 18px;
        }
        .meta {
            text-align: center;
            margin-bottom: 20px;
            font-size: 9px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th {
            background-color: #f0f0f0;
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
        }
        td {
            border: 1px solid #999;
            padding: 6px;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-masuk {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-keluar {
            background-color: #f8d7da;
            color: #721c24;
        }
        .badge-expenses {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .badge-complete {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #999;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Riwayat Transaksi</h1>
        <div class="meta">
            <p>Laporan riwayat transaksi masuk, keluar, dan pengeluaran</p>
            <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th class="text-center">Tipe</th>
                    <th>No. Referensi</th>
                    <th>Keterangan</th>
                    <th>Lokasi/Kategori</th>
                    <th class="text-right">Nominal</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($histories as $history)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($history->tanggal)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            @if ($history->tipe === 'masuk')
                                <span class="badge badge-masuk">MASUK</span>
                            @elseif ($history->tipe === 'keluar')
                                <span class="badge badge-keluar">KELUAR</span>
                            @else
                                <span class="badge badge-expenses">PENGELUARAN</span>
                            @endif
                        </td>
                        <td>{{ $history->referensi_no }}</td>
                        <td>
                            @if ($history->tipe === 'masuk')
                                {{ $history->supplier_name ?? '-' }}
                            @elseif ($history->tipe === 'keluar')
                                {{ $history->customer_name ?? '-' }}
                            @else
                                {{ $history->keterangan ?? '-' }}
                            @endif
                        </td>
                        <td>
                            @if ($history->tipe === 'expenses')
                                {{ $history->kategori ?? '-' }}
                            @else
                                {{ $history->lokasi ?? '-' }}
                            @endif
                        </td>
                        <td class="text-right">Rp {{ number_format($history->nominal, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if ($history->status === 'complete')
                                <span class="badge badge-complete">Selesai</span>
                            @elseif ($history->status === 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @else
                                <span class="badge" style="background-color:#f2dede; color:#a94442;">Dibatalkan</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <p>Laporan ini adalah dokumen resmi dari sistem Golday Inventory Management</p>
        </div>
    </div>
</body>
</html>
