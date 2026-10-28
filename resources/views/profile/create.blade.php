@extends('layouts.horizon')

@section('page_title', isset($profile) ? 'Edit Profil' : 'Buat Profil')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">{{ isset($profile) ? 'Edit Profil Toko' : 'Atur Profil Toko' }}</h2>
			<p class="hz-top-sub">Lengkapi informasi identitas utama toko Anda.</p>
		</div>
	</div>

	@if ($errors->any())
		<div style="margin-top: 16px; padding: 10px 12px; border-radius: 10px; border:1px solid rgba(239,68,68,0.6); background: rgba(127,29,29,0.7); color:#fecaca; font-size:0.8rem;">
			<p><strong>⚠️ Terdapat kesalahan pada data yang Anda masukkan:</strong></p>
			<ul style="margin: 8px 0 0 20px; padding: 0;">
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1;">
			<form method="POST" action="{{ isset($profile) ? route('profile.update', $profile) : route('profile.store') }}" enctype="multipart/form-data">
				@csrf
				@if(isset($profile))
					@method('PUT')
				@endif

				<div style="display:flex; flex-wrap:wrap; gap: 20px;">
					<div style="flex:1 1 260px;">
						<p class="hz-card-title">Identitas Toko</p>
						<div class="hz-form-grid">
							<label class="hz-form-label">Nama Toko
								<input type="text" name="store_name" value="{{ old('store_name', $profile->store_name ?? '') }}" class="hz-input @error('store_name') hz-input-error @enderror" required>
							</label>
							@error('store_name')
								<p class="hz-error-text">{{ $message }}</p>
							@enderror

							<label class="hz-form-label">Logo Toko (PNG tanpa background, berbentuk bulat)
								<div style="display: flex; gap: 12px; margin-top: 8px;">
									<div style="flex: 1;">
										<div style="position: relative; display: inline-block; width: 100%;">
											<input type="file" name="logo" accept=".png,image/png" class="hz-file-input @error('logo') hz-input-error @enderror" id="logoInput" style="cursor: pointer;">
										</div>
										<p style="font-size: 0.75rem; color: #64748b; margin-top: 4px;">📁 Format: PNG | Max: 5MB | Disarankan ukuran 512x512px</p>
									</div>
									<div style="width: 100px; flex-shrink: 0;">
										<div id="logoPreview" style="width: 100px; height: 100px; border-radius: 50%; background: rgba(148,163,184,0.2); border: 2px solid rgba(148,163,184,0.4); display: flex; align-items: center; justify-content: center; overflow: hidden;">
											@if($profile && $profile->logo_path)
												<img src="{{ asset('storage/' . $profile->logo_path) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
											@else
												<p style="font-size: 0.7rem; color: #64748b; text-align: center;">Belum ada logo</p>
											@endif
										</div>
									</div>
								</div>
							</label>
							@error('logo')
								<p class="hz-error-text">❌ {{ $message }}</p>
							@enderror

							<label class="hz-form-label">Nama Pemilik
								<input type="text" name="owner_name" value="{{ old('owner_name', $profile->owner_name ?? '') }}" class="hz-input @error('owner_name') hz-input-error @enderror">
							</label>
							@error('owner_name')
								<p class="hz-error-text">{{ $message }}</p>
							@enderror

							<label class="hz-form-label">Telepon / WA
								<input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" class="hz-input @error('phone') hz-input-error @enderror">
							</label>
							@error('phone')
								<p class="hz-error-text">{{ $message }}</p>
							@enderror

							<label class="hz-form-label">Email
								<input type="email" name="email" value="{{ old('email', $profile->email ?? '') }}" class="hz-input @error('email') hz-input-error @enderror">
							</label>
							@error('email')
								<p class="hz-error-text">{{ $message }}</p>
							@enderror

							<label class="hz-form-label">Alamat Lengkap
								<textarea name="address" rows="3" class="hz-input @error('address') hz-input-error @enderror">{{ old('address', $profile->address ?? '') }}</textarea>
							</label>
							@error('address')
								<p class="hz-error-text">{{ $message }}</p>
							@enderror

							<div style="display:flex; gap:10px;">
								<label class="hz-form-label" style="flex:2;">Kota / Kabupaten
									<input type="text" name="city" value="{{ old('city', $profile->city ?? '') }}" class="hz-input @error('city') hz-input-error @enderror">
								</label>
								<label class="hz-form-label" style="flex:1;">Kode Pos
									<input type="text" name="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}" class="hz-input @error('postal_code') hz-input-error @enderror">
								</label>
							</div>
							@error('city')
								<p class="hz-error-text">{{ $message }}</p>
							@enderror
							@error('postal_code')
								<p class="hz-error-text">{{ $message }}</p>
							@enderror
						</div>
					</div>
				</div>

				<div style="margin-top: 18px; display:flex; justify-content:flex-end; gap:10px;">
					<a href="{{ route('profile.index') }}" class="hz-btn-outline">Batal</a>
					<button type="submit" class="hz-btn-primary">Simpan</button>
				</div>
			</form>
		</div>
	</section>

	<style>
		.hz-form-grid {
			margin-top: 12px;
			display: flex;
			flex-direction: column;
			gap: 10px;
		}
		.hz-form-label {
			font-size: 0.8rem;
			color: #94a3b8;
		display: flex;
			flex-direction: column;
			gap: 4px;
		}
		.hz-input {
			border-radius: 10px;
			border: 1px solid rgba(148,163,184,0.9);
			background: #ffffff;
			padding: 8px 10px;
			color: #1e293b;
			font-size: 0.8rem;
			outline: none;
			resize: vertical;
		}
		.hz-input:focus {
			border-color: #3b82f6;
			box-shadow: 0 0 0 1px rgba(251,191,36,0.6);
		}
		.hz-input-error {
			border-color: rgba(239,68,68,0.9);
		}
		.hz-error-text {
			font-size: 0.7rem;
			color: #991b1b;
		}
		.hz-file-input {
			border-radius: 10px;
			border: 1px dashed rgba(51,65,85,0.9);
			background: rgba(255,255,255,0.95);
			padding: 10px;
			color: #1e293b;
			font-size: 0.8rem;
			outline: none;
			cursor: pointer;
		}
		.hz-file-input:hover {
			border-color: #3b82f6;
		}
	</style>

	<script>
		const logoInput = document.getElementById('logoInput');
		const logoPreview = document.getElementById('logoPreview');

		if (logoInput) {
			logoInput.addEventListener('change', function(e) {
				const file = e.target.files[0];
				console.log('File selected:', file);
				
				if (file) {
					// Validate file size (5MB max)
					const maxSize = 5 * 1024 * 1024; // 5MB
					if (file.size > maxSize) {
						alert('❌ Ukuran file terlalu besar! Max 5MB');
						return;
					}

					// Validate file type
					if (!file.type.includes('image') && !file.name.endsWith('.png')) {
						alert('❌ File harus PNG!');
						return;
					}

					const reader = new FileReader();
					reader.onload = function(event) {
						logoPreview.style.background = 'transparent';
						logoPreview.innerHTML = '<img src="' + event.target.result + '" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">';
						console.log('Preview updated');
					};
					reader.onerror = function() {
						alert('❌ Gagal membaca file!');
					};
					reader.readAsDataURL(file);
				}
			});
		}
	</script>
@endsection

