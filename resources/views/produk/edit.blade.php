@extends('layouts.horizon')

@section('page_title', 'Edit Produk')
@section('page_subtitle', 'Perbarui data dan stok produk: ' . $product->nama_produk)

@section('content')
<!-- Form Edit Produk -->
<div id="produkModal" style="display:flex; position:fixed; inset:0; background:rgba(15, 34, 61, 0.6); backdrop-filter: blur(4px); z-index:50; align-items:center; justify-content:center; padding: 16px;">
	<div style="background: #FFFFFF; border-radius: 18px; padding: 28px; width: 100%; max-width: 520px; border: 1px solid #E2E8F0; box-shadow: 0 20px 50px rgba(15, 34, 61, 0.25);">
		<div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #F1F5F9;">
			<a href="{{ route('produk.index') }}" class="hz-btn-back" title="Kembali ke Daftar Produk">
				<i data-lucide="arrow-left"></i>
			</a>
			<div>
				<h3 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.35rem; font-weight: 600; color: #0F223D; margin: 0;">Edit Produk</h3>
				<p style="font-size: 0.8rem; color: #64748B; margin: 2px 0 0 0;">{{ $product->nama_produk }}</p>
			</div>
		</div>

		<form action="{{ route('produk.update', $product->id) }}" method="POST" id="produkForm">
			@csrf
			@method('PUT')
			@if ($errors->any())
				<div style="margin-bottom: 16px; padding: 12px 14px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 10px; color: #DC2626; font-size: 0.85rem;">
					@foreach ($errors->all() as $error)
						<div>{{ $error }}</div>
					@endforeach
				</div>
			@endif

			<div style="display:flex; flex-direction:column; gap:16px;">
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:600; color:#0F223D;">Kategori Produk</label>
					<input type="text" value="{{ $product->kategori }}" readonly style="padding:10px 14px; border-radius:10px; border:1px solid #E2E8F0; background:#F8FAFC; color:#64748B; font-size:0.875rem; cursor:not-allowed;">
				</div>

				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:600; color:#0F223D;">Harga Jual (Rp) <span style="color:#DC2626;">*</span></label>
					<input type="text" name="harga_jual_display" id="harga_jual_display" value="{{ old('harga_jual') ? number_format(old('harga_jual'), 0, '', '.') : number_format($product->harga_jual, 0, '', '.') }}" required min="0" style="padding:10px 14px; border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; color:#0F223D; font-size:0.875rem; outline:none;" placeholder="0" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
					<input type="hidden" name="harga_jual" id="harga_jual" value="{{ old('harga_jual', $product->harga_jual) }}">
				</div>

				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:600; color:#0F223D;">Tambah / Sesuaikan Stok <span style="color:#DC2626;">*</span></label>
					<input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0" placeholder="Jumlah unit" style="padding:10px 14px; border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; color:#0F223D; font-size:0.875rem; outline:none;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
					<small style="color:#64748B; font-size:0.75rem;">Stok saat ini di sistem: <strong>{{ $product->stock }} unit</strong></small>
				</div>

				<div style="display:flex; gap:10px; justify-content:flex-end; margin-top:10px; padding-top:16px; border-top:1px solid #F1F5F9;">
					<a href="{{ route('produk.index') }}" class="hz-btn hz-btn-secondary" style="padding: 9px 18px;">Batal</a>
					<button type="submit" class="hz-btn hz-btn-primary" style="padding: 9px 22px;">Simpan Perubahan</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
function formatNumber(value) {
	return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function unformatNumber(value) {
	return value.replace(/\./g, "");
}

document.addEventListener('DOMContentLoaded', function() {
	var inputDisplay = document.getElementById('harga_jual_display');
	var inputHidden = document.getElementById('harga_jual');
	if (inputDisplay && inputHidden) {
		inputDisplay.addEventListener('input', function(e) {
			var rawValue = unformatNumber(this.value.replace(/[^0-9]/g, ''));
			inputHidden.value = rawValue;
			this.value = rawValue ? formatNumber(rawValue) : '';
		});
	}

	if (typeof lucide !== 'undefined') {
		lucide.createIcons();
	}
});
</script>
@endsection
