@extends('layouts.horizon')

@section('page_title', 'Approve Subscription')

@section('content')
<div class="hz-top-row">
    <div>
        <h2 class="hz-top-title">Approve Subscription</h2>
        <p class="hz-top-sub">User: {{ $user->name }} ({{ $user->email }})</p>
    </div>
</div>

@if ($errors->any())
    <div style="margin: 16px 0; padding: 12px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px; color: #ef4444;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<section class="hz-secondary-grid" style="margin-top: 24px;">
    <div class="hz-card">
        <div class="hz-card-header" style="margin-bottom: 16px;">
            <p class="hz-card-title">Request Details</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
                <label style="color: #64748b; font-size: 0.85rem; font-weight: 500; display: block; margin-bottom: 4px;">Paket Saat Ini</label>
                <p style="color: #1e293b; background: rgba(148,163,184,0.1); padding: 8px 12px; border-radius: 6px; margin: 0;">
                    {{ $subscription->paket_name ?? 'Trial' }}
                </p>
            </div>

            <div>
                <label style="color: #64748b; font-size: 0.85rem; font-weight: 500; display: block; margin-bottom: 4px;">Nominal Pembayaran</label>
                <p style="color: #1e293b; background: rgba(148,163,184,0.1); padding: 8px 12px; border-radius: 6px; margin: 0;">
                    Rp{{ number_format($subscription->price, 0, ',', '.') }}
                </p>
            </div>

            <div>
                <label style="color: #64748b; font-size: 0.85rem; font-weight: 500; display: block; margin-bottom: 4px;">Status</label>
                <p style="color: #1e293b; background: rgba(148,163,184,0.1); padding: 8px 12px; border-radius: 6px; margin: 0;">
                    {{ ucfirst($subscription->status) }}
                </p>
            </div>

            <div>
                <label style="color: #64748b; font-size: 0.85rem; font-weight: 500; display: block; margin-bottom: 4px;">Approval Status</label>
                <span style="display: inline-block; background: rgba(59,130,246,0.2); color: #3b82f6; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">
                    {{ ucfirst($subscription->approval_status) }}
                </span>
            </div>
        </div>

        @if ($subscription->bukti_transaksi)
            <div style="margin-bottom: 20px;">
                <label style="color: #64748b; font-size: 0.85rem; font-weight: 500; display: block; margin-bottom: 8px;">📷 Bukti Pembayaran</label>
                <div style="border: 1px solid rgba(148,163,184,0.3); border-radius: 6px; overflow: hidden; max-width: 300px;">
                    <img src="{{ asset('storage/' . $subscription->bukti_transaksi) }}" alt="Bukti Pembayaran" style="width: 100%; max-height: 400px; object-fit: contain; background: #ffffff;">
                </div>
            </div>
        @endif
    </div>

    <div class="hz-card">
        <div class="hz-card-header" style="margin-bottom: 16px;">
            <p class="hz-card-title">Approve Settings</p>
        </div>

        <form method="POST" action="{{ route('manajemen-user.subscription.approve', $subscription) }}">
            @csrf

            <div style="margin-bottom: 16px;">
                <label for="paket_id" style="color: #64748b; font-size: 0.85rem; font-weight: 500; display: block; margin-bottom: 6px;">
                    Pilih Paket *
                </label>
                <select name="paket_id" id="paket_id" required style="width: 100%; background: rgba(148,163,184,0.1); border: 1px solid rgba(148,163,184,0.3); color: #1e293b; padding: 10px 12px; border-radius: 6px; font-size: 0.95rem;">
                    <option value="">-- Pilih Paket --</option>
                    @foreach($packages as $pkg)
                        <option value="{{ $pkg->id }}" {{ $subscription->paket_id == $pkg->id ? 'selected' : '' }}>
                            {{ $pkg->nama_paket }} - Rp{{ number_format($pkg->harga, 0, ',', '.') }} ({{ $pkg->durasi_bulan }} bulan)
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 16px;">
                <label for="duration_days" style="color: #64748b; font-size: 0.85rem; font-weight: 500; display: block; margin-bottom: 6px;">
                    Durasi Berlangganan (hari) *
                </label>
                <input type="number" name="duration_days" id="duration_days" value="{{ $subscription->duration_days ?? 30 }}" 
                       required min="1" max="3650" style="width: 100%; background: rgba(148,163,184,0.1); border: 1px solid rgba(148,163,184,0.3); color: #1e293b; padding: 10px 12px; border-radius: 6px; font-size: 0.95rem;">
                <small style="color: #64748b; display: block; margin-top: 4px;">Contoh: 30 hari, 90 hari, 365 hari</small>
            </div>

            <div style="margin-bottom: 16px;">
                <label for="admin_notes" style="color: #64748b; font-size: 0.85rem; font-weight: 500; display: block; margin-bottom: 6px;">
                    Catatan Admin (Optional)
                </label>
                <textarea name="admin_notes" id="admin_notes" rows="3" style="width: 100%; background: rgba(148,163,184,0.1); border: 1px solid rgba(148,163,184,0.3); color: #1e293b; padding: 10px 12px; border-radius: 6px; font-size: 0.95rem; font-family: inherit;">{{ $subscription->admin_notes }}</textarea>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" style="flex: 1; background: rgba(16,185,129,0.2); color: #10b981; border: 1px solid rgba(16,185,129,0.5); padding: 10px 16px; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 0.95rem;">
                    ✓ Approve Subscription
                </button>
                <a href="{{ route('manajemen-user.index') }}" style="flex: 1; background: rgba(148,163,184,0.2); color: #64748b; border: 1px solid rgba(148,163,184,0.5); padding: 10px 16px; border-radius: 6px; text-decoration: none; text-align: center; font-weight: 500; font-size: 0.95rem;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</section>

<script>
    // Auto-calculate durasi berdasarkan paket yang dipilih
    document.getElementById('paket_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        // Opsi untuk auto-calculate bisa ditambahkan di sini jika perlu
    });
</script>
@endsection
