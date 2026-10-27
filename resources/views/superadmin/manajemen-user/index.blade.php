@extends('layouts.horizon')

@section('page_title', 'Manajemen User')

@section('content')
<div class="hz-top-row">
    <div>
        <h2 class="hz-top-title">Manajemen User</h2>
        <p class="hz-top-sub">Kelola user dengan role owner.</p>
    </div>
    <div class="hz-top-actions" style="display:flex; align-items:center; gap:10px;">
        <form method="GET" action="{{ route('manajemen-user.index') }}" style="display:flex; align-items:center; gap:6px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email" style="border-radius:999px; border:1px solid rgba(148,163,184,0.5); background:#020617; padding:6px 10px; font-size:0.8rem; color:#e5e7eb; min-width:250px;">
            <select name="per_page" onchange="this.form.submit()" style="border-radius:999px; border:1px solid rgba(148,163,184,0.5); background:#020617; padding:6px 8px; font-size:0.8rem; color:#e5e7eb;">
                <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
            </select>
        </form>
    </div>
</div>

<!-- Notifikasi Pending Subscriptions -->
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
    <div style="margin: 16px 0; padding: 14px; background: linear-gradient(135deg, rgba(59,130,246,0.15) 0%, rgba(59,130,246,0.05) 100%); border: 1.5px solid rgba(59,130,246,0.5); border-radius: 12px; color: #93c5fd;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
            <div style="font-size: 1.4rem;">⚡</div>
            <div>
                <strong style="font-size: 0.95rem; color: #bfdbfe;">{{ $pendingUsers->count() }} Subscription Menunggu Approval</strong>
                <p style="margin: 4px 0 0 0; font-size: 0.85rem; color: #93c5fd;">User telah mengirim bukti pembayaran dan menunggu persetujuan Anda</p>
            </div>
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            @foreach ($pendingUsers as $user)
                <div style="background: rgba(59,130,246,0.2); padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; display: flex; align-items: center; gap: 6px;">
                    <span>👤 {{ $user->name }}</span>
                    <span style="background: #ef4444; color: white; border-radius: 999px; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.7rem;">{{ $user->subscriptions->count() }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if ($errors->any())
    <div style="margin: 16px 0; padding: 12px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.5); border-radius: 6px; color: #ef4444;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div style="margin: 16px 0; padding: 12px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.5); border-radius: 6px; color: #10b981;">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div style="margin: 16px 0; padding: 12px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.5); border-radius: 6px; color: #ef4444;">
        {{ session('error') }}
    </div>
@endif

