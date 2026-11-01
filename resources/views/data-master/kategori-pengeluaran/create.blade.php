@extends('layouts.horizon')

@section('page_title', 'Tambah Kategori Pengeluaran')

@section('content')
<div id="kategoriModal" style="display:flex; position:fixed; inset:0; background:rgba(15,23,42,0.8); z-index:50; align-items:center; justify-content:center;">
	<div style="background:rgba(255,255,255,0.95); border-radius:16px; padding:24px; width:100%; max-width:400px; border:1px solid rgba(59,130,246,0.3); box-shadow:0 18px 45px rgba(15,23,42,0.9);">
		<h3 style="font-size:1.1rem; font-weight:600; color:#1e293b; margin-bottom:16px;">Tambah Kategori</h3>
		<form action="{{ route('data-master.kategori-pengeluaran.store') }}" method="POST">
			@csrf
			@if ($errors->any())
				<div style="margin-bottom: 16px; padding: 12px 14px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 10px; color: #dc2626; font-size: 0.85rem;">
					@foreach ($errors->all() as $error)
						<div>{{ $error }}</div>
					@endforeach
				</div>
			@endif
			<div style="display:flex; flex-direction:column; gap:16px;">
				<div style="display:flex; flex-direction:column; gap:6px;">
					<label style="font-size:0.85rem; font-weight:500; color:#1e293b;">Nama Kategori <span style="color:#3b82f6;">*</span></label>
					<input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Listrik, Sewa, Gaji" style="padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.4); background:rgba(255,255,255,0.95); color:#1e293b; font-size:0.9rem;">
				</div>
				<div style="display:flex; gap:8px; justify-content:flex-end; margin-top:8px;">
					<a href="{{ route('data-master.kategori-pengeluaran.index') }}" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:transparent; color:#1e293b; padding:8px 16px; font-size:0.85rem; cursor:pointer; text-decoration:none;">Batal</a>
					<button type="submit" style="border-radius:999px; border:none; background:#3b82f6; color:#fff; padding:8px 16px; font-size:0.85rem; font-weight:600; cursor:pointer;">Simpan</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
