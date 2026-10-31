@extends('layouts.horizon')

@section('page_title', 'Edit Transaksi Keluar')
@section('page_subtitle', 'Perbarui informasi header transaksi keluar (status, customer, pembayaran).')

@section('content')
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

	<form action="{{ route('transaksi.keluar.update', $transaksiKeluar->id) }}" method="POST" style="margin-top: 24px;">
		@csrf
		@method('PUT')

		<section class="hz-secondary-grid">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Informasi Transaksi</p>
				</div>

				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">No. Invoice</label>
						<input type="text" value="{{ $transaksiKeluar->invoice_no }}" readonly style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.2); background: rgba(255,255,255,0.5); color: #64748b; font-size: 0.9rem;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Tanggal Transaksi <span style="color: #3b82f6;">*</span></label>
						<input type="date" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', optional($transaksiKeluar->tanggal_transaksi)->format('Y-m-d')) }}" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Status <span style="color: #3b82f6;">*</span></label>
						@php($status = old('status', $transaksiKeluar->status))
						<select name="status" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
							<option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
							<option value="complete" {{ $status === 'complete' ? 'selected' : '' }}>Complete</option>
							<option value="cancel" {{ $status === 'cancel' ? 'selected' : '' }}>Cancel</option>
						</select>
					</div>
				</div>
			</div>
		</section>

		<section class="hz-secondary-grid" style="margin-top: 20px;">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Informasi Customer</p>
				</div>

				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Nama Customer <span style="color: #3b82f6;">*</span></label>
						<input type="text" name="customer_name" value="{{ old('customer_name', $transaksiKeluar->customer_name) }}" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">No. Telepon Customer</label>
						<input type="text" name="customer_contact" value="{{ old('customer_contact', $transaksiKeluar->customer_contact) }}" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Lokasi (Kota/Kabupaten)</label>
						<input type="text" name="lokasi" value="{{ old('lokasi', $transaksiKeluar->lokasi) }}" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>
				</div>
			</div>
		</section>

		<section class="hz-secondary-grid" style="margin-top: 20px;">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Pembayaran & Catatan</p>
				</div>

				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Metode Pembayaran <span style="color: #3b82f6;">*</span></label>
						@php($metode = old('metode_pembayaran', $transaksiKeluar->metode_pembayaran))
						<select name="metode_pembayaran" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
							<option value="">-- Pilih --</option>
							<option value="cash" {{ $metode === 'cash' ? 'selected' : '' }}>Cash</option>
							<option value="non-cash" {{ $metode === 'non-cash' ? 'selected' : '' }}>Non-Cash</option>
						</select>
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Nominal Bayar (Rp)</label>
						<input type="number" name="nominal_bayar" value="{{ old('nominal_bayar', $transaksiKeluar->nominal_bayar ?? 0) }}" step="0.01" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>
				</div>

				<div style="margin-top: 16px; display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Catatan</label>
					<textarea name="catatan" style="padding: 12px 14px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem; height: 100px; resize: vertical;">{{ old('catatan', $transaksiKeluar->catatan) }}</textarea>
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
