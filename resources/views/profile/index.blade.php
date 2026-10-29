@extends('layouts.horizon')

@section('page_title', auth()->user()?->role === 'superadmin' ? 'Edit Profil Admin' : 'Edit Profil Toko')

@section('content')
<style>
	.page-header-row {
		margin-bottom: 24px;
	}

	.page-title-block h2 {
		font-family: 'Newsreader', Georgia, serif;
		font-size: 2.2rem;
		font-weight: 500;
		color: #0F223D;
		margin: 0 0 4px 0;
		line-height: 1.2;
	}

	.page-title-block p {
		font-size: 0.925rem;
		color: #64748B;
		margin: 0;
	}

	/* Tab Navigation */
	.profile-tab-bar {
		display: flex;
		gap: 12px;
		margin-bottom: 24px;
		border-bottom: 1px solid #E2E8F0;
		padding-bottom: 12px;
	}

	.tab-pill-btn {
		display: inline-flex;
		align-items: center;
		gap: 8px;
		padding: 8px 16px;
		border-radius: 10px;
		background: #FFFFFF;
		border: 1px solid #E2E8F0;
		color: #64748B;
		font-family: inherit;
		font-size: 0.85rem;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.15s ease;
	}

	.tab-pill-btn:hover {
		background: #F8FAFC;
		color: #0F223D;
		border-color: #CBD5E1;
	}

	.tab-pill-btn.active {
		background: #0F223D;
		color: #FFFFFF;
		border-color: #0F223D;
		box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15);
	}

	/* Form Card */
	.form-profile-card {
		background: #FFFFFF;
		border-radius: 18px;
		border: 1px solid rgba(226, 232, 240, 0.95);
		padding: 32px;
		width: 100%;
		max-width: 100%;
		box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04);
		margin-bottom: 32px;
	}

	.section-divider-title {
		display: flex;
		align-items: center;
		gap: 10px;
		padding-bottom: 12px;
		margin-top: 28px;
		margin-bottom: 20px;
		border-bottom: 1px solid #F1F5F9;
	}

	.section-divider-title:first-of-type {
		margin-top: 0;
	}

	.section-divider-title h4 {
		font-family: 'Newsreader', Georgia, serif;
		font-size: 1.25rem;
		font-weight: 600;
		color: #0F223D;
		margin: 0;
	}

	.section-divider-title span {
		font-size: 0.775rem;
		color: #94A3B8;
		font-weight: 500;
	}

	/* Avatar & Logo Row */
	.avatar-upload-row {
		display: flex;
		align-items: center;
		gap: 24px;
		padding: 20px;
		background: #FAF8F5;
		border: 1px solid #E2E8F0;
		border-radius: 14px;
		margin-bottom: 24px;
		flex-wrap: wrap;
	}

	.avatar-preview-circle {
		width: 84px;
		height: 84px;
		border-radius: 50%;
		background: #0F223D;
		color: #FFFFFF;
		display: flex;
		align-items: center;
		justify-content: center;
		font-family: 'Newsreader', Georgia, serif;
		font-size: 2.2rem;
		font-weight: 600;
		overflow: hidden;
		flex-shrink: 0;
		border: 3px solid #FFFFFF;
		box-shadow: 0 4px 12px rgba(15, 34, 61, 0.12);
	}

	.avatar-preview-circle img {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}

	.avatar-upload-actions {
		flex: 1;
		min-width: 220px;
	}

	.avatar-upload-actions label {
		display: block;
		font-size: 0.85rem;
		font-weight: 600;
		color: #0F223D;
		margin-bottom: 6px;
	}

	.avatar-upload-actions p {
		font-size: 0.775rem;
		color: #64748B;
		margin: 6px 0 0 0;
	}

	/* Form Elements */
	.form-group {
		display: flex;
		flex-direction: column;
		gap: 6px;
		margin-bottom: 20px;
	}

	.form-grid-two {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 20px;
	}

	@media (max-width: 768px) {
		.form-grid-two {
			grid-template-columns: 1fr;
		}
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

	.form-submit-row {
		display: flex;
		align-items: center;
		justify-content: flex-end;
		gap: 12px;
		margin-top: 28px;
		padding-top: 20px;
		border-top: 1px solid #F1F5F9;
	}

	.btn-save-profile {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 11px 28px;
		border-radius: 10px;
		background: #0F223D;
		color: #FFFFFF;
		font-family: inherit;
		font-size: 0.875rem;
		font-weight: 600;
		border: none;
		cursor: pointer;
		transition: all 0.15s ease;
		box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15);
	}

	.btn-save-profile:hover {
		background: #162C50;
		transform: translateY(-1px);
		box-shadow: 0 4px 12px rgba(15, 34, 61, 0.2);
	}

	.flash-alert-success {
		margin-bottom: 24px;
		padding: 12px 18px;
		border-radius: 10px;
		font-size: 0.85rem;
		display: flex;
		align-items: center;
		gap: 10px;
		background: #ECFDF5;
		border: 1px solid #A7F3D0;
		color: #065F46;
	}

	.flash-alert-error {
		margin-bottom: 24px;
		padding: 12px 18px;
		border-radius: 10px;
		font-size: 0.85rem;
		display: flex;
		align-items: flex-start;
		gap: 10px;
		background: #FEF2F2;
		border: 1px solid #FECACA;
		color: #991B1B;
	}
