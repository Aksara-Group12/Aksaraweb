@extends('layouts.horizon')

@section('page_title', 'Edit Kategori Produk')

@section('content')
<div class="hz-top-row">
	<div>
		<h2 class="hz-top-title">Edit Kategori</h2>
		<p class="hz-top-sub">Perbarui data kategori</p>
	</div>
	<div class="hz-top-actions">
		<a href="{{ route('data-master.kategori-produk.index') }}" class="hz-btn hz-btn-secondary">
			← Kembali
		</a>
	</div>
</div>

<form action="{{ route('data-master.kategori-produk.update', $kategori) }}" method="POST" style="margin-top: 24px;">
	@csrf
	@method('PUT')
	<section class="hz-secondary-grid">
		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 16px;">
				<p class="hz-card-title">Informasi Kategori</p>
			</div>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Nama Kategori <span style="color:#3b82f6;">*</span></label>
					<input type="text" name="name" value="{{ old('name', $kategori->name) }}" required style="padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.4); background:rgba(255,255,255,0.95); color:#1e293b; font-size:0.9rem;">
				</div>
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Status</label>
					<select name="is_active" style="padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.4); background:rgba(255,255,255,0.95); color:#1e293b; font-size:0.9rem;">
						<option value="1" {{ old('is_active', $kategori->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
						<option value="0" {{ old('is_active', $kategori->is_active) == 0 ? 'selected' : '' }}>Non-aktif</option>
					</select>
				</div>
			</div>
		</div>
		<div style="margin-top: 24px; display: flex; gap: 8px;">
			<button type="submit" class="hz-btn hz-btn-primary">Simpan</button>
			<a href="{{ route('data-master.kategori-produk.index') }}" class="hz-btn hz-btn-secondary">Batal</a>
		</div>
	</section>
</form>
@endsection
