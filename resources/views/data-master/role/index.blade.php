@extends('layouts.horizon')

@section('page_title', 'Role')
@section('page_subtitle', 'Kelola role pengguna (owner, admin, kasir).')

@section('content')
<!-- Top Actions -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
	<a href="{{ route('data-master.index') }}" class="hz-btn-back" title="Kembali ke Data Master">
		<i data-lucide="arrow-left"></i>
	</a>
	<button type="button" onclick="openTambahRoleModal()" class="hz-btn hz-btn-primary" style="cursor: pointer;">
		<i data-lucide="plus" style="width: 16px; height: 16px;"></i>
		<span>Tambah Role</span>
	</button>
</div>

@if ($message = Session::get('success'))
	<div style="margin-bottom: 20px; padding: 12px 16px; background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 10px; color: #065F46; font-size: 0.85rem; display: flex; align-items: center; gap: 8px;">
		<i data-lucide="check-circle" style="width: 16px; height: 16px; color: #10B981;"></i>
		<span>{{ $message }}</span>
	</div>
@endif

@if ($errors->any())
	<div style="margin-bottom: 20px; padding: 12px 16px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 10px; color: #DC2626; font-size: 0.85rem;">
		<ul style="margin: 0; padding-left: 20px;">
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif

<section class="hz-card">
	<div class="hz-card-header" style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
		<p class="hz-card-title">Daftar Role</p>
		<span style="font-size: 0.78rem; font-weight: 600; color: #64748B; background: #F8FAFC; padding: 4px 10px; border-radius: 999px; border: 1px solid #E2E8F0;">
			Total: {{ $roles->total() ?? $roles->count() }} Role
		</span>
	</div>
	<div class="hz-card-body" style="padding: 0; overflow-x: auto;">
		@if($roles->count() > 0)
			<table style="width: 100%; border-collapse: separate; border-spacing: 0; font-size: 0.875rem;">
				<thead>
					<tr>
						<th style="padding: 12px 16px; text-align: left; font-size: 0.725rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; border-top: 1px solid #E2E8F0;">Nama</th>
						<th style="padding: 12px 16px; text-align: left; font-size: 0.725rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; border-top: 1px solid #E2E8F0;">Display Name</th>
						<th style="padding: 12px 16px; text-align: left; font-size: 0.725rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; border-top: 1px solid #E2E8F0;">Status</th>
						<th style="padding: 12px 16px; text-align: right; font-size: 0.725rem; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.05em; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; border-top: 1px solid #E2E8F0;">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach($roles as $role)
						<tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease;" onmouseover="this.style.background='#FAF9F6'" onmouseout="this.style.background='transparent'">
							<td style="padding: 14px 16px; font-weight: 600; color: #0F223D; border-bottom: 1px solid #F1F5F9;">
								<code style="background: #F1F5F9; padding: 2px 6px; border-radius: 4px; font-size: 0.825rem;">{{ $role->name }}</code>
							</td>
							<td style="padding: 14px 16px; color: #334155; font-weight: 500; border-bottom: 1px solid #F1F5F9;">
								{{ $role->display_name }}
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
										Nonaktif
									</span>
								@endif
							</td>
							<td style="padding: 14px 16px; text-align: right; border-bottom: 1px solid #F1F5F9;">
								<div style="display: inline-flex; align-items: center; gap: 8px; justify-content: flex-end;">
									<button type="button" 
										onclick="openEditRoleModal('{{ $role->id }}', '{{ addslashes($role->name) }}', '{{ addslashes($role->display_name) }}', '{{ $role->is_active }}', '{{ route('data-master.role.update', $role) }}')" 
										style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 12px; border-radius: 8px; background: #FFFFFF; color: #0F223D; border: 1px solid #CBD5E1; font-size: 0.78rem; font-weight: 600; cursor: pointer; transition: all 0.15s ease;" onmouseover="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 10px rgba(197, 160, 89, 0.25)'" onmouseout="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
										<i data-lucide="edit-2" style="width: 13px; height: 13px;"></i>
										Edit
									</button>
									<form action="{{ route('data-master.role.destroy', $role) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus role {{ $role->display_name }}?');">
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
			<div style="padding: 16px;">
				{{ $roles->links() }}
			</div>
		@else
			<div style="text-align: center; padding: 48px; color: #64748B;">
				<i data-lucide="shield-check" style="width: 42px; height: 42px; color: #CBD5E1; margin: 0 auto 12px; display: block;"></i>
				<p style="margin: 0; font-size: 0.95rem; font-weight: 600; color: #0F223D;">Belum ada data role</p>
				<p style="margin: 4px 0 16px 0; font-size: 0.8rem; color: #64748B;">Mulai buat role baru untuk mengatur wewenang pengguna sistem.</p>
				<button type="button" onclick="openTambahRoleModal()" class="hz-btn hz-btn-primary" style="padding: 8px 18px; font-size: 0.825rem; cursor: pointer;">
					+ Tambah Role Baru
				</button>
			</div>
		@endif
	</div>
</section>

<!-- ==================== POP UP MODAL TAMBAH ROLE ==================== -->
<div id="modalTambahRole" style="display: none; position: fixed; inset: 0; background: rgba(15, 34, 61, 0.5); backdrop-filter: blur(6px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
	<div style="background: #FFFFFF; border-radius: 20px; max-width: 500px; width: 100%; padding: 28px; box-shadow: 0 25px 50px -12px rgba(15, 34, 61, 0.25); border: 1px solid #E2E8F0; animation: hzModalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1); position: relative;">
		
		<!-- Modal Header -->
		<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #F1F5F9;">
			<div>
				<h3 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.4rem; font-weight: 600; color: #0F223D; margin: 0; line-height: 1.2;">
					Tambah Role Baru
				</h3>
				<p style="font-size: 0.8rem; color: #64748B; margin: 4px 0 0 0;">
					Definisikan nama teknis role dan nama tampilan wewenangnya.
				</p>
			</div>
			<button type="button" onclick="closeTambahRoleModal()" style="border: none; background: #F8FAFC; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748B; cursor: pointer; transition: all 0.15s ease;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#0F223D'" onmouseout="this.style.background='#F8FAFC'; this.style.color='#64748B'">
				<i data-lucide="x" style="width: 18px; height: 18px;"></i>
			</button>
		</div>

		<!-- Modal Form -->
		<form action="{{ route('data-master.role.store') }}" method="POST">
			@csrf
			<div style="display: flex; flex-direction: column; gap: 16px;">
				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Nama Role (Identifier) <span style="color: #DC2626;">*</span>
					</label>
					<input type="text" name="name" id="tambah_role_name" value="{{ old('name') }}" placeholder="Contoh: manager, supervisor" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; outline: none; transition: all 0.15s ease;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
					<p style="font-size: 0.73rem; color: #64748B; margin: 4px 0 0;">Gunakan huruf kecil tanpa spasi.</p>
				</div>

				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Display Name (Nama Tampilan) <span style="color: #DC2626;">*</span>
					</label>
					<input type="text" name="display_name" id="tambah_role_display_name" value="{{ old('display_name') }}" placeholder="Contoh: Manager Operasional" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; outline: none; transition: all 0.15s ease;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
				</div>

				<!-- Actions -->
				<div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 8px; padding-top: 16px; border-top: 1px solid #F1F5F9;">
					<button type="button" onclick="closeTambahRoleModal()" style="padding: 10px 18px; border-radius: 10px; background: #FAF8F5; border: 1px solid #CBD5E1; color: #475569; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
						Batal
					</button>
					<button type="submit" class="hz-btn hz-btn-primary" style="padding: 10px 22px; cursor: pointer;">
						<i data-lucide="check" style="width: 16px; height: 16px;"></i>
						Simpan Role
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- ==================== POP UP MODAL EDIT ROLE ==================== -->
<div id="modalEditRole" style="display: none; position: fixed; inset: 0; background: rgba(15, 34, 61, 0.5); backdrop-filter: blur(6px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
	<div style="background: #FFFFFF; border-radius: 20px; max-width: 500px; width: 100%; padding: 28px; box-shadow: 0 25px 50px -12px rgba(15, 34, 61, 0.25); border: 1px solid #E2E8F0; animation: hzModalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1); position: relative;">
		
		<!-- Modal Header -->
		<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #F1F5F9;">
			<div>
				<h3 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.4rem; font-weight: 600; color: #0F223D; margin: 0; line-height: 1.2;">
					Edit Role
				</h3>
				<p style="font-size: 0.8rem; color: #64748B; margin: 4px 0 0 0;">
					Sesuaikan nama tampilan dan status keaktifan role ini.
				</p>
			</div>
			<button type="button" onclick="closeEditRoleModal()" style="border: none; background: #F8FAFC; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748B; cursor: pointer; transition: all 0.15s ease;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#0F223D'" onmouseout="this.style.background='#F8FAFC'; this.style.color='#64748B'">
				<i data-lucide="x" style="width: 18px; height: 18px;"></i>
			</button>
		</div>

		<!-- Modal Form -->
		<form id="formEditRole" action="" method="POST">
			@csrf
			@method('PUT')
			<div style="display: flex; flex-direction: column; gap: 16px;">
				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Nama Role (Identifier Sistem)
					</label>
					<input type="text" id="edit_role_name" disabled style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #E2E8F0; background: #F8FAFC; color: #64748B; font-size: 0.875rem; cursor: not-allowed;">
					<p style="font-size: 0.73rem; color: #64748B; margin: 4px 0 0;">Identifier teknis tidak dapat diubah.</p>
				</div>

				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Display Name (Nama Tampilan) <span style="color: #DC2626;">*</span>
					</label>
					<input type="text" name="display_name" id="edit_role_display_name" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; outline: none; transition: all 0.15s ease;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
				</div>

				<div style="padding: 12px 14px; border-radius: 10px; background: #F8FAFC; border: 1px solid #E2E8F0;">
					<label style="display: flex; align-items: center; gap: 10px; font-size: 0.85rem; font-weight: 600; color: #0F223D; cursor: pointer;">
						<input type="checkbox" name="is_active" id="edit_role_is_active" value="1" style="width: 18px; height: 18px; accent-color: #0F223D; cursor: pointer;">
						<span>Role Aktif</span>
					</label>
				</div>

				<!-- Actions -->
				<div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 8px; padding-top: 16px; border-top: 1px solid #F1F5F9;">
					<button type="button" onclick="closeEditRoleModal()" style="padding: 10px 18px; border-radius: 10px; background: #FAF8F5; border: 1px solid #CBD5E1; color: #475569; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
						Batal
					</button>
					<button type="submit" class="hz-btn hz-btn-primary" style="padding: 10px 22px; cursor: pointer;">
						<i data-lucide="check" style="width: 16px; height: 16px;"></i>
						Simpan Perubahan
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script>
function openTambahRoleModal() {
	const modal = document.getElementById('modalTambahRole');
	if (modal) {
		modal.style.display = 'flex';
		setTimeout(() => {
			const inp = document.getElementById('tambah_role_name');
			if (inp) inp.focus();
		}, 50);
	}
}

function closeTambahRoleModal() {
	const modal = document.getElementById('modalTambahRole');
	if (modal) modal.style.display = 'none';
}

function openEditRoleModal(id, name, displayName, isActive, updateUrl) {
	const modal = document.getElementById('modalEditRole');
	const form = document.getElementById('formEditRole');
	const nameInp = document.getElementById('edit_role_name');
	const displayInp = document.getElementById('edit_role_display_name');
	const activeBox = document.getElementById('edit_role_is_active');

	if (form) form.action = updateUrl;
	if (nameInp) nameInp.value = name;
	if (displayInp) displayInp.value = displayName;
	if (activeBox) activeBox.checked = (isActive == '1' || isActive == 'true' || isActive == true);

	if (modal) {
		modal.style.display = 'flex';
		setTimeout(() => {
			if (displayInp) displayInp.focus();
		}, 50);
	}
}

function closeEditRoleModal() {
	const modal = document.getElementById('modalEditRole');
	if (modal) modal.style.display = 'none';
}

@if($errors->any())
document.addEventListener('DOMContentLoaded', function() {
	openTambahRoleModal();
});
@endif
</script>
@endsection
