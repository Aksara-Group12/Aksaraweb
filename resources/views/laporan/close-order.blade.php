@extends('layouts.horizon')

@section('page_title', 'Laporan Close Order')

@section('content')
<div class="hz-top-row">
	<div>
		<h2 class="hz-top-title">Laporan Close Order</h2>
		<p class="hz-top-sub">Riwayat shift dan pendapatan POS Kasir</p>
	</div>
	<div style="display: flex; gap: 12px;">
		<form action="{{ route('close-order.export.excel') }}" method="GET" style="display: inline;">
			@csrf
			<button type="submit" class="hz-btn hz-btn-success">
				Export Excel
			</button>
		</form>
	</div>
</div>

<div class="hz-card" style="margin-top: 24px;">
	<div class="hz-card-header">
		<p class="hz-card-title">Riwayat Shift</p>
	</div>
	<div style="overflow-x: auto;">
		<table style="width: 100%; border-collapse: collapse;">
			<thead>
				<tr style="background: rgba(59, 130, 246, 0.1);">
					<th style="padding: 12px; text-align: left; border-bottom: 1px solid rgba(59, 130, 246, 0.2);">ID</th>
					<th style="padding: 12px; text-align: left; border-bottom: 1px solid rgba(59, 130, 246, 0.2);">Operator</th>
					<th style="padding: 12px; text-align: left; border-bottom: 1px solid rgba(59, 130, 246, 0.2);">Waktu Buka</th>
					<th style="padding: 12px; text-align: left; border-bottom: 1px solid rgba(59, 130, 246, 0.2);">Waktu Tutup</th>
					<th style="padding: 12px; text-align: left; border-bottom: 1px solid rgba(59, 130, 246, 0.2);">Durasi</th>
					<th style="padding: 12px; text-align: right; border-bottom: 1px solid rgba(59, 130, 246, 0.2);">Total Pendapatan</th>
					<th style="padding: 12px; text-align: right; border-bottom: 1px solid rgba(59, 130, 246, 0.2);">Jumlah Transaksi</th>
					<th style="padding: 12px; text-align: center; border-bottom: 1px solid rgba(59, 130, 246, 0.2);">Status</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($shifts as $shift)
					<tr>
						<td style="padding: 12px; border-bottom: 1px solid rgba(59, 130, 246, 0.1);">{{ $shift->id }}</td>
						<td style="padding: 12px; border-bottom: 1px solid rgba(59, 130, 246, 0.1);">{{ $shift->operator_name }}</td>
						<td style="padding: 12px; border-bottom: 1px solid rgba(59, 130, 246, 0.1);">{{ $shift->opened_at ? $shift->opened_at->format('d/m/Y H:i:s') : '-' }}</td>
						<td style="padding: 12px; border-bottom: 1px solid rgba(59, 130, 246, 0.1);">{{ $shift->closed_at ? $shift->closed_at->format('d/m/Y H:i:s') : '-' }}</td>
						<td style="padding: 12px; border-bottom: 1px solid rgba(59, 130, 246, 0.1);">
							@if ($shift->closed_at && $shift->opened_at)
								{{ $shift->closed_at->diffInMinutes($shift->opened_at) }} menit
							@else
								-
							@endif
						</td>
						<td style="padding: 12px; border-bottom: 1px solid rgba(59, 130, 246, 0.1); text-align: right; font-weight: 600; color: #22c55e;">Rp {{ number_format($shift->total_revenue, 0, ',', '.') }}</td>
						<td style="padding: 12px; border-bottom: 1px solid rgba(59, 130, 246, 0.1); text-align: right;">{{ $shift->total_transactions }}</td>
						<td style="padding: 12px; border-bottom: 1px solid rgba(59, 130, 246, 0.1); text-align: center;">
							@if ($shift->status === 'open')
								<span style="padding: 4px 12px; border-radius: 999px; background: rgba(34, 197, 94, 0.2); color: #16a34a; font-size: 0.8rem; font-weight: 600;">Open</span>
							@else
								<span style="padding: 4px 12px; border-radius: 999px; background: rgba(239, 68, 68, 0.2); color: #ef4444; font-size: 0.8rem; font-weight: 600;">Closed</span>
							@endif
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="9" style="padding: 40px; text-align: center; color: #64748b;">Belum ada data shift</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
	<div style="margin-top: 16px; padding: 12px;">
		{{ $shifts->links() }}
	</div>
</div>
@endsection
