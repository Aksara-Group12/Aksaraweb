@extends('layouts.horizon')

@section('page_title', 'Data Master')
@section('page_subtitle', 'Pusat pengelolaan konfigurasi data master toko dan wewenang pengguna.')

@section('content')
<section class="hz-card" style="margin-top: 4px;">
	<div class="hz-card-header" style="margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid #F1F5F9;">
		<p class="hz-card-title">Pilihan Modul Data Master</p>
		<p class="hz-card-sub">Pilih salah satu menu di bawah ini untuk mengelola entitas sistem.</p>
	</div>

	@php
		$masterMenus = [
			[
				'route' => route('data-master.pengguna.index'),
				'title' => 'Pengguna & Staf',
				'desc'  => 'Kelola data akun Owner, Admin, dan Kasir toko',
				'icon'  => 'users',
				'badge' => 'Akun',
			],
			[
				'route' => route('data-master.role.index'),
				'title' => 'Role Pengguna',
				'desc'  => 'Kelola peran pengguna dan nama tampilan hak akses',
				'icon'  => 'shield-check',
				'badge' => 'Role',
			],
			[
				'route' => route('data-master.rbac.index'),
				'title' => 'RBAC (Role Permissions)',
				'desc'  => 'Atur izin buka menu dan batasan fitur tiap role',
				'icon'  => 'shield',
				'badge' => 'Otorisasi',
			],
			[
				'route' => route('data-master.kategori-produk.index'),
				'title' => 'Kategori Produk',
				'desc'  => 'Kelola klasifikasi katalog menu kasir POS',
				'icon'  => 'package',
				'badge' => 'Katalog',
			],
			[
				'route' => route('data-master.kategori-pengeluaran.index'),
				'title' => 'Kategori Pengeluaran',
				'desc'  => 'Kelola jenis pos biaya pengeluaran operasional',
				'icon'  => 'receipt',
				'badge' => 'Keuangan',
			],
		];
	@endphp

	<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px;">
		@foreach($masterMenus as $menu)
			<a href="{{ $menu['route'] }}" style="display: flex; flex-direction: column; gap: 12px; padding: 22px; border-radius: 14px; border: 1px solid #E2E8F0; background: #FFFFFF; text-decoration: none; color: #0F223D; transition: all 0.2s ease; position: relative;" onmouseover="this.style.borderColor='rgba(197, 160, 89, 0.7)'; this.style.boxShadow='0 0 20px rgba(197, 160, 89, 0.25), 0 4px 12px rgba(15, 34, 61, 0.05)'; this.style.background='#FAF9F6'" onmouseout="this.style.borderColor='#E2E8F0'; this.style.boxShadow='none'; this.style.background='#FFFFFF'">
				<div style="display: flex; justify-content: space-between; align-items: center;">
					<div style="width: 44px; height: 44px; border-radius: 10px; background: #F1F5F9; display: flex; align-items: center; justify-content: center; color: #0F223D; border: 1px solid #CBD5E1;">
						<i data-lucide="{{ $menu['icon'] }}" style="width: 22px; height: 22px;"></i>
					</div>
					<span style="font-size: 0.725rem; font-weight: 600; padding: 3px 10px; border-radius: 999px; background: #F8FAFC; color: #64748B; border: 1px solid #E2E8F0;">
						{{ $menu['badge'] }}
					</span>
				</div>
				<div>
					<h3 style="font-family: {{ (auth()->check() && auth()->user()->role === 'superadmin') ? "'Geist', 'Geist Sans', system-ui, -apple-system, sans-serif" : "'Newsreader', Georgia, serif" }}; font-size: 1.25rem; font-weight: 600; color: #0F223D; margin: 0 0 4px 0; letter-spacing: -0.015em;">
						{{ $menu['title'] }}
					</h3>
					<p style="font-size: 0.825rem; color: #64748B; margin: 0; line-height: 1.4;">
						{{ $menu['desc'] }}
					</p>
				</div>
				<div style="margin-top: auto; padding-top: 10px; display: flex; align-items: center; gap: 6px; font-size: 0.8rem; font-weight: 600; color: #C5A059;">
					<span>Kelola data</span>
					<i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
				</div>
			</a>
		@endforeach
	</div>
</section>

<script>
	if (typeof lucide !== 'undefined') {
		lucide.createIcons();
	}
</script>
@endsection
