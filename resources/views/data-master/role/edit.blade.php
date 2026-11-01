@extends('layouts.horizon')

@section('page_title', 'Edit Role')

@section('content')
<div class="hz-top-row">
	<div>
		<h2 class="hz-top-title">Edit Role</h2>
		<p class="hz-top-sub">Edit role: {{ $role->display_name }}</p>
	</div>
</div>

<section class="hz-card" style="margin-top: 24px; max-width: 600px;">
	<div class="hz-card-body">
		<form action="{{ route('data-master.role.update', $role) }}" method="POST">
			@csrf
			@method('PUT')
			<div style="display: flex; flex-direction: column; gap: 16px;">
				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 500; color: #1e293b; margin-bottom: 6px;">Nama Role</label>
					<input type="text" name="name" value="{{ $role->name }}" disabled style="width: 100%; padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #64748b; font-size: 0.9rem;">
					<p style="font-size: 0.75rem; color: #64748b; margin-top: 4px;">Nama role tidak dapat diubah</p>
				</div>
				<div>
					<label style="display: block; font-size: 0.85rem; font-weight: 500; color: #1e293b; margin-bottom: 6px;">Display Name</label>
					<input type="text" name="display_name" value="{{ old('display_name', $role->display_name) }}" placeholder="Contoh: Manager" style="width: 100%; padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;" required>
					@if($errors->has('display_name'))
						<p style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $errors->first('display_name') }}</p>
					@endif
				</div>
				<div>
					<label style="display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 500; color: #1e293b;">
						<input type="checkbox" name="is_active" value="1" {{ $role->is_active ? 'checked' : '' }} style="width: 16px; height: 16px;">
						Role Aktif
					</label>
				</div>
				<div style="display: flex; gap: 10px; margin-top: 8px;">
					<button type="submit" style="flex: 1; padding: 10px 16px; border-radius: 10px; background: #3b82f6; color: white; font-size: 0.9rem; font-weight: 500; border: none; cursor: pointer;">Simpan</button>
					<a href="{{ route('data-master.role.index') }}" style="flex: 1; padding: 10px 16px; border-radius: 10px; background: rgba(239,68,68,0.1); color: #ef4444; font-size: 0.9rem; font-weight: 500; text-decoration: none; text-align: center;">Batal</a>
				</div>
			</div>
		</form>
	</div>
</section>
@endsection
