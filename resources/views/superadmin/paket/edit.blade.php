@extends('layouts.horizon')

@section('page_title', 'Edit Paket')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Edit Paket</h2>
			<p class="hz-top-sub">Perbarui informasi paket berlangganan.</p>
		</div>
		<div class="hz-top-actions">
			<a href="{{ route('paket.index') }}" style="background:rgba(148,163,184,0.2); color:#1e293b; border:1px solid rgba(59,130,246,0.3); padding:8px 16px; border-radius:6px; text-decoration:none; font-size:0.9rem;">
				← Kembali
			</a>
		</div>
	</div>

	@if ($errors->any())
		<div style="margin: 16px 0; padding: 12px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px; color: #ef4444;">
			<ul style="margin: 0; padding-left: 20px;">
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<section class="hz-secondary-grid" style="margin-top: 24px; max-width: 700px;">
		<div class="hz-card">
			<div class="hz-card-header" style="margin-bottom: 20px;">
				<div>
					<p class="hz-card-title">Form Edit Paket</p>
				</div>
			</div>

			<form method="POST" action="{{ route('paket.update', $paket->id) }}">
				@csrf
				@method('PUT')

				<div style="margin-bottom: 16px;">
					<label style="display:block; color:#1e293b; font-weight:500; margin-bottom:6px; font-size:0.9rem;">
						Nama Paket <span style="color:#ef4444;">*</span>
					</label>
					<input type="text" name="nama_paket" value="{{ old('nama_paket', $paket->nama_paket) }}" placeholder="Contoh: Standard, Premium, Enterprise" 
						style="width:100%; background:rgba(255,255,255,0.9); border:1px solid rgba(148,163,184,0.3); border-radius:6px; padding:10px 12px; color:#1e293b; font-size:0.9rem;" required>
					@error('nama_paket')
						<span style="color:#ef4444; font-size:0.8rem;">{{ $message }}</span>
					@enderror
				</div>

				<div style="margin-bottom: 16px;">
					<label style="display:block; color:#1e293b; font-weight:500; margin-bottom:6px; font-size:0.9rem;">
						Harga / Bulan (Rp) <span style="color:#ef4444;">*</span>
					</label>
					@php($hargaOld = old('harga', $paket->harga))
					<input type="text" name="harga" id="paket-harga" inputmode="numeric" autocomplete="off" value="{{ is_numeric($hargaOld) ? number_format($hargaOld, 0, ',', '.') : $hargaOld }}" placeholder="Contoh: 1000000" 
						style="width:100%; background:rgba(255,255,255,0.9); border:1px solid rgba(148,163,184,0.3); border-radius:6px; padding:10px 12px; color:#1e293b; font-size:0.9rem;" required>
					@error('harga')
						<span style="color:#ef4444; font-size:0.8rem;">{{ $message }}</span>
					@enderror
				</div>

				<div style="margin-bottom: 16px;">
					<label style="display:block; color:#cbd5f5; font-weight:500; margin-bottom:6px; font-size:0.9rem; opacity:0.9;">
						Harga Diskon (Rp) <span style="color:#94a3af; font-size:0.8rem;">[Opsional]</span>
					</label>
					@php($diskonOld = old('harga_diskon', $paket->harga_diskon))
					<input type="text" name="harga_diskon" id="paket-harga-diskon" inputmode="numeric" autocomplete="off" value="{{ is_numeric($diskonOld) && $diskonOld ? number_format($diskonOld, 0, ',', '.') : $diskonOld }}" placeholder="Kosongkan jika tidak ada promo" 
						style="width:100%; background:rgba(255,255,255,0.9); border:1px solid rgba(148,163,184,0.3); border-radius:6px; padding:10px 12px; color:#cbd5f5; font-size:0.9rem;">
					<p style="font-size:0.75rem; color:#9ca3af; margin-top:4px;">💡 Jika diisi, harga asli akan coret dan harga diskon yang ditampilkan</p>
					@error('harga_diskon')
						<span style="color:#ef4444; font-size:0.8rem;">{{ $message }}</span>
					@enderror
				</div>

				<div style="margin-bottom: 16px;">
					<label style="display:block; color:#1e293b; font-weight:500; margin-bottom:6px; font-size:0.9rem;">
						Durasi (Bulan) <span style="color:#ef4444;">*</span>
					</label>
					<input type="number" name="durasi_bulan" value="{{ old('durasi_bulan', $paket->durasi_bulan) }}" min="1" 
						style="width:100%; background:rgba(255,255,255,0.9); border:1px solid rgba(148,163,184,0.3); border-radius:6px; padding:10px 12px; color:#1e293b; font-size:0.9rem;" required>
					@error('durasi_bulan')
						<span style="color:#ef4444; font-size:0.8rem;">{{ $message }}</span>
					@enderror
				</div>

				<div style="margin-bottom: 16px;">
					<label style="display:block; color:#1e293b; font-weight:500; margin-bottom:6px; font-size:0.9rem;">
						Status <span style="color:#ef4444;">*</span>
					</label>
					@php($status = old('is_active', $paket->is_active ? 1 : 0))
					<select name="is_active" 
						style="width:100%; background:rgba(255,255,255,0.9); border:1px solid rgba(148,163,184,0.3); border-radius:6px; padding:10px 12px; color:#1e293b; font-size:0.9rem;" required>
						<option value="1" {{ (string) $status === '1' ? 'selected' : '' }}>Aktif</option>
						<option value="0" {{ (string) $status === '0' ? 'selected' : '' }}>Nonaktif</option>
					</select>
					@error('is_active')
						<span style="color:#ef4444; font-size:0.8rem;">{{ $message }}</span>
					@enderror
				</div>

				<div style="margin-bottom: 20px;">
					<label style="display:block; color:#1e293b; font-weight:500; margin-bottom:6px; font-size:0.9rem;">
						Deskripsi / Benefit
					</label>
					<textarea name="deskripsi" placeholder="Contoh: Akses laporan, dashboard real-time, dukungan 24/7" rows="4"
						style="width:100%; background:rgba(255,255,255,0.9); border:1px solid rgba(148,163,184,0.3); border-radius:6px; padding:10px 12px; color:#1e293b; font-size:0.9rem; resize:vertical;">{{ old('deskripsi', $paket->deskripsi) }}</textarea>
					@error('deskripsi')
						<span style="color:#ef4444; font-size:0.8rem;">{{ $message }}</span>
					@enderror
				</div>

				<div style="display:flex; gap:10px;">
					<button type="submit" style="flex:1; background:rgba(34,197,94,0.2); color:#22c55e; border:1px solid rgba(34,197,94,0.5); padding:10px 16px; border-radius:6px; cursor:pointer; font-weight:500; font-size:0.9rem; transition: all 0.2s;">
						✓ Simpan Perubahan
					</button>
					<a href="{{ route('paket.index') }}" style="flex:1; background:rgba(148,163,184,0.2); color:#1e293b; border:1px solid rgba(59,130,246,0.3); padding:10px 16px; border-radius:6px; text-decoration:none; font-weight:500; font-size:0.9rem; text-align:center;">
						✕ Batal
					</a>
				</div>
			</form>
		</div>
	</section>

	<script>
		(function () {
			var hargaInput = document.getElementById('paket-harga');
			if (!hargaInput) {
				return;
			}

			var formatValue = function (value) {
				var digits = String(value || '').replace(/[^\d]/g, '');
				if (!digits) {
					return '';
				}
				return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
			};

			var unformatValue = function (value) {
				return String(value || '').replace(/[^\d]/g, '');
			};

			hargaInput.value = formatValue(hargaInput.value);

			hargaInput.addEventListener('input', function () {
				this.value = formatValue(this.value);
			});

			var form = hargaInput.closest('form');
			if (form) {
				form.addEventListener('submit', function () {
					hargaInput.value = unformatValue(hargaInput.value);
				});
			}
		})();

		// Format untuk harga_diskon
		(function () {
			var diskonInput = document.getElementById('paket-harga-diskon');
			if (!diskonInput) {
				return;
			}

			var formatValue = function (value) {
				var digits = String(value || '').replace(/[^\d]/g, '');
				if (!digits) {
					return '';
				}
				return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
			};

			var unformatValue = function (value) {
				return String(value || '').replace(/[^\d]/g, '');
			};

			diskonInput.value = formatValue(diskonInput.value);

			diskonInput.addEventListener('input', function () {
				this.value = formatValue(this.value);
			});

			var form = diskonInput.closest('form');
			if (form) {
				form.addEventListener('submit', function () {
					if (unformatValue(diskonInput.value) === '') {
						diskonInput.value = '';
					} else {
						diskonInput.value = unformatValue(diskonInput.value);
					}
				});
			}
		})();
	</script>
@endsection