@extends('layouts.horizon')

@section('page_title', 'Beban Operasional & Pengeluaran')
@section('page_subtitle', 'Kelola dan pantau seluruh biaya operasional toko Anda.')

@section('content')
	<div class="hz-top-row" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px; flex-wrap:wrap; gap:12px;">
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; width:100%; justify-content:space-between;">
			<form method="GET" action="{{ route('expenses.index') }}" style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
				<div style="position:relative; display:inline-flex; align-items:center;">
					<svg style="position:absolute; left:12px; width:15px; height:15px; color:#94A3B8; pointer-events:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
					<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori / keterangan..." style="padding-left:36px; min-width:220px;">
				</div>
				<select name="per_page" onchange="this.form.submit()">
					<option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
					<option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
					<option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
				</select>
			</form>
			<a href="{{ route('expenses.export.excel', request()->only('search')) }}" style="border-radius:10px; border:1px solid #A7F3D0; background:#ECFDF5; color:#065F46; padding:8px 14px; font-size:0.85rem; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:6px;">
				<svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M12 2V12M12 12L7 7M12 12L17 7M3 12V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				<span>Excel</span>
			</a>
			<a href="{{ route('expenses.export.pdf', request()->only('search')) }}" style="border-radius:10px; border:1px solid #FECACA; background:#FEF2F2; color:#991B1B; padding:8px 14px; font-size:0.85rem; font-weight:600; text-decoration:none; display:flex; align-items:center; gap:6px;">
				<svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M12 2V12M12 12L7 7M12 12L17 7M3 12V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				<span>PDF</span>
			</a>
			<form id="expenses-bulk-delete-form" onsubmit="return false;">
				<button type="button" id="expenses-bulk-delete-btn" style="display:none; border-radius:10px; border:none; background:#DC2626; color:#FFFFFF; padding:8px 14px; font-size:0.85rem; font-weight:600; cursor:pointer;">
					Hapus Terpilih
				</button>
			</form>
			<a href="{{ route('expenses.create') }}" class="hz-btn hz-btn-primary">
				<svg width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M10 4.5V15.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
					<path d="M4.5 10H15.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
				</svg>
				<span>Catat Pengeluaran</span>
			</a>
		</div>
	</div>

	@if ($message = Session::get('success'))
		<div style="margin-top: 20px; padding: 14px 18px; background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 12px; color: #065F46; font-size: 0.875rem; font-weight: 500; display:flex; align-items:center; gap:8px;">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
				<polyline points="22 4 12 14.01 9 11.01"></polyline>
			</svg>
			<span>{{ $message }}</span>
		</div>
	@endif

	@if ($message = Session::get('error'))
		<div style="margin-top: 20px; padding: 14px 18px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 12px; color: #991B1B; font-size: 0.875rem; display:flex; align-items:center; gap:8px;">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<circle cx="12" cy="12" r="10"></circle>
				<line x1="12" y1="8" x2="12" y2="12"></line>
				<line x1="12" y1="16" x2="12.01" y2="16"></line>
			</svg>
			<span>{{ $message }}</span>
		</div>
	@endif

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 12px; display:flex; justify-content:space-between; align-items:center;">
				<div>
					<p class="hz-card-title">Daftar pengeluaran</p>
					<p class="hz-card-sub">Total pengeluaran tercatat: <strong>Rp {{ number_format($summaryTotal ?? 0, 0, ',', '.') }}</strong></p>
				</div>
			</div>

			<div style="margin-top: 8px; overflow-x: auto;">
				@if ($expenses->count() > 0)
					<form id="expenses-table-form" method="POST" action="{{ route('expenses.bulk-delete.post') }}">
						@csrf
						<input type="hidden" name="ids" id="expensesBulkIds">
						<table>
							<thead>
								<tr>
									<th style="padding:14px 10px; text-align:center; width:36px;">
										<input type="checkbox" id="expenses-select-all" style="cursor:pointer;">
									</th>
									<th style="text-align:left;">Tanggal</th>
									<th style="text-align:left;">Kategori</th>
									<th style="text-align:left;">Keterangan</th>
									<th style="text-align:right;">Nominal</th>
									<th style="text-align:left;">Metode</th>
									<th style="text-align:right;">Aksi</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($expenses as $e)
									<tr>
										<td style="padding:14px 10px; text-align:center;">
											<input type="checkbox" value="{{ $e->id }}" class="expenses-row-checkbox" style="cursor:pointer;">
										</td>
										<td style="font-weight:600; color: #0F223D;">{{ $e->tanggal ? $e->tanggal->format('d/m/Y') : ($e->created_at ? $e->created_at->format('d/m/Y') : '-') }}</td>
										<td>
											<span style="background: #F1F5F9; border: 1px solid #E2E8F0; color: #475569; padding: 3px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 600;">
												{{ $e->kategori ?? '-' }}
											</span>
										</td>
										<td style="color: #475569; max-width: 320px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $e->keterangan ?? '-' }}</td>
										<td style="text-align:right; font-weight: 700; color: #0F223D;">Rp {{ number_format($e->nominal, 0, ',', '.') }}</td>
										<td>
											<span style="color: #64748B; font-size: 0.825rem;">
												{{ $e->metode_pembayaran === 'cash' ? 'Cash' : 'Non-Cash' }}
											</span>
										</td>
										<td style="text-align:right; position: relative;">
											<button type="button" class="hz-more-btn" style="background: transparent; border:1px solid #CBD5E1; color:#64748B; cursor:pointer; padding:5px 9px; border-radius:8px; font-weight:700;">
												⋮
											</button>
											<div class="hz-more-menu actions-dropdown" style="display:none; position:absolute; right:10px; top:36px; min-width:140px; z-index:20;">
												<a href="{{ route('expenses.edit', $e->id) }}" class="actions-dropdown-item edit">
													<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg">
														<path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
													</svg>
													<span>Edit</span>
												</a>
												<button type="button" class="actions-dropdown-item delete js-row-delete" data-url="{{ route('expenses.destroy.post', $e->id) }}" style="width:100%; text-align:left;">
													<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg">
														<path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
													</svg>
													<span>Hapus</span>
												</button>
											</div>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</form>
					@if (method_exists($expenses, 'links'))
						<div style="margin-top: 12px; display:flex; justify-content:space-between; align-items:center; font-size:0.8rem; color:#9ca3af;">
							<div>
								Menampilkan {{ $expenses->firstItem() ?? 0 }} - {{ $expenses->lastItem() ?? 0 }} dari {{ $expenses->total() }} pengeluaran
							</div>
							<div>
								{{ $expenses->links() }}
							</div>
						</div>
					@endif
				@else
					<div style="padding: 40px; text-align: center; color: #64748b;">
						<p style="margin-bottom: 12px;">Belum ada pengeluaran tercatat.</p>
						<a href="{{ route('expenses.create') }}" class="hz-btn-primary" style="display: inline-block; text-decoration: none; padding: 10px 16px; border-radius: 10px;">Catat Pengeluaran Pertama</a>
					</div>
				@endif
			</div>
		</div>
	</section>

	<style>
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

	<!-- Modal konfirmasi bulk delete pengeluaran -->
	<div id="expenses-bulk-modal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.8); z-index:50; align-items:center; justify-content:center;">
		<div style="background:rgba(255,255,255,0.9); border-radius:16px; padding:20px 22px; width:100%; max-width:360px; border:1px solid rgba(59,130,246,0.3); box-shadow:0 18px 45px rgba(15,23,42,0.9);">
			<h3 style="font-size:1rem; font-weight:600; color:#1e293b; margin-bottom:4px;">Hapus pengeluaran terpilih?</h3>
			<p style="font-size:0.85rem; color:#9ca3af; margin-bottom:16px;">Data pengeluaran yang dihapus tidak dapat dikembalikan.</p>
			<div style="display:flex; justify-content:flex-end; gap:8px;">
				<button type="button" id="expenses-bulk-cancel" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:transparent; color:#1e293b; padding:6px 14px; font-size:0.8rem; cursor:pointer;">Batal</button>
				<button type="button" id="expenses-bulk-confirm" style="border-radius:999px; border:none; background:#b91c1c; color:#fee2e2; padding:6px 14px; font-size:0.8rem; font-weight:600; cursor:pointer;">Ya, hapus</button>
			</div>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const selectAll = document.getElementById('expenses-select-all');
			const checkboxes = document.querySelectorAll('.expenses-row-checkbox');
			const bulkBtn = document.getElementById('expenses-bulk-delete-btn');
			const formTable = document.getElementById('expenses-table-form');
			const formTop = document.getElementById('expenses-bulk-delete-form');
			const modal = document.getElementById('expenses-bulk-modal');
			const modalConfirm = document.getElementById('expenses-bulk-confirm');
			const modalCancel = document.getElementById('expenses-bulk-cancel');

			function updateBulkButton() {
				const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
				bulkBtn.style.display = anyChecked ? 'inline-flex' : 'none';
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


			function submitExpensesBulkDelete() {
				const ids = Array.from(checkboxes)
					.filter(cb => cb.checked)
					.map(cb => cb.value);
				if (!ids.length) return;
				const idsInput = document.getElementById('expensesBulkIds');
				if (idsInput) {
					idsInput.value = JSON.stringify(ids);
				}

				const formData = new FormData();
				formData.append('_token', '{{ csrf_token() }}');
				formData.append('ids', JSON.stringify(ids));

				fetch(formTable.action, {
					method: 'POST',
					body: formData,
					headers: {
						'X-Requested-With': 'XMLHttpRequest'
					}
				}).then(response => {
					if (!response.ok) {
						throw new Error('Gagal menghapus pengeluaran.');
					}
					window.location.reload();
				}).catch(error => {
					alert(error.message || 'Gagal menghapus pengeluaran.');
				});
			}

			if (modalConfirm && modal && formTable) {
				modalConfirm.addEventListener('click', function () {
					modal.style.display = 'none';
					submitExpensesBulkDelete();
				});
			}
		});
	</script>

	<script>
		// Toggle menu tiga titik pada tabel expenses
		document.addEventListener('click', function(e) {
			const deleteBtn = e.target.closest('.js-row-delete');
			if (deleteBtn) {
				e.preventDefault();
				const url = deleteBtn.getAttribute('data-url');
				if (!url) return;
				if (!confirm('Hapus pengeluaran ini?')) return;

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
						throw new Error('Gagal menghapus pengeluaran.');
					}
					window.location.reload();
				}).catch(error => {
					alert(error.message || 'Gagal menghapus pengeluaran.');
				});
				return;
			}

			if (e.target.closest('.hz-more-btn')) {
				const btn = e.target.closest('.hz-more-btn');
				const menu = btn.nextElementSibling;
				const isShown = menu && menu.style.display === 'block';
				// Tutup semua menu lain
				document.querySelectorAll('.hz-more-menu').forEach(m => m.style.display = 'none');
				if (menu) {
					menu.style.display = isShown ? 'none' : 'block';
				}
			} else {
				// Klik di luar -> tutup semua menu
				document.querySelectorAll('.hz-more-menu').forEach(m => m.style.display = 'none');
			}
		});
	</script>
@endsection

