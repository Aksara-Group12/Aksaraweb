@extends('layouts.horizon')

@section('page_title', 'Manajemen User')
@section('page_subtitle', 'Kelola seluruh akun merchant pemilik toko (role owner) dan status langganannya.')

@section('content')
<style>
    .page-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .page-title-block h2 {
        font-family: 'Newsreader', Georgia, serif;
        font-size: 2.2rem;
        font-weight: 500;
        color: #0F223D;
        margin: 0 0 4px 0;
        line-height: 1.2;
    }

    .page-title-block p {
        font-size: 0.925rem;
        color: #64748B;
        margin: 0;
    }

    /* Filter & Search Bar */
    .filter-form {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-input-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
    }

    .search-input-wrap svg,
    .search-input-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 15px;
        height: 15px;
        color: #94A3B8;
        pointer-events: none;
        z-index: 5;
    }

    .search-input {
        border-radius: 10px;
        border: 1px solid #CBD5E1;
        background: #FFFFFF;
        padding: 8px 12px 8px 36px !important;
        font-family: inherit;
        font-size: 0.825rem;
        color: #0F223D;
        min-width: 250px;
        outline: none;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(15, 34, 61, 0.04);
    }

    .search-input:focus {
        border-color: #0F223D;
        box-shadow: 0 0 0 3px rgba(15, 34, 61, 0.08);
    }

    .per-page-select {
        border-radius: 10px;
        border: 1px solid #CBD5E1;
        background: #FFFFFF;
        padding: 8px 10px;
        font-family: inherit;
        font-size: 0.825rem;
        color: #0F223D;
        outline: none;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(15, 34, 61, 0.04);
    }

    .per-page-select:focus {
        border-color: #0F223D;
    }

    /* Alerts */
    .pending-alert-box {
        margin-bottom: 20px;
        padding: 16px 20px;
        background: #FFFBEB;
        border: 1px solid #FDE68A;
        border-radius: 14px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .pending-alert-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(217, 119, 6, 0.12);
        color: #D97706;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .flash-alert {
        margin-bottom: 20px;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .flash-success {
        background: #ECFDF5;
        border: 1px solid #A7F3D0;
        color: #065F46;
    }

    .flash-error {
        background: #FEF2F2;
        border: 1px solid #FECACA;
        color: #991B1B;
    }

    /* Card & Table */
    .user-table-card {
        background: #FFFFFF;
        border-radius: 18px;
        border: 1px solid rgba(226, 232, 240, 0.95);
        padding: 24px;
        box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04);
    }

    .table-top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .table-title {
        font-family: 'Newsreader', Georgia, serif;
        font-size: 1.35rem;
        font-weight: 600;
        color: #0F223D;
        margin: 0;
    }

    .bulk-action-bar {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-bulk-secondary {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 8px;
        border: 1px solid #CBD5E1;
        background: #FFFFFF;
        color: #334155;
        font-family: inherit;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-bulk-secondary:hover {
        background: #F8FAFC;
        border-color: #94A3B8;
        color: #0F223D;
    }

    .btn-bulk-delete {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 8px;
        border: 1px solid #FECACA;
        background: #FEF2F2;
        color: #DC2626;
        font-family: inherit;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-bulk-delete:hover {
        background: #FEE2E2;
        border-color: #F87171;
    }

    /* Table Styling */
    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.85rem;
    }

    .custom-table th {
        background: #F8FAFC;
        padding: 12px 14px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #E2E8F0;
    }

    .custom-table th:first-child {
        border-top-left-radius: 10px;
    }

    .custom-table th:last-child {
        border-top-right-radius: 10px;
    }

    .custom-table td {
        padding: 14px;
        border-bottom: 1px solid #F1F5F9;
        color: #334155;
        vertical-align: middle;
        transition: background 0.15s ease;
    }

    .custom-table tr:hover td {
        background: #FAF8F5;
    }

    .user-name-title {
        font-weight: 600;
        color: #0F223D;
        font-size: 0.875rem;
        margin: 0;
    }

    .user-email-text {
        font-size: 0.78rem;
        color: #64748B;
        margin: 2px 0 0;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 9999px;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    .status-badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .badge-trial {
        background: rgba(197, 160, 89, 0.12);
        color: #9E7E36;
        border: 1px solid rgba(197, 160, 89, 0.28);
    }
    .badge-trial .status-badge-dot { background: #C5A059; }

    .badge-active {
        background: #ECFDF5;
        color: #065F46;
        border: 1px solid #A7F3D0;
    }
    .badge-active .status-badge-dot { background: #10B981; }

    .badge-inactive {
        background: #F8FAFC;
        color: #64748B;
        border: 1px solid #E2E8F0;
    }
    .badge-inactive .status-badge-dot { background: #94A3B8; }

    /* Action Buttons (Shadcn Aligned) */
    .row-actions-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-action-status {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 8px;
        background: rgba(197, 160, 89, 0.1);
        color: #9E7E36;
        border: 1px solid rgba(197, 160, 89, 0.3);
        font-family: inherit;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .btn-action-status:hover {
        background: rgba(197, 160, 89, 0.2);
        color: #0F223D;
        border-color: rgba(197, 160, 89, 0.5);
    }

    .btn-action-edit {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 8px;
        background: #FFFFFF;
        color: #0F223D;
        border: 1px solid #CBD5E1;
        font-family: inherit;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .btn-action-edit:hover {
        background: #F8FAFC;
        border-color: #94A3B8;
        transform: translateY(-1px);
    }

    .btn-action-delete {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 8px;
        background: #FEF2F2;
        color: #DC2626;
        border: 1px solid #FECACA;
        font-family: inherit;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-action-delete:hover {
        background: #FEE2E2;
        border-color: #F87171;
        transform: translateY(-1px);
    }

    .pending-chip {
        position: absolute;
        top: -6px;
        right: -6px;
        background: #EF4444;
        color: #FFFFFF;
        border-radius: 9999px;
        width: 18px;
        height: 18px;
        font-size: 0.65rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #FFFFFF;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Modal Backdrop & Dialog (Shadcn UI Standards) */
    .custom-modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 34, 61, 0.5);
        backdrop-filter: blur(4px);
        z-index: 100;
        animation: fadeIn 0.15s ease;
    }

    .custom-modal-dialog {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #FFFFFF;
        border-radius: 16px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 25px 50px -12px rgba(15, 34, 61, 0.25);
        z-index: 101;
        width: 90%;
        max-width: 480px;
        overflow: hidden;
        animation: scaleIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: translate(-50%, -48%) scale(0.96); }
        to { opacity: 1; transform: translate(-50%, -50%) scale(1); }
    }

    .modal-content-pad {
        padding: 24px;
    }

    .modal-icon-badge {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }

    .modal-icon-danger {
        background: #FEF2F2;
        color: #DC2626;
        border: 1px solid #FECACA;
    }

    .modal-title {
        font-family: 'Newsreader', Georgia, serif;
        font-size: 1.4rem;
        font-weight: 600;
        color: #0F223D;
        margin: 0 0 6px;
        line-height: 1.25;
    }

    .modal-desc {
        font-size: 0.85rem;
        color: #64748B;
        line-height: 1.5;
        margin: 0 0 20px;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding-top: 16px;
        border-top: 1px solid #F1F5F9;
    }

    .btn-modal-cancel {
        padding: 9px 16px;
        border-radius: 9px;
        border: 1px solid #CBD5E1;
        background: #FFFFFF;
        color: #334155;
        font-family: inherit;
        font-size: 0.825rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-modal-cancel:hover {
        background: #F8FAFC;
        border-color: #94A3B8;
    }

    .btn-modal-confirm-delete {
        padding: 9px 16px;
        border-radius: 9px;
        border: none;
        background: #DC2626;
        color: #FFFFFF;
        font-family: inherit;
        font-size: 0.825rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.2);
    }

    .btn-modal-confirm-delete:hover {
        background: #B91C1C;
    }

    /* Subscription Details Modal */
    #subscriptionModal {
        max-width: 560px;
        max-height: 85vh;
        overflow-y: auto;
    }

    .sub-card-item {
        background: #FAF8F5;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        transition: border-color 0.15s ease;
    }

    .sub-card-item:hover {
        border-color: rgba(197, 160, 89, 0.4);
    }
</style>

<div class="hz-top-row" style="display:flex; justify-content:flex-end; align-items:center; margin-bottom: 20px;">
    <div class="page-actions">
        <form method="GET" action="{{ route('manajemen-user.index') }}" class="filter-form">
            <div class="search-input-wrap">
                <i data-lucide="search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="search-input">
            </div>
            <select name="per_page" onchange="this.form.submit()" class="per-page-select" title="Baris per halaman">
                <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10 / hal</option>
                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 / hal</option>
                <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50 / hal</option>
            </select>
        </form>
    </div>
</div>

{{-- Notifikasi Pending Subscriptions --}}
@php
    $pendingUsers = \App\Models\User::where('role', 'owner')
        ->whereHas('subscriptions', function ($q) {
            $q->where('approval_status', 'pending');
        })
        ->with(['subscriptions' => function ($q) {
            $q->where('approval_status', 'pending')->latest();
        }])
        ->limit(5)
        ->get();
@endphp

@if ($pendingUsers->count() > 0)
    <div class="pending-alert-box">
        <div class="pending-alert-icon">
            <i data-lucide="zap" style="width: 20px; height: 20px;"></i>
        </div>
        <div>
            <strong style="font-size: 0.95rem; color: #92400E; display: block;">{{ $pendingUsers->count() }} Permintaan Langganan Menunggu Persetujuan</strong>
            <p style="margin: 3px 0 10px 0; font-size: 0.85rem; color: #B45309;">
                Terdapat bukti pembayaran transfer baru yang perlu diverifikasi oleh Superadmin.
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                @foreach ($pendingUsers as $pUser)
                    <div style="background: #FFFFFF; border: 1px solid #FDE68A; padding: 4px 10px; border-radius: 8px; font-size: 0.78rem; display: flex; align-items: center; gap: 6px; color: #78350F; font-weight: 600;">
                        <span>{{ $pUser->name }}</span>
                        <span style="background: #EF4444; color: white; border-radius: 9999px; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; font-size: 0.65rem;">
                            {{ $pUser->subscriptions->count() }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

@if (session('success'))
    <div class="flash-alert flash-success">
        <i data-lucide="check-circle-2" style="width: 16px; height: 16px;"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error') || $errors->any())
    <div class="flash-alert flash-error">
        <i data-lucide="alert-circle" style="width: 16px; height: 16px;"></i>
        <span>{{ session('error') ?? $errors->first() }}</span>
    </div>
@endif

<div class="user-table-card">
    <div class="table-top-bar">
        <h3 class="table-title">Daftar Pengguna</h3>
        <div class="bulk-action-bar">
            <button type="button" onclick="selectAllUsers()" class="btn-bulk-secondary" title="Pilih semua baris pengguna">
                <i data-lucide="check-square" style="width: 14px; height: 14px;"></i>
                <span>Pilih Semua</span>
            </button>
            <button type="button" onclick="clearSelectUsers()" class="btn-bulk-secondary" title="Batalkan seluruh pilihan">
                <i data-lucide="square" style="width: 14px; height: 14px;"></i>
                <span>Batal Pilih</span>
            </button>
            <button type="button" onclick="triggerBulkDelete()" class="btn-bulk-delete" id="btnBulkDeleteUsers">
                <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                <span id="bulkDeleteUsersLabel">Hapus Terpilih</span>
            </button>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <form id="bulkForm" method="POST" action="{{ route('manajemen-user.bulk-destroy') }}">
            @csrf
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="text-align: center; width: 44px;">
                            <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAllFromCheckbox(this)" style="cursor: pointer; accent-color: #0F223D;" title="Centang Semua">
                        </th>
                        <th style="text-align: left;">Nama & Email Pengguna</th>
                        <th style="text-align: center; width: 140px;">Status Akun</th>
                        <th style="text-align: center; width: 230px;">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $hasPending = \App\Models\Subscribe::where('user_id', $user->id)
                                ->where('approval_status', 'pending')
                                ->exists();
                            $activeSub = $activeSubscriptions[$user->id] ?? null;
                        @endphp
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" name="ids[]" value="{{ $user->id }}" class="user-checkbox" style="cursor: pointer; accent-color: #0F223D;">
                            </td>
                            <td>
                                <p class="user-name-title">{{ $user->name }}</p>
                                <p class="user-email-text">{{ $user->email }}</p>
                            </td>
                            <td style="text-align: center;">
                                @if ($activeSub)
                                    @if ($activeSub->is_trial)
                                        <span class="status-badge badge-trial">
                                            <span class="status-badge-dot"></span>
                                            <span>Trial</span>
                                        </span>
                                    @else
                                        <span class="status-badge badge-active">
                                            <span class="status-badge-dot"></span>
                                            <span>Aktif</span>
                                        </span>
                                    @endif
                                @else
                                    <span class="status-badge badge-inactive">
                                        <span class="status-badge-dot"></span>
                                        <span>Non-Aktif</span>
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="row-actions-wrap">
                                    @php
                                        $pendingCount = \App\Models\Subscribe::where('user_id', $user->id)
                                            ->where('approval_status', 'pending')
                                            ->count();
                                    @endphp
                                    <button type="button" class="btn-action-status" onclick="showSubscriptionModal({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                        <i data-lucide="credit-card" style="width: 14px; height: 14px;"></i>
                                        <span>Status</span>
                                        @if($pendingCount > 0)
                                            <span class="pending-chip">{{ $pendingCount }}</span>
                                        @endif
                                    </button>

                                    <a href="{{ route('manajemen-user.edit', $user) }}" class="btn-action-edit">
                                        <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i>
                                        <span>Edit</span>
                                    </a>

                                    <button type="button" class="btn-action-delete" onclick="openDeleteModal('single', '{{ route('manajemen-user.destroy', $user) }}', '{{ addslashes($user->name) }}')">
                                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 32px 14px; text-align: center; color: #94A3B8;">
                                <i data-lucide="user-x" style="width: 32px; height: 32px; margin: 0 auto 8px; color: #CBD5E1; display: block;"></i>
                                <span style="font-size: 0.9rem;">Belum ada akun merchant pengguna terdaftar.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>

    @if (method_exists($users, 'links'))
        <div style="margin-top: 16px; padding-top: 14px; border-top: 1px solid #F1F5F9; display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; color: #64748B;">
            <div>
                Menampilkan <strong>{{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }}</strong> dari total <strong>{{ $users->total() }}</strong> pengguna
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    @endif
</div>

{{-- Modal Konfirmasi Hapus (Pop-up Shadcn UI) --}}
<div id="deleteModalBackdrop" class="custom-modal-backdrop" onclick="closeDeleteModal()"></div>
<div id="deleteConfirmModal" class="custom-modal-dialog">
    <div class="modal-content-pad">
        <div class="modal-icon-badge modal-icon-danger">
            <i data-lucide="alert-triangle" style="width: 24px; height: 24px;"></i>
        </div>
        <h4 class="modal-title">Konfirmasi Hapus Akun</h4>
        <p class="modal-desc" id="deleteModalDescription">
            Apakah Anda yakin ingin menghapus akun pengguna ini? Tindakan ini tidak dapat dibatalkan.
        </p>

        <form id="singleDeleteForm" method="POST" action="" style="display: none;">
            @csrf
            @method('DELETE')
        </form>

        <div class="modal-actions">
            <button type="button" class="btn-modal-cancel" onclick="closeDeleteModal()">Batal</button>
            <button type="button" class="btn-modal-confirm-delete" id="confirmDeleteButton" onclick="executeDelete()">
                Ya, Hapus Pengguna
            </button>
        </div>
    </div>
</div>

{{-- Modal Detail Langganan (Subscription Details) --}}
<div id="subscriptionBackdrop" class="custom-modal-backdrop" onclick="closeSubscriptionModal()"></div>
<div id="subscriptionModal" class="custom-modal-dialog">
    <div style="padding: 20px 24px; border-bottom: 1px solid #F1F5F9; display: flex; justify-content: space-between; align-items: center; background: #FFFFFF; position: sticky; top: 0; z-index: 10;">
        <div>
            <h4 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.35rem; font-weight: 600; color: #0F223D; margin: 0;">Detail Langganan Pengguna</h4>
            <p style="margin: 3px 0 0; color: #64748B; font-size: 0.8rem;">
                <span id="modalUserName" style="font-weight: 600; color: #0F223D;"></span> &bull; <span id="modalUserEmail"></span>
            </p>
        </div>
        <button type="button" onclick="closeSubscriptionModal()" style="background: none; border: none; color: #94A3B8; cursor: pointer; padding: 4px; border-radius: 6px; display: flex; align-items: center; justify-content: center; transition: color 0.15s ease;" onmouseover="this.style.color='#0F223D'" onmouseout="this.style.color='#94A3B8'">
            <i data-lucide="x" style="width: 20px; height: 20px;"></i>
        </button>
    </div>
    <div style="padding: 20px 24px;" id="subscriptionsList">
        <p style="color: #94A3B8; text-align: center; padding: 20px;">Memuat data langganan...</p>
    </div>
</div>

<script>
    let currentDeleteMode = 'single';
    let singleDeleteActionUrl = '';

    function updateBulkUsersState() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        const checkedCount = document.querySelectorAll('.user-checkbox:checked').length;
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const bulkDeleteLabel = document.getElementById('bulkDeleteUsersLabel');
        const btnBulkDelete = document.getElementById('btnBulkDeleteUsers');

        if (selectAllCheckbox && checkboxes.length > 0) {
            selectAllCheckbox.checked = (checkedCount === checkboxes.length);
            selectAllCheckbox.indeterminate = (checkedCount > 0 && checkedCount < checkboxes.length);
        }

        if (bulkDeleteLabel) {
            bulkDeleteLabel.textContent = checkedCount > 0 ? `Hapus Terpilih (${checkedCount})` : 'Hapus Terpilih';
        }

        if (btnBulkDelete) {
            if (checkedCount > 0) {
                btnBulkDelete.style.background = '#DC2626';
                btnBulkDelete.style.color = '#FFFFFF';
                btnBulkDelete.style.borderColor = '#DC2626';
                btnBulkDelete.style.boxShadow = '0 2px 8px rgba(220, 38, 38, 0.25)';
            } else {
                btnBulkDelete.style.background = '#FEF2F2';
                btnBulkDelete.style.color = '#DC2626';
                btnBulkDelete.style.borderColor = '#FECACA';
                btnBulkDelete.style.boxShadow = 'none';
            }
        }
    }

    function selectAllUsers() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(cb => { cb.checked = true; });
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        }
        updateBulkUsersState();
    }

    function clearSelectUsers() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(cb => { cb.checked = false; });
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
        updateBulkUsersState();
    }

    function toggleSelectAllFromCheckbox(headerCb) {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(cb => { cb.checked = headerCb.checked; });
        updateBulkUsersState();
    }

    // Modal Konfirmasi Hapus
    function openDeleteModal(mode, actionUrl, userName = '') {
        currentDeleteMode = mode;
        const modal = document.getElementById('deleteConfirmModal');
        const backdrop = document.getElementById('deleteModalBackdrop');
        const desc = document.getElementById('deleteModalDescription');

        if (mode === 'single') {
            singleDeleteActionUrl = actionUrl;
            desc.innerHTML = `Apakah Anda yakin ingin menghapus pengguna <strong>"${userName}"</strong>? Seluruh data profil toko, produk, dan transaksi pengguna ini akan dihapus secara permanen.`;
        } else {
            const count = document.querySelectorAll('.user-checkbox:checked').length;
            desc.innerHTML = `Apakah Anda yakin ingin menghapus <strong>${count} pengguna terpilih</strong> sekaligus? Tindakan ini bersifat permanen dan tidak dapat dibatalkan.`;
        }

        backdrop.style.display = 'block';
        modal.style.display = 'block';
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function closeDeleteModal() {
        document.getElementById('deleteConfirmModal').style.display = 'none';
        document.getElementById('deleteModalBackdrop').style.display = 'none';
    }

    function triggerBulkDelete() {
        const selected = document.querySelectorAll('.user-checkbox:checked');
        if (selected.length === 0) {
            alert('Silakan pilih minimal 1 pengguna yang ingin dihapus.');
            return;
        }
        openDeleteModal('bulk');
    }

    function executeDelete() {
        if (currentDeleteMode === 'single') {
            const form = document.getElementById('singleDeleteForm');
            form.action = singleDeleteActionUrl;
            form.submit();
        } else if (currentDeleteMode === 'bulk') {
            document.getElementById('bulkForm').submit();
        }
    }

    // Modal Detail Langganan
    function showSubscriptionModal(userId, userName) {
        const modal = document.getElementById('subscriptionModal');
        const backdrop = document.getElementById('subscriptionBackdrop');
        const subList = document.getElementById('subscriptionsList');

        subList.innerHTML = '<p style="color:#94A3B8; text-align:center; padding:24px;">Memuat data langganan...</p>';
        backdrop.style.display = 'block';
        modal.style.display = 'block';

        fetch(`/manajemen-user/${userId}/subscription-details`)
            .then(res => res.json())
            .then(data => {
                if (data.error) throw new Error(data.error);

                document.getElementById('modalUserName').textContent = data.user_name;
                document.getElementById('modalUserEmail').textContent = data.user_email;

                if (!data.subscriptions || data.subscriptions.length === 0) {
                    subList.innerHTML = '<p style="color:#94A3B8; text-align:center; padding:24px;">Belum ada riwayat langganan untuk pengguna ini.</p>';
                    return;
                }

                subList.innerHTML = '';
                data.subscriptions.forEach(sub => {
                    const isApproved = sub.approval_status === 'approved';
                    const isRejected = sub.approval_status === 'rejected';
                    const isPending = sub.approval_status === 'pending';

                    let badgeColor = isApproved ? 'background:#ECFDF5; color:#065F46; border:1px solid #A7F3D0;' :
                                    (isRejected ? 'background:#FEF2F2; color:#991B1B; border:1px solid #FECACA;' :
                                    'background:#FFFBEB; color:#92400E; border:1px solid #FDE68A;');

                    const cardHTML = `
                        <div class="sub-card-item">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px;">
                                <div>
                                    <h5 style="margin:0; font-size:0.95rem; font-weight:700; color:#0F223D;">Paket: ${sub.paket_name}</h5>
                                    <p style="margin:3px 0 0; font-size:0.8rem; color:#64748B;">Nominal: <strong>Rp ${parseInt(sub.price).toLocaleString('id-ID')}</strong></p>
                                </div>
                                <span style="display:inline-flex; align-items:center; padding:3px 8px; border-radius:6px; font-size:0.72rem; font-weight:700; text-transform:uppercase; ${badgeColor}">
                                    ${sub.approval_status}
                                </span>
                            </div>

                            <div style="font-size:0.78rem; color:#64748B; display:flex; flex-direction:column; gap:4px;">
                                <p style="margin:0;"><strong>Jenis:</strong> ${sub.is_trial ? 'Masa Percobaan (Trial)' : 'Langganan Berbayar'}</p>
                                <p style="margin:0;"><strong>Periode:</strong> ${sub.start_date} s/d ${sub.end_date}</p>
                                ${sub.bukti_transaksi_url ? `
                                    <p style="margin:4px 0 0;">
                                        <a href="${sub.bukti_transaksi_url}" target="_blank" style="color:#C5A059; font-weight:600; text-decoration:underline; display:inline-flex; align-items:center; gap:4px;">
                                            <span>📷 Lihat Bukti Transfer</span>
                                        </a>
                                    </p>
                                ` : ''}
                                <p style="margin:4px 0 0; font-size:0.72rem; color:#94A3B8;">Dibuat: ${sub.created_at}</p>
                            </div>

                            ${isPending ? `
                                <div style="display:flex; gap:8px; margin-top:12px; padding-top:10px; border-top:1px solid #E2E8F0;">
                                    <button onclick="openApproveForm(${sub.id})" style="flex:1; background:#0F223D; color:#FFFFFF; border:none; padding:8px 12px; border-radius:8px; cursor:pointer; font-size:0.78rem; font-weight:600;">
                                        ✓ Verifikasi &amp; Setujui
                                    </button>
                                    <button onclick="openRejectForm(${sub.id})" style="flex:1; background:#FEF2F2; color:#DC2626; border:1px solid #FECACA; padding:8px 12px; border-radius:8px; cursor:pointer; font-size:0.78rem; font-weight:600;">
                                        ✕ Tolak Pembayaran
                                    </button>
                                </div>
                            ` : ''}
                        </div>
                    `;
                    subList.innerHTML += cardHTML;
                });
                if (typeof lucide !== 'undefined') lucide.createIcons();
            })
            .catch(err => {
                subList.innerHTML = `<p style="color:#DC2626; text-align:center; padding:20px;">Gagal memuat data: ${err.message}</p>`;
            });
    }

    function closeSubscriptionModal() {
        document.getElementById('subscriptionModal').style.display = 'none';
        document.getElementById('subscriptionBackdrop').style.display = 'none';
    }

    function openApproveForm(subscriptionId) {
        const basePath = `{{ route('manajemen-user.index') }}`;
        window.location.href = basePath.replace(/\/$/, '') + '/subscription/' + subscriptionId + '/edit';
    }

    function openRejectForm(subscriptionId) {
        const notes = prompt('Masukkan alasan penolakan pembayaran:');
        if (notes !== null && notes.trim() !== '') {
            const form = document.createElement('form');
            form.method = 'POST';
            const basePath = `{{ route('manajemen-user.index') }}`;
            form.action = basePath.replace(/\/$/, '') + '/subscription/' + subscriptionId + '/reject';
            form.innerHTML = `
                @csrf
                <input type="hidden" name="admin_notes" value="${notes}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        document.querySelectorAll('.user-checkbox').forEach(cb => {
            cb.addEventListener('change', updateBulkUsersState);
        });

        updateBulkUsersState();
    });
</script>
@endsection
