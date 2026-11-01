@extends('layouts.horizon')

@section('page_title', 'Data Master - RBAC')
@section('page_subtitle', 'Role Based Access Control: Pengaturan wewenang dan izin menu untuk setiap role.')

@section('content')
<!-- Top Actions -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
	<a href="{{ route('data-master.index') }}" class="hz-btn-back" title="Kembali ke Data Master">
		<i data-lucide="arrow-left"></i>
	</a>
	<a href="{{ route('data-master.rbac.create') }}" class="hz-btn hz-btn-primary">
		<i data-lucide="plus" style="width: 16px; height: 16px;"></i>
		<span>Tambah Role</span>
	</a>
</div>

@if ($message = Session::get('success'))
	<div style="margin-bottom: 20px; padding: 12px 16px; background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 10px; color: #065F46; font-size: 0.85rem; display: flex; align-items: center; gap: 8px;">
		<i data-lucide="check-circle" style="width: 16px; height: 16px; color: #10B981;"></i>
		<span>{{ $message }}</span>
	</div>
@endif

<section class="hz-card">
	<div class="hz-card-header" style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
		<p class="hz-card-title">Daftar Role & Hak Akses</p>
		<span style="font-size: 0.78rem; font-weight: 600; color: #64748B; background: #F8FAFC; padding: 4px 10px; border-radius: 999px; border: 1px solid #E2E8F0;">
			Total: {{ $roles->total() ?? $roles->count() }} Role
		</span>
	</div>

	<div class="hz-card-body" style="padding: 0; overflow-x: auto;">
		@if ($roles->count() > 0)
			<table style="width: 100%; border-collapse: separate; border-spacing: 0; font-size: 0.875rem;">
				<thead>
					<tr>
						<th style="padding: 12px 16px; text-align: left; font-size: 0.725rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; border-top: 1px solid #E2E8F0;">Name</th>
						<th style="padding: 12px 16px; text-align: left; font-size: 0.725rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; border-top: 1px solid #E2E8F0;">Display Name</th>
						<th style="padding: 12px 16px; text-align: left; font-size: 0.725rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; border-top: 1px solid #E2E8F0;">Permissions (Menu Akses)</th>
						<th style="padding: 12px 16px; text-align: left; font-size: 0.725rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; border-top: 1px solid #E2E8F0;">Status</th>
						<th style="padding: 12px 16px; text-align: right; font-size: 0.725rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; border-top: 1px solid #E2E8F0;">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($roles as $role)
						<tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease;" onmouseover="this.style.background='#FAF9F6'" onmouseout="this.style.background='transparent'">
							<td style="padding: 14px 16px; font-weight: 600; color: #0F223D; border-bottom: 1px solid #F1F5F9;">
								<code>{{ $role->name }}</code>
							</td>
							<td style="padding: 14px 16px; color: #334155; font-weight: 500; border-bottom: 1px solid #F1F5F9;">
								{{ $role->display_name }}
							</td>
							<td style="padding: 14px 16px; border-bottom: 1px solid #F1F5F9; max-width: 380px;">
								@if(!empty($role->permissions) && count($role->permissions) > 0)
									<div style="display: flex; flex-wrap: wrap; gap: 5px;">
										@foreach($role->permissions as $perm)
											<span style="display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 6px; background: #F8FAFC; color: #334155; font-size: 0.73rem; font-weight: 500; border: 1px solid #E2E8F0;">
												{{ ucfirst(str_replace('_', ' ', $perm)) }}
											</span>
										@endforeach
									</div>
								@else
									<span style="color: #94A3B8; font-style: italic; font-size: 0.8rem;">Tidak ada izin</span>
								@endif
							</td>
							<td style="padding: 14px 16px; border-bottom: 1px solid #F1F5F9;">
								@if($role->is_active)
									<span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; background: #ECFDF5; color: #059669; font-size: 0.75rem; font-weight: 600; border: 1px solid #A7F3D0;">
										<span style="width: 6px; height: 6px; border-radius: 999px; background: #10B981;"></span>
										Aktif
									</span>
								@else
									<span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; background: #F1F5F9; color: #64748B; font-size: 0.75rem; font-weight: 600; border: 1px solid #CBD5E1;">
										<span style="width: 6px; height: 6px; border-radius: 999px; background: #94A3B8;"></span>
										Non-aktif
									</span>
								@endif
							</td>
							<td style="padding: 14px 16px; text-align: right; border-bottom: 1px solid #F1F5F9;">
								<div style="display: inline-flex; align-items: center; gap: 8px; justify-content: flex-end;">
									<a href="{{ route('data-master.rbac.edit', $role) }}" style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; border-radius: 8px; background: #FFFFFF; color: #0F223D; border: 1px solid #CBD5E1; font-size: 0.78rem; font-weight: 600; text-decoration: none; transition: all 0.15s ease;" onmouseover="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 10px rgba(197, 160, 89, 0.25)'" onmouseout="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
										<i data-lucide="edit-2" style="width: 13px; height: 13px;"></i>
										Edit
									</a>
									<form action="{{ route('data-master.rbac.destroy', $role) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus hak akses role {{ $role->display_name }}?');">
										@csrf
										@method('DELETE')
										<button type="submit" style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; border-radius: 8px; background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; font-size: 0.78rem; font-weight: 600; cursor: pointer; transition: all 0.15s ease;" onmouseover="this.style.background='#FEE2E2'; this.style.boxShadow='0 0 10px rgba(220, 38, 38, 0.2)'" onmouseout="this.style.background='#FEF2F2'; this.style.boxShadow='none'">
											<i data-lucide="trash-2" style="width: 13px; height: 13px;"></i>
											Hapus
										</button>
									</form>
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<div style="padding: 16px 20px;">
				{{ $roles->links() }}
			</div>
		@else
			<div style="padding: 48px 24px; text-align: center; color: #64748B;">
				<i data-lucide="shield-alert" style="width: 36px; height: 36px; color: #94A3B8; margin: 0 auto 12px; display: block;"></i>
				<p style="font-weight: 600; color: #0F223D; margin-bottom: 4px;">Belum Ada Data Role RBAC</p>
				<p style="font-size: 0.85rem; margin-bottom: 16px;">Tambahkan role untuk mengatur permission akses menu.</p>
				<a href="{{ route('data-master.rbac.create') }}" class="hz-btn hz-btn-primary" style="display: inline-flex;">
					<i data-lucide="plus" style="width: 15px; height: 15px;"></i>
					Tambah Role Pertama
				</a>
			</div>
		@endif
	</div>
</section>

<script>
	if (typeof lucide !== 'undefined') {
		lucide.createIcons();
	}
</script>
@endsection
