@extends('layouts.horizon')

@section('page_title', 'Edit Paket')
@section('page_subtitle', 'Perbarui informasi harga, benefit, dan status paket langganan.')

@section('content')
<style>
	.page-header-row {
		margin-bottom: 24px;
	}

	.btn-back-circle {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 38px;
		height: 38px;
		border-radius: 10px;
		background: #FFFFFF;
		border: 1px solid #CBD5E1;
		color: #0F223D;
		transition: all 0.15s ease;
		box-shadow: 0 1px 3px rgba(15, 34, 61, 0.04);
		flex-shrink: 0;
		text-decoration: none;
	}

	.btn-back-circle:hover {
		background: #F8FAFC;
		border-color: #0F223D;
		color: #0F223D;
		transform: translateX(-2px);
		box-shadow: 0 2px 8px rgba(15, 34, 61, 0.08);
	}

	.page-title-block h2 {
		font-family: 'Newsreader', Georgia, serif;
		font-size: 2.2rem;
		font-weight: 500;
		color: #0F223D;
		margin: 0;
		line-height: 1.2;
	}

	.page-title-block p {
		font-size: 0.925rem;
		color: #64748B;
		margin: 4px 0 0 52px;
	}

	.form-card {
		background: #FFFFFF;
		border-radius: 18px;
		border: 1px solid rgba(226, 232, 240, 0.95);
		padding: 28px;
		width: 100%;
		max-width: 100%;
		box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04);
	}

	.card-header-line {
		padding-bottom: 16px;
		margin-bottom: 20px;
		border-bottom: 1px solid #F1F5F9;
	}

	.card-header-title {
		font-family: 'Newsreader', Georgia, serif;
		font-size: 1.35rem;
		font-weight: 600;
		color: #0F223D;
		margin: 0;
	}

	.form-group {
		display: flex;
		flex-direction: column;
		gap: 6px;
		margin-bottom: 16px;
	}

	.form-label {
		font-size: 0.825rem;
		font-weight: 600;
		color: #334155;
	}

	.form-label .req {
		color: #EF4444;
	}

	.form-input, .form-textarea, .form-select {
		width: 100%;
		padding: 10px 14px;
		border-radius: 10px;
		border: 1px solid #CBD5E1;
		background: #FFFFFF;
		color: #0F223D;
		font-family: inherit;
		font-size: 0.875rem;
		outline: none;
		transition: all 0.15s ease;
		box-sizing: border-box;
	}

	.form-input:focus, .form-textarea:focus, .form-select:focus {
		border-color: #0F223D;
		box-shadow: 0 0 0 3px rgba(15, 34, 61, 0.08);
	}

	.field-hint {
		font-size: 0.75rem;
		color: #64748B;
		margin-top: 2px;
		line-height: 1.4;
	}

	.field-error {
		color: #DC2626;
		font-size: 0.75rem;
		margin-top: 3px;
	}

	.form-actions {
		display: flex;
		align-items: center;
		gap: 10px;
		margin-top: 24px;
		padding-top: 18px;
		border-top: 1px solid #F1F5F9;
	}

	.btn-submit {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 10px 20px;
		border-radius: 10px;
		border: none;
		background: #0F223D;
		color: #FFFFFF;
		font-family: inherit;
		font-size: 0.875rem;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.15s ease;
		box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15);
	}

	.btn-submit:hover {
		background: #162C50;
		transform: translateY(-1px);
		box-shadow: 0 4px 12px rgba(15, 34, 61, 0.2);
	}

	.btn-cancel {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 10px 18px;
		border-radius: 10px;
		border: 1px solid #CBD5E1;
		background: #FFFFFF;
		color: #334155;
		font-family: inherit;
		font-size: 0.875rem;
		font-weight: 600;
		text-decoration: none;
		transition: all 0.15s ease;
	}

	.btn-cancel:hover {
		background: #F8FAFC;
		border-color: #94A3B8;
		color: #0F223D;
	}
</style>

<div class="hz-top-row" style="display:flex; justify-content:flex-start; align-items:center; margin-bottom: 20px;">
	<a href="{{ route('paket.index') }}" class="btn-back-circle" title="Kembali ke Daftar Paket">
		<i data-lucide="arrow-left" style="width: 18px; height: 18px;"></i>
	</a>
</div>

@if ($errors->any())
	<div style="margin-bottom: 20px; padding: 12px 16px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 10px; color: #991B1B; font-size: 0.85rem;">
		<ul style="margin: 0; padding-left: 20px;">
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

