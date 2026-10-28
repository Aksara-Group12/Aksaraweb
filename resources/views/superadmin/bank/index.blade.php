@extends('layouts.horizon')

@section('page_title', 'Manajemen Bank')

@section('content')
<div class="hz-top-row">
    <div>
        <h2 class="hz-top-title">Manajemen Bank</h2>
        <p class="hz-top-sub">Kelola rekening bank untuk bertransaksi.</p>
    </div>
    <div class="hz-top-actions" style="display:flex; align-items:center; gap:10px;">
        <a href="{{ route('bank.create') }}" style="background:rgba(34,197,94,0.2); color:#22c55e; border:1px solid rgba(34,197,94,0.5); padding:8px 16px; border-radius:6px; text-decoration:none; font-size:0.9rem; font-weight:500;">
            ➕ Tambah Bank
        </a>
        <form method="GET" action="{{ route('bank.index') }}" style="display:flex; align-items:center; gap:6px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama bank atau nomor rekening" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 10px; font-size:0.8rem; color:#1e293b; min-width:300px;">
            <select name="per_page" onchange="this.form.submit()" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 8px; font-size:0.8rem; color:#1e293b;">
                <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
            </select>
        </form>
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

@if (session('success'))
    <div style="margin: 16px 0; padding: 12px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.5); border-radius: 6px; color: #10b981;">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div style="margin: 16px 0; padding: 12px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px; color: #ef4444;">
        {{ session('error') }}
    </div>
@endif

<section class="hz-secondary-grid" style="margin-top: 24px;">
    <div class="hz-card" style="grid-column: 1 / -1;">
        <div class="hz-card-header" style="margin-bottom: 12px;">
            <div>
                <p class="hz-card-title">Daftar Bank</p>
            </div>
        </div>

        <div style="margin-top: 8px; overflow-x: auto;">
            <form id="bulkForm" method="POST" action="{{ route('bank.bulk-destroy') }}" style="display: inline;">
                @csrf
                <div style="margin-bottom: 12px; display: flex; gap: 8px;">
                    <button type="button" onclick="toggleSelectAll()" style="background: rgba(148,163,184,0.2); color:#1e293b; border:1px solid rgba(59,130,246,0.3); padding:6px 12px; border-radius:6px; cursor:pointer; font-size:0.9rem;">
                        Pilih Semua
                    </button>
                    <button type="button" onclick="clearSelect()" style="background: rgba(148,163,184,0.2); color:#1e293b; border:1px solid rgba(59,130,246,0.3); padding:6px 12px; border-radius:6px; cursor:pointer; font-size:0.9rem;">
                        Batal Pilih
                    </button>
                    <button type="submit" onclick="return confirm('Yakin hapus bank terpilih?')" style="background: rgba(239,68,68,0.2); color:#ef4444; border:1px solid rgba(239,68,68,0.5); padding:6px 12px; border-radius:6px; cursor:pointer; font-size:0.9rem;">
                        Hapus Terpilih
                    </button>
                </div>

                <table style="width: 100%; border-collapse: collapse; font-size: 1rem;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(59, 130, 246, 0.4); color:#9ca3af;">
                            <th style="text-align:center; padding:14px 10px; width:50px;">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()" style="cursor:pointer;">
                            </th>
                            <th style="text-align:left; padding:14px 10px;">Nama Bank</th>
                            <th style="text-align:left; padding:14px 10px;">Pemilik Rekening</th>
                            <th style="text-align:left; padding:14px 10px;">Nomor Rekening</th>
                            <th style="text-align:center; padding:14px 10px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banks as $bank)
                            <tr style="border-bottom: 1px solid rgba(15,23,42,0.8);">
                                <td style="text-align:center; padding:14px 10px;">
                                    <input type="checkbox" name="ids[]" value="{{ $bank->id }}" class="bank-checkbox" style="cursor:pointer;">
                                </td>
                                <td style="padding:14px 10px;">
                                    <span style="color:#1e293b; font-weight:500;">{{ $bank->bank_name }}</span>
                                </td>
                                <td style="padding:14px 10px;">
                                    <span style="color:#9ca3af;">{{ $bank->account_holder }}</span>
                                </td>
                                <td style="padding:14px 10px;">
                                    <span style="color:#9ca3af; font-family:monospace; font-size:0.9rem;">{{ $bank->account_number }}</span>
                                </td>
                                <td style="padding:14px 10px; text-align:center;">
                                    <div style="display:flex; gap:8px; justify-content:center;">
                                        <a href="{{ route('bank.edit', $bank) }}" style="background:rgba(59,130,246,0.2); color:#3b82f6; border:1px solid rgba(59,130,246,0.5); padding:6px 12px; border-radius:4px; text-decoration:none; font-size:0.85rem;">
                                            ✎ Edit
                                        </a>
                                        <form method="POST" action="{{ route('bank.destroy', $bank) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin hapus bank ini?')" style="background:rgba(239,68,68,0.2); color:#ef4444; border:1px solid rgba(239,68,68,0.5); padding:6px 12px; border-radius:4px; cursor:pointer; font-size:0.85rem;">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:40px 20px; color:#9ca3af;">
                                    📭 Belum ada data bank
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>

        <!-- Pagination -->
        <div style="margin-top: 20px; display:flex; justify-content:center; gap:6px;">
            {{ $banks->links('pagination::simple-bootstrap-5') }}
        </div>
    </div>
</section>

<script>
    function toggleSelectAll() {
        const checkboxes = document.querySelectorAll('.bank-checkbox');
        const selectAll = document.getElementById('selectAllCheckbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
    }

    function clearSelect() {
        const checkboxes = document.querySelectorAll('.bank-checkbox');
        const selectAll = document.getElementById('selectAllCheckbox');
        checkboxes.forEach(cb => cb.checked = false);
        selectAll.checked = false;
    }
</script>
@endsection
