@extends('layouts.horizon')

@section('page_title', 'Edit Produk')

@section('content')
<!-- Modal Edit Produk -->
<div id="produkModal" style="display:flex; position:fixed; inset:0; background:rgba(15,23,42,0.8); z-index:50; align-items:center; justify-content:center;">
	<div style="background:rgba(255,255,255,0.95); border-radius:16px; padding:24px; width:100%; max-width:400px; border:1px solid rgba(59,130,246,0.3); box-shadow:0 18px 45px rgba(15,23,42,0.9);">
		<h3 style="font-size:1.1rem; font-weight:600; color:#1e293b; margin-bottom:16px;">Edit Produk - {{ $product->nama_produk }}</h3>
		<form action="{{ route('produk.update', $product->id) }}" method="POST" id="produkForm">
			@csrf
			@method('PUT')
			@if ($errors->any())
				<div style="margin-bottom: 16px; padding: 12px 14px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 10px; color: #dc2626; font-size: 0.85rem;">
					@foreach ($errors->all() as $error)
						<div>{{ $error }}</div>
					@endforeach
				</div>
			@endif
			<div style="display:flex; flex-direction:column; gap:16px;">
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Nama Produk <span style="color:#3b82f6;">*</span></label>
					<input type="text" name="nama_produk" value="{{ old('nama_produk', $product->nama_produk) }}" required style="padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.4); background:rgba(255,255,255,0.95); color:#1e293b; font-size:0.9rem;">
				</div>
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Kategori Produk</label>
					<input type="text" value="{{ $product->kategori }}" readonly style="padding:10px 12px; border-radius:10px; border:1px solid rgba(148,163,184,0.4); background:rgba(255,255,255,0.4); color:#64748b; font-size:0.9rem;">
				</div>
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Harga Jual (Rp) <span style="color:#3b82f6;">*</span></label>
					<input type="text" name="harga_jual_display" id="harga_jual_display" value="{{ old('harga_jual') ? number_format(old('harga_jual'), 0, '', '.') : number_format($product->harga_jual, 0, '', '.') }}" required min="0" style="padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.4); background:rgba(255,255,255,0.95); color:#1e293b; font-size:0.9rem;" placeholder="0">
					<input type="hidden" name="harga_jual" id="harga_jual" value="{{ old('harga_jual', $product->harga_jual) }}">
				</div>
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Tambah Stok <span style="color:#3b82f6;">*</span></label>
					<input type="number" name="stock" value="{{ old('stock') }}" required min="0" placeholder="Masukkan jumlah stok yang ditambahkan" style="padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.4); background:rgba(255,255,255,0.95); color:#1e293b; font-size:0.9rem;">
					<small style="color:#64748b; font-size:0.8rem;">Stok saat ini: {{ $product->stock }}</small>
				</div>
				<div style="display:flex; gap:8px; justify-content:flex-end; margin-top:8px;">
					<a href="{{ route('produk.index') }}" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:transparent; color:#1e293b; padding:8px 16px; font-size:0.85rem; cursor:pointer; text-decoration:none;">Batal</a>
					<button type="submit" style="border-radius:999px; border:none; background:#3b82f6; color:#fff; padding:8px 16px; font-size:0.85rem; font-weight:600; cursor:pointer;">Simpan</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
// Format number with dots as thousand separators
function formatNumber(value) {
	return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Remove dots and convert to number
function parseNumber(value) {
	return parseInt(value.replace(/\./g, '')) || 0;
}

// Harga Jual input
const hargaJualDisplay = document.getElementById('harga_jual_display');
const hargaJualHidden = document.getElementById('harga_jual');

if (hargaJualDisplay) {
	hargaJualDisplay.addEventListener('input', function(e) {
		// Remove non-numeric characters
		let value = e.target.value.replace(/[^0-9]/g, '');
		// Format with dots
		e.target.value = formatNumber(value);
		// Update hidden field
		hargaJualHidden.value = parseNumber(value);
	});

	hargaJualDisplay.addEventListener('blur', function(e) {
		let value = e.target.value.replace(/[^0-9]/g, '');
		if (value) {
			e.target.value = formatNumber(value);
			hargaJualHidden.value = parseNumber(value);
		} else {
			e.target.value = '';
			hargaJualHidden.value = 0;
		}
	});
}
</script>
@endsection
