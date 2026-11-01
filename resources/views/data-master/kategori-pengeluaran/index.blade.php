@extends('layouts.horizon')

@section('page_title', 'Kategori Pengeluaran')
@section('page_subtitle', 'Kelola klasifikasi pos pengeluaran operasional toko.')

@section('content')
<div class="hz-crud-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
	<a href="{{ route('data-master.index') }}" class="hz-btn-back" title="Kembali ke Data Master">
		<i data-lucide="arrow-left"></i>
	</a>
	<div class="hz-top-actions" style="margin-left: auto;">
		<button type="button" onclick="openTambahKategoriModal()" class="hz-btn hz-btn-primary" style="cursor: pointer;">
			<i data-lucide="plus" style="width: 16px; height: 16px;"></i>
			<span>Tambah Kategori</span>
		</button>
	</div>
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

<section class="hz-card">
	<div class="hz-card-header" style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
		<p class="hz-card-title">Daftar Kategori Pengeluaran</p>
		<span style="font-size: 0.78rem; font-weight: 600; color: #64748B; background: #F8FAFC; padding: 4px 10px; border-radius: 999px; border: 1px solid #E2E8F0;">
			Total: {{ $kategoris->total() ?? $kategoris->count() }} Kategori
		</span>
	</div>
	<div style="overflow-x: auto;">
		@if ($kategoris->count() > 0)
			<table style="width: 100%; border-collapse: separate; border-spacing: 0; font-size: 0.875rem;">
				<thead>
					<tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #64748B; font-size: 0.73rem; text-transform: uppercase; letter-spacing: 0.05em;">
						<th style="text-align: left; padding: 13px 18px; font-weight: 600; border-bottom: 1px solid #E2E8F0;">Nama Kategori</th>
						<th style="text-align: left; padding: 13px 18px; font-weight: 600; border-bottom: 1px solid #E2E8F0;">Status</th>
						<th style="text-align: right; padding: 13px 18px; font-weight: 600; border-bottom: 1px solid #E2E8F0;">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($kategoris as $kategori)
						<tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s ease;" onmouseover="this.style.background='#FAF8F5'" onmouseout="this.style.background='transparent'">
							<td style="padding: 14px 18px; color: #0F223D; font-weight: 600; border-bottom: 1px solid #F1F5F9;">
								{{ $kategori->name }}
							</td>
							<td style="padding: 14px 18px; border-bottom: 1px solid #F1F5F9;">
								<span style="display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 600; {{ $kategori->is_active ? 'background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0;' : 'background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA;' }}">
									{{ $kategori->is_active ? 'Aktif' : 'Non-aktif' }}
								</span>
							</td>
							<td style="padding: 14px 18px; text-align: right; border-bottom: 1px solid #F1F5F9;">
								<div style="display: inline-flex; align-items: center; gap: 8px;">
									<button type="button" 
										onclick="openEditKategoriModal('{{ $kategori->id }}', '{{ addslashes($kategori->name) }}', '{{ $kategori->is_active }}', '{{ route('data-master.kategori-pengeluaran.update', $kategori) }}')" 
										style="padding: 5px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; background: #F1F5F9; color: #0F223D; border: 1px solid #E2E8F0; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
										<i data-lucide="edit-2" style="width: 13px; height: 13px;"></i>
										Edit
									</button>
									<form action="{{ route('data-master.kategori-pengeluaran.destroy', $kategori) }}" method="POST" style="display: inline;">
										@csrf
										@method('DELETE')
										<button type="submit" onclick="return confirm('Hapus kategori ini?')" style="padding: 5px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 600; background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
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
			<div style="margin-top: 16px; padding: 10px 18px;">
				{{ $kategoris->links() }}
			</div>
		@else
			<div style="padding: 48px; text-align: center; color: #64748B;">
				<i data-lucide="receipt" style="width: 42px; height: 42px; color: #CBD5E1; margin: 0 auto 12px; display: block;"></i>
				<p style="margin: 0; font-size: 0.95rem; font-weight: 600; color: #0F223D;">Belum ada kategori pengeluaran</p>
				<p style="margin: 4px 0 16px 0; font-size: 0.8rem; color: #64748B;">Mulai buat kategori baru untuk pencatatan beban operasional toko.</p>
				<button type="button" onclick="openTambahKategoriModal()" class="hz-btn hz-btn-primary" style="padding: 8px 18px; font-size: 0.825rem; cursor: pointer;">
					+ Tambah Kategori Baru
				</button>
			</div>
		@endif
	</div>
</section>

