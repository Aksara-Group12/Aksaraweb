@extends('layouts.horizon')

@section('page_title', 'Catat Pengeluaran')
@section('page_subtitle', 'Pencatatan biaya operasional, beban, dan pengeluaran lain.')

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

	@if ($message = Session::get('success'))
		<div style="margin-top: 20px; padding: 14px 16px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 10px; color: #16a34a;">
			{{ $message }}
		</div>
	@endif

	<form action="{{ route('expenses.store') }}" method="POST" style="margin-top: 24px;">
		@csrf

		<section class="hz-secondary-grid">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Informasi Pengeluaran</p>
				</div>

				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Tanggal <span style="color: #3b82f6;">*</span></label>
						<input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Kategori</label>
						<select name="kategori_produk_id" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
							<option value="">-- Pilih Kategori --</option>
							@foreach($kategoriProduk as $kat)
								<option value="{{ $kat->id }}" {{ old('kategori_produk_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
							@endforeach
						</select>
						<input type="text" name="kategori" value="{{ old('kategori') }}" placeholder="Atau ketik kategori lain..." style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem; margin-top: 4px;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Nominal (Rp) <span style="color: #3b82f6;">*</span></label>
						@php($nominalOld = old('nominal'))
						<input type="text" name="nominal" id="nominal" inputmode="numeric" autocomplete="off" value="{{ is_numeric($nominalOld) ? number_format($nominalOld, 0, ',', '.') : $nominalOld }}" placeholder="0" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Metode Pembayaran <span style="color: #3b82f6;">*</span></label>
						@php($metode = old('metode_pembayaran'))
						<select name="metode_pembayaran" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
							<option value="">-- Pilih --</option>
							<option value="cash" {{ $metode === 'cash' ? 'selected' : '' }}>Cash</option>
							<option value="non-cash" {{ $metode === 'non-cash' ? 'selected' : '' }}>Non-Cash</option>
						</select>
					</div>
				</div>
			</div>
		</section>

		<section class="hz-secondary-grid" style="margin-top: 20px;">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Keterangan</p>
				</div>
				<div style="display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Catatan / Keterangan</label>
					<textarea name="keterangan" placeholder="Contoh: Bayar listrik bulan Maret, sewa toko, dll" style="padding: 12px 14px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem; height: 100px; resize: vertical;">{{ old('keterangan') }}</textarea>
				</div>
			</div>
		</section>

		<section class="hz-secondary-grid" style="margin-top: 24px; margin-bottom: 40px;">
			<div style="grid-column: 1 / -1; display: flex; gap: 12px; justify-content: flex-end;">
				<button type="button" class="hz-btn-outline" onclick="window.history.back()">
					<span>Batal</span>
				</button>
				<button type="submit" class="hz-btn-primary">
					<span>Simpan Pengeluaran</span>
				</button>
			</div>
		</section>
	</form>

	<script>
		(function () {
			var nominalInput = document.getElementById('nominal');
			if (!nominalInput) {
				return;
			}

			var formatNominal = function (value) {
				var digits = String(value || '').replace(/[^\d]/g, '');
				if (!digits) {
					return '';
				}
				return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
			};

			var unformatNominal = function (value) {
				return String(value || '').replace(/[^\d]/g, '');
			};

			nominalInput.value = formatNominal(nominalInput.value);

			nominalInput.addEventListener('input', function () {
				this.value = formatNominal(this.value);
			});

			var form = nominalInput.closest('form');
			if (form) {
				form.addEventListener('submit', function () {
					nominalInput.value = unformatNominal(nominalInput.value);
				});
			}
		})();
	</script>
@endsection

