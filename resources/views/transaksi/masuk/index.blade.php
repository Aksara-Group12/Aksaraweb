@extends('layouts.horizon')

@section('page_title', 'Transaksi Masuk')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Transaksi Masuk</h2>
			<p class="hz-top-sub">Pencatatan pembelian barang untuk menambah persediaan. Total Transaksi: {{ $transaksi->count() }}</p>
		</div>
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px;">
			<form method="GET" action="{{ route('transaksi.masuk') }}" style="display:flex; align-items:center; gap:6px;">
				<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari referensi / supplier / kota" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 10px; font-size:0.8rem; color:#1e293b; min-width:220px;">
				<select name="per_page" onchange="this.form.submit()" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 8px; font-size:0.8rem; color:#1e293b;">
					<option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
					<option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
					<option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
				</select>
			</form>
			<a href="{{ route('transaksi.masuk.export.excel', request()->only('search')) }}" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); color:#1e293b; padding:6px 10px; font-size:0.8rem; text-decoration:none;">
				Export Excel
			</a>
			<a href="{{ route('transaksi.masuk.export.pdf', request()->only('search')) }}" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); color:#1e293b; padding:6px 10px; font-size:0.8rem; text-decoration:none;">
				Export PDF
			</a>
			<form id="masuk-bulk-delete-form" onsubmit="return false;">
				<button type="button" id="masuk-bulk-delete-btn" style="display:none; border-radius:999px; border:none; background:#b91c1c; color:#fee2e2; padding:6px 12px; font-size:0.8rem; font-weight:600; cursor:pointer;">
					Delete selected
				</button>
			</form>
			<a href="{{ route('transaksi.masuk.create') }}" class="hz-btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; padding: 10px 16px; gap: 8px;">
				<svg width="14" height="14" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M10 4.5V15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
					<path d="M4.5 10H15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
				</svg>
				<span>Buat Transaksi</span>
			</a>
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
					<p class="hz-card-title">Daftar Transaksi Masuk ({{ $transaksi->count() }} transaksi)</p>
					<p class="hz-card-sub">Riwayat pembelian barang untuk menambah persediaan.</p>
				</div>
			</div>
			<div style="margin-top: 8px; overflow-x: auto;">
				@if ($transaksi->count() > 0)
					<form id="masuk-table-form" method="POST" action="{{ route('transaksi.masuk.bulk-delete.post') }}">
						@csrf
						<input type="hidden" name="ids" id="masukBulkIds">
						<table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
						<thead>
							<tr style="border-bottom: 1px solid rgba(59, 130, 246, 0.4); color:#9ca3af;">
									<th style="padding:14px 10px; text-align:center; width:36px;">
										<input type="checkbox" id="masuk-select-all" style="cursor:pointer;">
									</th>
									<th style="text-align:left; padding:14px 10px;">Tanggal</th>
									<th style="text-align:left; padding:14px 10px;">No. Referensi</th>
									<th style="text-align:left; padding:14px 10px;">Supplier</th>
									<th style="text-align:left; padding:14px 10px;">Kota/Kabupaten</th>
									<th style="text-align:right; padding:14px 10px;">Total Harga</th>
									<th style="text-align:center; padding:14px 10px;">Status</th>
									<th style="text-align:right; padding:14px 10px;">Aksi</th>
								</tr>
						</thead>
						<tbody>
							@forelse ($transaksi as $t)
								<tr style="border-bottom: 1px solid rgba(15,23,42,0.8); cursor: pointer;" onmouseover="this.style.background='rgba(251,191,36,0.05)'" onmouseout="this.style.background='transparent'">
									<td style="padding:14px 10px; text-align:center;" onclick="event.stopPropagation();">
										<input type="checkbox" value="{{ $t->id }}" class="masuk-row-checkbox" style="cursor:pointer;">
									</td>
									<td style="padding:14px 10px; color: #1e293b;">{{ $t->created_at->format('d/m/Y') }}</td>
									<td style="padding:14px 10px; font-weight: 500; color: #3b82f6;">{{ $t->reference_no }}</td>
									<td style="padding:14px 10px; color: #cbd5e1;">{{ $t->supplier_name }}</td>
									<td style="padding:14px 10px; color: #cbd5e1;">{{ $t->lokasi ?? '-' }}</td>
									<td style="padding:14px 10px; text-align:right; font-weight: 600; color: #3b82f6;">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
									<td style="padding:14px 10px; text-align:center;">
										@if ($t->status === 'complete')
											<span style="background:rgba(34, 197, 94, 0.2); color:#86efac; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem;">Selesai</span>
										@elseif ($t->status === 'pending')
											<span style="background:rgba(251,191,36,0.2); color:#3b82f6; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem;">Pending</span>
										@elseif ($t->status === 'reopened')
											<span style="background:rgba(148,163,184,0.2); color:#cbd5e1; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem;">Reopened</span>
										@else
											<span style="background:rgba(239, 68, 68, 0.2); color:#fca5a5; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem;">Dibatalkan</span>
										@endif
									</td>
									<td style="padding:14px 10px; text-align:right; position: relative;">
										<button type="button" class="hz-more-btn" style="background: transparent; border:none; color:#9ca3af; cursor:pointer; padding:4px 8px; border-radius:999px;">
											&#8942;
										</button>
										<div class="hz-more-menu actions-dropdown" style="display:none; position:absolute; right:10px; top:32px; background:rgba(255,255,255,0.9); border:1px solid rgba(148,163,184,0.4); border-radius:8px; min-width:160px; z-index:10;">
											<a href="{{ route('transaksi.masuk.print', $t->id) }}" class="actions-dropdown-item print">
												<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg">
													<path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
												</svg>
												<span>Cetak</span>
											</a>
											<button type="button" class="actions-dropdown-item delete js-row-delete" data-url="{{ route('transaksi.masuk.destroy.post', $t->id) }}" style="width:100%; text-align:left;">
												<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg">
													<path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
												</svg>
												<span>Hapus</span>
											</button>
										</div>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="7" style="padding: 40px 10px; text-align: center; color: #64748b;">
										Belum ada transaksi masuk. <a href="{{ route('transaksi.masuk.create') }}" style="color: #3b82f6; text-decoration: underline;">Buat transaksi pertama</a>
									</td>
								</tr>
							@endforelse
						</tbody>
						</table>
					</form>
					@if (method_exists($transaksi, 'links'))
						<div style="margin-top: 12px; display:flex; justify-content:space-between; align-items:center; font-size:0.8rem; color:#9ca3af;">
							<div>
								Menampilkan {{ $transaksi->firstItem() ?? 0 }} - {{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }} transaksi
							</div>
							<div>
								{{ $transaksi->links() }}
							</div>
						</div>
					@endif
				@else
					<div style="padding: 40px; text-align: center; color: #64748b;">
						<p style="margin-bottom: 12px;">Belum ada transaksi masuk.</p>
						<a href="{{ route('transaksi.masuk.create') }}" class="hz-btn-primary" style="display: inline-block; text-decoration: none; padding: 10px 16px; border-radius: 10px;">
							Buat Transaksi Pertama
						</a>
					</div>
				@endif
			</div>
		</div>
	</section>

	<style>
		.hz-btn-primary {
			background: #3b82f6;
			color: #020617;
			border: none;
			border-radius: 10px;
			font-weight: 600;
			transition: all 0.2s;
		}

		.hz-btn-primary:hover {
			background: #2563eb;
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

		.actions-dropdown-item.print {
			color: #3b82f6;
		}

		.actions-dropdown-item.delete {
			color: #dc2626;
		}
	</style>

	<!-- Modal konfirmasi bulk delete transaksi masuk -->
	<div id="masuk-bulk-modal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.8); z-index:50; align-items:center; justify-content:center;">
		<div style="background:rgba(255,255,255,0.9); border-radius:16px; padding:20px 22px; width:100%; max-width:360px; border:1px solid rgba(59,130,246,0.3); box-shadow:0 18px 45px rgba(15,23,42,0.9);">
			<h3 style="font-size:1rem; font-weight:600; color:#1e293b; margin-bottom:4px;">Hapus transaksi masuk terpilih?</h3>
			<p style="font-size:0.85rem; color:#9ca3af; margin-bottom:16px;">Semua data dan nomor seri terkait transaksi ini akan ikut terhapus.</p>
			<div style="display:flex; justify-content:flex-end; gap:8px;">
				<button type="button" id="masuk-bulk-cancel" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:transparent; color:#1e293b; padding:6px 14px; font-size:0.8rem; cursor:pointer;">Batal</button>
				<button type="button" id="masuk-bulk-confirm" style="border-radius:999px; border:none; background:#b91c1c; color:#fee2e2; padding:6px 14px; font-size:0.8rem; font-weight:600; cursor:pointer;">Ya, hapus</button>
			</div>
		</div>
	</div>

	<script>
		// Toggle menu tiga titik pada tabel transaksi masuk
		document.addEventListener('click', function(e) {
			const deleteBtn = e.target.closest('.js-row-delete');
			if (deleteBtn) {
				e.preventDefault();
				const url = deleteBtn.getAttribute('data-url');
				if (!url) return;
				if (!confirm('Hapus transaksi ini?')) return;

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
						throw new Error('Gagal menghapus transaksi.');
					}
					window.location.reload();
				}).catch(error => {
					alert(error.message || 'Gagal menghapus transaksi.');
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

		// Bulk delete checkbox handling + modal
		const masukSelectAll = document.getElementById('masuk-select-all');
		const masukCheckboxes = document.querySelectorAll('.masuk-row-checkbox');
		const masukBulkBtn = document.getElementById('masuk-bulk-delete-btn');
		const masukBulkFormTop = document.getElementById('masuk-bulk-delete-form');
		const masukTableForm = document.getElementById('masuk-table-form');
		const masukModal = document.getElementById('masuk-bulk-modal');
		const masukModalConfirm = document.getElementById('masuk-bulk-confirm');
		const masukModalCancel = document.getElementById('masuk-bulk-cancel');

		function updateMasukBulkButton() {
			const anyChecked = Array.from(masukCheckboxes).some(cb => cb.checked);
			if (masukBulkBtn) {
				masukBulkBtn.style.display = anyChecked ? 'inline-flex' : 'none';
			}
		}

		if (masukSelectAll) {
			masukSelectAll.addEventListener('change', function () {
				masukCheckboxes.forEach(cb => {
					cb.checked = masukSelectAll.checked;
				});
				updateMasukBulkButton();
			});
		}

		masukCheckboxes.forEach(cb => {
			cb.addEventListener('change', function () {
				updateMasukBulkButton();
			});
		});

		if (masukBulkBtn && masukModal) {
			masukBulkBtn.addEventListener('click', function () {
				const anyChecked = Array.from(masukCheckboxes).some(cb => cb.checked);
				if (!anyChecked) return;
				masukModal.style.display = 'flex';
			});
		}

		if (masukModalCancel && masukModal) {
			masukModalCancel.addEventListener('click', function () {
				masukModal.style.display = 'none';
			});
		}


		function submitMasukBulkDelete() {
			const ids = Array.from(masukCheckboxes)
				.filter(cb => cb.checked)
				.map(cb => cb.value);
			if (!ids.length) return;
			const idsInput = document.getElementById('masukBulkIds');
			if (idsInput) {
				idsInput.value = JSON.stringify(ids);
			}

			const formData = new FormData();
			formData.append('_token', '{{ csrf_token() }}');
			formData.append('ids', JSON.stringify(ids));

			fetch(masukTableForm.action, {
				method: 'POST',
				body: formData,
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				}
			}).then(response => {
				if (!response.ok) {
					throw new Error('Gagal menghapus transaksi.');
				}
				window.location.reload();
			}).catch(error => {
				alert(error.message || 'Gagal menghapus transaksi.');
			});
		}

		if (masukModalConfirm && masukModal && masukTableForm) {
			masukModalConfirm.addEventListener('click', function () {
				masukModal.style.display = 'none';
				submitMasukBulkDelete();
			});
		}
	</script>
@endsection

