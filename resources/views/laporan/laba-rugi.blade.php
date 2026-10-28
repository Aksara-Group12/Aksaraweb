@extends('layouts.horizon')

@section('page_title', 'Laba Rugi Detail')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Laba Rugi Detail</h2>
			<p class="hz-top-sub">Laporan profit & loss dengan HPP.</p>
		</div>
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px;">
			<form method="GET" action="{{ route('laporan.laba-rugi') }}" style="display:flex; align-items:center; gap:6px;">
				<input type="date" name="start_date" value="{{ $startDate }}" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 10px; font-size:0.8rem; color:#1e293b;">
				<span style="color:#9ca3af;">s/d</span>
				<input type="date" name="end_date" value="{{ $endDate }}" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 10px; font-size:0.8rem; color:#1e293b;">
				<button type="submit" class="hz-btn hz-btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;">Filter</button>
			</form>
		</div>
	</div>

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 16px;">
				<p class="hz-card-title">Laba Rugi ({{ $startDate }} s/d {{ $endDate }})</p>
			</div>
			
			<div style="background: rgba(255,255,255,0.95); border-radius: 12px; padding: 20px;">
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px;">
					<div style="text-align: center; padding: 16px; background: rgba(34,197,94,0.1); border-radius: 10px;">
						<div style="color: #64748b; font-size: 0.85rem; margin-bottom: 4px;">Pendapatan Penjualan</div>
						<div style="color: #16a34a; font-size: 1.4rem; font-weight: 700;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
						<div style="color: #6b7280; font-size: 0.8rem;">{{ $totalSalesCount }} transaksi</div>
					</div>
					<div style="text-align: center; padding: 16px; background: rgba(239,68,68,0.1); border-radius: 10px;">
						<div style="color: #64748b; font-size: 0.85rem; margin-bottom: 4px;">HPP (COGS)</div>
						<div style="color: #dc2626; font-size: 1.4rem; font-weight: 700;">Rp {{ number_format($totalHPP, 0, ',', '.') }}</div>
					</div>
					<div style="text-align: center; padding: 16px; background: rgba(59,130,246,0.1); border-radius: 10px;">
						<div style="color: #64748b; font-size: 0.85rem; margin-bottom: 4px;">Laba Kotor</div>
						<div style="color: #3b82f6; font-size: 1.4rem; font-weight: 700;">Rp {{ number_format($grossProfit, 0, ',', '.') }}</div>
						<div style="color: #6b7280; font-size: 0.8rem;">Margin: {{ number_format($grossMargin, 2) }}%</div>
					</div>
					<div style="text-align: center; padding: 16px; background: {{ $netProfit >= 0 ? 'rgba(34,197,94,0.1)' : 'rgba(239,68,68,0.1)' }}; border-radius: 10px;">
						<div style="color: #64748b; font-size: 0.85rem; margin-bottom: 4px;">Laba Bersih</div>
						<div style="color: {{ $netProfit >= 0 ? '#86efac' : '#fca5a5' }}; font-size: 1.4rem; font-weight: 700;">Rp {{ number_format($netProfit, 0, ',', '.') }}</div>
						<div style="color: #6b7280; font-size: 0.8rem;">Margin: {{ number_format($netMargin, 2) }}%</div>
					</div>
				</div>

				<div style="border-top: 1px solid rgba(251,191,36,0.3); padding-top: 20px;">
					<h3 style="color: #1e293b; font-size: 1rem; margin-bottom: 16px;">Rincian Beban Operasional</h3>
					@if (!empty($expensesByCategory))
						<table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
							<thead>
								<tr style="border-bottom: 1px solid rgba(251,191,36,0.3); color:#9ca3af;">
									<th style="text-align:left; padding:10px;">Kategori</th>
									<th style="text-align:right; padding:10px;">Nominal</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($expensesByCategory as $kategori => $nominal)
									<tr style="border-bottom: 1px solid rgba(15,23,42,0.8);">
										<td style="padding:10px; color: #1e293b;">{{ $kategori }}</td>
										<td style="padding:10px; text-align:right; color: #dc2626;">Rp {{ number_format($nominal, 0, ',', '.') }}</td>
									</tr>
								@endforeach
								<tr style="border-bottom: 1px solid rgba(251,191,36,0.3);">
									<td style="padding:10px; color: #1e293b; font-weight: 600;">Total Beban</td>
									<td style="padding:10px; text-align:right; color: #dc2626; font-weight: 600;">Rp {{ number_format($totalOperatingExpenses, 0, ',', '.') }}</td>
								</tr>
							</tbody>
						</table>
					@else
						<div style="text-align: center; color: #64748b; padding: 20px;">Tidak ada data beban operasional.</div>
					@endif
				</div>
			</div>
		</div>
	</section>
@endsection
