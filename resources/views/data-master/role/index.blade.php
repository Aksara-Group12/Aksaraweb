@extends('layouts.horizon')

@section('page_title', 'Role')

@section('content')
<div class="hz-top-row">
	<div>
		<h2 class="hz-top-title">Role</h2>
		<p class="hz-top-sub">Kelola role (owner, admin, kasir)</p>
	</div>
	<div class="hz-top-actions">
		<a href="{{ route('data-master.role.create') }}" class="hz-btn-primary">
			<i data-lucide="plus" style="width: 16px; height: 16px;"></i>
			Tambah Role
		</a>
	</div>
</div>

<section class="hz-card" style="margin-top: 24px;">
	<div class="hz-card-header">
		<p class="hz-card-title">Daftar Role</p>
	</div>
	<div class="hz-card-body">
		@if($roles->count() > 0)
			<table style="width: 100%; border-collapse: collapse;">
				<thead>
					<tr style="border-bottom: 1px solid rgba(59,130,246,0.2);">
						<th style="padding: 12px; text-align: left; font-size: 0.85rem; color: #64748b;">Nama</th>
						<th style="padding: 12px; text-align: left; font-size: 0.85rem; color: #64748b;">Display Name</th>
						<th style="padding: 12px; text-align: left; font-size: 0.85rem; color: #64748b;">Status</th>
						<th style="padding: 12px; text-align: right; font-size: 0.85rem; color: #64748b;">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach($roles as $role)
						<tr style="border-bottom: 1px solid rgba(59,130,246,0.1);">
							<td style="padding: 12px; font-size: 0.9rem; color: #1e293b;">{{ $role->name }}</td>
							<td style="padding: 12px; font-size: 0.9rem; color: #1e293b;">{{ $role->display_name }}</td>
							<td style="padding: 12px;">
								@if($role->is_active)
									<span style="padding: 4px 8px; border-radius: 999px; background: rgba(34,197,94,0.1); color: #22c55e; font-size: 0.75rem; font-weight: 500;">Aktif</span>
								@else
									<span style="padding: 4px 8px; border-radius: 999px; background: rgba(239,68,68,0.1); color: #ef4444; font-size: 0.75rem; font-weight: 500;">Nonaktif</span>
								@endif
							</td>
							<td style="padding: 12px; text-align: right;">
								<a href="{{ route('data-master.role.edit', $role) }}" style="padding: 6px 12px; border-radius: 8px; background: rgba(59,130,246,0.1); color: #3b82f6; font-size: 0.8rem; text-decoration: none;">Edit</a>
								<form action="{{ route('data-master.role.destroy', $role) }}" method="POST" style="display: inline;">
									@csrf
									@method('DELETE')
									<button type="submit" style="padding: 6px 12px; border-radius: 8px; background: rgba(239,68,68,0.1); color: #ef4444; font-size: 0.8rem; border: none; cursor: pointer;">Hapus</button>
								</form>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			{{ $roles->links() }}
		@else
			<div style="padding: 40px; text-align: center; color: #64748b;">
				<p>Belum ada role</p>
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