</style>

<div class="page-header-row">
	<div class="page-title-block">
		<h2>{{ auth()->user()?->role === 'superadmin' ? 'Edit Profil Superadmin' : 'Edit Profil Toko' }}</h2>
		<p>Kelola data akun login, kata sandi, dan identitas profil Anda.</p>
	</div>
</div>

@if (session('success'))
	<div class="flash-alert-success">
		<i data-lucide="check-circle-2" style="width: 18px; height: 18px; flex-shrink: 0;"></i>
		<span>{{ session('success') }}</span>
	</div>
@endif

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

<!-- Tab Navigation (Jika Owner) -->
@if(auth()->user()?->role === 'owner')
	<div class="profile-tab-bar">
		<button type="button" 
			id="tab-profil-btn" 
			class="tab-pill-btn active"
			onclick="switchTab('profil')">
			<i data-lucide="user" style="width: 15px; height: 15px;"></i>
			<span>Edit Profil</span>
		</button>
		<button type="button" 
			id="tab-berlangganan-btn" 
			class="tab-pill-btn"
			onclick="switchTab('berlangganan')">
			<i data-lucide="sparkles" style="width: 15px; height: 15px;"></i>
			<span>Paket Berlangganan</span>
		</button>
	</div>
@endif

<!-- Tab Content: Form Edit Profil -->
<div id="content-profil" class="tab-pane">
	<div class="form-profile-card">
		<form method="POST" action="{{ route('profile.update', $profile) }}" enctype="multipart/form-data">
			@csrf
			@method('PUT')

			<!-- 1. Foto Profil / Logo -->
			<div class="avatar-upload-row">
				<div id="logoPreview" class="avatar-preview-circle">
					@if($profile && $profile->logo_path)
						<img src="{{ asset('storage/' . $profile->logo_path) }}" alt="{{ $profile->store_name }}">
					@else
						{{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
					@endif
				</div>
				<div class="avatar-upload-actions">
					<label for="logoInput">
						{{ auth()->user()?->role === 'superadmin' ? 'Foto Profil / Logo Brand' : 'Logo Toko' }}
					</label>
					<input type="file" name="logo" id="logoInput" accept="image/png,image/jpeg,image/jpg" 
						class="form-input" style="padding: 7px 10px; background: #FFFFFF; font-size: 0.8rem; cursor: pointer;">
					<p>Format yang didukung: PNG, JPG, JPEG (maks. 5MB). Rekomendasi rasio 1:1 (persegi/bulat).</p>
					@error('logo')
						<span class="field-error">{{ $message }}</span>
					@enderror
				</div>
			</div>

			<!-- 2. Data Akun Pengguna -->
			<div class="section-divider-title">
				<h4>Informasi Akun Pengguna</h4>
				<span>(Data utama untuk login ke dalam sistem)</span>
			</div>

			<div class="form-grid-two">
				<div class="form-group">
					<label class="form-label" for="name">
						Nama Lengkap <span class="req">*</span>
					</label>
					<input type="text" id="name" name="name" 
						value="{{ old('name', $user->name) }}" 
						placeholder="Contoh: Wahyu Widodo" 
						class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}" required>
					@error('name')
						<span class="field-error">{{ $message }}</span>
					@enderror
				</div>

				<div class="form-group">
					<label class="form-label" for="email">
						Alamat Email Login <span class="req">*</span>
					</label>
					<input type="email" id="email" name="email" 
						value="{{ old('email', $user->email) }}" 
						placeholder="Contoh: user@aksara.com" 
						class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}" required>
					@error('email')
						<span class="field-error">{{ $message }}</span>
					@enderror
				</div>
			</div>

			<div class="form-grid-two">
				<div class="form-group">
					<label class="form-label" for="password">
						Kata Sandi Baru <span style="color:#64748B; font-weight:normal;">[Opsional]</span>
					</label>
					<input type="password" id="password" name="password" 
						placeholder="Kosongkan jika tidak ingin mengubah kata sandi" 
						class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}">
					<span class="field-hint">Minimal 8 karakter jika ingin diperbarui.</span>
					@error('password')
						<span class="field-error">{{ $message }}</span>
					@enderror
				</div>

				<div class="form-group">
					<label class="form-label" for="password_confirmation">
						Konfirmasi Kata Sandi Baru
					</label>
					<input type="password" id="password_confirmation" name="password_confirmation" 
						placeholder="Ulangi kata sandi baru di atas" 
						class="form-input">
				</div>
			</div>

			<!-- 3. Identitas Profil / Toko -->
			<div class="section-divider-title">
				<h4>{{ auth()->user()?->role === 'superadmin' ? 'Identitas Sistem & Kontak' : 'Identitas Toko & Usaha' }}</h4>
				<span>(Informasi publik yang ditampilkan pada nota dan profil)</span>
			</div>

			<div class="form-grid-two">
				<div class="form-group">
					<label class="form-label" for="store_name">
						{{ auth()->user()?->role === 'superadmin' ? 'Nama Sistem / Brand' : 'Nama Toko' }} <span class="req">*</span>
					</label>
					<input type="text" id="store_name" name="store_name" 
						value="{{ old('store_name', $profile->store_name ?? ($user->role === 'superadmin' ? 'Aksara Developer' : '')) }}" 
						placeholder="{{ auth()->user()?->role === 'superadmin' ? 'Contoh: Aksara Developer' : 'Contoh: Toko Berkah Aksara' }}" 
						class="form-input {{ $errors->has('store_name') ? 'is-invalid' : '' }}" required>
					@error('store_name')
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

			<!-- Tombol Simpan -->
			<div class="form-submit-row">
				<button type="submit" class="btn-save-profile">
					<i data-lucide="check" style="width: 16px; height: 16px;"></i>
					<span>Simpan Perubahan Profil</span>
				</button>
			</div>
		</form>
	</div>
