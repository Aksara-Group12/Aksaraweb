@extends('layouts.horizon')

@section('page_title', 'Edit User')

@section('content')
<div class="hz-top-row">
    <div>
        <h2 class="hz-top-title">Edit User</h2>
        <p class="hz-top-sub">Ubah data user.</p>
    </div>
</div>

@if ($errors->any())
    <div style="margin: 16px 0; padding: 12px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.5); border-radius: 6px; color: #ef4444;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<section class="hz-secondary-grid" style="margin-top: 24px;">
    <div class="hz-card" style="grid-column: 1 / -1; max-width: 600px;">
        <div class="hz-card-header" style="margin-bottom: 20px;">
            <div>
                <p class="hz-card-title">Form Edit User</p>
            </div>
        </div>

        <form method="POST" action="{{ route('manajemen-user.update', $user) }}" style="display: flex; flex-direction: column; gap: 16px;">
            @csrf
            @method('PUT')

            <div>
                <label style="display:block; color:#fbbf24; font-size:0.9rem; margin-bottom:6px;">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required style="width:100%; padding:10px; border-radius:6px; border:1px solid rgba(148,163,184,0.5); background:#020617; color:#e5e7eb; font-size:0.9rem; box-sizing:border-box;">
                @error('name')
                    <span style="color:#ef4444; font-size:0.85rem; margin-top:4px;">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label style="display:block; color:#fbbf24; font-size:0.9rem; margin-bottom:6px;">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required style="width:100%; padding:10px; border-radius:6px; border:1px solid rgba(148,163,184,0.5); background:#020617; color:#e5e7eb; font-size:0.9rem; box-sizing:border-box;">
                @error('email')
                    <span style="color:#ef4444; font-size:0.85rem; margin-top:4px;">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label style="display:block; color:#fbbf24; font-size:0.9rem; margin-bottom:6px;">Password (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password" style="width:100%; padding:10px; border-radius:6px; border:1px solid rgba(148,163,184,0.5); background:#020617; color:#e5e7eb; font-size:0.9rem; box-sizing:border-box;">
                @error('password')
                    <span style="color:#ef4444; font-size:0.85rem; margin-top:4px;">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label style="display:block; color:#fbbf24; font-size:0.9rem; margin-bottom:6px;">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" style="width:100%; padding:10px; border-radius:6px; border:1px solid rgba(148,163,184,0.5); background:#020617; color:#e5e7eb; font-size:0.9rem; box-sizing:border-box;">
                @error('password_confirmation')
                    <span style="color:#ef4444; font-size:0.85rem; margin-top:4px;">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label style="display:block; color:#fbbf24; font-size:0.9rem; margin-bottom:6px;">Status Akun</label>
                <select name="status" style="width:100%; padding:10px; border-radius:6px; border:1px solid rgba(148,163,184,0.5); background:#020617; color:#e5e7eb; font-size:0.9rem; box-sizing:border-box; cursor:pointer;">
                    <option value="">-- Jangan Ubah Status --</option>
                    <option value="aktif">✓ Aktif</option>
                    <option value="trial">◎ Trial</option>
                    <option value="non_aktif">✕ Non Aktif</option>
                </select>
                <p style="color:#9ca3af; font-size:0.8rem; margin-top:4px;">
                    Kosongkan jika tidak ingin mengubah status. Memilih status akan otomatis membuat/update subscription user.
                </p>
                @error('status')
                    <span style="color:#ef4444; font-size:0.85rem; margin-top:4px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 8px; margin-top: 20px;">
                <button type="submit" style="flex:1; background:rgba(251,191,36,0.2); color:#fbbf24; border:1px solid rgba(251,191,36,0.5); padding:10px; border-radius:6px; cursor:pointer; font-size:0.9rem; font-weight:500;">
                    Simpan Perubahan
                </button>
                <a href="{{ route('manajemen-user.index') }}" style="flex:1; display:flex; align-items:center; justify-content:center; background:rgba(148,163,184,0.2); color:#e5e7eb; border:1px solid rgba(148,163,184,0.5); padding:10px; border-radius:6px; text-decoration:none; font-size:0.9rem;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
