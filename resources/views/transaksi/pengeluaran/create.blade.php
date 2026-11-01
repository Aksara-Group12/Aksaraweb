@extends('layouts.horizon')

@section('page_title', 'Catat Pengeluaran')

@section('content')
<!-- Modal Catat Pengeluaran -->
<div id="pengeluaranModal" style="display:flex; position:fixed; inset:0; background:rgba(15,23,42,0.8); z-index:50; align-items:center; justify-content:center;">
	<div style="background:rgba(255,255,255,0.95); border-radius:16px; padding:24px; width:100%; max-width:400px; border:1px solid rgba(59,130,246,0.3); box-shadow:0 18px 45px rgba(15,23,42,0.9);">
		<h3 style="font-size:1.1rem; font-weight:600; color:#1e293b; margin-bottom:16px;">Catat Pengeluaran</h3>
		<form action="{{ route('pengeluaran.store') }}" method="POST" id="pengeluaranForm">
			@csrf
			@if ($errors->any())
				<div style="margin-bottom: 16px; padding: 12px 14px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 10px; color: #dc2626; font-size: 0.85rem;">
					@foreach ($errors->all() as $error)
						<div>{{ $error }}</div>
					@endforeach
				</div>
			@endif
			<div style="display:flex; flex-direction:column; gap:16px;">
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Kategori Pengeluaran <span style="color:#3b82f6;">*</span></label>
					<select name="kategori_pengeluaran_id" required style="padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.4); background:rgba(255,255,255,0.95); color:#1e293b; font-size:0.9rem;">
						<option value="">-- Pilih Kategori --</option>
						@foreach($kategoriPengeluarans as $kategori)
							<option value="{{ $kategori->id }}">{{ $kategori->name }}</option>
						@endforeach
					</select>
				</div>
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Nama Pengeluaran <span style="color:#3b82f6;">*</span></label>
					<input type="text" name="keterangan" value="{{ old('keterangan') }}" required placeholder="Contoh: Bayar listrik, sewa toko" style="padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.4); background:rgba(255,255,255,0.95); color:#1e293b; font-size:0.9rem;">
				</div>
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Nominal (Rp) <span style="color:#3b82f6;">*</span></label>
					<input type="text" name="nominal_display" id="nominal_display" value="{{ old('nominal') ? number_format(old('nominal'), 0, '', '.') : '' }}" required min="0" placeholder="0" style="padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.4); background:rgba(255,255,255,0.95); color:#1e293b; font-size:0.9rem;">
					<input type="hidden" name="nominal" id="nominal" value="{{ old('nominal') }}">
				</div>
				<div style="display:flex; gap:8px; justify-content:flex-end; margin-top:8px;">
					<a href="{{ route('pengeluaran.index') }}" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:transparent; color:#1e293b; padding:8px 16px; font-size:0.85rem; cursor:pointer; text-decoration:none;">Batal</a>
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

// Nominal input
const nominalDisplay = document.getElementById('nominal_display');
const nominalHidden = document.getElementById('nominal');

if (nominalDisplay) {
	nominalDisplay.addEventListener('input', function(e) {
		// Remove non-numeric characters
		let value = e.target.value.replace(/[^0-9]/g, '');
		// Format with dots
		e.target.value = formatNumber(value);
		// Update hidden field
		nominalHidden.value = parseNumber(value);
	});

	nominalDisplay.addEventListener('blur', function(e) {
		let value = e.target.value.replace(/[^0-9]/g, '');
		if (value) {
			e.target.value = formatNumber(value);
			nominalHidden.value = parseNumber(value);
		} else {
			e.target.value = '';
			nominalHidden.value = 0;
		}
	});
}
</script>
@endsection