</div>

<!-- Tab Content: Berlangganan (Owner Only) -->
@if(auth()->user()?->role === 'owner')
	<div id="content-berlangganan" class="tab-pane" style="display: none;">
		@include('subscribe.content')
	</div>
@endif

<script>
	// Tab Switcher
	function switchTab(tabName) {
		const profilBtn = document.getElementById('tab-profil-btn');
		const langgananBtn = document.getElementById('tab-berlangganan-btn');
		const contentProfil = document.getElementById('content-profil');
		const contentLangganan = document.getElementById('content-berlangganan');

		if (tabName === 'profil') {
			if (profilBtn) profilBtn.classList.add('active');
			if (langgananBtn) langgananBtn.classList.remove('active');
			if (contentProfil) contentProfil.style.display = 'block';
			if (contentLangganan) contentLangganan.style.display = 'none';
		} else if (tabName === 'berlangganan') {
			if (profilBtn) profilBtn.classList.remove('active');
			if (langgananBtn) langgananBtn.classList.add('active');
			if (contentProfil) contentProfil.style.display = 'none';
			if (contentLangganan) contentLangganan.style.display = 'block';
		}

		if (typeof lucide !== 'undefined') {
			lucide.createIcons();
		}
	}

	// Live Preview Logo
	const logoInput = document.getElementById('logoInput');
	const logoPreview = document.getElementById('logoPreview');

	if (logoInput && logoPreview) {
		logoInput.addEventListener('change', function(e) {
			const file = e.target.files[0];
			if (file) {
				const maxSize = 5 * 1024 * 1024;
				if (file.size > maxSize) {
					alert('Ukuran file maksimal adalah 5MB.');
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
