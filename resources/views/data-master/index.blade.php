@extends('layouts.horizon')

@section('page_title', 'Data Master')

@section('content')
<div class="hz-top-row">
	<div>
		<h2 class="hz-top-title">Data Master</h2>
		<p class="hz-top-sub">Kelola data master aplikasi</p>
	</div>
</div>

<section class="hz-secondary-grid" style="margin-top: 24px;">
	<div class="hz-card" style="grid-column: 1 / -1;">
		<div class="hz-card-header" style="margin-bottom: 16px;">
			<p class="hz-card-title">Menu Data Master</p>
		</div>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
			<a href="{{ route('data-master.pengguna.index') }}" style="display: flex; flex-direction: column; gap: 8px; padding: 20px; border-radius: 12px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); text-decoration: none; color: #1e293b;">
				<i data-lucide="users" style="width: 32px; height: 32px; color: #3b82f6;"></i>
				<span style="font-weight: 600; font-size: 0.95rem;">Pengguna</span>
				<span style="font-size: 0.8rem; color: #64748b;">Kelola Owner, Admin, Kasir</span>
			</a>
			<a href="{{ route('data-master.kategori-pengeluaran.index') }}" style="display: flex; flex-direction: column; gap: 8px; padding: 20px; border-radius: 12px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); text-decoration: none; color: #1e293b;">
				<i data-lucide="tag" style="width: 32px; height: 32px; color: #3b82f6;"></i>
				<span style="font-weight: 600; font-size: 0.95rem;">Kategori Pengeluaran</span>
				<span style="font-size: 0.8rem; color: #64748b;">Dropdown kategori pengeluaran</span>
			</a>
			<a href="{{ route('data-master.kategori-produk.index') }}" style="display: flex; flex-direction: column; gap: 8px; padding: 20px; border-radius: 12px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); text-decoration: none; color: #1e293b;">
				<i data-lucide="tag" style="width: 32px; height: 32px; color: #3b82f6;"></i>
				<span style="font-weight: 600; font-size: 0.95rem;">Kategori Produk</span>
				<span style="font-size: 0.8rem; color: #64748b;">Dropdown kategori produk di POS</span>
			</a>
			<a href="{{ route('data-master.rbac.index') }}" style="display: flex; flex-direction: column; gap: 8px; padding: 20px; border-radius: 12px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); text-decoration: none; color: #1e293b;">
				<i data-lucide="shield" style="width: 32px; height: 32px; color: #3b82f6;"></i>
				<span style="font-weight: 600; font-size: 0.95rem;">RBAC</span>
				<span style="font-size: 0.8rem; color: #64748b;">Role Based Access Control</span>
			</a>
		</div>
	</div>
</section>

<script>
	// Initialize Lucide icons
	if (typeof lucide !== 'undefined') {
		lucide.createIcons();
	}
</script>
@endsection