<section class="hz-secondary-grid" style="margin-top: 24px;">
    <div class="hz-card" style="grid-column: 1 / -1;">
        <div class="hz-card-header" style="margin-bottom: 12px;">
            <div>
                <p class="hz-card-title">Daftar User</p>
            </div>
        </div>

        <div style="margin-top: 8px; overflow-x: auto;">
            <form id="bulkForm" method="POST" action="{{ route('manajemen-user.bulk-destroy') }}" style="display: inline;">
                @csrf
                <div style="margin-bottom: 12px; display: flex; gap: 8px;">
                    <button type="button" onclick="toggleSelectAll()" style="background: rgba(148,163,184,0.2); color:#e5e7eb; border:1px solid rgba(148,163,184,0.5); padding:6px 12px; border-radius:6px; cursor:pointer; font-size:0.9rem;">
                        Pilih Semua
                    </button>
                    <button type="button" onclick="clearSelect()" style="background: rgba(148,163,184,0.2); color:#e5e7eb; border:1px solid rgba(148,163,184,0.5); padding:6px 12px; border-radius:6px; cursor:pointer; font-size:0.9rem;">
                        Batal Pilih
                    </button>
                    <button type="submit" onclick="return confirm('Yakin hapus user terpilih?')" style="background: rgba(239,68,68,0.2); color:#ef4444; border:1px solid rgba(239,68,68,0.5); padding:6px 12px; border-radius:6px; cursor:pointer; font-size:0.9rem;">
                        Hapus Terpilih
                    </button>
                </div>

                <table style="width: 100%; border-collapse: collapse; font-size: 1rem; table-layout: auto;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(251, 191, 36, 0.4); color:#9ca3af;">
                            <th style="text-align:center; padding:14px 10px; width:50px;">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()" style="cursor:pointer;">
                            </th>
                            <th style="text-align:left; padding:14px 10px;">Nama</th>
                            <th style="text-align:left; padding:14px 10px;">Email</th>
                            <th style="text-align:center; padding:14px 10px;">Status</th>
                            <th style="text-align:center; padding:14px 10px; min-width: 280px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="overflow: visible;">
                        @forelse($users as $user)
                            @php
                                $hasPending = \App\Models\Subscribe::where('user_id', $user->id)
                                    ->where('approval_status', 'pending')
                                    ->exists();
                                $rowStyle = $hasPending ? 'background: rgba(59,130,246,0.08); border-left: 3px solid #3b82f6;' : '';
                            @endphp
                            <tr style="border-bottom: 1px solid rgba(15,23,42,0.8); {{ $rowStyle }}">
                                <td style="text-align:center; padding:14px 10px;">
                                    <input type="checkbox" name="ids[]" value="{{ $user->id }}" class="user-checkbox" style="cursor:pointer;">
                                </td>
                                <td style="padding:14px 10px;">
                                    <span style="color:#e5e7eb;">{{ $user->name }}</span>
                                </td>
                                <td style="padding:14px 10px;">
                                    <span style="color:#9ca3af; font-size:0.9rem;">{{ $user->email }}</span>
                                </td>
                                <td style="padding:14px 10px; text-align:center;">
                                    @php
                                        $activeSub = $activeSubscriptions[$user->id] ?? null;
                                        $statusLabel = 'Non Aktif';
                                        $statusStyle = 'background: rgba(148,163,184,0.2); color:#9ca3af; border:1px solid rgba(148,163,184,0.4);';

                                        if ($activeSub) {
                                            if ($activeSub->is_trial) {
                                                $statusLabel = 'Trial';
                                                $statusStyle = 'background: rgba(234,179,8,0.2); color:#f59e0b; border:1px solid rgba(234,179,8,0.5);';
                                            } else {
                                                $statusLabel = 'Aktif';
                                                $statusStyle = 'background: rgba(16,185,129,0.2); color:#10b981; border:1px solid rgba(16,185,129,0.5);';
                                            }
                                        }
                                    @endphp
                                    <span style="display:inline-block; padding:4px 10px; border-radius:999px; font-size:0.75rem; font-weight:600; {{ $statusStyle }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td style="padding:14px 10px; text-align:center;">
                                    <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap; position: relative; z-index: 10;">
                                        @php
                                            $pendingCount = \App\Models\Subscribe::where('user_id', $user->id)
                                                ->where('approval_status', 'pending')
                                                ->count();
                                        @endphp
                                        <button type="button" onclick="showSubscriptionModal({{ $user->id }}, '{{ $user->name }}')" style="position:relative; background:rgba(168,85,247,0.2); color:#a855f7; border:1px solid rgba(168,85,247,0.5); padding:6px 12px; border-radius:4px; text-decoration:none; font-size:0.85rem; cursor:pointer; white-space: nowrap; z-index: 20;">
                                            💳 Status
                                            @if($pendingCount > 0)
                                                <span style="position:absolute; top:-8px; right:-8px; background:#ef4444; color:white; border-radius:50%; width:22px; height:22px; display:flex; align-items:center; justify-content:center; font-size:0.7rem; font-weight:bold; border:2px solid #0f172a; z-index: 25;">{{ $pendingCount }}</span>
                                            @endif
                                        </button>
                                        <a href="{{ route('manajemen-user.edit', $user) }}" style="background:rgba(59,130,246,0.2); color:#3b82f6; border:1px solid rgba(59,130,246,0.5); padding:6px 12px; border-radius:4px; text-decoration:none; font-size:0.85rem; white-space: nowrap;">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('manajemen-user.destroy', $user) }}" style="display:inline;" onsubmit="return confirm('Yakin hapus user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background:rgba(239,68,68,0.2); color:#ef4444; border:1px solid rgba(239,68,68,0.5); padding:6px 12px; border-radius:4px; cursor:pointer; font-size:0.85rem; white-space: nowrap;">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding:18px 10px; text-align:center; color:#9ca3af;">Belum ada user dengan role owner.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>

        @if (method_exists($users, 'links'))
            <div style="margin-top: 12px; display:flex; justify-content:space-between; align-items:center; font-size:0.8rem; color:#9ca3af;">
                <div>
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} baris
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>
</section>

