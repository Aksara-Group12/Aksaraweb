@extends('layouts.horizon')

@section('page_title', isset($profile) ? (auth()->user()?->role === 'superadmin' ? 'Edit Profil Admin' : 'Edit Profil Toko') : 'Atur Profil')

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
		margin-bottom: 22px;
		border-bottom: 1px solid #F1F5F9;
	}

	.card-header-title {
		font-family: 'Newsreader', Georgia, serif;
		font-size: 1.35rem;
		font-weight: 600;
		color: #0F223D;
		margin: 0;
	}

	.form-grid-two {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 16px;
	}

	@media (max-width: 768px) {
		.form-grid-two {
			grid-template-columns: 1fr;
		}
	}

	.form-group {
		display: flex;
		flex-direction: column;
		gap: 6px;
		margin-bottom: 18px;
	}

	.form-label {
		font-size: 0.825rem;
		font-weight: 600;
		color: #334155;
	}

	.form-label .req {
		color: #EF4444;
	}

	.form-input, .form-textarea {
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
		box-shadow: 0 1px 2px rgba(15, 34, 61, 0.04);
	}

	.form-input:focus, .form-textarea:focus {
		border-color: #0F223D;
		box-shadow: 0 0 0 3px rgba(15, 34, 61, 0.08);
	}

	.form-input.is-invalid, .form-textarea.is-invalid {
		border-color: #EF4444;
		background: #FEF2F2;
	}

	.field-error {
		font-size: 0.775rem;
		color: #DC2626;
		margin-top: 4px;
	}

	.field-hint {
		font-size: 0.775rem;
		color: #64748B;
		margin-top: 4px;
	}

	/* Upload Logo Box */
	.logo-upload-wrap {
		display: flex;
		align-items: center;
		gap: 20px;
		padding: 16px;
		background: #FAF8F5;
		border: 1px dashed #CBD5E1;
		border-radius: 12px;
	}

	.logo-preview-box {
		width: 80px;
		height: 80px;
		border-radius: 50%;
		background: #FFFFFF;
		border: 2px solid #E2E8F0;
		display: flex;
		align-items: center;
		justify-content: center;
		overflow: hidden;
		flex-shrink: 0;
		box-shadow: 0 2px 8px rgba(15, 34, 61, 0.06);
	}

	.logo-preview-box img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}

	.form-actions {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-top: 26px;
		padding-top: 20px;
		border-top: 1px solid #F1F5F9;
	}

	.btn-submit-primary {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 10px 22px;
		border-radius: 10px;
		background: #0F223D;
		color: #FFFFFF;
		font-family: inherit;
		font-size: 0.85rem;
		font-weight: 600;
		border: none;
		cursor: pointer;
		transition: all 0.15s ease;
		box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15);
	}

	.btn-submit-primary:hover {
		background: #162C50;
		transform: translateY(-1px);
		box-shadow: 0 4px 12px rgba(15, 34, 61, 0.2);
	}

	.btn-cancel {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 10px 18px;
		border-radius: 10px;
		background: #FFFFFF;
		color: #64748B;
		border: 1px solid #CBD5E1;
		font-family: inherit;
		font-size: 0.85rem;
		font-weight: 600;
		text-decoration: none;
		transition: all 0.15s ease;
	}

	.btn-cancel:hover {
		background: #F8FAFC;
		color: #0F223D;
		border-color: #94A3B8;
	}

	.flash-alert-error {
		margin-bottom: 20px;
		padding: 12px 16px;
		border-radius: 10px;
		font-size: 0.85rem;
		display: flex;
		align-items: flex-start;
		gap: 10px;
		background: #FEF2F2;
		border: 1px solid #FECACA;
		color: #991B1B;
		width: 100%;
		max-width: 100%;
	}
</style>

<div class="page-header-row">
	<div class="page-title-block">
		<div style="display: flex; align-items: center; gap: 14px;">
			<a href="{{ route('profile.index') }}" class="btn-back-circle" title="Kembali ke Informasi Profil">
				<i data-lucide="arrow-left" style="width: 18px; height: 18px;"></i>
			</a>
			<h2>{{ isset($profile) ? (auth()->user()?->role === 'superadmin' ? 'Edit Profil Admin' : 'Edit Profil Toko') : 'Atur Profil Baru' }}</h2>
		</div>
		<p>Lengkapi informasi identitas utama akun dan profil bisnis Anda.</p>
	</div>
</div>

@if ($errors->any())
	<div class="flash-alert-error">
		<i data-lucide="alert-circle" style="width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px;"></i>
		<div>
			<strong style="display: block; margin-bottom: 4px;">Terdapat kesalahan pada formulir:</strong>
			<ul style="margin: 0; padding-left: 18px;">
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	</div>
@endif

