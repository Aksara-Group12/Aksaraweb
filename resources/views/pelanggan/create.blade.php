@extends('layouts.horizon')

@section('page_title', 'Tambah Pelanggan')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Tambah Pelanggan</h2>
			<p class="hz-top-sub">Tambah data pelanggan baru.</p>
		</div>
	</div>

	<form action="{{ route('pelanggan.store') }}" method="POST" style="margin-top: 24px;">
		@csrf
		<section class="hz-secondary-grid">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Informasi Pelanggan</p>
				</div>
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Nama <span style="color: #fbbf24;">*</span></label>
						<input type="text" name="nama" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
					</div>
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Kontak</label>
						<input type="text" name="kontak" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
					</div>
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Email</label>
						<input type="email" name="email" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
					</div>
					<div style="display: flex; flex-direction: column; gap: 6px; grid-column: 1 / -1;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Alamat</label>
						<textarea name="alamat" rows="3" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem; resize: vertical;"></textarea>
					</div>
					<div style="display: flex; flex-direction: column; gap: 6px; grid-column: 1 / -1;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Catatan</label>
						<textarea name="catatan" rows="2" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem; resize: vertical;"></textarea>
					</div>
				</div>
			</div>
		</section>
		<div style="margin-top: 24px; display: flex; gap: 8px;">
			<button type="submit" class="hz-btn hz-btn-primary">Simpan</button>
			<a href="{{ route('pelanggan.index') }}" class="hz-btn hz-btn-secondary">Batal</a>
		</div>
	</form>
@endsection
