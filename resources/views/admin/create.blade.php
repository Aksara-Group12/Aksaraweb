@extends('layouts.horizon')

@section('page_title', 'Tambah Admin')
@section('page_subtitle', 'Buat admin baru untuk toko Anda.')

@section('content')
<div style="max-width: 640px; margin-top: 10px;">
    <form method="POST" action="{{ route('admin.store') }}" style="background: #FFFFFF; border-radius: 16px; border: 1px solid #E2E8F0; box-shadow: 0 4px 20px rgba(15, 34, 61, 0.04); padding: 28px;">
        @csrf

        <!-- Name -->
        <div style="margin-bottom: 18px;">
            <label style="display: block; color: #0F223D; font-weight: 600; font-size: 0.85rem; margin-bottom: 6px;">Nama Admin</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama admin" required
                style="width: 100%; background: #FFFFFF; border: 1px solid #CBD5E1; color: #0F223D; padding: 10px 14px; border-radius: 10px; font-size: 0.875rem; font-family: inherit; outline: none; transition: border-color 0.15s ease;">
            @error('name')
                <small style="color: #DC2626; font-size: 0.78rem; display: block; margin-top: 4px;">{{ $message }}</small>
            @enderror
        </div>

        <!-- Email -->
        <div style="margin-bottom: 18px;">
            <label style="display: block; color: #0F223D; font-weight: 600; font-size: 0.85rem; margin-bottom: 6px;">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com" required
                style="width: 100%; background: #FFFFFF; border: 1px solid #CBD5E1; color: #0F223D; padding: 10px 14px; border-radius: 10px; font-size: 0.875rem; font-family: inherit; outline: none; transition: border-color 0.15s ease;">
            @error('email')
                <small style="color: #DC2626; font-size: 0.78rem; display: block; margin-top: 4px;">{{ $message }}</small>
            @enderror
        </div>

        <!-- Phone -->
        <div style="margin-bottom: 18px;">
            <label style="display: block; color: #0F223D; font-weight: 600; font-size: 0.85rem; margin-bottom: 6px;">Nomor Telepon</label>
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08123456789" required
                style="width: 100%; background: #FFFFFF; border: 1px solid #CBD5E1; color: #0F223D; padding: 10px 14px; border-radius: 10px; font-size: 0.875rem; font-family: inherit; outline: none; transition: border-color 0.15s ease;">
            @error('phone')
                <small style="color: #DC2626; font-size: 0.78rem; display: block; margin-top: 4px;">{{ $message }}</small>
            @enderror
        </div>

        <!-- Address -->
        <div style="margin-bottom: 18px;">
            <label style="display: block; color: #0F223D; font-weight: 600; font-size: 0.85rem; margin-bottom: 6px;">Alamat</label>
            <textarea name="address" placeholder="Masukkan alamat (opsional)" rows="3"
                style="width: 100%; background: #FFFFFF; border: 1px solid #CBD5E1; color: #0F223D; padding: 10px 14px; border-radius: 10px; font-size: 0.875rem; font-family: inherit; outline: none; resize: vertical; transition: border-color 0.15s ease;">{{ old('address') }}</textarea>
            @error('address')
                <small style="color: #DC2626; font-size: 0.78rem; display: block; margin-top: 4px;">{{ $message }}</small>
            @enderror
        </div>

        <!-- Password -->
        <div style="margin-bottom: 18px;">
            <label style="display: block; color: #0F223D; font-weight: 600; font-size: 0.85rem; margin-bottom: 6px;">Password</label>
            <input type="password" name="password" placeholder="Minimal 8 karakter" required
                style="width: 100%; background: #FFFFFF; border: 1px solid #CBD5E1; color: #0F223D; padding: 10px 14px; border-radius: 10px; font-size: 0.875rem; font-family: inherit; outline: none; transition: border-color 0.15s ease;">
            @error('password')
                <small style="color: #DC2626; font-size: 0.78rem; display: block; margin-top: 4px;">{{ $message }}</small>
            @enderror
        </div>

        <!-- Password Confirmation -->
        <div style="margin-bottom: 18px;">
            <label style="display: block; color: #0F223D; font-weight: 600; font-size: 0.85rem; margin-bottom: 6px;">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" placeholder="Ulangi password" required
                style="width: 100%; background: #FFFFFF; border: 1px solid #CBD5E1; color: #0F223D; padding: 10px 14px; border-radius: 10px; font-size: 0.875rem; font-family: inherit; outline: none; transition: border-color 0.15s ease;">
            @error('password_confirmation')
                <small style="color: #DC2626; font-size: 0.78rem; display: block; margin-top: 4px;">{{ $message }}</small>
            @enderror
        </div>

        <!-- Status -->
        <div style="margin-bottom: 24px;">
            <label style="display: block; color: #0F223D; font-weight: 600; font-size: 0.85rem; margin-bottom: 6px;">Status</label>
            <select name="status" required
                style="width: 100%; background: #FFFFFF; border: 1px solid #CBD5E1; color: #0F223D; padding: 10px 14px; border-radius: 10px; font-size: 0.875rem; font-family: inherit; outline: none; transition: border-color 0.15s ease;">
                <option value="">Pilih Status</option>
                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <small style="color: #DC2626; font-size: 0.78rem; display: block; margin-top: 4px;">{{ $message }}</small>
            @enderror
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 10px; align-items: center;">
            <button type="submit" style="background: #0F223D; color: #FFFFFF; border: none; padding: 10px 22px; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 0.85rem; font-family: inherit; box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15); transition: background 0.15s ease;">
                Simpan Admin
            </button>
            <a href="{{ route('admin.index') }}" style="background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; padding: 10px 20px; border-radius: 10px; cursor: pointer; text-decoration: none; font-weight: 600; font-size: 0.85rem; font-family: inherit; display: inline-block;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
