@extends('layouts.horizon')

@section('page_title', 'Role')
@section('page_subtitle', 'Kelola role pengguna (owner, admin, kasir).')

@section('content')
<!-- Background placeholder -->
<div class="hz-crud-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
	<a href="{{ route('data-master.role.index') }}" class="hz-btn-back" title="Kembali">
		<i data-lucide="arrow-left"></i>
	</a>
	<div class="hz-top-actions" style="margin-left: auto;">
		<a href="{{ route('data-master.role.index') }}" class="hz-btn hz-btn-secondary">
			Kembali ke Daftar
		</a>
	</div>
</div>

<!-- ==================== POP UP MODAL EDIT ROLE ==================== -->
<div id="modalEditRole" style="display: flex; position: fixed; inset: 0; background: rgba(15, 34, 61, 0.55); backdrop-filter: blur(6px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
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
			<a href="{{ route('data-master.role.index') }}" style="border: none; background: #F8FAFC; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748B; text-decoration: none; transition: all 0.15s ease;" onmouseover="this.style.background='#F1F5F9'; this.style.color='#0F223D'" onmouseout="this.style.background='#F8FAFC'; this.style.color='#64748B'">
				<i data-lucide="x" style="width: 18px; height: 18px;"></i>
			</a>
		</div>

		@if ($errors->any())
			<div style="margin-bottom: 16px; padding: 12px 14px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 10px; color: #DC2626; font-size: 0.85rem;">
				<ul style="margin: 0; padding-left: 20px;">
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<!-- Modal Form -->
		<form action="{{ route('data-master.role.update', $role) }}" method="POST">
			@csrf
			@method('PUT')
			<div style="display: flex; flex-direction: column; gap: 16px;">
				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Nama Role (Identifier Sistem)
					</label>
					<input type="text" value="{{ $role->name }}" disabled style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #E2E8F0; background: #F8FAFC; color: #64748B; font-size: 0.875rem; cursor: not-allowed;">
					<p style="font-size: 0.73rem; color: #64748B; margin: 4px 0 0;">Identifier teknis tidak dapat diubah.</p>
				</div>

				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 600; color: #0F223D; margin-bottom: 6px;">
						Display Name (Nama Tampilan) <span style="color: #DC2626;">*</span>
					</label>
					<input type="text" name="display_name" value="{{ old('display_name', $role->display_name) }}" required style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.875rem; outline: none; transition: all 0.15s ease;" onfocus="this.style.borderColor='#C5A059'; this.style.boxShadow='0 0 0 3px rgba(197, 160, 89, 0.15)'" onblur="this.style.borderColor='#CBD5E1'; this.style.boxShadow='none'">
				</div>

				<div style="padding: 12px 14px; border-radius: 10px; background: #F8FAFC; border: 1px solid #E2E8F0;">
					<label style="display: flex; align-items: center; gap: 10px; font-size: 0.85rem; font-weight: 600; color: #0F223D; cursor: pointer;">
						<input type="checkbox" name="is_active" value="1" {{ $role->is_active ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #0F223D; cursor: pointer;">
						<span>Role Aktif</span>
					</label>
				</div>

				<!-- Actions -->
				<div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 8px; padding-top: 16px; border-top: 1px solid #F1F5F9;">
					<a href="{{ route('data-master.role.index') }}" style="padding: 10px 18px; border-radius: 10px; background: #FAF8F5; border: 1px solid #CBD5E1; color: #475569; font-weight: 600; font-size: 0.875rem; text-decoration: none; display: inline-flex; align-items: center;">
						Batal
					</a>
					<button type="submit" class="hz-btn hz-btn-primary" style="padding: 10px 22px; cursor: pointer;">
						<i data-lucide="check" style="width: 16px; height: 16px;"></i>
						Simpan Perubahan
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
