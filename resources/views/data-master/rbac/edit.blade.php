@extends('layouts.horizon')

@section('page_title', 'Edit Role & Permission')
@section('page_subtitle', 'Kelola hak akses dan wewenang menu untuk role ' . $role->display_name)

@section('content')
<div style="margin-bottom: 20px;">
	<a href="{{ route('data-master.rbac.index') }}" class="hz-btn-back" title="Kembali">
		<i data-lucide="arrow-left"></i>
	</a>
</div>

<form action="{{ route('data-master.rbac.update', $role) }}" method="POST">
	@csrf
	@method('PUT')
	
	<section class="hz-card" style="margin-bottom: 24px;">
		<div class="hz-card-header" style="margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #F1F5F9;">
			<p class="hz-card-title">Informasi Dasar Role</p>
			<p class="hz-card-sub">Nama tampilan dan status aktif role.</p>
		</div>

		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
			<div>
				<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
					Display Name (Nama Tampilan) <span style="color: #DC2626;">*</span>
				</label>
				<input type="text" name="display_name" value="{{ old('display_name', $role->display_name) }}" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; transition: all 0.2s ease; outline: none;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
			</div>

			<div>
				<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
					Status Role
				</label>
				<select name="is_active" style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; transition: all 0.2s ease; outline: none;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
					<option value="1" {{ old('is_active', $role->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
					<option value="0" {{ old('is_active', $role->is_active) == 0 ? 'selected' : '' }}>Non-aktif</option>
				</select>
			</div>
		</div>
	</section>

	<section class="hz-card">
		<div class="hz-card-header" style="margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #F1F5F9;">
			<p class="hz-card-title">Hak Akses Menu (Permissions)</p>
			<p class="hz-card-sub">Centang menu apa saja yang diizinkan untuk diakses oleh role ini.</p>
		</div>

		@php
			$availablePermissions = [
				'dashboard' => ['label' => 'Dashboard', 'desc' => 'Melihat ringkasan metrik dan analitik', 'icon' => 'layout-dashboard'],
				'produk' => ['label' => 'Produk / Menu', 'desc' => 'Melihat dan mengelola katalog produk', 'icon' => 'package'],
				'pos' => ['label' => 'POS Kasir', 'desc' => 'Mengoperasikan kasir dan cetak struk', 'icon' => 'shopping-cart'],
				'riwayat_penjualan' => ['label' => 'Riwayat Penjualan', 'desc' => 'Melihat transaksi riwayat order kasir', 'icon' => 'history'],
				'pengeluaran' => ['label' => 'Pengeluaran', 'desc' => 'Mencatat pengeluaran operasional toko', 'icon' => 'receipt'],
				'laporan' => ['label' => 'Laporan', 'desc' => 'Laporan keuangan dan close order shift', 'icon' => 'bar-chart-3'],
				'data_master' => ['label' => 'Data Master', 'desc' => 'Mengelola kategori, pengguna, dan RBAC', 'icon' => 'database'],
			];
			$currentPerms = $role->permissions ?? [];
		@endphp

		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 14px;">
			@foreach($availablePermissions as $key => $info)
				@php $isChecked = in_array($key, $currentPerms); @endphp
				<label style="display: flex; align-items: flex-start; gap: 12px; padding: 14px 16px; border-radius: 12px; border: 1.5px solid {{ $isChecked ? 'rgba(197, 160, 89, 0.7)' : '#E2E8F0' }}; background: {{ $isChecked ? '#FAF9F6' : '#FFFFFF' }}; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.boxShadow='0 0 12px rgba(197, 160, 89, 0.2)'" onmouseout="this.style.boxShadow='none'">
					<input type="checkbox" name="permissions[]" value="{{ $key }}" id="perm-{{ $key }}" {{ $isChecked ? 'checked' : '' }} style="margin-top: 3px; width: 18px; height: 18px; accent-color: #0F223D; cursor: pointer;">
					<div style="flex: 1;">
						<div style="display: flex; align-items: center; gap: 6px;">
							<i data-lucide="{{ $info['icon'] }}" style="width: 15px; height: 15px; color: #0F223D;"></i>
							<span style="font-size: 0.875rem; font-weight: 600; color: #0F223D;">{{ $info['label'] }}</span>
						</div>
						<p style="font-size: 0.75rem; color: #64748B; margin: 4px 0 0;">{{ $info['desc'] }}</p>
					</div>
				</label>
			@endforeach
		</div>

		<div style="display: flex; gap: 12px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #F1F5F9;">
			<button type="submit" class="hz-btn hz-btn-primary" style="padding: 10px 24px;">
				<i data-lucide="check" style="width: 16px; height: 16px;"></i>
				Simpan Perubahan
			</button>
			<a href="{{ route('data-master.rbac.index') }}" class="hz-btn hz-btn-secondary" style="padding: 10px 20px;">
				Batal
			</a>
		</div>
	</section>
</form>

<script>
	if (typeof lucide !== 'undefined') {
		lucide.createIcons();
	}
</script>
@endsection
