@extends('layouts.horizon')

@section('page_title', 'Edit Pengeluaran')
@section('page_subtitle', 'Perbarui informasi catatan pengeluaran toko.')

@section('content')
<div style="margin-bottom: 20px;">
	<a href="{{ route('pengeluaran.index') }}" class="hz-btn-back" title="Kembali">
		<i data-lucide="arrow-left"></i>
	</a>
</div>

@if ($errors->any())
	<div style="margin-bottom: 20px; padding: 12px 16px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 10px; color: #DC2626; font-size: 0.85rem;">
		<ul style="margin: 0; padding-left: 20px;">
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

@if ($message = Session::get('error'))
	<div style="margin-bottom: 20px; padding: 12px 16px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 10px; color: #DC2626; font-size: 0.85rem;">
		{{ $message }}
	</div>
@endif

<form action="{{ route('pengeluaran.update', $pengeluaran->id) }}" method="POST">
	@csrf
	@method('PUT')

	<section class="hz-card" style="width: 100%; max-width: 100%;">
		<div class="hz-card-header" style="margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #F1F5F9;">
			<p class="hz-card-title">Informasi Pengeluaran</p>
			<p class="hz-card-sub">Sesuaikan tanggal, kategori, dan nominal biaya.</p>
		</div>

		<div style="display: flex; flex-direction: column; gap: 18px;">
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Tanggal Transaksi <span style="color: #DC2626;">*</span>
					</label>
					<input type="date" name="tanggal" value="{{ old('tanggal', optional($pengeluaran->tanggal)->format('Y-m-d')) }}" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; transition: all 0.2s ease; outline: none;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
				</div>

				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Kategori Pengeluaran
					</label>
					<select name="kategori_pengeluaran_id" style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; transition: all 0.2s ease; outline: none;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
						<option value="">-- Pilih Kategori --</option>
						@foreach($kategoriPengeluarans as $kat)
							<option value="{{ $kat->id }}" {{ old('kategori_pengeluaran_id', $pengeluaran->kategori_pengeluaran_id) == $kat->id ? 'selected' : '' }}>{{ $kat->name }}</option>
						@endforeach
					</select>
				</div>
			</div>

			<div>
				<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
					Nominal (Rp) <span style="color: #DC2626;">*</span>
				</label>
				<input type="text" name="nominal_display" id="nominal_display" value="{{ old('nominal', number_format($pengeluaran->nominal, 0, ',', '.')) }}" required min="0" placeholder="0" style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; transition: all 0.2s ease; outline: none;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
				<input type="hidden" name="nominal" id="nominal" value="{{ old('nominal', $pengeluaran->nominal) }}">
			</div>

			<div>
				<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
					Catatan / Keterangan
				</label>
				<textarea name="keterangan" placeholder="Contoh: Pembayaran listrik toko bulan ini" style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; min-height: 90px; resize: vertical; transition: all 0.2s ease; outline: none;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">{{ old('keterangan', $pengeluaran->keterangan) }}</textarea>
			</div>

			<div style="display: flex; gap: 12px; margin-top: 12px; padding-top: 18px; border-top: 1px solid #F1F5F9;">
				<button type="submit" class="hz-btn hz-btn-primary" style="padding: 10px 24px;">
					<i data-lucide="check" style="width: 16px; height: 16px;"></i>
					Simpan Perubahan
				</button>
				<a href="{{ route('pengeluaran.index') }}" class="hz-btn hz-btn-secondary" style="padding: 10px 20px;">
					Batal
				</a>
			</div>
		</div>
	</section>
</form>

<script>
function formatNumber(value) {
	return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function parseNumber(value) {
	return parseInt(value.replace(/\./g, '')) || 0;
}

document.addEventListener('DOMContentLoaded', function() {
	var nominalDisplay = document.getElementById('nominal_display');
	var nominalHidden = document.getElementById('nominal');

	if (nominalDisplay) {
		nominalDisplay.addEventListener('input', function(e) {
			var value = e.target.value.replace(/[^0-9]/g, '');
			e.target.value = formatNumber(value);
			nominalHidden.value = parseNumber(value);
		});

		nominalDisplay.addEventListener('blur', function(e) {
			var value = e.target.value.replace(/[^0-9]/g, '');
			if (value) {
				e.target.value = formatNumber(value);
				nominalHidden.value = parseNumber(value);
			} else {
				e.target.value = '';
				nominalHidden.value = 0;
			}
		});
	}

	if (typeof lucide !== 'undefined') {
		lucide.createIcons();
	}
});
</script>
@endsection
