@extends('layouts.horizon')

@section('page_title', 'Edit Role')

@section('content')
<div class="hz-top-row">
	<div>
		<h2 class="hz-top-title">Edit Role</h2>
		<p class="hz-top-sub">Perbarui data role dan permission</p>
	</div>
	<div class="hz-top-actions">
		<a href="{{ route('data-master.rbac.index') }}" class="hz-btn hz-btn-secondary">
			← Kembali
		</a>
	</div>
</div>

<form action="{{ route('data-master.rbac.update', $role) }}" method="POST" style="margin-top: 24px;">
	@csrf
	@method('PUT')
	<section class="hz-secondary-grid">
		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 16px;">
				<p class="hz-card-title">Informasi Role</p>
			</div>
			<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Display Name <span style="color:#3b82f6;">*</span></label>
					<input type="text" name="display_name" value="{{ old('display_name', $role->display_name) }}" required style="padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.4); background:rgba(255,255,255,0.95); color:#1e293b; font-size:0.9rem;">
				</div>
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Status</label>
					<select name="is_active" style="padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.4); background:rgba(255,255,255,0.95); color:#1e293b; font-size:0.9rem;">
						<option value="1" {{ old('is_active', $role->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
						<option value="0" {{ old('is_active', $role->is_active) == 0 ? 'selected' : '' }}>Non-aktif</option>
					</select>
				</div>
				<div style="display:flex; flex-direction:column; gap:6px; grid-column: 1 / -1;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Permissions (Menu yang boleh diakses)</label>
					<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 8px;">
						<div style="display:flex; align-items:center; gap:8px;">
							<input type="checkbox" name="permissions[]" value="dashboard" id="perm-dashboard" {{ in_array('dashboard', $role->permissions ?? []) ? 'checked' : '' }} style="width:16px; height:16px;">
							<label for="perm-dashboard" style="font-size:0.85rem; color:#1e293b;">Dashboard</label>
						</div>
						<div style="display:flex; align-items:center; gap:8px;">
							<input type="checkbox" name="permissions[]" value="produk" id="perm-produk" {{ in_array('produk', $role->permissions ?? []) ? 'checked' : '' }} style="width:16px; height:16px;">
							<label for="perm-produk" style="font-size:0.85rem; color:#1e293b;">Produk/Menu</label>
						</div>
						<div style="display:flex; align-items:center; gap:8px;">
							<input type="checkbox" name="permissions[]" value="pos" id="perm-pos" {{ in_array('pos', $role->permissions ?? []) ? 'checked' : '' }} style="width:16px; height:16px;">
							<label for="perm-pos" style="font-size:0.85rem; color:#1e293b;">POS Kasir</label>
						</div>
						<div style="display:flex; align-items:center; gap:8px;">
							<input type="checkbox" name="permissions[]" value="riwayat_penjualan" id="perm-riwayat" {{ in_array('riwayat_penjualan', $role->permissions ?? []) ? 'checked' : '' }} style="width:16px; height:16px;">
							<label for="perm-riwayat" style="font-size:0.85rem; color:#1e293b;">Riwayat Penjualan</label>
						</div>
						<div style="display:flex; align-items:center; gap:8px;">
							<input type="checkbox" name="permissions[]" value="pengeluaran" id="perm-pengeluaran" {{ in_array('pengeluaran', $role->permissions ?? []) ? 'checked' : '' }} style="width:16px; height:16px;">
							<label for="perm-pengeluaran" style="font-size:0.85rem; color:#1e293b;">Pengeluaran</label>
						</div>
						<div style="display:flex; align-items:center; gap:8px;">
							<input type="checkbox" name="permissions[]" value="laporan" id="perm-laporan" {{ in_array('laporan', $role->permissions ?? []) ? 'checked' : '' }} style="width:16px; height:16px;">
							<label for="perm-laporan" style="font-size:0.85rem; color:#1e293b;">Laporan</label>
						</div>
						<div style="display:flex; align-items:center; gap:8px;">
							<input type="checkbox" name="permissions[]" value="data_master" id="perm-data-master" {{ in_array('data_master', $role->permissions ?? []) ? 'checked' : '' }} style="width:16px; height:16px;">
							<label for="perm-data-master" style="font-size:0.85rem; color:#1e293b;">Data Master</label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div style="margin-top: 24px; display: flex; gap: 8px;">
			<button type="submit" class="hz-btn hz-btn-primary">Simpan</button>
			<a href="{{ route('data-master.rbac.index') }}" class="hz-btn hz-btn-secondary">Batal</a>
		</div>
	</section>
</form>
@endsection