<script>
    function toggleSelectAll() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    }

    function clearSelect() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectAllCheckbox.checked = false;
    }

    // Update selectAll checkbox jika ada perubahan
    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            document.getElementById('selectAllCheckbox').checked = allChecked;
        });
    });

    function showSubscriptionModal(userId, userName) {
        try {
            const modal = document.getElementById('subscriptionModal');
            const backdrop = document.getElementById('subscriptionBackdrop');
            
            if (!modal || !backdrop) {
                alert('❌ ERROR: Modal element tidak ditemukan');
                console.error('Modal or backdrop not found');
                return;
            }
            
            // Fetch subscription details
            const url = `/manajemen-user/${userId}/subscription-details`;
            
            console.log('🔍 Fetching from URL:', url);
            console.log('User ID:', userId);
            
            fetch(url)
                .then(response => {
                    console.log('📡 Response status:', response.status);
                    console.log('📡 Response headers:', response.headers);
                    console.log('📡 Content-Type:', response.headers.get('content-type'));
                    
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('❌ Non-OK Response Body:', text.substring(0, 200));
                            throw new Error(`HTTP ${response.status}: ${text.substring(0, 100)}`);
                        });
                    }
                    
                    // Check if response is HTML (redirect) or JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('text/html')) {
                        console.error('❌ REDIRECT DETECTED: Server returned HTML instead of JSON!');
                        return response.text().then(html => {
                            console.error('HTML Response:', html.substring(0, 500));
                            throw new Error('🚨 REDIRECT DETECTED: Middleware is intercepting the request! Check /clear-cache endpoint.');
                        });
                    }
                    
                    return response.json();
                })
                .then(data => {
                    console.log('✅ Response data:', data);
                    
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    
                    document.getElementById('modalUserName').textContent = data.user_name;
                    document.getElementById('modalUserEmail').textContent = data.user_email;
                
                const subList = document.getElementById('subscriptionsList');
                subList.innerHTML = '';
                
                if (data.subscriptions.length === 0) {
                    subList.innerHTML = '<p style="color:#9ca3af; text-align:center; padding:20px;">Belum ada subscription</p>';
                } else {
                    data.subscriptions.forEach(sub => {
                        const subHTML = `
                            <div style="border: 1px solid rgba(148,163,184,0.3); border-radius:6px; padding:12px; margin-bottom:10px;">
                                <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:8px;">
                                    <div>
                                        <p style="color:#e5e7eb; font-weight:600; margin:0;">Paket: ${sub.paket_name}</p>
                                        <p style="color:#9ca3af; font-size:0.9rem; margin:4px 0;">Nominal: Rp${parseInt(sub.price).toLocaleString('id-ID')}</p>
                                        <p style="color:#9ca3af; font-size:0.9rem; margin:0;">Status: <span style="${sub.is_trial && sub.status === 'active' ? 'color:#f59e0b' : sub.status === 'active' ? 'color:#10b981' : 'color:#f59e0b'}">${sub.is_trial && sub.status === 'active' ? 'trial' : sub.status}</span></p>
                                    </div>
                                    <div style="text-align:right;">
                                        <span style="background:${sub.approval_status === 'approved' ? 'rgba(16,185,129,0.2)' : sub.approval_status === 'rejected' ? 'rgba(239,68,68,0.2)' : 'rgba(59,130,246,0.2)'}; color:${sub.approval_status === 'approved' ? '#10b981' : sub.approval_status === 'rejected' ? '#ef4444' : '#3b82f6'}; padding:4px 8px; border-radius:4px; font-size:0.8rem; font-weight:600;">
                                            ${sub.approval_status}
                                        </span>
                                    </div>
                                </div>
                                <p style="color:#9ca3af; font-size:0.85rem; margin:4px 0;">Jenis: ${sub.is_trial ? 'Trial' : 'Berlangganan'}</p>
                                <p style="color:#9ca3af; font-size:0.85rem; margin:4px 0;">Periode: ${sub.start_date} - ${sub.end_date}</p>
                                ${sub.bukti_transaksi_url ? `<p style="color:#9ca3af; font-size:0.85rem; margin:4px 0;"><a href="${sub.bukti_transaksi_url}" target="_blank" style="color:#3b82f6; text-decoration:underline;">📷 Lihat Bukti Pembayaran</a></p>` : ''}
                                <p style="color:#9ca3af; font-size:0.8rem; margin:4px 0;">Dibuat: ${sub.created_at}</p>
                                ${sub.approval_status === 'pending' ? `
                                    <div style="display:flex; gap:6px; margin-top:8px;">
                                        <button onclick="openApproveForm(${sub.id})" style="background:rgba(16,185,129,0.2); color:#10b981; border:1px solid rgba(16,185,129,0.5); padding:4px 8px; border-radius:4px; cursor:pointer; font-size:0.8rem; flex:1;">
                                            ✓ Approve
                                        </button>
                                        <button onclick="openRejectForm(${sub.id})" style="background:rgba(239,68,68,0.2); color:#ef4444; border:1px solid rgba(239,68,68,0.5); padding:4px 8px; border-radius:4px; cursor:pointer; font-size:0.8rem; flex:1;">
                                            ✗ Reject
                                        </button>
                                    </div>
                                ` : ''}
                            </div>
                        `;
                        subList.innerHTML += subHTML;
                    });
                }
                    modal.style.display = 'flex';
                    backdrop.style.display = 'block';
                })
            .catch(error => {
                console.error('❌ ERROR:', error);
                alert('❌ Error: ' + error.message);
                const subList = document.getElementById('subscriptionsList');
                subList.innerHTML = `<p style="color:#ef4444; text-align:center; padding:20px;">❌ Gagal mengambil data:<br><br>${error.message}</p>`;
                
                const modal = document.getElementById('subscriptionModal');
                const backdrop = document.getElementById('subscriptionBackdrop');
                modal.style.display = 'flex';
                backdrop.style.display = 'block';
            });
        } catch(err) {
            console.error('🔴 EXCEPTION:', err);
            alert('🔴 Exception: ' + err.message);
        }
    }

    function closeSubscriptionModal() {
        document.getElementById('subscriptionModal').style.display = 'none';
        document.getElementById('subscriptionBackdrop').style.display = 'none';
    }

    function openApproveForm(subscriptionId) {
        // This will open a separate approval form/modal
        const basePath = `{{ route('manajemen-user.index') }}`;
        const editUrl = basePath.replace(/\/$/, '') + '/subscription/' + subscriptionId + '/edit';
        window.location.href = editUrl;
    }

    function openRejectForm(subscriptionId) {
        const notes = prompt('Masukkan alasan penolakan:');
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
</script>
<div id="subscriptionBackdrop" onclick="closeSubscriptionModal()" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.7); z-index:999;"></div>

<div id="subscriptionModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#0f172a; border:1px solid rgba(148,163,184,0.3); border-radius:8px; width:90%; max-width:600px; max-height:80vh; overflow-y:auto; z-index:1000; padding:0;">
    <div style="padding:20px; border-bottom:1px solid rgba(148,163,184,0.3); display:flex; justify-content:space-between; align-items:center; position:sticky; top:0; background:#0f172a;">
        <div>
            <p style="margin:0; color:#e5e7eb; font-weight:600;">Subscription Details</p>
            <p style="margin:4px 0; color:#9ca3af; font-size:0.9rem;">
                <span id="modalUserName"></span> - <span id="modalUserEmail"></span>
            </p>
        </div>
        <button onclick="closeSubscriptionModal()" style="background:transparent; border:none; color:#9ca3af; font-size:1.5rem; cursor:pointer; padding:0;">✕</button>
    </div>
    <div style="padding:20px;" id="subscriptionsList">
        <p style="color:#9ca3af; text-align:center;">Loading...</p>
    </div>
</div>

@endsection