<!-- ==================== POP UP MODAL TAMBAH KATEGORI PENGELUARAN ==================== -->
<div id="modalTambahKategoriPengeluaran" style="display: none; position: fixed; inset: 0; background: rgba(15, 34, 61, 0.5); backdrop-filter: blur(6px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
	<div style="background: #FFFFFF; border-radius: 20px; max-width: 480px; width: 100%; padding: 28px; box-shadow: 0 25px 50px -12px rgba(15, 34, 61, 0.25); border: 1px solid #E2E8F0; animation: hzModalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1); position: relative;">
		
		<!-- Modal Header -->
		<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #F1F5F9;">
			<div>
				<h3 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.4rem; font-weight: 600; color: #0F223D; margin: 0; line-height: 1.2;">
					Tambah Kategori Pengeluaran
				</h3>
				<p style="font-size: 0.8rem; color: #64748B; margin: 4px 0 0 0;">
					Buat pos biaya baru untuk mengelompokkan beban operasional toko.
				</p>
			</div>
			<button type="button" onclick="closeTambahKategoriModal()" style="border: none; background: #F8FAFC; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748B; cursor: pointer; transition: all 0.15s ease;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#0F223D'" onmouseout="this.style.background='#F8FAFC'; this.style.color='#64748B'">
				<i data-lucide="x" style="width: 18px; height: 18px;"></i>
			</button>
		</div>

		<!-- Modal Form -->
		<form action="{{ route('data-master.kategori-pengeluaran.store') }}" method="POST">
			@csrf
			<div style="display: flex; flex-direction: column; gap: 16px;">
				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Nama Kategori <span style="color: #DC2626;">*</span>
					</label>
					<input type="text" name="name" id="tambah_kategori_name" required placeholder="Contoh: Belanja Bahan Baku, Gaji Karyawan, Listrik & WiFi" style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; outline: none; transition: all 0.15s ease;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
				</div>

				<!-- Actions -->
				<div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 8px; padding-top: 16px; border-top: 1px solid #F1F5F9;">
					<button type="button" onclick="closeTambahKategoriModal()" style="padding: 10px 18px; border-radius: 10px; background: #FAF8F5; border: 1px solid #CBD5E1; color: #475569; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
						Batal
					</button>
					<button type="submit" class="hz-btn hz-btn-primary" style="padding: 10px 22px; cursor: pointer;">
						<i data-lucide="check" style="width: 16px; height: 16px;"></i>
						Simpan Kategori
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- ==================== POP UP MODAL EDIT KATEGORI PENGELUARAN ==================== -->
<div id="modalEditKategoriPengeluaran" style="display: none; position: fixed; inset: 0; background: rgba(15, 34, 61, 0.5); backdrop-filter: blur(6px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
	<div style="background: #FFFFFF; border-radius: 20px; max-width: 480px; width: 100%; padding: 28px; box-shadow: 0 25px 50px -12px rgba(15, 34, 61, 0.25); border: 1px solid #E2E8F0; animation: hzModalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1); position: relative;">
		
		<!-- Modal Header -->
		<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #F1F5F9;">
			<div>
				<h3 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.4rem; font-weight: 600; color: #0F223D; margin: 0; line-height: 1.2;">
					Edit Kategori Pengeluaran
				</h3>
				<p style="font-size: 0.8rem; color: #64748B; margin: 4px 0 0 0;">
					Perbarui nama atau status aktif kategori pengeluaran ini.
				</p>
			</div>
			<button type="button" onclick="closeEditKategoriModal()" style="border: none; background: #F8FAFC; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748B; cursor: pointer; transition: all 0.15s ease;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#0F223D'" onmouseout="this.style.background='#F8FAFC'; this.style.color='#64748B'">
				<i data-lucide="x" style="width: 18px; height: 18px;"></i>
			</button>
		</div>

		<!-- Modal Form -->
		<form id="formEditKategoriPengeluaran" action="" method="POST">
			@csrf
			@method('PUT')
			<div style="display: flex; flex-direction: column; gap: 16px;">
				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Nama Kategori <span style="color: #DC2626;">*</span>
					</label>
					<input type="text" name="name" id="edit_kategori_name" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; outline: none; transition: all 0.15s ease;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
				</div>

				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Status Kategori
					</label>
					<select name="is_active" id="edit_kategori_is_active" style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; outline: none; cursor: pointer;">
						<option value="1">Aktif</option>
						<option value="0">Non-aktif</option>
					</select>
				</div>

				<!-- Actions -->
				<div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 8px; padding-top: 16px; border-top: 1px solid #F1F5F9;">
					<button type="button" onclick="closeEditKategoriModal()" style="padding: 10px 18px; border-radius: 10px; background: #FAF8F5; border: 1px solid #CBD5E1; color: #475569; font-weight: 600; font-size: 0.875rem; cursor: pointer;">
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
function openTambahKategoriModal() {
	const modal = document.getElementById('modalTambahKategoriPengeluaran');
	if (modal) {
		modal.style.display = 'flex';
		setTimeout(() => {
			const inp = document.getElementById('tambah_kategori_name');
			if (inp) inp.focus();
		}, 50);
	}
}

function closeTambahKategoriModal() {
	const modal = document.getElementById('modalTambahKategoriPengeluaran');
	if (modal) modal.style.display = 'none';
}

function openEditKategoriModal(id, name, isActive, updateUrl) {
	const modal = document.getElementById('modalEditKategoriPengeluaran');
	const form = document.getElementById('formEditKategoriPengeluaran');
	const nameInp = document.getElementById('edit_kategori_name');
	const activeSel = document.getElementById('edit_kategori_is_active');

	if (form) form.action = updateUrl;
	if (nameInp) nameInp.value = name;
	if (activeSel) activeSel.value = (isActive == '1' || isActive == 'true' || isActive == true) ? '1' : '0';

	if (modal) {
		modal.style.display = 'flex';
		setTimeout(() => {
			if (nameInp) nameInp.focus();
		}, 50);
	}
}

function closeEditKategoriModal() {
	const modal = document.getElementById('modalEditKategoriPengeluaran');
	if (modal) modal.style.display = 'none';
}

// Auto open modal jika ada error validasi saat submit form
@if($errors->any())
document.addEventListener('DOMContentLoaded', function() {
	openTambahKategoriModal();
});
@endif
</script>
@endsection
