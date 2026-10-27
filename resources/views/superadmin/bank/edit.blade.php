@extends('layouts.horizon')

@section('page_title', 'Edit Bank')

@section('content')
<div class="hz-top-row">
    <div>
        <h2 class="hz-top-title">Edit Bank</h2>
        <p class="hz-top-sub">Ubah data rekening bank yang tersedia.</p>
    </div>
    <div class="hz-top-actions">
        <a href="{{ route('bank.index') }}" style="background:rgba(148,163,184,0.2); color:#e5e7eb; border:1px solid rgba(148,163,184,0.5); padding:8px 16px; border-radius:6px; text-decoration:none; font-size:0.9rem;">
            ← Kembali
        </a>
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

<section class="hz-secondary-grid" style="margin-top: 24px; max-width: 600px;">
    <div class="hz-card">
        <div class="hz-card-header" style="margin-bottom: 20px;">
            <div>
                <p class="hz-card-title">Form Edit Bank</p>
            </div>
        </div>

        <form method="POST" action="{{ route('bank.update', $bank) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 16px;">
                <label style="display:block; color:#e5e7eb; font-weight:500; margin-bottom:6px; font-size:0.9rem;">
                    Nama Bank <span style="color:#ef4444;">*</span>
                </label>
                <input type="text" name="bank_name" value="{{ old('bank_name', $bank->bank_name) }}" placeholder="Contoh: Bank Central Asia" 
                    style="width:100%; background:#020617; border:1px solid rgba(148,163,184,0.3); border-radius:6px; padding:10px 12px; color:#e5e7eb; font-size:0.9rem;" required>
                @error('bank_name')
                    <span style="color:#ef4444; font-size:0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display:block; color:#e5e7eb; font-weight:500; margin-bottom:6px; font-size:0.9rem;">
                    Nama Pemilik Rekening <span style="color:#ef4444;">*</span>
                </label>
                <input type="text" name="account_holder" value="{{ old('account_holder', $bank->account_holder) }}" placeholder="Contoh: PT Golday Indonesia" 
                    style="width:100%; background:#020617; border:1px solid rgba(148,163,184,0.3); border-radius:6px; padding:10px 12px; color:#e5e7eb; font-size:0.9rem;" required>
                @error('account_holder')
                    <span style="color:#ef4444; font-size:0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display:block; color:#e5e7eb; font-weight:500; margin-bottom:6px; font-size:0.9rem;">
                    Nomor Rekening <span style="color:#ef4444;">*</span>
                </label>
                <input type="text" name="account_number" value="{{ old('account_number', $bank->account_number) }}" placeholder="Contoh: 1234567890" 
                    style="width:100%; background:#020617; border:1px solid rgba(148,163,184,0.3); border-radius:6px; padding:10px 12px; color:#e5e7eb; font-size:0.9rem;" required>
                @error('account_number')
                    <span style="color:#ef4444; font-size:0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display:flex; gap:10px;">
                <button type="submit" style="flex:1; background:rgba(34,197,94,0.2); color:#22c55e; border:1px solid rgba(34,197,94,0.5); padding:10px 16px; border-radius:6px; cursor:pointer; font-weight:500; font-size:0.9rem;">
                    ✓ Simpan Perubahan
                </button>
                <a href="{{ route('bank.index') }}" style="flex:1; background:rgba(148,163,184,0.2); color:#e5e7eb; border:1px solid rgba(148,163,184,0.5); padding:10px 16px; border-radius:6px; text-decoration:none; font-weight:500; font-size:0.9rem; text-align:center;">
                    ✕ Batal
                </a>
            </div>
        </form>
    </div>
</section>
@endsection
