@extends('layouts.horizon')

@section('page_title', 'Rekening Bank')
@section('page_subtitle', 'Kelola rekening bank tujuan transfer pembayaran langganan merchant.')

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

    .header-actions-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-add-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        border-radius: 10px;
        background: #0F223D;
        color: #FFFFFF;
        font-family: inherit;
        font-size: 0.825rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
        box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15);
    }

    .btn-add-primary:hover {
        background: #162C50;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(15, 34, 61, 0.2);
    }

    .filter-form-wrap {
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

    .select-filter {
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

    .select-filter:focus {
        border-color: #0F223D;
    }

    /* Alerts */
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
    .bank-table-card {
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

    /* Table */
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

    .custom-table th:first-child { border-top-left-radius: 10px; }
    .custom-table th:last-child { border-top-right-radius: 10px; }

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

    .bank-name-text {
        font-weight: 700;
        color: #0F223D;
        font-size: 0.875rem;
    }

    .account-holder-text {
        color: #475569;
        font-weight: 500;
    }

    .account-number-pill {
        display: inline-block;
        padding: 4px 10px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.85rem;
        color: #0F223D;
        font-weight: 600;
        letter-spacing: 0.04em;
    }

    .row-actions-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
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

    /* Modal Confirmation */
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
        padding: 24px;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: translate(-50%, -48%) scale(0.96); }
        to { opacity: 1; transform: translate(-50%, -50%) scale(1); }
    }
</style>

<div class="hz-top-row" style="display:flex; justify-content:flex-end; align-items:center; margin-bottom: 20px;">
    <div class="header-actions-wrap" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
        <a href="{{ route('bank.create') }}" class="btn-add-primary">
            <i data-lucide="plus" style="width: 15px; height: 15px;"></i>
            <span>Tambah Rekening Bank</span>
        </a>
        <form method="GET" action="{{ route('bank.index') }}" class="filter-form-wrap">
            <div class="search-input-wrap">
                <i data-lucide="search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama bank atau no. rekening..." class="search-input">
            </div>
            <select name="per_page" onchange="this.form.submit()" class="select-filter" title="Baris per halaman">
                <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10 / hal</option>
                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 / hal</option>
                <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50 / hal</option>
            </select>
        </form>
    </div>
</div>

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

<div class="bank-table-card">
    <div class="table-top-bar">
        <h3 class="table-title">Daftar Rekening Bank</h3>
        <div class="bulk-action-bar">
            <button type="button" onclick="selectAllBanks()" class="btn-bulk-secondary" title="Pilih semua baris bank">
                <i data-lucide="check-square" style="width: 14px; height: 14px;"></i>
                <span>Pilih Semua</span>
            </button>
            <button type="button" onclick="clearSelectBanks()" class="btn-bulk-secondary" title="Batalkan seluruh pilihan">
                <i data-lucide="square" style="width: 14px; height: 14px;"></i>
                <span>Batal Pilih</span>
            </button>
            <button type="button" onclick="triggerBulkDelete()" class="btn-bulk-delete" id="btnBulkDelete">
                <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                <span id="bulkDeleteLabel">Hapus Terpilih</span>
            </button>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <form id="bulkForm" method="POST" action="{{ route('bank.bulk-destroy') }}">
            @csrf
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="text-align: center; width: 44px;">
                            <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAllFromCheckbox(this)" style="cursor: pointer; accent-color: #0F223D;" title="Centang Semua">
                        </th>
                        <th style="text-align: left;">Nama Bank</th>
                        <th style="text-align: left;">Nama Pemilik Rekening</th>
                        <th style="text-align: left;">Nomor Rekening</th>
                        <th style="text-align: center; width: 170px;">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banks as $bank)
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" name="ids[]" value="{{ $bank->id }}" class="bank-checkbox" style="cursor: pointer; accent-color: #0F223D;">
                            </td>
                            <td>
                                <span class="bank-name-text">{{ $bank->bank_name }}</span>
                            </td>
                            <td>
                                <span class="account-holder-text">{{ $bank->account_holder }}</span>
                            </td>
                            <td>
                                <span class="account-number-pill">{{ $bank->account_number }}</span>
                            </td>
                            <td>
                                <div class="row-actions-wrap">
                                    <a href="{{ route('bank.edit', $bank) }}" class="btn-action-edit">
                                        <i data-lucide="edit-3" style="width: 13px; height: 13px;"></i>
                                        <span>Edit</span>
                                    </a>
                                    <button type="button" class="btn-action-delete" onclick="openDeleteBankModal('single', '{{ route('bank.destroy', $bank) }}', '{{ addslashes($bank->bank_name . ' (' . $bank->account_number . ')') }}')">
                                        <i data-lucide="trash-2" style="width: 13px; height: 13px;"></i>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 36px 14px; color: #94A3B8;">
                                <i data-lucide="credit-card" style="width: 32px; height: 32px; margin: 0 auto 8px; color: #CBD5E1; display: block;"></i>
                                <span style="font-size: 0.9rem;">Belum ada rekening bank yang tersimpan.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>

    @if (method_exists($banks, 'links'))
        <div style="margin-top: 16px; padding-top: 14px; border-top: 1px solid #F1F5F9; display: flex; justify-content: center;">
            {{ $banks->links('pagination::simple-bootstrap-5') }}
        </div>
    @endif
