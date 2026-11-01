@extends('layouts.horizon')

@section('page_title', 'Edit Pengguna')

@section('content')
<div class="hz-top-row">
	<div>
		<h2 class="hz-top-title">Edit Pengguna</h2>
		<p class="hz-top-sub">Perbarui data pengguna</p>
	</div>
	<div class="hz-top-actions">
		<a href="{{ route('data-master.pengguna.index') }}" class="hz-btn hz-btn-secondary">
			← Kembali
		</a>
	</div>
</div>

@if ($errors->any())
	<div style="margin-top: 20px; padding: 14px 16px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 10px; color: #dc2626;">
		@foreach ($errors->all() as $error)
			<div>{{ $error }}</div>
		@endforeach
	</div>
@endif

<form action="{{ route('data-master.pengguna.update', $pengguna) }}" method="POST" style="margin-top: 24px;">
	@csrf
	@method('PUT')
	<section class="hz-secondary-grid">
		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 16px;">
				<p class="hz-card-title">Informasi Pengguna</p>
			</div>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Nama <span style="color:#3b82f6;">*</span></label>
					<input type="text" name="name" value="{{ old('name', $pengguna->name) }}" required style="padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.4); background:rgba(255,255,255,0.95); color:#1e293b; font-size:0.9rem;">
				</div>
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Email <span style="color:#3b82f6;">*</span></label>
					<input type="email" name="email" value="{{ old('email', $pengguna->email) }}" required style="padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.4); background:rgba(255,255,255,0.95); color:#1e293b; font-size:0.9rem;">
				</div>
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Password</label>
					<input type="password" name="password" placeholder="Biarkan kosong jika tidak ingin mengubah" style="padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.4); background:rgba(255,255,255,0.95); color:#1e293b; font-size:0.9rem;">
				</div>
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Role <span style="color:#3b82f6;">*</span></label>
					<select name="role" required style="padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.4); background:rgba(255,255,255,0.95); color:#1e293b; font-size:0.9rem;">
						<option value="owner" {{ old('role', $pengguna->role) == 'owner' ? 'selected' : '' }}>Owner</option>
						<option value="admin" {{ old('role', $pengguna->role) == 'admin' ? 'selected' : '' }}>Admin</option>
						<option value="kasir" {{ old('role', $pengguna->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
					</select>
				</div>
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Status</label>
					<select name="is_active" style="padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.4); background:rgba(255,255,255,0.95); color:#1e293b; font-size:0.9rem;">
						<option value="1" {{ old('is_active', $pengguna->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
						<option value="0" {{ old('is_active', $pengguna->is_active) == 0 ? 'selected' : '' }}>Non-aktif</option>
					</select>
				</div>
			</div>
		</div>
		<div style="margin-top: 24px; display: flex; gap: 8px;">
			<button type="submit" class="hz-btn hz-btn-primary">Simpan</button>
			<a href="{{ route('data-master.pengguna.index') }}" class="hz-btn hz-btn-secondary">Batal</a>
		</div>
	</section>
</form>
@endsection