<div class="form-card">
	<div class="card-header-line">
		<h3 class="card-header-title">Formulir Ubah Paket</h3>
	</div>

	<form method="POST" action="{{ route('paket.update', $paket->id) }}">
		@csrf
		@method('PUT')

		<div class="form-group">
			<label class="form-label">Nama Paket <span class="req">*</span></label>
			<input type="text" name="nama_paket" value="{{ old('nama_paket', $paket->nama_paket) }}" placeholder="Contoh: Standard, Premium, Enterprise" class="form-input" required>
			@error('nama_paket')
				<span class="field-error">{{ $message }}</span>
			@enderror
		</div>

		<div class="form-group">
			<label class="form-label">Harga / Bulan (Rp) <span class="req">*</span></label>
			@php($hargaOld = old('harga', $paket->harga))
			<input type="text" name="harga" id="paket-harga" inputmode="numeric" autocomplete="off" value="{{ is_numeric($hargaOld) ? number_format($hargaOld, 0, ',', '.') : $hargaOld }}" placeholder="Contoh: 150.000" class="form-input" required>
			@error('harga')
				<span class="field-error">{{ $message }}</span>
			@enderror
		</div>

		<div class="form-group">
			<label class="form-label">Harga Diskon Promo (Rp) <span style="color:#64748B; font-weight:normal;">[Opsional]</span></label>
			@php($diskonOld = old('harga_diskon', $paket->harga_diskon))
			<input type="text" name="harga_diskon" id="paket-harga-diskon" inputmode="numeric" autocomplete="off" value="{{ is_numeric($diskonOld) ? number_format($diskonOld, 0, ',', '.') : $diskonOld }}" placeholder="Kosongkan bila tidak ada harga promo" class="form-input">
			<p class="field-hint">Jika diisi, harga asli akan ditampilkan tercoret dan harga diskon yang berlaku.</p>
			@error('harga_diskon')
				<span class="field-error">{{ $message }}</span>
			@enderror
		</div>

		<div class="form-group">
			<label class="form-label">Durasi Masa Aktif (Bulan) <span class="req">*</span></label>
			<input type="number" name="durasi_bulan" value="{{ old('durasi_bulan', $paket->durasi_bulan ?? 1) }}" min="1" class="form-input" required>
			@error('durasi_bulan')
				<span class="field-error">{{ $message }}</span>
			@enderror
		</div>

		<div class="form-group">
			<label class="form-label">Status Paket <span class="req">*</span></label>
			@php($status = old('is_active', $paket->is_active ?? 1))
			<select name="is_active" class="form-select" required>
				<option value="1" {{ (string) $status === '1' ? 'selected' : '' }}>Aktif (Dapat Dipilih Merchant)</option>
				<option value="0" {{ (string) $status === '0' ? 'selected' : '' }}>Nonaktif (Disembunyikan)</option>
			</select>
			@error('is_active')
				<span class="field-error">{{ $message }}</span>
			@enderror
		</div>

		<div class="form-group">
			<label class="form-label">Deskripsi &amp; Daftar Fitur Keunggulan</label>
			<textarea name="deskripsi" placeholder="Tuliskan fitur per baris. Contoh:&#10;Akses Kasir POS Tak Terbatas&#10;Laporan Penjualan &amp; Laba Rugi&#10;Dukungan Teknis Prioritas" rows="4" class="form-textarea" style="resize:vertical;">{{ old('deskripsi', $paket->deskripsi) }}</textarea>
			<p class="field-hint">Pisahkan tiap poin fitur dengan baris baru (Enter) agar tampil rapi sebagai daftar centang.</p>
			@error('deskripsi')
				<span class="field-error">{{ $message }}</span>
			@enderror
		</div>

		<div class="form-actions">
			<button type="submit" class="btn-submit">
				<i data-lucide="check" style="width: 16px; height: 16px;"></i>
				<span>Simpan Perubahan</span>
			</button>
			<a href="{{ route('paket.index') }}" class="btn-cancel">
				<i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
				<span>Batal</span>
			</a>
		</div>
	</form>
</div>

<script>
	(function () {
		var hargaInput = document.getElementById('paket-harga');
		if (!hargaInput) return;

		var formatValue = function (value) {
			var digits = String(value || '').replace(/[^\d]/g, '');
			if (!digits) return '';
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

	(function () {
		var diskonInput = document.getElementById('paket-harga-diskon');
		if (!diskonInput) return;

		var formatValue = function (value) {
			var digits = String(value || '').replace(/[^\d]/g, '');
			if (!digits) return '';
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

	document.addEventListener('DOMContentLoaded', function() {
		if (typeof lucide !== 'undefined') {
			lucide.createIcons();
		}
	});
</script>
@endsection
