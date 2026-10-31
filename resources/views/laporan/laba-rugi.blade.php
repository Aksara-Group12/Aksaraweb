@extends('layouts.horizon')

@section('page_title', 'Laba Rugi Detail')
@section('page_subtitle', 'Laporan profit & loss terperinci dengan HPP dan beban operasional.')

@section('content')
	<div class="hz-top-row" style="display:flex; justify-content:flex-start; align-items:center; margin-bottom: 22px;">
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
			<form method="GET" action="{{ route('laporan.laba-rugi') }}" style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
				<input type="date" name="start_date" value="{{ $startDate }}" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; outline:none;">
				<span style="color:#64748B; font-size:0.85rem;">s/d</span>
				<input type="date" name="end_date" value="{{ $endDate }}" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; outline:none;">
				<button type="submit" style="border-radius:10px; border:none; background:#0F223D; color:#FFFFFF; padding:8px 16px; font-size:0.85rem; font-weight:600; cursor:pointer;">Filter</button>
			</form>
		</div>
	</div>

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1; padding: 24px;">
			<div class="hz-card-header" style="margin-bottom: 20px;">
				<p class="hz-card-title">Ikhtisar Laba Rugi ({{ $startDate }} s/d {{ $endDate }})</p>
			</div>
			
			<div>
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
					<div style="padding: 18px; background: #FAF8F5; border: 1px solid #E2E8F0; border-radius: 12px;">
						<div style="color: #64748B; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Pendapatan Penjualan</div>
						<div style="color: #0F223D; font-size: 1.5rem; font-weight: 800;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
						<div style="color: #64748B; font-size: 0.75rem; margin-top: 4px;">{{ $totalSalesCount }} transaksi</div>
					</div>
					<div style="padding: 18px; background: #FAF8F5; border: 1px solid #E2E8F0; border-radius: 12px;">
						<div style="color: #64748B; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">HPP (COGS)</div>
						<div style="color: #0F223D; font-size: 1.5rem; font-weight: 800;">Rp {{ number_format($totalHPP, 0, ',', '.') }}</div>
						<div style="color: #64748B; font-size: 0.75rem; margin-top: 4px;">Harga Pokok Penjualan</div>
					</div>
					<div style="padding: 18px; background: #FAF8F5; border: 1px solid #E2E8F0; border-radius: 12px;">
						<div style="color: #64748B; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Laba Kotor</div>
						<div style="color: #0F223D; font-size: 1.5rem; font-weight: 800;">Rp {{ number_format($grossProfit, 0, ',', '.') }}</div>
						<div style="color: #059669; font-size: 0.75rem; font-weight: 600; margin-top: 4px;">Margin: {{ number_format($grossMargin, 2) }}%</div>
					</div>
					<div style="padding: 18px; background: #FAF8F5; border: 1px solid #E2E8F0; border-radius: 12px;">
						<div style="color: #64748B; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Laba Bersih</div>
						<div style="color: {{ $netProfit >= 0 ? '#059669' : '#DC2626' }}; font-size: 1.5rem; font-weight: 800;">Rp {{ number_format($netProfit, 0, ',', '.') }}</div>
						<div style="color: #64748B; font-size: 0.75rem; margin-top: 4px;">Margin: {{ number_format($netMargin, 2) }}%</div>
					</div>
				</div>

				<div style="border-top: 1px solid #E2E8F0; padding-top: 20px;">
					<h3 style="color: #0F223D; font-size: 1rem; font-weight: 600; margin-bottom: 16px;">Rincian Beban Operasional</h3>
					@if (!empty($expensesByCategory) && count($expensesByCategory) > 0)
						<table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
							<thead>
								<tr style="border-bottom: 1px solid #E2E8F0; background: #F8FAFC; color: #475569; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">
									<th style="text-align:left; padding:12px 14px; font-weight:600;">Kategori</th>
									<th style="text-align:right; padding:12px 14px; font-weight:600;">Nominal</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($expensesByCategory as $cat => $val)
									<tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease;">
										<td style="padding:12px 14px; color:#0F223D; font-weight:500;">{{ $cat }}</td>
										<td style="padding:12px 14px; text-align:right; color:#0F223D; font-weight:600;">Rp {{ number_format($val, 0, ',', '.') }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					@else
						<p style="color: #64748B; font-size: 0.9rem; padding: 12px 0;">Tidak ada catatan beban operasional pada periode ini.</p>
					@endif
				</div>
			</div>
		</div>
	</section>
@endsection
