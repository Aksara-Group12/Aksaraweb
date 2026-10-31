@extends('layouts.horizon')

@section('page_title', 'Manajemen Admin')

@section('content')
<div class="hz-top-row">
    <div>
        <h2 class="hz-top-title">Manajemen Admin</h2>
        <p class="hz-top-sub">Kelola admin toko Anda</p>
    </div>
    <div class="hz-top-actions" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
        <form method="GET" action="{{ route('admin.index') }}" style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau phone" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; min-width:220px; outline:none;">
            <select name="status" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; outline:none;">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <select name="deleted" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; outline:none;">
                <option value="">Aktif</option>
                <option value="only" {{ request('deleted') === 'only' ? 'selected' : '' }}>Dihapus</option>
            </select>
            <select name="per_page" onchange="this.form.submit()" style="border-radius:10px; border:1px solid #CBD5E1; background:#FFFFFF; padding:8px 12px; font-size:0.85rem; color:#0F223D; outline:none;">
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
            </select>
            <button type="submit" style="border-radius:10px; border:none; background:#0F223D; color:#FFFFFF; padding:8px 16px; font-size:0.85rem; font-weight:600; cursor:pointer; box-shadow:0 2px 6px rgba(15,34,61,0.15);">
                Filter
            </button>
        </form>
        <form id="admin-bulk-delete-form" onsubmit="return false;">
            <button type="button" id="admin-bulk-delete-btn" style="display:none; border-radius:10px; border:none; background:#dc2626; color:#ffffff; padding:8px 14px; font-size:0.85rem; font-weight:600; cursor:pointer;">
                Hapus terpilih
            </button>
        </form>
        @if(!session()->has('admin_id'))
            <a href="{{ route('admin.create') }}" class="hz-btn-primary" style="text-decoration:none; display:inline-flex; align-items:center; padding:9px 18px; gap:8px;">
                <span>Tambah Admin</span>
            </a>
        @endif
    </div>
</div>

@if ($message = Session::get('success'))
    <div style="margin-top: 20px; padding: 14px 16px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 10px; color: #16a34a;">
        {{ $message }}
    </div>
@endif

@if ($message = Session::get('error'))
    <div style="margin-top: 20px; padding: 14px 16px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 10px; color: #dc2626;">
        {{ $message }}
    </div>
@endif

