@extends('layouts.horizon')

@section('page_title', 'Data Master Pengguna')
@section('page_subtitle', 'Kelola akun staf, admin kasir, dan hak akses pengguna toko.')

@section('content')
<!-- Top Actions -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
	<a href="{{ route('data-master.index') }}" class="hz-btn-back" title="Kembali ke Data Master">
		<i data-lucide="arrow-left"></i>
	</a>
	<button type="button" onclick="openTambahPenggunaModal()" 
		style="display: inline-flex; align-items: center; gap: 8px; background: #0F223D; color: #FFFFFF; font-weight: 600; font-size: 0.85rem; padding: 10px 18px; border-radius: 10px; border: none; cursor: pointer; box-shadow: 0 2px 8px rgba(15, 34, 61, 0.15); transition: all 0.2s ease;"
		onmouseover="this.style.background='#162C50'; this.style.boxShadow='0 0 16px rgba(197, 160, 89, 0.3)';" 
		onmouseout="this.style.background='#0F223D'; this.style.boxShadow='0 2px 8px rgba(15, 34, 61, 0.15)';">
		<i data-lucide="user-plus" style="width: 16px; height: 16px; color: #C5A059;"></i>
		<span>+ Tambah Pengguna</span>
	</button>
</div>

@if ($message = Session::get('success'))
	<div style="margin-bottom: 20px; padding: 14px 18px; background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 12px; color: #065F46; font-size: 0.875rem; font-weight: 500; display: flex; align-items: center; gap: 10px;">
		<i data-lucide="check-circle" style="width: 18px; height: 18px; color: #10B981; flex-shrink: 0;"></i>
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

