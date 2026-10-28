@extends('layouts.horizon')

@section('page_title', 'Tambah Admin')

@section('content')
<div class="hz-top-row">
    <div>
        <h2 class="hz-top-title">Tambah Admin</h2>
        <p class="hz-top-sub">Buat admin baru untuk toko Anda</p>
    </div>
</div>

<div style="max-width:600px;">
    <form method="POST" action="{{ route('admin.store') }}" style="background:rgba(30,41,59,0.4); border-radius:12px; border:1px solid rgba(71,85,105,0.3); padding:24px;">
        @csrf

        <!-- Name -->
        <div style="margin-bottom:20px;">
            <label style="display:block; color:#cbd5e1; font-weight:600; margin-bottom:8px;">Nama Admin</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama admin" required
                style="width:100%; background:rgba(15,23,42,0.6); border:1px solid rgba(71,85,105,0.3); color:#1e293b; padding:10px 12px; border-radius:6px; font-size:0.95rem;">
            @error('name')
                <small style="color:#ef4444;">{{ $message }}</small>
            @enderror
        </div>

        <!-- Email -->
        <div style="margin-bottom:20px;">
            <label style="display:block; color:#cbd5e1; font-weight:600; margin-bottom:8px;">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" required
                style="width:100%; background:rgba(15,23,42,0.6); border:1px solid rgba(71,85,105,0.3); color:#1e293b; padding:10px 12px; border-radius:6px; font-size:0.95rem;">
            @error('email')
                <small style="color:#ef4444;">{{ $message }}</small>
            @enderror
        </div>

        <!-- Phone -->
        <div style="margin-bottom:20px;">
            <label style="display:block; color:#cbd5e1; font-weight:600; margin-bottom:8px;">Nomor Telepon</label>
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08123456789" required
                style="width:100%; background:rgba(15,23,42,0.6); border:1px solid rgba(71,85,105,0.3); color:#1e293b; padding:10px 12px; border-radius:6px; font-size:0.95rem;">
            @error('phone')
                <small style="color:#ef4444;">{{ $message }}</small>
            @enderror
        </div>

        <!-- Address -->
        <div style="margin-bottom:20px;">
            <label style="display:block; color:#cbd5e1; font-weight:600; margin-bottom:8px;">Alamat</label>
            <textarea name="address" placeholder="Masukkan alamat (opsional)" rows="3"
                style="width:100%; background:rgba(15,23,42,0.6); border:1px solid rgba(71,85,105,0.3); color:#1e293b; padding:10px 12px; border-radius:6px; font-size:0.95rem;">{{ old('address') }}</textarea>
            @error('address')
                <small style="color:#ef4444;">{{ $message }}</small>
            @enderror
        </div>

        <!-- Password -->
        <div style="margin-bottom:20px;">
            <label style="display:block; color:#cbd5e1; font-weight:600; margin-bottom:8px;">Password</label>
            <input type="password" name="password" placeholder="Minimal 8 karakter" required
                style="width:100%; background:rgba(15,23,42,0.6); border:1px solid rgba(71,85,105,0.3); color:#1e293b; padding:10px 12px; border-radius:6px; font-size:0.95rem;">
            @error('password')
                <small style="color:#ef4444;">{{ $message }}</small>
            @enderror
        </div>

        <!-- Password Confirmation -->
        <div style="margin-bottom:20px;">
            <label style="display:block; color:#cbd5e1; font-weight:600; margin-bottom:8px;">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" placeholder="Ulangi password" required
                style="width:100%; background:rgba(15,23,42,0.6); border:1px solid rgba(71,85,105,0.3); color:#1e293b; padding:10px 12px; border-radius:6px; font-size:0.95rem;">
            @error('password_confirmation')
                <small style="color:#ef4444;">{{ $message }}</small>
            @enderror
        </div>

        <!-- Status -->
        <div style="margin-bottom:24px;">
            <label style="display:block; color:#cbd5e1; font-weight:600; margin-bottom:8px;">Status</label>
            <select name="status" required
                style="width:100%; background:rgba(15,23,42,0.6); border:1px solid rgba(71,85,105,0.3); color:#1e293b; padding:10px 12px; border-radius:6px; font-size:0.95rem;">
                <option value="">Pilih Status</option>
                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <small style="color:#ef4444;">{{ $message }}</small>
            @enderror
        </div>

        <!-- Buttons -->
        <div style="display:flex; gap:12px;">
            <button type="submit" style="background:#3b82f6; color:white; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; font-weight:600;">
                ✅ Simpan
            </button>
            <a href="{{ route('admin.index') }}" style="background:rgba(107,114,128,0.2); color:#9ca3af; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; text-decoration:none; font-weight:600; display:inline-block;">
                ❌ Batal
            </a>
        </div>
    </form>
</div>

@endsection
