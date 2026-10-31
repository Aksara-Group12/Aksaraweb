@extends('layouts.horizon')

@section('page_title', 'Edit Produk')

@section('content')
<div class="hz-top-row">
	<div>
		<h2 class="hz-top-title">Edit Produk</h2>
		<p class="hz-top-sub">Perbarui informasi produk. Mengedit harga jual tidak mengubah riwayat transaksi terdahulu.</p>
	</div>
	<div class="hz-top-actions">
		<a href="{{ route('produk.index') }}" class="hz-btn hz-btn-secondary">
			← Kembali
		</a>
	</div>
</div>

@if ($errors->any())
	<div style="margin-top: 20px; padding: 14px 18px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 12px; color: #991B1B;">
		<p style="font-weight: 600; margin-bottom: 8px;">Perhatian Pengisian Produk:</p>
		<ul style="margin: 0; padding-left: 20px;">
			@foreach ($errors->all() as $error)
				<li style="font-size: 0.875rem;">{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

@if ($message = Session::get('error'))
	<div style="margin-top: 20px; padding: 14px 18px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 12px; color: #991B1B; font-size: 0.875rem;">
		{{ $message }}
	</div>
@endif

@if ($message = Session::get('success'))
	<div style="margin-top: 20px; padding: 14px 18px; background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 12px; color: #065F46; font-size: 0.875rem; font-weight: 500;">
		{{ $message }}
	</div>
@endif

<form action="{{ route('produk.update', $product->id) }}" method="POST" style="margin-top: 24px;">
	@csrf
	@method('PUT')

	<section class="hz-secondary-grid">
		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 16px;">
				<p class="hz-card-title">Informasi Produk</p>
			</div>

			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Kode Produk</label>
					<input type="text" value="{{ $product->code }}" readonly
					       style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(255,255,255,0.4); color: #64748b; font-size: 0.9rem;">
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Nama Produk <span style="color: #3b82f6;">*</span></label>
					<input type="text" name="nama_produk" value="{{ old('nama_produk', $product->nama_produk) }}" required
					       style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Kategori Produk</label>
					<select name="kategori_produk_id" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
						<option value="">-- Pilih Kategori --</option>
						@foreach($kategoriProduk as $kat)
							<option value="{{ $kat->id }}" {{ old('kategori_produk_id', $product->kategori_produk_id) == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
						@endforeach
					</select>
					<small style="color: #64748b; font-size: 0.8rem;">Opsional - data master kategori</small>
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Kategori (Free Text)</label>
					<input type="text" name="kategori" value="{{ old('kategori', $product->kategori) }}" placeholder="Contoh: Makanan, Minuman, Pakaian"
					       style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					<small style="color: #64748b; font-size: 0.8rem;">Opsional - fallback jika tidak ada di dropdown</small>
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
n				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Tipe Produk <span style="color: #3b82f6;">*</span></label>
					<select name="tipe" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
						<option value="Barang" {{ old('tipe', $product->tipe) == 'Barang' ? 'selected' : '' }}>Barang (beli jadi, jual lagi)</option>
						<option value="Menu" {{ old('tipe', $product->tipe) == 'Menu' ? 'selected' : '' }}>Menu (makanan/minuman olahan)</option>
						<option value="Jasa" {{ old('tipe', $product->tipe) == 'Jasa' ? 'selected' : '' }}>Jasa (servis/jasa)</option>
					</select>
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Lacak Stok?</label>
					<div style="display: flex; align-items: center; gap: 8px;">
						<input type="checkbox" name="lacak_stok" value="1" {{ old('lacak_stok', $product->lacak_stok) ? 'checked' : ($product->lacak_stok ? 'checked' : '') }} id="lacak_stok"
						       style="width: 18px; height: 18px; cursor: pointer;">
						<label for="lacak_stok" style="font-size: 0.85rem; color: #64748b; cursor: pointer;">Ya, lacak stok</label>
					</div>
					<small style="color: #64748b; font-size: 0.8rem;">Centang jika perlu tracking inventory (contoh: Barang jual, Menu dengan bahan, Jasa spa dengan bahan pijat)</small>
				</div>
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Satuan (Unit) <span style="color: #3b82f6;">*</span></label>
					<input type="text" name="unit" value="{{ old('unit', $product->unit) }}" required
					       style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
				</div>
			</div>
		</div>

		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 16px;">
				<p class="hz-card-title">Harga & Stok</p>
			</div>

			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Harga Jual <span style="color: #3b82f6;">*</span></label>
					<div style="display: flex; align-items: center; background: rgba(255,255,255,0.95); border: 1px solid rgba(59,130,246,0.4); border-radius: 10px;">
						<span style="padding: 10px 12px; color: #64748b;">Rp</span>
						<input type="number" name="harga_jual" value="{{ old('harga_jual', $product->harga_jual) }}" required min="0" step="0.01"
						       style="flex: 1; padding: 10px 12px; border: none; background: transparent; color: #1e293b; font-size: 0.9rem;">
					</div>
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Harga Beli / Modal Referensi</label>
					<div style="display: flex; align-items: center; background: rgba(255,255,255,0.95); border: 1px solid rgba(148,163,184,0.4); border-radius: 10px;">
						<span style="padding: 10px 12px; color: #64748b;">Rp</span>
						<input type="number" name="harga_beli" value="{{ old('harga_beli', $product->harga_beli) }}" min="0" step="0.01"
						       style="flex: 1; padding: 10px 12px; border: none; background: transparent; color: #1e293b; font-size: 0.9rem;">
					</div>
					<small style="color: #64748b; font-size: 0.8rem;">Harga beli pada master produk hanya referensi, bukan harga aktual transaksi</small>
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Stok</label>
					<input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0"
					       style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Stok Minimum</label>
					<input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" min="0"
					       style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #e7e7;">Status</label>
					<select name="is_active" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
						<option value="1" {{ old('is_active', $product->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
						<option value="0" {{ old('is_active', $product->is_active) == 0 ? 'selected' : '' }}>Non-aktif</option>
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
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Lokasi</label>
					<input type="text" name="lokasi" value="{{ old('lokasi', $product->lokasi) }}"
					       style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Barcode</label>
					<input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}"
					       style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
				</div>

				<div style="display: flex; flex-direction: column; gap: 6px; grid-column: 1 / -1;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Deskripsi</label>
					<textarea name="description" rows="3" placeholder="Deskripsi produk..."
					          style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem; resize: vertical;">{{ old('description', $product->description) }}</textarea>
				</div>
			</div>
		</div>

		<div style="margin-top: 24px; display: flex; gap: 12px; align-items: center;">
			<button type="submit" style="background: #0F223D; color: #FFFFFF; font-weight: 600; padding: 11px 22px; border-radius: 10px; border: none; cursor: pointer; box-shadow: 0 2px 6px rgba(15,34,61,0.15); font-size: 0.9rem; transition: all 0.15s ease;">
				Simpan Perubahan
			</button>
			<a href="{{ route('produk.index') }}" style="background: #FFFFFF; color: #475569; font-weight: 600; padding: 10px 20px; border-radius: 10px; border: 1px solid #CBD5E1; text-decoration: none; font-size: 0.9rem; transition: all 0.15s ease;">
				Batal
			</a>
		</div>
	</section>
</form>
@endsection
