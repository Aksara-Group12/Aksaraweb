@extends('layouts.horizon')

@section('page_title', 'Laporan Close Order')

@section('header_content')
	<div>
		<h1 style="font-family: 'Geist', 'Geist Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 1.45rem; font-weight: 600; color: #0F223D; margin: 0; line-height: 1.2; letter-spacing: -0.02em;">
			Laporan Close Order
		</h1>
		<p style="font-family: 'Inter', sans-serif; font-size: 0.8rem; color: #64748B; margin: 3px 0 0 0;">
			Rekapitulasi riwayat shift kasir, durasi operasional, dan total pendapatan penjualan POS.
		</p>
	</div>
@endsection

@section('content')
@php
	$userId = auth()->id();
	$totalShiftsCount = $shifts->total();
	$activeShift = \App\Models\CloseOrderShift::where('user_id', $userId)->where('status', 'open')->first();
	$totalClosedRevenue = \App\Models\CloseOrderShift::where('user_id', $userId)->where('status', 'closed')->sum('total_revenue');
	$totalCompletedShifts = \App\Models\CloseOrderShift::where('user_id', $userId)->where('status', 'closed')->count();
@endphp

<!-- Bar Aksi & KPI Ringkasan Cepat -->
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
	<!-- 3 Mini Summary Badges -->
	<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; flex: 1; max-width: 640px;">
		<!-- 1. Total Shift -->
		<div style="background: #FFFFFF; border: 1px solid rgba(226, 232, 240, 0.95); border-radius: 14px; padding: 14px 18px; box-shadow: 0 2px 8px rgba(15, 34, 61, 0.03);">
			<p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin: 0; letter-spacing: 0.04em;">Total Sesi Shift</p>
			<p style="font-size: 1.35rem; font-weight: 800; color: #0F223D; margin: 4px 0 0 0; line-height: 1.2;">
				{{ number_format($totalShiftsCount, 0, ',', '.') }} <span style="font-size: 0.8rem; font-weight: 500; color: #94A3B8;">Sesi</span>
			</p>
		</div>

		<!-- 2. Status Shift Aktif -->
		<div style="background: #FFFFFF; border: 1px solid rgba(226, 232, 240, 0.95); border-radius: 14px; padding: 14px 18px; box-shadow: 0 2px 8px rgba(15, 34, 61, 0.03);">
			<p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin: 0; letter-spacing: 0.04em;">Status Shift Kasir</p>
			<div style="display: flex; align-items: center; gap: 8px; margin-top: 6px;">
				@if($activeShift)
					<span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #10B981; box-shadow: 0 0 8px #10B981;"></span>
					<span style="font-size: 0.95rem; font-weight: 700; color: #065F46;">Sedang Aktif</span>
				@else
					<span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #94A3B8;"></span>
					<span style="font-size: 0.95rem; font-weight: 700; color: #64748B;">Tidak Ada Shift Aktif</span>
				@endif
			</div>
		</div>

		<!-- 3. Total Omset Shift Closed -->
		<div style="background: #FFFFFF; border: 1px solid rgba(226, 232, 240, 0.95); border-radius: 14px; padding: 14px 18px; box-shadow: 0 2px 8px rgba(15, 34, 61, 0.03);">
			<p style="font-size: 0.72rem; font-weight: 600; text-transform: uppercase; color: #64748B; margin: 0; letter-spacing: 0.04em;">Total Omset Rekap</p>
			<p style="font-size: 1.35rem; font-weight: 800; color: #0F223D; margin: 4px 0 0 0; line-height: 1.2;">
				<span style="font-size: 0.95rem; font-weight: 600; color: #C5A059;">Rp</span> {{ number_format($totalClosedRevenue, 0, ',', '.') }}
			</p>
		</div>
	</div>

	<!-- Action Button Export Excel -->
	<div>
		<form action="{{ route('close-order.export.excel') }}" method="GET" style="margin: 0;">
			<button type="submit" 
				style="display: inline-flex; align-items: center; gap: 8px; background: #0F223D; color: #FFFFFF; font-weight: 600; font-size: 0.85rem; padding: 11px 20px; border-radius: 10px; border: none; cursor: pointer; box-shadow: 0 2px 8px rgba(15, 34, 61, 0.15); transition: all 0.2s ease;"
				onmouseover="this.style.background='#162C50'; this.style.boxShadow='0 0 16px rgba(197, 160, 89, 0.3)';" 
				onmouseout="this.style.background='#0F223D'; this.style.boxShadow='0 2px 8px rgba(15, 34, 61, 0.15)';">
				<i data-lucide="file-spreadsheet" style="width: 17px; height: 17px; color: #10B981;"></i>
				<span>Export Excel</span>
			</button>
		</form>
	</div>