<section class="hz-secondary-grid" style="margin-top: 24px;">
    <div class="hz-card" style="grid-column: 1 / -1;">
        <div class="hz-card-header" style="margin-bottom: 12px;">
            <div>
                <p class="hz-card-title">Daftar admin ({{ $admins->count() }} admin)</p>
                <p class="hz-card-sub">Admin aktif yang terdaftar pada akun Anda.</p>
            </div>
        </div>
        <div style="margin-top: 8px; overflow-x: auto;">
            @if ($admins->count() > 0)
                <form id="admin-table-form" method="POST" action="{{ route('admin.bulk-destroy') }}">
                    @csrf
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                        <thead>
                            <tr style="border-bottom: 1px solid rgba(59, 130, 246, 0.4); color:#9ca3af;">
                                <th style="padding:14px 10px; text-align:center; width:36px;">
                                    <input type="checkbox" id="admin-select-all" style="cursor:pointer;">
                                </th>
                                <th style="text-align:left; padding:14px 10px;">Nama</th>
                                <th style="text-align:left; padding:14px 10px;">Email</th>
                                <th style="text-align:left; padding:14px 10px;">Phone</th>
                                <th style="text-align:center; padding:14px 10px;">Status</th>
                                <th style="text-align:center; padding:14px 10px;">Email Verif</th>
                                @if(!session()->has('admin_id'))
                                    <th style="text-align:right; padding:14px 10px;">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($admins as $admin)
                                <tr style="border-bottom: 1px solid rgba(15,23,42,0.8);">
                                    <td style="padding:14px 10px; text-align:center;">
                                        <input type="checkbox" name="ids[]" value="{{ $admin->id }}" class="admin-row-checkbox" style="cursor:pointer;">
                                    </td>
                                    <td style="padding:14px 10px; color: #1e293b;">{{ $admin->name }}</td>
                                    <td style="padding:14px 10px; color: #64748b;">{{ $admin->email }}</td>
                                    <td style="padding:14px 10px; color: #64748b;">{{ $admin->phone }}</td>
                                    <td style="padding:14px 10px; text-align:center;">
                                        <span style="background:{{ $admin->status === 'active' ? 'rgba(16,185,129,0.2)' : 'rgba(107,114,128,0.2)' }}; color:{{ $admin->status === 'active' ? '#10b981' : '#9ca3af' }}; padding:4px 8px; border-radius:4px; font-size:0.8rem; font-weight:600;">
                                            {{ ucfirst($admin->status) }}
                                        </span>
                                    </td>
                                    <td style="padding:14px 10px; text-align:center;">
                                        @if($admin->hasVerifiedEmail())
                                            <span style="background:rgba(34,197,94,0.2); color:#86efac; padding:4px 8px; border-radius:4px; font-size:0.8rem; font-weight:600;">
                                                ✓ Terverifikasi
                                            </span>
                                        @else
                                            <span style="background:rgba(239,68,68,0.2); color:#fca5a5; padding:4px 8px; border-radius:4px; font-size:0.8rem; font-weight:600;">
                                                ✕ Pending
                                            </span>
                                        @endif
                                    </td>
                                    @if(!session()->has('admin_id'))
                                        <td style="padding:14px 10px; text-align:right; position: relative;">
                                            <button type="button" class="hz-more-btn" style="background: transparent; border:none; color:#9ca3af; cursor:pointer; padding:4px 8px; border-radius:999px;">
                                                &#8942;
                                            </button>
                                            <div class="hz-more-menu actions-dropdown" style="display:none; position:absolute; right:10px; top:32px; background:rgba(255,255,255,0.9); border:1px solid rgba(148,163,184,0.4); border-radius:8px; min-width:160px; z-index:10;">
                                                <a href="{{ route('admin.edit', $admin->id) }}" class="actions-dropdown-item edit">
                                                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    <span>Edit</span>
                                                </a>
                                                <button type="button" class="actions-dropdown-item delete js-row-delete" data-url="{{ route('admin.destroy.post', $admin->id) }}" style="width:100%; text-align:left;">
                                                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    <span>Hapus</span>
                                                </button>
                                                @if($admin->deleted_at)
                                                    <form method="POST" action="{{ route('admin.restore', $admin->id) }}" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="actions-dropdown-item" style="width:100%; text-align:left; color:#22c55e;">
                                                            Pulihkan
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="padding: 40px 10px; text-align: center; color: #64748b;">
                                        Belum ada admin.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>
                @if (method_exists($admins, 'links'))
                    <div style="margin-top: 12px; display:flex; justify-content:space-between; align-items:center; font-size:0.8rem; color:#9ca3af;">
                        <div>
                            Menampilkan {{ $admins->firstItem() ?? 0 }} - {{ $admins->lastItem() ?? 0 }} dari {{ $admins->total() }} admin
                        </div>
                        <div>
                            {{ $admins->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div style="padding: 40px; text-align: center; color: #64748b;">
                    <p style="margin-bottom: 12px;">Belum ada admin.</p>
                    <a href="{{ route('admin.create') }}" class="hz-btn-primary" style="display: inline-block; text-decoration: none; padding: 10px 16px; border-radius: 10px;">
                        Tambah Admin
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Modal konfirmasi bulk delete admin -->
<div id="admin-bulk-modal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.8); z-index:50; align-items:center; justify-content:center;">
    <div style="background:rgba(255,255,255,0.9); border-radius:16px; padding:20px 22px; width:100%; max-width:360px; border:1px solid rgba(59,130,246,0.3); box-shadow:0 18px 45px rgba(15,23,42,0.9);">
        <h3 style="font-size:1rem; font-weight:600; color:#1e293b; margin-bottom:4px;">Hapus admin terpilih?</h3>
        <p style="font-size:0.85rem; color:#9ca3af; margin-bottom:16px;">Tindakan ini tidak dapat dibatalkan. Pastikan admin yang dipilih sudah benar.</p>
        <div style="display:flex; justify-content:flex-end; gap:8px;">
            <button type="button" id="admin-bulk-cancel" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:transparent; color:#1e293b; padding:6px 14px; font-size:0.8rem; cursor:pointer;">Batal</button>
            <button type="button" id="admin-bulk-confirm" style="border-radius:999px; border:none; background:#b91c1c; color:#fee2e2; padding:6px 14px; font-size:0.8rem; font-weight:600; cursor:pointer;">Ya, hapus</button>
        </div>
    </div>
</div>

<script>
    // Toggle menu tiga titik pada tabel admin
    document.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.js-row-delete');
        if (deleteBtn) {
            e.preventDefault();
            const url = deleteBtn.getAttribute('data-url');
            if (!url) return;
            if (!confirm('Hapus admin ini?')) return;

                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Gagal menghapus admin.');
                }
                window.location.reload();
            }).catch(error => {
                alert(error.message || 'Gagal menghapus admin.');
            });
            return;
        }

        if (e.target.closest('.hz-more-btn')) {
            const btn = e.target.closest('.hz-more-btn');
            const menu = btn.nextElementSibling;
            const isShown = menu && menu.style.display === 'block';
            document.querySelectorAll('.hz-more-menu').forEach(m => m.style.display = 'none');
            if (menu) {
                menu.style.display = isShown ? 'none' : 'block';
            }
        } else {
            document.querySelectorAll('.hz-more-menu').forEach(m => m.style.display = 'none');
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('admin-select-all');
        const checkboxes = document.querySelectorAll('.admin-row-checkbox');
        const bulkBtn = document.getElementById('admin-bulk-delete-btn');
        const formTable = document.getElementById('admin-table-form');
        const modal = document.getElementById('admin-bulk-modal');
        const modalConfirm = document.getElementById('admin-bulk-confirm');
        const modalCancel = document.getElementById('admin-bulk-cancel');

        function updateBulkButton() {
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            if (bulkBtn) {
                bulkBtn.style.display = anyChecked ? 'inline-flex' : 'none';
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checkboxes.forEach(cb => {
                    cb.checked = selectAll.checked;
                });
                updateBulkButton();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                updateBulkButton();
            });
        });

        if (bulkBtn && modal) {
            bulkBtn.addEventListener('click', function () {
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                if (!anyChecked) return;
                modal.style.display = 'flex';
            });
        }

        if (modalCancel && modal) {
            modalCancel.addEventListener('click', function () {
                modal.style.display = 'none';
            });
        }

        if (modalConfirm && modal && formTable) {
            modalConfirm.addEventListener('click', function () {
                modal.style.display = 'none';
                formTable.submit();
            });
        }
    });
</script>
<style>
	.hz-btn-primary {
		background: #0F223D;
		color: #FFFFFF !important;
		border: none;
		border-radius: 10px;
		font-weight: 600;
		box-shadow: 0 2px 6px rgba(15,34,61,0.15);
		transition: all 0.2s ease;
	}

	.hz-btn-primary:hover {
		background: #1B355A;
		color: #FFFFFF !important;
	}

	.actions-dropdown {
		background: #ffffff;
		border-radius: 8px;
		padding: 4px 0;
		box-shadow: 0 10px 25px rgba(15,23,42,0.7);
	}

	.actions-dropdown-item {
		width: 100%;
		display: flex;
		align-items: center;
		gap: 8px;
		padding: 8px 12px;
		font-size: 0.85rem;
		text-decoration: none;
		border: none;
		background: transparent;
		color: #1e293b;
		cursor: pointer;
	}

	.actions-dropdown-item svg {
		width: 14px;
		height: 14px;
	}

	.actions-dropdown-item:hover {
		background: rgba(148,163,184,0.15);
	}

	.actions-dropdown-item.edit {
		color: #1e293b;
	}

	.actions-dropdown-item.delete {
		color: #dc2626;
	}
</style>

@endsection
