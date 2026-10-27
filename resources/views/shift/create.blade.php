@extends('layouts.horizon')

@section('page_title', 'Buka Shift')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Buka Shift</h2>
			<p class="hz-top-sub">Input modal awal kas untuk shift baru.</p>
		</div>
	</div>

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1; max-width: 600px; margin: 0 auto;">
			<div class="hz-card-header" style="margin-bottom: 16px;">
				<p class="hz-card-title">Form Buka Shift</p>
			</div>

			<form method="POST" action="{{ route('shift.store') }}">
				@csrf
				<div style="display: flex; flex-direction: column; gap: 16px;">
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Modal Awal (Rp)</label>
						<input type="number" name="modal_awal" required min="0" step="0.01" placeholder="0" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
						<p style="color: #9ca3af; font-size: 0.8rem;">Jumlah uang kas saat mulai shift.</p>
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Catatan (Opsional)</label>
						<textarea name="catatan" rows="3" placeholder="Catatan tambahan..." style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem; resize: vertical;"></textarea>
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
