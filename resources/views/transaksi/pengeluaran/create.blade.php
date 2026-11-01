@extends('layouts.horizon')

@section('page_title', 'Catat Pengeluaran')
@section('page_subtitle', 'Catat pengeluaran biaya operasional toko.')

@section('content')
<!-- Form Catat Pengeluaran -->
<div id="pengeluaranModal" style="display:flex; position:fixed; inset:0; background:rgba(15, 34, 61, 0.6); backdrop-filter: blur(4px); z-index:50; align-items:center; justify-content:center; padding: 16px;">
	<div style="background: #FFFFFF; border-radius: 18px; padding: 28px; width: 100%; max-width: 500px; border: 1px solid #E2E8F0; box-shadow: 0 20px 50px rgba(15, 34, 61, 0.25);">
		<div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #F1F5F9;">
			<a href="{{ route('pengeluaran.index') }}" class="hz-btn-back" title="Kembali ke Daftar Pengeluaran">
				<i data-lucide="arrow-left"></i>
			</a>
			<div>
				<h3 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.35rem; font-weight: 600; color: #0F223D; margin: 0;">Catat Pengeluaran</h3>
				<p style="font-size: 0.8rem; color: #64748B; margin: 2px 0 0 0;">Pengeluaran operasional toko</p>
			</div>
		</div>

		<form action="{{ route('pengeluaran.store') }}" method="POST" id="pengeluaranForm">
			@csrf
			@if ($errors->any())
				<div style="margin-bottom: 16px; padding: 12px 14px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 10px; color: #DC2626; font-size: 0.85rem;">
					@foreach ($errors->all() as $error)
						<div>{{ $error }}</div>
					@endforeach
				</div>
			@endif

			<div style="display:flex; flex-direction:column; gap:16px;">
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:600; color:#0F223D;">Kategori Pengeluaran <span style="color:#DC2626;">*</span></label>
					<select name="kategori_pengeluaran_id" required style="padding:10px 14px; border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; color:#0F223D; font-size:0.875rem; outline:none;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
						<option value="">-- Pilih Kategori --</option>
						@foreach($kategoriPengeluarans as $kategori)
							<option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
						@endforeach
					</select>
				</div>

				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:600; color:#0F223D;">Nama / Keterangan Pengeluaran <span style="color:#DC2626;">*</span></label>
					<input type="text" name="keterangan" value="{{ old('keterangan') }}" required placeholder="Contoh: Tagihan listrik, sewa ruko" style="padding:10px 14px; border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; color:#0F223D; font-size:0.875rem; outline:none;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
				</div>

				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:600; color:#0F223D;">Nominal Biaya (Rp) <span style="color:#DC2626;">*</span></label>
					<input type="text" name="nominal_display" id="nominal_display" value="{{ old('nominal') ? number_format(old('nominal'), 0, '', '.') : '' }}" required min="0" placeholder="0" style="padding:10px 14px; border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; color:#0F223D; font-size:0.875rem; outline:none;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
					<input type="hidden" name="nominal" id="nominal" value="{{ old('nominal') }}">
				</div>

				<div style="display:flex; gap:10px; justify-content:flex-end; margin-top:10px; padding-top:16px; border-top:1px solid #F1F5F9;">
					<a href="{{ route('pengeluaran.index') }}" class="hz-btn hz-btn-secondary" style="padding: 9px 18px;">Batal</a>
					<button type="submit" class="hz-btn hz-btn-primary" style="padding: 9px 22px;">Simpan Pengeluaran</button>
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
	var inputDisplay = document.getElementById('nominal_display');
	var inputHidden = document.getElementById('nominal');
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