<!-- Card Table Pengguna -->
<div style="background: #FFFFFF; border-radius: 18px; border: 1px solid rgba(226, 232, 240, 0.95); box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04); overflow: hidden;">
	<div style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #F1F5F9; flex-wrap: wrap; gap: 10px;">
		<div>
			<h3 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.25rem; font-weight: 600; color: #0F223D; margin: 0; line-height: 1.2;">
				Daftar Pengguna & Staf
			</h3>
			<p style="font-size: 0.8rem; color: #64748B; margin: 3px 0 0 0;">
				Total {{ $users->total() ?? $users->count() }} pengguna terdaftar di sistem.
			</p>
		</div>
	</div>

	<div style="overflow-x: auto;">
		@if ($users->count() > 0)
			<table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
				<thead>
					<tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #64748B; font-size: 0.73rem; text-transform: uppercase; letter-spacing: 0.05em;">
						<th style="text-align: left; padding: 13px 18px; font-weight: 600;">Nama Lengkap</th>
						<th style="text-align: left; padding: 13px 18px; font-weight: 600;">Alamat Email</th>
						<th style="text-align: left; padding: 13px 18px; font-weight: 600;">Role Akses</th>
						<th style="text-align: center; padding: 13px 18px; font-weight: 600;">Status</th>
						<th style="text-align: right; padding: 13px 18px; font-weight: 600;">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($users as $u)
						<tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease;" onmouseover="this.style.background='#FAF8F5'" onmouseout="this.style.background='transparent'">
							<!-- Nama -->
							<td style="padding: 14px 18px;">
								<div style="display: flex; align-items: center; gap: 10px;">
									<div style="width: 32px; height: 32px; border-radius: 50%; background: #0F223D; color: #C5A059; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; flex-shrink: 0;">
										{{ strtoupper(substr($u->name, 0, 1)) }}
									</div>
									<span style="font-weight: 600; color: #0F223D;">{{ $u->name }}</span>
								</div>
							</td>

							<!-- Email -->
							<td style="padding: 14px 18px; color: #64748B;">
								{{ $u->email }}
							</td>

							<!-- Role -->
							<td style="padding: 14px 18px;">
								<span style="display: inline-block; background: #FAF8F5; border: 1px solid #E2E8F0; color: #0F223D; padding: 3px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize;">
									{{ $u->role }}
								</span>
							</td>

							<!-- Status -->
							<td style="padding: 14px 18px; text-align: center;">
								@if($u->is_active)
									<span style="display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 9999px; background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; font-size: 0.75rem; font-weight: 700;">
										<span style="width: 6px; height: 6px; border-radius: 50%; background: #10B981;"></span>
										<span>Aktif</span>
									</span>
								@else
									<span style="display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 9999px; background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; font-size: 0.75rem; font-weight: 700;">
										<span>Non-aktif</span>
									</span>
								@endif
							</td>

							<!-- Aksi -->
							<td style="padding: 14px 18px; text-align: right; white-space: nowrap;">
								<div style="display: inline-flex; align-items: center; gap: 8px;">
									<button type="button" 
										onclick="openEditPenggunaModal('{{ $u->id }}', '{{ addslashes($u->name) }}', '{{ addslashes($u->email) }}', '{{ $u->role }}', '{{ route('data-master.pengguna.update', $u) }}')" 
										style="padding: 6px 12px; border-radius: 8px; border: 1px solid #E2E8F0; background: #FFFFFF; color: #0F223D; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: background 0.15s ease;"
										onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='#FFFFFF'">
										<i data-lucide="edit-3" style="width: 13px; height: 13px; color: #64748B;"></i>
										<span>Edit</span>
									</button>
									<form action="{{ route('data-master.pengguna.destroy', $u) }}" method="POST" style="display: inline;">
										@csrf
										@method('DELETE')
										<button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $u->name }}?')" 
											style="padding: 6px 12px; border-radius: 8px; border: 1px solid #FECACA; background: #FEF2F2; color: #DC2626; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: background 0.15s ease;"
											onmouseover="this.style.background='#FEE2E2'" onmouseout="this.style.background='#FEF2F2'">
											<i data-lucide="trash-2" style="width: 13px; height: 13px;"></i>
											<span>Hapus</span>
										</button>
									</form>
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			@if($users->hasPages())
				<div style="padding: 16px 24px; border-top: 1px solid #F1F5F9; background: #FFFFFF; display: flex; justify-content: flex-end;">
					{{ $users->links() }}
				</div>
			@endif
		@else
			<div style="padding: 56px 20px; text-align: center;">
				<div style="max-width: 380px; margin: 0 auto; display: flex; flex-direction: column; align-items: center;">
					<div style="width: 54px; height: 54px; border-radius: 50%; background: #FAF8F5; border: 1.5px dashed rgba(197, 160, 89, 0.4); display: flex; align-items: center; justify-content: center; margin-bottom: 16px; color: #C5A059;">
						<i data-lucide="users" style="width: 26px; height: 26px;"></i>
					</div>
					<h4 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.15rem; font-weight: 600; color: #0F223D; margin: 0 0 6px 0;">
						Belum Ada Pengguna Terdaftar
					</h4>
					<p style="font-size: 0.825rem; color: #64748B; margin: 0 0 16px 0; line-height: 1.5;">
						Klik tombol Tambah Pengguna untuk mendaftarkan akun staf atau admin kasir baru.
					</p>
					<button type="button" onclick="openTambahPenggunaModal()" class="hz-btn hz-btn-primary" style="padding: 8px 18px; font-size: 0.825rem; cursor: pointer;">
						+ Tambah Pengguna Baru
					</button>
				</div>
			</div>
		@endif
	</div>
</div>

