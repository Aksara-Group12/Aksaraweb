@extends('layouts.horizon')

@section('page_title', 'Edit Pengeluaran')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Edit Pengeluaran</h2>
			<p class="hz-top-sub">Perbarui informasi pengeluaran yang sudah tercatat.</p>
		</div>
	</div>

	@if ($errors->any())
		<div style="margin-top: 20px; padding: 14px 16px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 10px; color: #dc2626;">
			<p style="font-weight: 600; margin-bottom: 8px;">❌ Terjadi kesalahan validasi:</p>
			<ul style="margin: 0; padding-left: 20px;">
				@foreach ($errors->all() as $error)
					<li style="font-size: 0.9rem;">{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	@if ($message = Session::get('error'))
		<div style="margin-top: 20px; padding: 14px 16px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 10px; color: #dc2626;">
			{{ $message }}
		</div>
	@endif

	<form action="{{ route('expenses.update', $expense->id) }}" method="POST" style="margin-top: 24px;">
		@csrf
		@method('PUT')

		<section class="hz-secondary-grid">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Informasi Pengeluaran</p>
				</div>

				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Tanggal <span style="color: #3b82f6;">*</span></label>
						<input type="date" name="tanggal" value="{{ old('tanggal', optional($expense->tanggal)->format('Y-m-d')) }}" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Kategori</label>
						<input type="text" name="kategori" value="{{ old('kategori', $expense->kategori) }}" placeholder="Listrik, sewa, gaji, dll" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Nominal (Rp) <span style="color: #3b82f6;">*</span></label>
						<input type="number" name="nominal" value="{{ old('nominal', $expense->nominal) }}" min="0" step="0.01" placeholder="0" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Metode Pembayaran <span style="color: #3b82f6;">*</span></label>
						@php($metode = old('metode_pembayaran', $expense->metode_pembayaran))
						<select name="metode_pembayaran" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
							<option value="">-- Pilih --</option>
							<option value="cash" {{ $metode === 'cash' ? 'selected' : '' }}>Cash</option>
							<option value="non-cash" {{ $metode === 'non-cash' ? 'selected' : '' }}>Non-Cash</option>
						</select>
					</div>
				</div>
			</div>
		</section>

		<section class="hz-secondary-grid" style="margin-top: 20px;">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Keterangan</p>
				</div>
				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Catatan / Keterangan</label>
					<textarea name="keterangan" placeholder="Contoh: Bayar listrik bulan Maret, sewa toko, dll" style="padding: 12px 14px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem; height: 100px; resize: vertical;">{{ old('keterangan', $expense->keterangan) }}</textarea>
				</div>
			</div>
		</section>

		<section class="hz-secondary-grid" style="margin-top: 24px; margin-bottom: 40px;">
			<div style="grid-column: 1 / -1; display: flex; gap: 12px; justify-content: flex-end;">
				<button type="button" class="hz-btn-outline" onclick="window.history.back()">
					<span>Batal</span>
				</button>
				<button type="submit" class="hz-btn-primary">
					<span>Simpan Perubahan</span>
				</button>
			</div>
		</section>
	</form>
@endsection