</div>

{{-- Modal Konfirmasi Hapus Bank --}}
<div id="deleteBankBackdrop" class="custom-modal-backdrop" onclick="closeDeleteBankModal()"></div>
<div id="deleteBankModal" class="custom-modal-dialog">
    <div style="width: 44px; height: 44px; border-radius: 12px; background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; display: flex; align-items: center; justify-content: center; margin-bottom: 14px;">
        <i data-lucide="alert-triangle" style="width: 22px; height: 22px;"></i>
    </div>
    <h4 style="font-family: 'Newsreader', Georgia, serif; font-size: 1.35rem; font-weight: 600; color: #0F223D; margin: 0 0 6px;">Konfirmasi Hapus Rekening</h4>
    <p style="font-size: 0.85rem; color: #64748B; line-height: 1.5; margin: 0 0 20px;" id="deleteBankDescription">
        Apakah Anda yakin ingin menghapus rekening bank ini?
    </p>

    <form id="singleDeleteBankForm" method="POST" action="" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <div style="display: flex; justify-content: flex-end; gap: 10px; padding-top: 14px; border-top: 1px solid #F1F5F9;">
        <button type="button" onclick="closeDeleteBankModal()" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #334155; font-family: inherit; font-size: 0.825rem; font-weight: 600; cursor: pointer;">
            Batal
        </button>
        <button type="button" onclick="executeDeleteBank()" style="padding: 8px 16px; border-radius: 8px; border: none; background: #DC2626; color: #FFFFFF; font-family: inherit; font-size: 0.825rem; font-weight: 600; cursor: pointer;">
            Ya, Hapus Rekening
        </button>
    </div>
</div>

<script>
    let currentDeleteMode = 'single';
    let singleDeleteBankActionUrl = '';

    function updateBulkState() {
        const checkboxes = document.querySelectorAll('.bank-checkbox');
        const checkedCount = document.querySelectorAll('.bank-checkbox:checked').length;
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const bulkDeleteLabel = document.getElementById('bulkDeleteLabel');
        const btnBulkDelete = document.getElementById('btnBulkDelete');

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

    function selectAllBanks() {
        const checkboxes = document.querySelectorAll('.bank-checkbox');
        checkboxes.forEach(cb => { cb.checked = true; });
        const selectAll = document.getElementById('selectAllCheckbox');
        if (selectAll) {
            selectAll.checked = true;
            selectAll.indeterminate = false;
        }
        updateBulkState();
    }

    function clearSelectBanks() {
        const checkboxes = document.querySelectorAll('.bank-checkbox');
        checkboxes.forEach(cb => { cb.checked = false; });
        const selectAll = document.getElementById('selectAllCheckbox');
        if (selectAll) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        }
        updateBulkState();
    }

    function toggleSelectAllFromCheckbox(headerCb) {
        const checkboxes = document.querySelectorAll('.bank-checkbox');
        checkboxes.forEach(cb => { cb.checked = headerCb.checked; });
        updateBulkState();
    }

    function openDeleteBankModal(mode, actionUrl, bankLabel = '') {
        currentDeleteMode = mode;
        const modal = document.getElementById('deleteBankModal');
        const backdrop = document.getElementById('deleteBankBackdrop');
        const desc = document.getElementById('deleteBankDescription');

        if (mode === 'single') {
            singleDeleteBankActionUrl = actionUrl;
            desc.innerHTML = `Apakah Anda yakin ingin menghapus rekening <strong>"${bankLabel}"</strong>? Tindakan ini tidak dapat dibatalkan.`;
        } else {
            const count = document.querySelectorAll('.bank-checkbox:checked').length;
            desc.innerHTML = `Apakah Anda yakin ingin menghapus <strong>${count} rekening bank terpilih</strong> sekaligus? Tindakan ini tidak dapat dibatalkan.`;
        }

        backdrop.style.display = 'block';
        modal.style.display = 'block';
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function closeDeleteBankModal() {
        document.getElementById('deleteBankModal').style.display = 'none';
        document.getElementById('deleteBankBackdrop').style.display = 'none';
    }

    function triggerBulkDelete() {
        const selected = document.querySelectorAll('.bank-checkbox:checked');
        if (selected.length === 0) {
            alert('Silakan pilih minimal 1 rekening bank terlebih dahulu dengan mencentang kotak pada daftar.');
            return;
        }
        openDeleteBankModal('bulk');
    }

    function executeDeleteBank() {
        if (currentDeleteMode === 'single') {
            const form = document.getElementById('singleDeleteBankForm');
            form.action = singleDeleteBankActionUrl;
            form.submit();
        } else if (currentDeleteMode === 'bulk') {
            document.getElementById('bulkForm').submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        document.querySelectorAll('.bank-checkbox').forEach(cb => {
            cb.addEventListener('change', updateBulkState);
        });

        updateBulkState();
    });
</script>
@endsection
