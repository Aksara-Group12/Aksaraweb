@extends('layouts.horizon')

@section('page_title', 'Edit User')

@section('content')
<style>
    .page-header-row {
        margin-bottom: 24px;
    }

    .btn-back-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #FFFFFF;
        border: 1px solid #CBD5E1;
        color: #0F223D;
        transition: all 0.15s ease;
        box-shadow: 0 1px 3px rgba(15, 34, 61, 0.04);
        flex-shrink: 0;
        text-decoration: none;
    }

    .btn-back-circle:hover {
        background: #F8FAFC;
        border-color: #0F223D;
        color: #0F223D;
        transform: translateX(-2px);
        box-shadow: 0 2px 8px rgba(15, 34, 61, 0.08);
    }

    .page-title-block h2 {
        font-family: 'Newsreader', Georgia, serif;
        font-size: 2.2rem;
        font-weight: 500;
        color: #0F223D;
        margin: 0;
        line-height: 1.2;
    }

    .page-title-block p {
        font-size: 0.925rem;
        color: #64748B;
        margin: 4px 0 0 52px;
    }

    .edit-card {
        background: #FFFFFF;
        border-radius: 18px;
        border: 1px solid rgba(226, 232, 240, 0.95);
        padding: 28px;
        width: 100%;
        max-width: 100%;
        box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04);
    }

    .card-header-line {
        padding-bottom: 16px;
        margin-bottom: 20px;
        border-bottom: 1px solid #F1F5F9;
    }

    .card-header-title {
        font-family: 'Newsreader', Georgia, serif;
        font-size: 1.35rem;
        font-weight: 600;
        color: #0F223D;
        margin: 0;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 16px;
    }

    .form-label {
        font-size: 0.825rem;
        font-weight: 600;
        color: #334155;
    }

    .form-input {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid #CBD5E1;
        background: #FFFFFF;
        color: #0F223D;
        font-family: inherit;
        font-size: 0.875rem;
        outline: none;
        transition: all 0.15s ease;
        box-sizing: border-box;
    }

    .form-input:focus {
        border-color: #0F223D;
        box-shadow: 0 0 0 3px rgba(15, 34, 61, 0.08);
    }

    .form-select {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid #CBD5E1;
        background: #FFFFFF;
        color: #0F223D;
        font-family: inherit;
        font-size: 0.875rem;
        outline: none;
        cursor: pointer;
        transition: all 0.15s ease;
        box-sizing: border-box;
    }

    .form-select:focus {
        border-color: #0F223D;
        box-shadow: 0 0 0 3px rgba(15, 34, 61, 0.08);
    }

    .field-hint {
        font-size: 0.75rem;
        color: #64748B;
        margin-top: 3px;
        line-height: 1.4;
    }

    .field-error {
        color: #DC2626;
        font-size: 0.75rem;
        margin-top: 3px;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid #F1F5F9;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        background: #0F223D;
        color: #FFFFFF;
        font-family: inherit;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15);
    }

    .btn-submit:hover {
        background: #162C50;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 34, 61, 0.2);
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 10px;
        border: 1px solid #CBD5E1;
        background: #FFFFFF;
        color: #334155;
        font-family: inherit;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-cancel:hover {
        background: #F8FAFC;
        border-color: #94A3B8;
        color: #0F223D;
    }
</style>

<div class="page-header-row">
    <div class="page-title-block">
        <div style="display: flex; align-items: center; gap: 14px;">
            <a href="{{ route('manajemen-user.index') }}" class="btn-back-circle" title="Kembali ke Manajemen User">
                <i data-lucide="arrow-left" style="width: 18px; height: 18px;"></i>
            </a>
            <h2>Edit Pengguna</h2>
        </div>
        <p>Perbarui informasi akun merchant dan status langganan.</p>
    </div>
</div>

@if ($errors->any())
    <div style="margin-bottom: 20px; padding: 12px 16px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 10px; color: #991B1B; font-size: 0.85rem;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="edit-card">
    <div class="card-header-line">
        <h3 class="card-header-title">Formulir Data Pengguna</h3>
    </div>

    <form method="POST" action="{{ route('manajemen-user.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
            @error('name')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Alamat Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
            @error('email')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Kata Sandi Baru</label>
            <input type="password" name="password" class="form-input" placeholder="Kosongkan jika tidak ingin mengubah kata sandi">
            <p class="field-hint">Minimal 8 karakter. Biarkan kosong jika kata sandi tetap sama.</p>
            @error('password')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Konfirmasi Kata Sandi Baru</label>
            <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi kata sandi baru">
            @error('password_confirmation')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Ubah Status Langganan</label>
            <select name="status" class="form-select">
                <option value="">-- Tetap Pertahankan Status Saat Ini --</option>
                <option value="aktif">✓ Jadikan Aktif (Berlangganan Aktif)</option>
                <option value="trial">◎ Jadikan Masa Percobaan (Trial 7 Hari)</option>
                <option value="non_aktif">✕ Non-Aktifkan Akun</option>
            </select>
            <p class="field-hint">
                Pilih opsi di atas jika Anda ingin mengubah status langganan pengguna secara langsung.
            </p>
            @error('status')
                <span class="field-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                <span>Simpan Perubahan</span>
            </button>
            <a href="{{ route('manajemen-user.index') }}" class="btn-cancel">
                <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
                <span>Batal</span>
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection
