@extends('layouts.horizon')

@section('page_title', 'Tutup Shift')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Tutup Shift</h2>
			<p class="hz-top-sub">Rekonsiliasi kas dan tutup shift aktif.</p>
		</div>
	</div>

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1; max-width: 600px; margin: 0 auto;">
			<div class="hz-card-header" style="margin-bottom: 16px;">
				<p class="hz-card-title">Rekonsiliasi Shift: {{ $shift->shift_number }}</p>
			</div>

			<form method="POST" action="{{ route('shift.updateClose', $shift->id) }}">
				@csrf
				<div style="display: flex; flex-direction: column; gap: 16px;">
					<!-- Ringkasan Shift -->
					<div style="background: rgba(15,23,42,0.6); border-radius: 10px; padding: 16px;">
						<div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
							<span style="color: #9ca3af; font-size: 0.9rem;">Tanggal Shift</span>
							<span style="color: #e5e7eb; font-size: 0.9rem;">{{ $shift->tanggal_shift->format('d/m/Y') }}</span>
						</div>
						<div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
							<span style="color: #9ca3af; font-size: 0.9rem;">Waktu Buka</span>
							<span style="color: #e5e7eb; font-size: 0.9rem;">{{ $shift->waktu_buka ? $shift->waktu_buka->format('H:i') : '-' }}</span>
						</div>
						<div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
							<span style="color: #9ca3af; font-size: 0.9rem;">Modal Awal</span>
							<span style="color: #fbbf24; font-weight: 600; font-size: 0.9rem;">Rp {{ number_format($shift->modal_awal, 0, ',', '.') }}</span>
						</div>
						<div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
							<span style="color: #9ca3af; font-size: 0.9rem;">Total Penjualan</span>
							<span style="color: #86efac; font-weight: 600; font-size: 0.9rem;">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
						</div>
						<div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
							<span style="color: #9ca3af; font-size: 0.9rem;">Total Penerimaan (Cash)</span>
							<span style="color: #86efac; font-weight: 600; font-size: 0.9rem;">Rp {{ number_format($totalPenerimaan, 0, ',', '.') }}</span>
						</div>
						<div style="border-top: 1px solid rgba(251,191,36,0.3); margin-top: 8px; padding-top: 8px;">
							<div style="display: flex; justify-content: space-between;">
								<span style="color: #fbbf24; font-weight: 600; font-size: 0.95rem;">Modal Akhir (Diharapkan)</span>
								<span style="color: #fbbf24; font-weight: 700; font-size: 1rem;">Rp {{ number_format($modalAkhir, 0, ',', '.') }}</span>
							</div>
						</div>
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Modal Akhir (Fisik)</label>
						<input type="number" name="modal_akhir" required min="0" step="0.01" value="{{ $modalAkhir }}" placeholder="0" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
						<p style="color: #9ca3af; font-size: 0.8rem;">Jumlah uang kas fisik saat tutup shift.</p>
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Catatan (Opsional)</label>
						<textarea name="catatan" rows="3" placeholder="Catatan rekonsiliasi..." style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem; resize: vertical;">{{ $shift->catatan }}</textarea>
					</div>

					<div style="display: flex; gap: 8px; margin-top: 8px;">
						<button type="submit" class="hz-btn hz-btn-primary" style="flex: 1;">Tutup Shift</button>
						<a href="{{ route('shift.index') }}" class="hz-btn hz-btn-secondary">Batal</a>
					</div>
				</div>
			</form>
		</div>
	</section>
@endsection
