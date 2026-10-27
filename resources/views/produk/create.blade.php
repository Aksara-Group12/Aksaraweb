@extends('layouts.horizon')

@section('page_title', 'Tambah Produk')

@section('content')
<div class="hz-top-row">
	<div>
		<h2 class="hz-top-title">Tambah Produk Baru</h2>
		<p class="hz-top-sub">Tambah produk baru ke katalog. Menambah produk tidak otomatis menambah stok.</p>
	</div>
	<div class="hz-top-actions">
		<a href="{{ route('produk.index') }}" class="hz-btn hz-btn-secondary">
			← Kembali
		</a>
	</div>
</div>

@if ($errors->any())
	<div style="margin-top: 20px; padding: 14px 16px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 10px; color: #fca5a5;">
		<p style="font-weight: 600; margin-bottom: 8px;">Terjadi kesalahan validasi:</p>
		<ul style="margin: 0; padding-left: 20px;">
			@foreach ($errors->all() as $error)
				<li style="font-size: 0.9rem;">{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

<form action="{{ route('produk.store') }}" method="POST" style="margin-top: 24px;">
	@csrf

	<section class="hz-secondary-grid">
		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 16px;">
				<p class="hz-card-title">Informasi Produk</p>
			</div>

			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Nama Produk <span style="color: #fbbf24;">*</span></label>
					<input type="text" name="nama_produk" value="{{ old('nama_produk') }}" required
					       style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Kode Produk / SKU</label>
					<input type="text" name="code" value="{{ old('code') }}" placeholder="Otomatis jika kosong"
					       style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Kategori</label>
					<input type="text" name="kategori" value="{{ old('kategori') }}" placeholder="Contoh: Makanan, Minuman, Pakaian"
					       style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Satuan (Unit) <span style="color: #fbbf24;">*</span></label>
					<input type="text" name="unit" value="{{ old('unit') ?? 'pcs' }}" required placeholder="Contoh: pcs, kg, gram, liter"
					       style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
				</div>
			</div>
		</div>

		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 16px;">
				<p class="hz-card-title">Harga & Stok</p>
			</div>

			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Harga Jual <span style="color: #fbbf24;">*</span></label>
					<div style="display: flex; align-items: center; background: rgba(15,23,42,0.6); border: 1px solid rgba(251,191,36,0.4); border-radius: 10px;">
						<span style="padding: 10px 12px; color: #9ca3af;">Rp</span>
						<input type="number" name="harga_jual" value="{{ old('harga_jual') }}" required min="0" step="0.01"
						       style="flex: 1; padding: 10px 12px; border: none; background: transparent; color: #e5e7eb; font-size: 0.9rem;">
					</div>
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Harga Beli / Modal Referensi</label>
					<div style="display: flex; align-items: center; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.4); border-radius: 10px;">
						<span style="padding: 10px 12px; color: #9ca3af;">Rp</span>
						<input type="number" name="harga_beli" value="{{ old('harga_beli') }}" min="0" step="0.01"
						       style="flex: 1; padding: 10px 12px; border: none; background: transparent; color: #e5e7eb; font-size: 0.9rem;">
					</div>
					<small style="color: #9ca3af; font-size: 0.8rem;">Harga beli pada master produk hanya referensi, bukan harga aktual transaksi</small>
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Stok Awal</label>
					<input type="number" name="stock" value="{{ old('stock') }}" min="0"
					       style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
					<small style="color: #9ca3af; font-size: 0.8rem;">Opsional - isi jika Inventory aktif</small>
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Stok Minimum</label>
					<input type="number" name="min_stock" value="{{ old('min_stock') }}" min="0"
					       style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
					<small style="color: #9ca3af; font-size: 0.8rem;">Alert jika stok di bawah jumlah ini</small>
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Status</label>
					<select name="is_active" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
						<option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Aktif</option>
						<option value="0" {{ old('is_active', 1) == 0 ? 'selected' : '' }}>Non-aktif</option>
					</select>
				</div>
			</div>
		</div>

		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 16px;">
				<p class="hz-card-title">Informasi Tambahan</p>
			</div>

			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Lokasi Penyimpanan</label>
					<input type="text" name="lokasi" value="{{ old('lokasi') }}"
					       style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Barcode</label>
					<input type="text" name="barcode" value="{{ old('barcode') }}"
					       style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px; grid-column: 1 / -1;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Deskripsi</label>
					<textarea name="description" rows="3" placeholder="Deskripsi produk..."
					          style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem; resize: vertical;">{{ old('description') }}</textarea>
				</div>
			</div>
		</div>

		<div style="margin-top: 24px; display: flex; gap: 8px;">
			<button type="submit" class="hz-btn hz-btn-primary">
				Simpan Produk
			</button>
			<a href="{{ route('produk.index') }}" class="hz-btn hz-btn-secondary">
				Batal
			</a>
		</div>
	</section>
</form>
@endsection