<div class="form-card">
	<div class="card-header-line">
		<h3 class="card-header-title">Formulir Identitas Profil</h3>
	</div>

	<form method="POST" action="{{ isset($profile) ? route('profile.update', $profile) : route('profile.store') }}" enctype="multipart/form-data">
		@csrf
		@if(isset($profile))
			@method('PUT')
		@endif

		<div class="form-grid-two">
			<div class="form-group">
				<label class="form-label" for="store_name">
					{{ auth()->user()?->role === 'superadmin' ? 'Nama Sistem / Brand' : 'Nama Toko' }} <span class="req">*</span>
				</label>
				<input type="text" id="store_name" name="store_name" 
					value="{{ old('store_name', $profile->store_name ?? (auth()->user()?->role === 'superadmin' ? 'Aksara Developer' : '')) }}" 
					placeholder="{{ auth()->user()?->role === 'superadmin' ? 'Contoh: Aksara Developer' : 'Contoh: Kedai Kopi Aksara' }}" 
					class="form-input {{ $errors->has('store_name') ? 'is-invalid' : '' }}" required autofocus>
				@error('store_name')
					<span class="field-error">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label class="form-label" for="owner_name">
					Nama Lengkap Penanggung Jawab
				</label>
				<input type="text" id="owner_name" name="owner_name" 
					value="{{ old('owner_name', $profile->owner_name ?? auth()->user()->name ?? '') }}" 
					placeholder="Contoh: Wahyu Widodo" 
					class="form-input {{ $errors->has('owner_name') ? 'is-invalid' : '' }}">
				@error('owner_name')
					<span class="field-error">{{ $message }}</span>
				@enderror
			</div>
		</div>

		<div class="form-group">
			<label class="form-label">
				Logo / Foto Profil (Format PNG transparan atau bulat)
			</label>
			<div class="logo-upload-wrap">
				<div id="logoPreview" class="logo-preview-box">
					@if($profile && $profile->logo_path)
						<img src="{{ asset('storage/' . $profile->logo_path) }}" alt="Logo">
					@else
						<i data-lucide="image" style="width: 28px; height: 28px; color: #94A3B8;"></i>
					@endif
				</div>
				<div style="flex: 1;">
					<input type="file" name="logo" id="logoInput" accept=".png,image/png" class="form-input" style="padding: 7px 10px; background: #FFFFFF;">
					<p class="field-hint">Format yang disarankan: PNG transparan (maks. 5MB). Rekomendasi resolusi 512x512 piksel.</p>
					@error('logo')
						<span class="field-error">{{ $message }}</span>
					@enderror
				</div>
			</div>
		</div>

		<div class="form-grid-two">
			<div class="form-group">
				<label class="form-label" for="email">
					Email Kontak
				</label>
				<input type="email" id="email" name="email" 
					value="{{ old('email', $profile->email ?? auth()->user()->email ?? '') }}" 
					placeholder="Contoh: admin@aksara.com" 
					class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}">
				@error('email')
					<span class="field-error">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label class="form-label" for="phone">
					Nomor WhatsApp / Telepon
				</label>
				<input type="text" id="phone" name="phone" 
					value="{{ old('phone', $profile->phone ?? '') }}" 
					placeholder="Contoh: 081234567890" 
					class="form-input {{ $errors->has('phone') ? 'is-invalid' : '' }}">
				@error('phone')
					<span class="field-error">{{ $message }}</span>
				@enderror
			</div>
		</div>

		<div class="form-group">
			<label class="form-label" for="address">
				Alamat Lengkap
			</label>
			<textarea id="address" name="address" rows="3" 
				placeholder="Alamat kantor, ruko, atau lokasi toko..." 
				class="form-textarea {{ $errors->has('address') ? 'is-invalid' : '' }}">{{ old('address', $profile->address ?? '') }}</textarea>
			@error('address')
				<span class="field-error">{{ $message }}</span>
			@enderror
		</div>

		<div class="form-grid-two">
			<div class="form-group">
				<label class="form-label" for="city">
					Kota / Kabupaten
				</label>
				<input type="text" id="city" name="city" 
					value="{{ old('city', $profile->city ?? '') }}" 
					placeholder="Contoh: Jakarta Selatan" 
					class="form-input {{ $errors->has('city') ? 'is-invalid' : '' }}">
				@error('city')
					<span class="field-error">{{ $message }}</span>
				@enderror
			</div>

			<div class="form-group">
				<label class="form-label" for="postal_code">
					Kode Pos
				</label>
				<input type="text" id="postal_code" name="postal_code" 
					value="{{ old('postal_code', $profile->postal_code ?? '') }}" 
					placeholder="Contoh: 12345" 
					class="form-input {{ $errors->has('postal_code') ? 'is-invalid' : '' }}">
				@error('postal_code')
					<span class="field-error">{{ $message }}</span>
				@enderror
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn-submit-primary">
				<i data-lucide="check" style="width: 16px; height: 16px;"></i>
				<span>Simpan Perubahan</span>
			</button>
			<a href="{{ route('profile.index') }}" class="btn-cancel">
				Batal
			</a>
		</div>
	</form>
</div>

<script>
	const logoInput = document.getElementById('logoInput');
	const logoPreview = document.getElementById('logoPreview');

	if (logoInput) {
		logoInput.addEventListener('change', function(e) {
			const file = e.target.files[0];
			if (file) {
				const maxSize = 5 * 1024 * 1024;
				if (file.size > maxSize) {
					alert('Ukuran file maksimal adalah 5MB.');
					return;
				}

				if (!file.type.includes('image') && !file.name.endsWith('.png')) {
					alert('File logo harus berformat PNG.');
					return;
				}

				const reader = new FileReader();
				reader.onload = function(event) {
					logoPreview.innerHTML = '<img src="' + event.target.result + '" alt="Preview">';
				};
				reader.readAsDataURL(file);
			}
		});
	}

	document.addEventListener('DOMContentLoaded', function() {
		if (typeof lucide !== 'undefined') {
			lucide.createIcons();
		}
	});
</script>
@endsection
