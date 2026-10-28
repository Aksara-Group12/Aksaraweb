@extends('layouts.horizon')

@section('page_title', 'Transaksi Keluar')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Transaksi Keluar</h2>
			<p class="hz-top-sub">Pencatatan pengeluaran stok dan transaksi penjualan.</p>
		</div>
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px;">
			<form method="GET" action="{{ route('transaksi.keluar') }}" style="display:flex; align-items:center; gap:6px;">
				<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice / pelanggan / kota" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 10px; font-size:0.8rem; color:#1e293b; min-width:220px;">
				@if(!session()->has('admin_id'))
					<select name="admin_id" onchange="this.form.submit()" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 8px; font-size:0.8rem; color:#1e293b;">
						<option value="">Semua Penjual</option>
						<option value="owner" {{ request('admin_id') === 'owner' ? 'selected' : '' }}>Owner</option>
						@if(!empty($admins))
							@foreach($admins as $admin)
								<option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
									{{ $admin->name }}
								</option>
							@endforeach
						@endif
					</select>
				@endif
				<select name="per_page" onchange="this.form.submit()" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); padding:6px 8px; font-size:0.8rem; color:#1e293b;">
					<option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
					<option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
					<option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
				</select>
			</form>
			<a href="{{ route('transaksi.keluar.export.excel', request()->only('search', 'admin_id')) }}" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); color:#1e293b; padding:6px 10px; font-size:0.8rem; text-decoration:none;">
				Export Excel
			</a>
			<a href="{{ route('transaksi.keluar.export.pdf', request()->only('search', 'admin_id')) }}" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); color:#1e293b; padding:6px 10px; font-size:0.8rem; text-decoration:none;">
				Export PDF
			</a>
			<form id="keluar-bulk-delete-form" onsubmit="return false;">
				<button type="button" id="keluar-bulk-delete-btn" style="display:none; border-radius:999px; border:none; background:#b91c1c; color:#fee2e2; padding:6px 12px; font-size:0.8rem; font-weight:600; cursor:pointer;">
					Delete selected
				</button>
			</form>
		</div>
	</div>

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 12px;">
				<div>
					<p class="hz-card-title">Daftar transaksi keluar</p>
					<p class="hz-card-sub">Daftar transaksi keluar terbaru dari sistem.</p>
				</div>
			</div>
			<div style="margin-top: 8px; overflow-x: auto;">
				<form id="keluar-table-form" method="POST" action="{{ route('transaksi.keluar.bulk-delete.post') }}">
					@csrf
					<input type="hidden" name="ids" id="keluarBulkIds">
					<table style="width: 100%; border-collapse: collapse; font-size: 1rem;">
						<thead>
							<tr style="border-bottom: 1px solid rgba(59, 130, 246, 0.4); color:#9ca3af;">
								<th style="padding:14px 10px; text-align:center; width:36px;">
									<input type="checkbox" id="keluar-select-all" style="cursor:pointer;">
								</th>
								<th style="text-align:left; padding:14px 10px;">Tanggal</th>
								<th style="text-align:left; padding:14px 10px;">No. Invoice</th>
								@if(!session()->has('admin_id'))
									<th style="text-align:left; padding:14px 10px;">Penjual</th>
								@endif
								<th style="text-align:left; padding:14px 10px;">Pelanggan</th>
								<th style="text-align:left; padding:14px 10px;">Kota/Kabupaten</th>
								<th style="text-align:right; padding:14px 10px;">Total</th>
								<th style="text-align:right; padding:14px 10px;">Status</th>
								<th style="text-align:right; padding:14px 10px;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							@forelse($transaksi as $t)
								<tr style="border-bottom: 1px solid rgba(15,23,42,0.8);">
									<td style="padding:14px 10px; text-align:center;">
										<input type="checkbox" value="{{ $t->id }}" class="keluar-row-checkbox" style="cursor:pointer;">
									</td>
									<td style="padding:14px 10px;">
										{{ $t->tanggal_transaksi ? \Carbon\Carbon::parse($t->tanggal_transaksi)->format('d/m/Y') : $t->created_at->format('d/m/Y') }}
									</td>
									<td style="padding:14px 10px;">{{ $t->invoice_no }}</td>
									@if(!session()->has('admin_id'))
										<td style="padding:14px 10px;">{{ $t->admin?->name ?? 'Owner' }}</td>
									@endif
									<td style="padding:14px 10px;">{{ $t->customer_name }}</td>
									<td style="padding:14px 10px;">{{ $t->lokasi ?? '-' }}</td>
									<td style="padding:14px 10px; text-align:right;">Rp {{ number_format($t->total_harga ?? 0, 0, ',', '.') }}</td>
									<td style="padding:14px 10px; text-align:right;">
										@php
											$badgeClass = 'hz-pill-success';
											$badgeLabel = ucfirst($t->status ?? 'complete');
											if (($t->status ?? '') === 'pending') {
												$badgeClass = 'hz-pill-warning';
											} elseif (($t->status ?? '') === 'cancel') {
												$badgeClass = 'hz-pill-danger';
											}
											if ($t->is_void ?? false) {
												$badgeClass = 'hz-pill-danger';
												$badgeLabel = 'Void';
											}
										@endphp
										<span class="{{ $badgeClass }}" style="background:rgba(59,130,246,0.2); color:#3b82f6;">{{ $badgeLabel }}</span>
									</td>
									<td style="padding:14px 10px; text-align:right; position: relative;">
									<button type="button" class="hz-more-btn" style="background: transparent; border:none; color:#9ca3af; cursor:pointer; padding:4px 8px; border-radius:999px;">
										&#8942;
									</button>
									<div class="hz-more-menu actions-dropdown" style="display:none; position:absolute; right:10px; top:32px; background:rgba(255,255,255,0.9); border:1px solid rgba(148,163,184,0.4); border-radius:8px; min-width:160px; z-index:10;">
										<!-- <a href="{{ route('transaksi.keluar.edit', $t->id) }}" class="actions-dropdown-item edit">
											<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg">
												<path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
											</svg>
											<span>Edit</span>
										</a> -->
										<a href="{{ route('transaksi.keluar.print', $t->id) }}" class="actions-dropdown-item print">
											<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg">
												<path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
											</svg>
											<span>Cetak</span>
										</a>
										@if (!$t->is_void)
											<button type="button" class="actions-dropdown-item void void-btn" data-id="{{ $t->id }}" data-invoice="{{ $t->invoice_no }}" data-total="{{ number_format($t->total_harga, 0, ',', '.') }}" data-stok="{{ $t->items->sum('qty') }}" style="width:100%; text-align:left;">
												<svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" class="h-4 w-4" xmlns="http://www.w3.org/2000/svg">
													<path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 0A9 9 0 015.636 18.364m12.728-12.728A9 9 0 015.636 5.636"></path>
												</svg>
												<span>Void / Batal</span>
											</button>
										@endif
										<button type="button" class="actions-dropdown-item delete js-row-delete" data-url="{{ route('transaksi.keluar.destroy.post', $t->id) }}" style="width:100%; text-align:left;">
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
								<td colspan="6" style="padding:18px 10px; text-align:center; color:#9ca3af;">Belum ada transaksi keluar.</td>
							</tr>
						@endforelse
					</tbody>
					</table>
				</form>
				@if(method_exists($transaksi, 'links'))
					<div style="margin-top: 12px; display:flex; justify-content:space-between; align-items:center; font-size:0.8rem; color:#9ca3af;">
						<div>
							Menampilkan {{ $transaksi->firstItem() ?? 0 }} - {{ $transaksi->lastItem() ?? 0 }} dari {{ $transaksi->total() }} transaksi
						</div>
						<div>
							{{ $transaksi->links() }}
						</div>
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

		.actions-dropdown-item.print {
			color: #3b82f6;
		}

		.actions-dropdown-item.delete {
			color: #dc2626;
		}

		.actions-dropdown-item.void {
			color: #f87171;
		}
	</style>

	<!-- Modal konfirmasi bulk delete transaksi keluar -->
	<div id="keluar-bulk-modal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.8); z-index:50; align-items:center; justify-content:center;">
		<div style="background:rgba(255,255,255,0.9); border-radius:16px; padding:20px 22px; width:100%; max-width:360px; border:1px solid rgba(59,130,246,0.3); box-shadow:0 18px 45px rgba(15,23,42,0.9);">
			<h3 style="font-size:1rem; font-weight:600; color:#1e293b; margin-bottom:4px;">Hapus transaksi keluar terpilih?</h3>
			<p style="font-size:0.85rem; color:#9ca3af; margin-bottom:16px;">Transaksi yang sudah dihapus tidak dapat dikembalikan dan akan mempengaruhi stok.</p>
			<div style="display:flex; justify-content:flex-end; gap:8px;">
				<button type="button" id="keluar-bulk-cancel" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:transparent; color:#1e293b; padding:6px 14px; font-size:0.8rem; cursor:pointer;">Batal</button>
				<button type="button" id="keluar-bulk-confirm" style="border-radius:999px; border:none; background:#b91c1c; color:#fee2e2; padding:6px 14px; font-size:0.8rem; font-weight:600; cursor:pointer;">Ya, hapus</button>
			</div>
		</div>
	</div>

	<!-- Modal Void Transaksi -->
	<div id="void-modal" style="display:none; position:fixed; inset:0; background:rgba(30,58,138,0.5); z-index:50; align-items:center; justify-content:center;">
		<div style="background:rgba(255,255,255,0.95); border-radius:16px; padding:20px 22px; width:100%; max-width:420px; border:1px solid rgba(59,130,246,0.3); box-shadow:0 18px 45px rgba(59,130,246,0.15);">
			<h3 style="font-size:1rem; font-weight:600; color:#1e3a8a; margin-bottom:4px;">Void / Batalkan Transaksi</h3>
			<div id="void-transaction-info" style="margin-bottom:12px; padding:12px; background:rgba(59,130,246,0.08); border-radius:10px; font-size:0.85rem; color:#1e293b;"></div>
			<p style="font-size:0.85rem; color:#64748b; margin-bottom:16px;">Stok akan dikembalikan ke produk. Transaksi akan ditandai sebagai void dan tidak ikut dalam laporan.</p>
			<form id="void-form" method="POST" action="{{ route('transaksi.keluar.void', ':id') }}">
				@csrf
				<div style="margin-bottom:16px;">
					<label style="display:block; font-size:0.85rem; font-weight:500; color:#1e3a8a; margin-bottom:6px;">Alasan Void (wajib)</label>
					<textarea name="void_reason" required rows="3" placeholder="Masukkan alasan pembatalan..." style="width:100%; padding:10px 12px; border-radius:10px; border:1px solid rgba(59,130,246,0.3); background:rgba(255,255,255,0.9); color:#1e293b; font-size:0.9rem; resize:vertical;"></textarea>
				</div>
				<div style="display:flex; justify-content:flex-end; gap:8px;">
					<button type="button" id="void-cancel" style="border-radius:999px; border:1px solid rgba(59,130,246,0.3); background:transparent; color:#1e293b; padding:6px 14px; font-size:0.8rem; cursor:pointer;">Batal</button>
					<button type="submit" style="border-radius:999px; border:none; background:#dc2626; color:#fee2e2; padding:6px 14px; font-size:0.8rem; font-weight:600; cursor:pointer;">Ya, Void</button>
				</div>
			</form>
		</div>
	</div>

	<script>
		// Toggle menu tiga titik pada tabel transaksi keluar
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

			const voidBtn = e.target.closest('.js-row-void');
			if (voidBtn) {
				e.preventDefault();
				const id = voidBtn.getAttribute('data-id');
				if (!id) return;
				const voidModal = document.getElementById('void-modal');
				const voidForm = document.getElementById('void-form');
				voidForm.action = voidForm.action.replace(':id', id);
				voidModal.style.display = 'flex';
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

		// Void modal handling
		const voidModal = document.getElementById('void-modal');
		const voidCancel = document.getElementById('void-cancel');
		if (voidCancel && voidModal) {
			voidCancel.addEventListener('click', function() {
				voidModal.style.display = 'none';
			});
		}

		// Bulk delete checkbox handling + modal
		const keluarSelectAll = document.getElementById('keluar-select-all');
		const keluarCheckboxes = document.querySelectorAll('.keluar-row-checkbox');
		const keluarBulkBtn = document.getElementById('keluar-bulk-delete-btn');
		const keluarBulkFormTop = document.getElementById('keluar-bulk-delete-form');
		const keluarTableForm = document.getElementById('keluar-table-form');
		const keluarModal = document.getElementById('keluar-bulk-modal');
		const keluarModalConfirm = document.getElementById('keluar-bulk-confirm');
		const keluarModalCancel = document.getElementById('keluar-bulk-cancel');

		function updateKeluarBulkButton() {
			const anyChecked = Array.from(keluarCheckboxes).some(cb => cb.checked);
			if (keluarBulkBtn) {
				keluarBulkBtn.style.display = anyChecked ? 'inline-flex' : 'none';
			}
		}

		if (keluarSelectAll) {
			keluarSelectAll.addEventListener('change', function () {
				keluarCheckboxes.forEach(cb => {
					cb.checked = keluarSelectAll.checked;
				});
				updateKeluarBulkButton();
			});
		}

		keluarCheckboxes.forEach(cb => {
			cb.addEventListener('change', function () {
				updateKeluarBulkButton();
			});
		});

		if (keluarBulkBtn && keluarModal) {
			keluarBulkBtn.addEventListener('click', function () {
				const anyChecked = Array.from(keluarCheckboxes).some(cb => cb.checked);
				if (!anyChecked) return;
				keluarModal.style.display = 'flex';
			});
		}

		if (keluarModalCancel && keluarModal) {
			keluarModalCancel.addEventListener('click', function () {
				keluarModal.style.display = 'none';
			});
		}


		function submitKeluarBulkDelete() {
			const ids = Array.from(keluarCheckboxes)
				.filter(cb => cb.checked)
				.map(cb => cb.value);
			if (!ids.length) return;
			const idsInput = document.getElementById('keluarBulkIds');
			if (idsInput) {
				idsInput.value = JSON.stringify(ids);
			}

			const formData = new FormData();
			formData.append('_token', '{{ csrf_token() }}');
			formData.append('ids', JSON.stringify(ids));

			fetch(keluarTableForm.action, {
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

		if (keluarModalConfirm && keluarModal && keluarTableForm) {
			keluarModalConfirm.addEventListener('click', function () {
				keluarModal.style.display = 'none';
				submitKeluarBulkDelete();
			});
		}
	</script>
@endsection

</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".void-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            const id = this.getAttribute("data-id");
            const invoice = this.getAttribute("data-invoice") || "-";
            const total = this.getAttribute("data-total") || "0";
            const stok = this.getAttribute("data-stok") || "0";
            document.getElementById("void-transaction-info").innerHTML = "<strong>Nomor:</strong> " + invoice + "<br><strong>Total:</strong> Rp " + total + "<br><strong>Stok kembali:</strong> " + stok;
            document.getElementById("void-form").action = "/transaksi/keluar/" + id + "/void";
            document.getElementById("void-modal").style.display = "flex";
        });
    });
    document.getElementById("void-cancel").addEventListener("click", function() {
        document.getElementById("void-modal").style.display = "none";
    });
});
</script>