</div>

<!-- Card Utama: Riwayat Shift Kasir -->
<div style="background: #FFFFFF; border-radius: 18px; border: 1px solid rgba(226, 232, 240, 0.95); box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04); overflow: hidden;">
	<!-- Header Card -->
	<div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #F1F5F9; flex-wrap: wrap; gap: 10px;">
		<div>
			<h3 style="font-family: 'Geist', 'Geist Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 1.2rem; font-weight: 700; color: #0F223D; margin: 0; line-height: 1.2; letter-spacing: -0.015em;">
				Riwayat Rekap Shift
			</h3>
			<p style="font-size: 0.8rem; color: #64748B; margin: 3px 0 0 0;">
				Daftar seluruh rekapitulasi shift kasir beserta durasi dan pendapatan riil per sesi.
			</p>
		</div>
		<span style="font-size: 0.75rem; font-weight: 700; background: #F8FAFC; color: #475569; padding: 5px 12px; border-radius: 8px; border: 1px solid #E2E8F0;">
			{{ $totalShiftsCount }} Data Shift
		</span>
	</div>

	<!-- Tabel Data Shift -->
	<div style="overflow-x: auto;">
		<table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
			<thead>
				<tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #64748B; font-size: 0.73rem; text-transform: uppercase; letter-spacing: 0.05em;">
					<th style="padding: 13px 18px; text-align: left; font-weight: 600;">ID Sesi</th>
					<th style="padding: 13px 18px; text-align: left; font-weight: 600;">Operator / Kasir</th>
					<th style="padding: 13px 18px; text-align: left; font-weight: 600;">Waktu Buka</th>
					<th style="padding: 13px 18px; text-align: left; font-weight: 600;">Waktu Tutup</th>
					<th style="padding: 13px 18px; text-align: center; font-weight: 600;">Durasi</th>
					<th style="padding: 13px 18px; text-align: right; font-weight: 600;">Total Omset</th>
					<th style="padding: 13px 18px; text-align: center; font-weight: 600;">Jumlah Transaksi</th>
					<th style="padding: 13px 18px; text-align: center; font-weight: 600;">Status</th>
				</tr>
			</thead>
			<tbody>
				@forelse ($shifts as $shift)
					<tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease;" onmouseover="this.style.background='#FAF8F5'" onmouseout="this.style.background='transparent'">
						<!-- ID Sesi -->
						<td style="padding: 14px 18px; font-weight: 600; color: #64748B; font-family: monospace; font-size: 0.85rem;">
							#{{ str_pad($shift->id, 4, '0', STR_PAD_LEFT) }}
						</td>

						<!-- Operator -->
						<td style="padding: 14px 18px;">
							<div style="display: flex; align-items: center; gap: 8px;">
								<div style="width: 28px; height: 28px; border-radius: 9999px; background: #0F223D; color: #C5A059; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.72rem; flex-shrink: 0;">
									{{ strtoupper(substr($shift->operator_name ?? 'K', 0, 1)) }}
								</div>
								<span style="font-weight: 600; color: #0F223D;">{{ $shift->operator_name ?? 'Kasir' }}</span>
							</div>
						</td>

						<!-- Waktu Buka -->
						<td style="padding: 14px 18px; color: #475569; white-space: nowrap;">
							<div style="display: flex; align-items: center; gap: 6px;">
								<i data-lucide="clock" style="width: 14px; height: 14px; color: #94A3B8;"></i>
								<span>{{ $shift->opened_at ? $shift->opened_at->format('d/m/Y H:i') : '-' }}</span>
							</div>
						</td>

						<!-- Waktu Tutup -->
						<td style="padding: 14px 18px; color: #475569; white-space: nowrap;">
							@if($shift->closed_at)
								<div style="display: flex; align-items: center; gap: 6px;">
									<i data-lucide="check-circle-2" style="width: 14px; height: 14px; color: #10B981;"></i>
									<span>{{ $shift->closed_at->format('d/m/Y H:i') }}</span>
								</div>
							@else
								<span style="color: #94A3B8; font-style: italic;">Sedang Berlangsung...</span>
							@endif
						</td>

						<!-- Durasi -->
						<td style="padding: 14px 18px; text-align: center;">
							@if ($shift->closed_at && $shift->opened_at)
								@php
									$minutes = $shift->closed_at->diffInMinutes($shift->opened_at);
									$hours = floor($minutes / 60);
									$remainingMinutes = $minutes % 60;
								@endphp
								<span style="background: #F1F5F9; color: #334155; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">
									@if($hours > 0) {{ $hours }}j @endif {{ $remainingMinutes }}m
								</span>
							@else
								<span style="background: #ECFDF5; color: #065F46; padding: 3px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">
									Aktif
								</span>
							@endif
						</td>

						<!-- Total Omset -->
						<td style="padding: 14px 18px; text-align: right; font-weight: 700; color: #0F223D; white-space: nowrap;">
							<span style="color: #C5A059; font-weight: 600; font-size: 0.8rem;">Rp</span> {{ number_format($shift->total_revenue ?? 0, 0, ',', '.') }}
						</td>

						<!-- Jumlah Transaksi -->
						<td style="padding: 14px 18px; text-align: center; font-weight: 600; color: #0F223D;">
							<span style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 3px 10px; border-radius: 6px; font-size: 0.8rem;">
								{{ $shift->total_transactions ?? 0 }}
							</span>
						</td>

						<!-- Status -->
						<td style="padding: 14px 18px; text-align: center; white-space: nowrap;">
							@if ($shift->status === 'open')
								<span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 9999px; background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; font-size: 0.75rem; font-weight: 700;">
									<span style="width: 6px; height: 6px; border-radius: 50%; background: #10B981;"></span>
									<span>Open</span>
								</span>
							@else
								<span style="display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 9999px; background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; font-size: 0.75rem; font-weight: 600;">
									<span>Closed</span>
								</span>
							@endif
						</td>
					</tr>
				@empty
					<!-- Empty State Elegan -->
					<tr>
						<td colspan="8" style="padding: 56px 20px; text-align: center;">
							<div style="max-width: 380px; margin: 0 auto; display: flex; flex-direction: column; align-items: center;">
								<div style="width: 54px; height: 54px; border-radius: 50%; background: #FAF8F5; border: 1.5px dashed rgba(197, 160, 89, 0.4); display: flex; align-items: center; justify-content: center; margin-bottom: 16px; color: #C5A059;">
									<i data-lucide="clock-3" style="width: 26px; height: 26px;"></i>
								</div>
								<h4 style="font-family: 'Geist', 'Geist Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 1.1rem; font-weight: 700; color: #0F223D; margin: 0 0 6px 0; letter-spacing: -0.015em;">
									Belum Ada Riwayat Close Order
								</h4>
								<p style="font-size: 0.825rem; color: #64748B; margin: 0; line-height: 1.5;">
									Rekap shift kasir beserta total pendapatan akan otomatis tercatat di tabel ini setiap kali kasir melakukan Tutup Shift di POS.
								</p>
							</div>
						</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>

	<!-- Pagination -->
	@if($shifts->hasPages())
		<div style="padding: 16px 24px; border-top: 1px solid #F1F5F9; background: #FFFFFF; display: flex; justify-content: flex-end;">
			{{ $shifts->links() }}
		</div>
	@endif
</div>
@endsection