<!-- ==================== POP UP MODAL TAMBAH PENGGUNA ==================== -->
<div id="modalTambahPengguna" style="display: none; position: fixed; inset: 0; background: rgba(15, 34, 61, 0.5); backdrop-filter: blur(6px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
	<div style="background: #FFFFFF; border-radius: 20px; max-width: 520px; width: 100%; padding: 28px; box-shadow: 0 25px 50px -12px rgba(15, 34, 61, 0.25); border: 1px solid #E2E8F0; animation: hzModalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1); position: relative;">
		
		<!-- Modal Header -->
		<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #F1F5F9;">
			<div>
				<h3 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.4rem; font-weight: 600; color: #0F223D; margin: 0; line-height: 1.2;">
					Tambah Pengguna Baru
				</h3>
				<p style="font-size: 0.8rem; color: #64748B; margin: 4px 0 0 0;">
					Daftarkan akun staf kasir, admin, atau owner toko.
				</p>
			</div>
			<button type="button" onclick="closeTambahPenggunaModal()" style="border: none; background: #F8FAFC; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748B; cursor: pointer; transition: all 0.15s ease;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#0F223D'" onmouseout="this.style.background='#F8FAFC'; this.style.color='#64748B'">
				<i data-lucide="x" style="width: 18px; height: 18px;"></i>
			</button>
		</div>

		<!-- Modal Form -->
		<form action="{{ route('data-master.pengguna.store') }}" method="POST">
			@csrf
			<div style="display: flex; flex-direction: column; gap: 14px;">
				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Nama Lengkap <span style="color: #DC2626;">*</span>
					</label>
					<input type="text" name="name" id="tambah_user_name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; outline: none; transition: all 0.15s ease;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
				</div>

				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Alamat Email <span style="color: #DC2626;">*</span>
					</label>
					<input type="email" name="email" id="tambah_user_email" value="{{ old('email') }}" placeholder="Contoh: budi@aksara.id" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; outline: none; transition: all 0.15s ease;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
				</div>

				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Password <span style="color: #DC2626;">*</span>
					</label>
					<div style="position: relative;">
						<input type="password" name="password" id="tambah_user_password" minlength="6" placeholder="Minimal 6 karakter" required style="width: 100%; padding: 10px 42px 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; outline: none; transition: all 0.15s ease;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
						<button type="button" onclick="togglePasswordVisibility('tambah_user_password', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94A3B8; display: flex; align-items: center; justify-content: center; padding: 4px; transition: color 0.15s ease;" onmouseover="this.style.color='#0F223D'" onmouseout="this.style.color='#94A3B8'" title="Lihat password" aria-label="Lihat password">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
								<circle cx="12" cy="12" r="3"></circle>
							</svg>
						</button>
					</div>
				</div>

				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Role Pengguna <span style="color: #DC2626;">*</span>
					</label>
					<select name="role" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; outline: none; cursor: pointer;">
						<option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir (Operator POS)</option>
						<option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Pengelola Menu & Stok)</option>
						<option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner (Pemilik Toko)</option>
					</select>
				</div>

				<!-- Actions -->
				<div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 8px; padding-top: 16px; border-top: 1px solid #F1F5F9;">
					<button type="button" onclick="closeTambahPenggunaModal()" style="padding: 10px 18px; border-radius: 10px; background: #FAF8F5; border: 1px solid #CBD5E1; color: #475569; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
						Batal
					</button>
					<button type="submit" class="hz-btn hz-btn-primary" style="padding: 10px 22px; cursor: pointer;">
						<i data-lucide="check" style="width: 16px; height: 16px;"></i>
						Simpan Pengguna
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- ==================== POP UP MODAL EDIT PENGGUNA ==================== -->
<div id="modalEditPengguna" style="display: none; position: fixed; inset: 0; background: rgba(15, 34, 61, 0.5); backdrop-filter: blur(6px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
	<div style="background: #FFFFFF; border-radius: 20px; max-width: 520px; width: 100%; padding: 28px; box-shadow: 0 25px 50px -12px rgba(15, 34, 61, 0.25); border: 1px solid #E2E8F0; animation: hzModalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1); position: relative;">
		
		<!-- Modal Header -->
		<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #F1F5F9;">
			<div>
				<h3 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.4rem; font-weight: 600; color: #0F223D; margin: 0; line-height: 1.2;">
					Edit Pengguna
				</h3>
				<p style="font-size: 0.8rem; color: #64748B; margin: 4px 0 0 0;">
					Perbarui nama, email, role, atau kata sandi pengguna.
				</p>
			</div>
			<button type="button" onclick="closeEditPenggunaModal()" style="border: none; background: #F8FAFC; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748B; cursor: pointer; transition: all 0.15s ease;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#0F223D'" onmouseout="this.style.background='#F8FAFC'; this.style.color='#64748B'">
				<i data-lucide="x" style="width: 18px; height: 18px;"></i>
			</button>
		</div>

		<!-- Modal Form -->
		<form id="formEditPengguna" action="" method="POST">
			@csrf
			@method('PUT')
			<div style="display: flex; flex-direction: column; gap: 14px;">
				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Nama Lengkap <span style="color: #DC2626;">*</span>
					</label>
					<input type="text" name="name" id="edit_user_name" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; outline: none; transition: all 0.15s ease;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
				</div>

				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Alamat Email <span style="color: #DC2626;">*</span>
					</label>
					<input type="email" name="email" id="edit_user_email" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; outline: none; transition: all 0.15s ease;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
				</div>

				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Password Baru
					</label>
					<div style="position: relative;">
						<input type="password" name="password" id="edit_user_password" placeholder="Kosongkan jika tidak ingin mengubah" style="width: 100%; padding: 10px 42px 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; outline: none; transition: all 0.15s ease;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
						<button type="button" onclick="togglePasswordVisibility('edit_user_password', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94A3B8; display: flex; align-items: center; justify-content: center; padding: 4px; transition: color 0.15s ease;" onmouseover="this.style.color='#0F223D'" onmouseout="this.style.color='#94A3B8'" title="Lihat password" aria-label="Lihat password">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
								<circle cx="12" cy="12" r="3"></circle>
							</svg>
						</button>
					</div>
					<p style="font-size: 0.73rem; color: #64748B; margin: 4px 0 0;">Minimal 6 karakter jika ingin mengganti.</p>
				</div>

				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Role Pengguna <span style="color: #DC2626;">*</span>
					</label>
					<select name="role" id="edit_user_role" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; outline: none; cursor: pointer;">
						<option value="kasir">Kasir (Operator POS)</option>
						<option value="admin">Admin (Pengelola Menu & Stok)</option>
						<option value="owner">Owner (Pemilik Toko)</option>
					</select>
				</div>

				<!-- Actions -->
				<div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 8px; padding-top: 16px; border-top: 1px solid #F1F5F9;">
					<button type="button" onclick="closeEditPenggunaModal()" style="padding: 10px 18px; border-radius: 10px; background: #FAF8F5; border: 1px solid #CBD5E1; color: #475569; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
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
function openTambahPenggunaModal() {
	const modal = document.getElementById('modalTambahPengguna');
	if (modal) {
		modal.style.display = 'flex';
		setTimeout(() => {
			const inp = document.getElementById('tambah_user_name');
			if (inp) inp.focus();
		}, 50);
	}
}

function closeTambahPenggunaModal() {
	const modal = document.getElementById('modalTambahPengguna');
	if (modal) modal.style.display = 'none';
}

function openEditPenggunaModal(id, name, email, role, updateUrl) {
	const modal = document.getElementById('modalEditPengguna');
	const form = document.getElementById('formEditPengguna');
	const nameInp = document.getElementById('edit_user_name');
	const emailInp = document.getElementById('edit_user_email');
	const roleSel = document.getElementById('edit_user_role');

	if (form) form.action = updateUrl;
	if (nameInp) nameInp.value = name;
	if (emailInp) emailInp.value = email;
	if (roleSel) roleSel.value = role;

	if (modal) {
		modal.style.display = 'flex';
		setTimeout(() => {
			if (nameInp) nameInp.focus();
		}, 50);
	}
}

function closeEditPenggunaModal() {
	const modal = document.getElementById('modalEditPengguna');
	if (modal) modal.style.display = 'none';
}

@if($errors->any())
document.addEventListener('DOMContentLoaded', function() {
	openTambahPenggunaModal();
});
@endif
</script>
@endsection
