@extends('layouts.horizon')

@section('page_title', 'Buka Shift')
@section('page_subtitle', 'Input modal awal kas untuk shift baru.' . (session()->has('admin_name') ? ' Kasir: ' . session('admin_name') : ''))

@section('content')
	<section class="hz-secondary-grid" style="margin-top: 10px;">
		<div class="hz-card" style="grid-column: 1 / -1; max-width: 600px; margin: 0 auto;">
			<div class="hz-card-header" style="margin-bottom: 16px;">
				<p class="hz-card-title">Form Buka Shift</p>
			</div>

			<form method="POST" action="{{ route('shift.store') }}">
				@csrf
				<div style="display: flex; flex-direction: column; gap: 16px;">
					<div style="background: rgba(251, 191, 36, 0.1); border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 8px; padding: 12px; margin-bottom: 8px;">
						<p style="color: #b45309; font-size: 0.85rem; margin: 0; font-weight: 500;">⚠️ Perhatian:</p>
						<p style="color: #92400e; font-size: 0.8rem; margin: 4px 0 0 0;">Modal awal hanya untuk tracking kas fisik di awal shift. Nilai ini tidak dihitung sebagai pendapatan atau pengeluaran di laporan keuangan.</p>
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e3a8a;">Modal Awal (Rp)</label>
						<input type="number" name="modal_awal" required min="0" step="0.01" placeholder="0" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.9); color: #1e293b; font-size: 0.9rem;">
						<p style="color: #64748b; font-size: 0.8rem;">Jumlah uang kas saat mulai shift.</p>
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e3a8a;">Catatan (Opsional)</label>
						<textarea name="catatan" rows="3" placeholder="Catatan tambahan..." style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.9); color: #1e293b; font-size: 0.9rem; resize: vertical;"></textarea>
					</div>

					<div style="display: flex; gap: 8px; margin-top: 8px;">
						<button type="submit" class="hz-btn hz-btn-primary" style="flex: 1;">Buka Shift</button>
						<a href="{{ route('shift.index') }}" class="hz-btn hz-btn-secondary">Batal</a>
					</div>
				</div>
			</form>
		</div>
	</section>
@endsection
