@extends('layouts.horizon')

@section('page_title', 'Data Master - RBAC')

@section('content')
<div class="hz-top-row">
	<div>
		<h2 class="hz-top-title">RBAC (Role Based Access Control)</h2>
		<p class="hz-top-sub">Kelola permission role dan menu akses</p>
	</div>
	<div class="hz-top-actions">
		<a href="{{ route('data-master.rbac.create') }}" class="hz-btn hz-btn-primary">
			+ Tambah Role
		</a>
	</div>
</div>

@if ($message = Session::get('success'))
	<div style="margin-top: 20px; padding: 14px 16px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 10px; color: #16a34a;">
		{{ $message }}
	</div>
@endif

<section class="hz-secondary-grid" style="margin-top: 24px;">
	<div class="hz-card" style="grid-column: 1 / -1;">
		<div class="hz-card-header" style="margin-bottom: 12px;">
			<p class="hz-card-title">Daftar Role</p>
		</div>
		<div style="margin-top: 8px; overflow-x: auto;">
			@if ($roles->count() > 0)
				<table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
					<thead>
						<tr style="border-bottom: 1px solid rgba(59, 130, 246, 0.4); color:#9ca3af;">
							<th style="text-align:left; padding:14px 10px;">Name</th>
							<th style="text-align:left; padding:14px 10px;">Display Name</th>
							<th style="text-align:left; padding:14px 10px;">Permissions</th>
							<th style="text-align:left; padding:14px 10px;">Status</th>
							<th style="text-align:right; padding:14px 10px;">Aksi</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($roles as $role)
							<tr style="border-bottom: 1px solid rgba(15,23,42,0.8);">
								<td style="padding:14px 10px; color: #1e293b;">{{ $role->name }}</td>
								<td style="padding:14px 10px; color: #64748b;">{{ $role->display_name }}</td>
								<td style="padding:14px 10px; color: #cbd5e1; font-size: 0.85rem;">{{ implode(', ', $role->permissions ?? []) }}</td>
								<td style="padding:14px 10px;">
									<span style="padding: 4px 8px; border-radius: 999px; font-size: 0.8rem; {{ $role->is_active ? 'background: rgba(34, 197, 94, 0.1); color: #16a34a;' : 'background: rgba(239, 68, 68, 0.1); color: #dc2626;' }}">
										{{ $role->is_active ? 'Aktif' : 'Non-aktif' }}
									</span>
								</td>
								<td style="padding:14px 10px; text-align:right;">
									<a href="{{ route('data-master.rbac.edit', $role) }}" style="color: #3b82f6; text-decoration: none; font-size: 0.85rem;">Edit</a>
									<form action="{{ route('data-master.rbac.destroy', $role) }}" method="POST" style="display:inline;">
										@csrf
										@method('DELETE')
										<button type="submit" onclick="return confirm('Hapus role ini?')" style="background:none; border:none; color: #dc2626; cursor:pointer; font-size: 0.85rem;">Hapus</button>
									</form>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				<div style="margin-top: 12px;">
					{{ $roles->links() }}
				</div>
			@else
				<div style="padding: 40px; text-align: center; color: #64748b;">
					<p>Belum ada role.</p>
				</div>
			@endif
		</div>
	</div>
</section>
@endsection
