@extends('layouts.horizon')

@section('page_title', 'Edit Transaksi Masuk')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Edit Transaksi Masuk</h2>
			<p class="hz-top-sub">Perbarui informasi header transaksi masuk (status, supplier, pembayaran).</p>
		</div>
		<a href="{{ route('transaksi.masuk.audit-log', $transaksiMasuk->id) }}" class="hz-btn-outline" style="align-self: flex-start; display: flex; align-items: center; gap: 8px;">
			<span>📋 Riwayat Perubahan</span>
		</a>
	</div>

	@if ($errors->any())
		<div style="margin-top: 20px; padding: 14px 16px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 10px; color: #dc2626;">
			<p style="font-weight: 600; margin-bottom: 8px;">❌ Terjadi kesalahan validasi:</p>
			<ul style="margin: 0; padding-left: 20px;">
				@foreach ($errors->all() as $error)
					<li style="font-size: 0.9rem;">{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	@if ($message = Session::get('error'))
		<div style="margin-top: 20px; padding: 14px 16px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 10px; color: #dc2626;">
			{{ $message }}
		</div>
	@endif

	<div id="reopened-warning" style="display:none; margin-top: 20px; padding: 14px 16px; background: rgba(59, 130, 246, 0.12); border: 1px solid rgba(59, 130, 246, 0.35); border-radius: 10px; color: #3b82f6;">
		<strong>Perhatian:</strong> Status transaksi sedang <strong>reopened</strong>. Stok dan laporan belum dihitung sampai status dikembalikan ke <strong>complete</strong>.
	</div>

	<form id="transactionEditForm" action="{{ route('transaksi.masuk.update', $transaksiMasuk->id) }}" method="POST" style="margin-top: 24px;">
		@csrf
		@method('PUT')

		<section class="hz-secondary-grid">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Informasi Transaksi</p>
				</div>

				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Tanggal Transaksi <span style="color: #3b82f6;">*</span></label>
						<input type="date" name="tanggal_transaksi" value="{{ old('tanggal_transaksi', optional($transaksiMasuk->tanggal_transaksi)->format('Y-m-d')) }}" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Status <span style="color: #3b82f6;">*</span></label>
						@php($status = old('status', $transaksiMasuk->status))
						<select name="status" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
							<option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
							<option value="complete" {{ $status === 'complete' ? 'selected' : '' }}>Complete</option>
							<option value="reopened" {{ $status === 'reopened' ? 'selected' : '' }}>Reopened (Edit)</option>
							<option value="cancel" {{ $status === 'cancel' ? 'selected' : '' }}>Cancel</option>
						</select>
						<small style="color:#9ca3af; font-size:0.75rem;">Status reopened/cancel membuat transaksi tidak dihitung stok dan laporan sampai status complete lagi.</small>
					</div>
				</div>
			</div>
		</section>

		<!-- ITEMS SECTION (editable only when status = reopened) -->
		<section class="hz-secondary-grid" style="margin-top: 20px;">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Detail Item Masuk</p>
					<p style="font-size: 0.75rem; color: #64748b; margin-top: 6px;">Edit item hanya aktif saat status transaksi = reopened.</p>
				</div>

				<div id="itemsContainer" style="display: flex; flex-direction: column; gap: 16px;">
					@php($items = $transaksiMasuk->items ?? collect())
					@forelse ($items as $index => $item)
						@php
							$serials = is_array($item->nomor_seri) ? $item->nomor_seri : json_decode($item->nomor_seri ?? '[]', true) ?? [];
							$serialText = implode("\n", $serials);
						@endphp
						<div class="item-card" style="border: 1px solid rgba(59,130,246,0.3); border-radius: 14px; padding: 18px; background: rgba(255,255,255,0.95);">
							<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
								<p style="font-size: 0.9rem; font-weight: 600; color: #3b82f6;">Item #{{ $index + 1 }}</p>
								<button type="button" class="btn-remove-item" style="padding: 4px 8px; background: rgba(239, 68, 68, 0.2); color: #dc2626; border: none; border-radius: 6px; cursor: pointer; font-size: 0.75rem; display: none;">Hapus</button>
							</div>

							<div style="border-bottom: 1px solid rgba(251,191,36,0.2); padding-bottom: 14px; margin-bottom: 14px;">
								<p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; margin-bottom: 12px;">Informasi Dasar</p>
								<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
									<div style="display: flex; flex-direction: column; gap: 4px;">
										<label style="font-size: 0.8rem; font-weight: 500; color: #cbd5e1;">Jenis Logam <span style="color: #3b82f6;">*</span></label>
										<select class="jenis-logam item-jenis-logam" required style="padding: 8px 10px; border-radius: 8px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.85rem;">
											<option value="">-- Pilih --</option>
											<option value="emas" {{ $item->product?->jenis_logam === 'emas' ? 'selected' : '' }}>Emas</option>
											<option value="perak" {{ $item->product?->jenis_logam === 'perak' ? 'selected' : '' }}>Perak</option>
										</select>
									</div>
									<div style="display: flex; flex-direction: column; gap: 4px;">
										<label style="font-size: 0.8rem; font-weight: 500; color: #cbd5e1;">Brand <span style="color: #3b82f6;">*</span></label>
										<input type="text" class="brand item-brand" value="{{ $item->product?->brand }}" required style="padding: 8px 10px; border-radius: 8px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.85rem;">
									</div>
									<div style="display: flex; flex-direction: column; gap: 4px;">
										<label style="font-size: 0.8rem; font-weight: 500; color: #cbd5e1;">Nama Produk <span style="color: #3b82f6;">*</span></label>
										<input type="text" class="nama-produk item-nama-produk" value="{{ $item->product?->nama_produk }}" required style="padding: 8px 10px; border-radius: 8px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.85rem;">
									</div>
								</div>
								<div style="margin-top: 12px;">
									<label style="font-size: 0.8rem; font-weight: 500; color: #cbd5e1;">Deskripsi (Opsional)</label>
									<textarea class="deskripsi item-deskripsi" style="padding: 8px 10px; border-radius: 8px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.85rem; width: 100%; height: 70px; margin-top: 4px; resize: vertical;">{{ $item->deskripsi }}</textarea>
								</div>
							</div>

							<div style="border-bottom: 1px solid rgba(251,191,36,0.2); padding-bottom: 14px; margin-bottom: 14px;">
								<p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; margin-bottom: 12px;">Spesifikasi Produk</p>
								<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px;">
									<div style="display: flex; flex-direction: column; gap: 4px;">
										<label style="font-size: 0.8rem; font-weight: 500; color: #cbd5e1;">Berat (gram) <span style="color: #3b82f6;">*</span></label>
										<input type="number" class="berat item-berat" value="{{ $item->berat }}" step="0.01" required style="padding: 8px 10px; border-radius: 8px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.85rem;">
									</div>
								</div>
							</div>

							<div style="border-bottom: 1px solid rgba(251,191,36,0.2); padding-bottom: 14px; margin-bottom: 14px;">
								<p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; margin-bottom: 12px;">Identitas Produk</p>
								<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
									<div style="display: flex; flex-direction: column; gap: 4px;">
										<label style="font-size: 0.8rem; font-weight: 500; color: #cbd5e1;">Nomor Seri <span style="color: #3b82f6;">*</span></label>
										<textarea class="nomor-seri item-nomor-seri" required style="padding: 8px 10px; border-radius: 8px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.85rem; width: 100%; height: 100px; resize: vertical;">{{ $serialText }}</textarea>
										<p style="font-size: 0.7rem; color: #64748b; margin-top: 4px;">Jumlah unit mengikuti jumlah nomor seri yang diinput.</p>
									</div>
								</div>
							</div>

							<div style="border-bottom: 1px solid rgba(251,191,36,0.2); padding-bottom: 14px; margin-bottom: 14px;">
								<p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; margin-bottom: 12px;">Informasi Harga</p>
								<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
									<div style="display: flex; flex-direction: column; gap: 4px;">
										<label style="font-size: 0.8rem; font-weight: 500; color: #cbd5e1;">Harga Beli per Gram (Rp) <span style="color: #3b82f6;">*</span></label>
										<input type="text" class="harga-gram item-harga-gram" value="{{ number_format($item->harga_gram ?? 0, 0, ',', '.') }}" required style="padding: 8px 10px; border-radius: 8px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.85rem;">
									</div>
								</div>
							</div>
						</div>
					@empty
						<div class="item-card" style="border: 1px solid rgba(59,130,246,0.3); border-radius: 14px; padding: 18px; background: rgba(255,255,255,0.95);">
							<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
								<p style="font-size: 0.9rem; font-weight: 600; color: #3b82f6;">Item #1</p>
								<button type="button" class="btn-remove-item" style="padding: 4px 8px; background: rgba(239, 68, 68, 0.2); color: #dc2626; border: none; border-radius: 6px; cursor: pointer; font-size: 0.75rem; display: none;">Hapus</button>
							</div>
							<div style="border-bottom: 1px solid rgba(251,191,36,0.2); padding-bottom: 14px; margin-bottom: 14px;">
								<p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; margin-bottom: 12px;">Informasi Dasar</p>
								<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
									<div style="display: flex; flex-direction: column; gap: 4px;">
										<label style="font-size: 0.8rem; font-weight: 500; color: #cbd5e1;">Jenis Logam <span style="color: #3b82f6;">*</span></label>
										<select class="jenis-logam item-jenis-logam" required style="padding: 8px 10px; border-radius: 8px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.85rem;">
											<option value="">-- Pilih --</option>
											<option value="emas">Emas</option>
											<option value="perak">Perak</option>
										</select>
									</div>
									<div style="display: flex; flex-direction: column; gap: 4px;">
										<label style="font-size: 0.8rem; font-weight: 500; color: #cbd5e1;">Brand <span style="color: #3b82f6;">*</span></label>
										<input type="text" class="brand item-brand" required style="padding: 8px 10px; border-radius: 8px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.85rem;">
									</div>
									<div style="display: flex; flex-direction: column; gap: 4px;">
										<label style="font-size: 0.8rem; font-weight: 500; color: #cbd5e1;">Nama Produk <span style="color: #3b82f6;">*</span></label>
										<input type="text" class="nama-produk item-nama-produk" required style="padding: 8px 10px; border-radius: 8px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.85rem;">
									</div>
								</div>
								<div style="margin-top: 12px;">
									<label style="font-size: 0.8rem; font-weight: 500; color: #cbd5e1;">Deskripsi (Opsional)</label>
									<textarea class="deskripsi item-deskripsi" style="padding: 8px 10px; border-radius: 8px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.85rem; width: 100%; height: 70px; margin-top: 4px; resize: vertical;"></textarea>
								</div>
							</div>
							<div style="border-bottom: 1px solid rgba(251,191,36,0.2); padding-bottom: 14px; margin-bottom: 14px;">
								<p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; margin-bottom: 12px;">Spesifikasi Produk</p>
								<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px;">
									<div style="display: flex; flex-direction: column; gap: 4px;">
										<label style="font-size: 0.8rem; font-weight: 500; color: #cbd5e1;">Berat (gram) <span style="color: #3b82f6;">*</span></label>
										<input type="number" class="berat item-berat" step="0.01" required style="padding: 8px 10px; border-radius: 8px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.85rem;">
									</div>
								</div>
							</div>
							<div style="border-bottom: 1px solid rgba(251,191,36,0.2); padding-bottom: 14px; margin-bottom: 14px;">
								<p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; margin-bottom: 12px;">Identitas Produk</p>
								<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
									<div style="display: flex; flex-direction: column; gap: 4px;">
										<label style="font-size: 0.8rem; font-weight: 500; color: #cbd5e1;">Nomor Seri <span style="color: #3b82f6;">*</span></label>
										<textarea class="nomor-seri item-nomor-seri" required style="padding: 8px 10px; border-radius: 8px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.85rem; width: 100%; height: 100px; resize: vertical;"></textarea>
										<p style="font-size: 0.7rem; color: #64748b; margin-top: 4px;">Jumlah unit mengikuti jumlah nomor seri yang diinput.</p>
									</div>
								</div>
							</div>
							<div style="border-bottom: 1px solid rgba(251,191,36,0.2); padding-bottom: 14px; margin-bottom: 14px;">
								<p style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; margin-bottom: 12px;">Informasi Harga</p>
								<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
									<div style="display: flex; flex-direction: column; gap: 4px;">
										<label style="font-size: 0.8rem; font-weight: 500; color: #cbd5e1;">Harga Beli per Gram (Rp) <span style="color: #3b82f6;">*</span></label>
										<input type="text" class="harga-gram item-harga-gram" required style="padding: 8px 10px; border-radius: 8px; border: 1px solid rgba(59,130,246,0.3); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.85rem;">
									</div>
								</div>
							</div>
						</div>
					@endforelse
				</div>

				<button type="button" id="addItemBtn" style="margin-top: 16px; padding: 10px 16px; background: rgba(251,191,36,0.15); color: #3b82f6; border: 1px solid rgba(59,130,246,0.4); border-radius: 10px; cursor: pointer; font-size: 0.85rem; font-weight: 500; transition: all 0.2s;">
					+ Tambah Item
				</button>
			</div>
		</section>

		<section class="hz-secondary-grid" style="margin-top: 20px;">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Ringkasan Transaksi</p>
				</div>
				<div style="display: flex; flex-direction: column; gap: 12px;">
					<div style="display: flex; justify-content: space-between; padding: 14px 0; background: rgba(251,191,36,0.1); padding: 14px 12px; border-radius: 10px;">
						<span style="color: #3b82f6; font-weight: 600; font-size: 1rem;">Total Transaksi :</span>
						<span id="totalAmount" style="color: #3b82f6; font-weight: 700; font-size: 1.1rem;">Rp 0</span>
					</div>
				</div>
			</div>
		</section>

		<section class="hz-secondary-grid" style="margin-top: 20px;">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Informasi Supplier</p>
				</div>

				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Nama Supplier <span style="color: #3b82f6;">*</span></label>
						<input type="text" name="supplier_name" value="{{ old('supplier_name', $transaksiMasuk->supplier_name) }}" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Kontak <span style="color: #3b82f6;">*</span></label>
						<input type="text" name="supplier_contact" value="{{ old('supplier_contact', $transaksiMasuk->supplier_contact) }}" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Lokasi (Kota/Kabupaten)</label>
						<input type="text" name="lokasi" value="{{ old('lokasi', $transaksiMasuk->lokasi ?? '') }}" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">PO / Invoice Supplier</label>
						<input type="text" name="po_invoice" value="{{ old('po_invoice', $transaksiMasuk->po_invoice) }}" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>
				</div>
			</div>
		</section>

		<section class="hz-secondary-grid" style="margin-top: 20px;">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Pembayaran & Catatan</p>
				</div>

				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Metode Pembayaran <span style="color: #3b82f6;">*</span></label>
						@php($metode = old('metode_pembayaran', $transaksiMasuk->metode_pembayaran))
						<select name="metode_pembayaran" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
							<option value="">-- Pilih --</option>
							<option value="cash" {{ $metode === 'cash' ? 'selected' : '' }}>Cash</option>
							<option value="non-cash" {{ $metode === 'non-cash' ? 'selected' : '' }}>Non-Cash</option>
						</select>
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Nominal Bayar (Rp)</label>
						<input type="number" name="nominal_bayar" value="{{ old('nominal_bayar', $transaksiMasuk->nominal_bayar ?? 0) }}" step="0.01" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>
				</div>

				<div style="margin-top: 16px; display: flex; flex-direction: column; gap: 6px;">
					<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Catatan</label>
					<textarea name="catatan" style="padding: 12px 14px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem; height: 100px; resize: vertical;">{{ old('catatan', $transaksiMasuk->catatan) }}</textarea>
				</div>
			</div>
		</section>

		<section class="hz-secondary-grid" style="margin-top: 24px; margin-bottom: 40px;">
			<div style="grid-column: 1 / -1; display: flex; gap: 12px; justify-content: flex-end;">
				<button type="button" class="hz-btn-outline" onclick="window.history.back()">
					<span>Batal</span>
				</button>
				
				@php($isReopened = old('status', $transaksiMasuk->status) === 'reopened')
				
				@if($isReopened)
					<!-- Button untuk save item changes dan set ke Complete -->
					<button type="button" id="setCompleteBtn" class="hz-btn-primary" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
						<span>✓ Set ke Complete</span>
					</button>
				@else
					<!-- Button normal save perubahan -->
					<button type="submit" class="hz-btn-primary">
						<span>Simpan Perubahan</span>
					</button>
				@endif
			</div>
		</section>
		<input type="hidden" name="items" id="itemsField">
	</form>

	<style>
		.item-card {
			transition: all 0.2s;
		}

		.item-card:hover {
			border-color: rgba(59, 130, 246, 0.5);
			background: rgba(15, 23, 42, 0.9);
		}

		#addItemBtn:hover {
			background: rgba(59, 130, 246, 0.25);
			border-color: #3b82f6;
		}

		.btn-remove-item:hover {
			background: rgba(239, 68, 68, 0.3);
			color: #dc2626;
		}
	</style>

	<script>
		function formatCurrency(num) {
			return Math.floor(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		}

		function parseCurrencyInput(inputEl) {
			const raw = (inputEl.value || '').replace(/[^\d]/g, '');
			return raw ? parseInt(raw, 10) : 0;
		}

		function updateTotals() {
			let total = 0;
			document.querySelectorAll('.item-card').forEach((itemCard) => {
				const hargaBeli = parseCurrencyInput(itemCard.querySelector('.harga-gram'));
				const nomorSeriText = itemCard.querySelector('.nomor-seri').value;
				const nomorSeriArray = nomorSeriText.split('\n').map(s => s.trim()).filter(s => s);
				const qty = nomorSeriArray.length;
				total += hargaBeli * qty;
			});
			const totalEl = document.getElementById('totalAmount');
			if (totalEl) {
				totalEl.textContent = 'Rp ' + formatCurrency(total);
			}
		}

		function updateRemoveButtons() {
			const items = document.querySelectorAll('.item-card');
			items.forEach((item) => {
				const btn = item.querySelector('.btn-remove-item');
				if (btn) {
					btn.style.display = items.length > 1 ? 'block' : 'none';
				}
			});
		}

		function setItemsEditable(editable) {
			document.querySelectorAll('#itemsContainer input, #itemsContainer textarea, #itemsContainer select').forEach((el) => {
				el.disabled = !editable;
			});
			const addBtn = document.getElementById('addItemBtn');
			if (addBtn) {
				addBtn.style.display = editable ? 'inline-flex' : 'none';
			}
			const itemsField = document.getElementById('itemsField');
			if (itemsField) {
				itemsField.disabled = !editable;
				if (!editable) {
					itemsField.value = '';
				}
			}
			updateRemoveButtons();
		}

		document.addEventListener('input', function (e) {
			if (e.target.classList.contains('harga-gram')) {
				let raw = e.target.value.replace(/[^\d]/g, '');
				e.target.value = raw ? formatCurrency(raw) : '';
			}

			if (e.target.classList.contains('berat') ||
				e.target.classList.contains('harga-gram') ||
				e.target.classList.contains('nomor-seri')) {
				updateTotals();
			}
		});

		const addBtn = document.getElementById('addItemBtn');
		if (addBtn) {
			addBtn.addEventListener('click', function () {
				const container = document.getElementById('itemsContainer');
				const itemCount = container.querySelectorAll('.item-card').length + 1;
				const template = container.querySelector('.item-card').cloneNode(true);

				const itemTitle = template.querySelector('p:first-child');
				itemTitle.textContent = `Item #${itemCount}`;

				const removeBtn = template.querySelector('.btn-remove-item');
				removeBtn.style.display = 'block';
				removeBtn.addEventListener('click', function () {
					template.remove();
					updateRemoveButtons();
					updateTotals();
				});

				template.querySelectorAll('input[type="text"], input[type="number"], textarea, select').forEach((input) => {
					input.value = '';
				});

				container.appendChild(template);
				updateRemoveButtons();
				updateTotals();
			});
		}

		const statusSelect = document.querySelector('select[name="status"]');
		function syncEditMode() {
			const editable = statusSelect && statusSelect.value === 'reopened';
			setItemsEditable(editable);
			const warning = document.getElementById('reopened-warning');
			if (warning) {
				warning.style.display = editable ? 'block' : 'none';
			}
		}
		if (statusSelect) {
			statusSelect.addEventListener('change', syncEditMode);
		}

		const urlParams = new URLSearchParams(window.location.search);
		if (statusSelect && urlParams.get('reopen') === '1') {
			statusSelect.value = 'reopened';
		}

		const form = document.getElementById('transactionEditForm');
		if (form) {
			form.addEventListener('submit', function (e) {
				const editable = statusSelect && statusSelect.value === 'reopened';
				const itemsField = document.getElementById('itemsField');
				if (!editable) {
					if (itemsField) {
						itemsField.value = '';
					}
					return;
				}

				const itemsData = [];
				let hasError = false;
				let errorMessage = '';
				const itemCards = document.querySelectorAll('.item-card');

				for (let i = 0; i < itemCards.length; i++) {
					const itemCard = itemCards[i];
					const itemNum = i + 1;
					const nomorSeriText = itemCard.querySelector('.nomor-seri').value;
					const nomorSeriArray = nomorSeriText.split('\n').map(s => s.trim()).filter(s => s);

					if (!itemCard.querySelector('.item-jenis-logam').value) {
						errorMessage = `Item #${itemNum}: Jenis Logam harus dipilih`;
						hasError = true;
						break;
					}
					if (!itemCard.querySelector('.item-brand').value) {
						errorMessage = `Item #${itemNum}: Brand harus diisi`;
						hasError = true;
						break;
					}
					if (!itemCard.querySelector('.item-nama-produk').value) {
						errorMessage = `Item #${itemNum}: Nama Produk harus diisi`;
						hasError = true;
						break;
					}
					if (!itemCard.querySelector('.item-berat').value || parseFloat(itemCard.querySelector('.item-berat').value) <= 0) {
						errorMessage = `Item #${itemNum}: Berat harus diisi dan lebih dari 0`;
						hasError = true;
						break;
					}
					if (!itemCard.querySelector('.item-harga-gram').value || parseCurrencyInput(itemCard.querySelector('.item-harga-gram')) <= 0) {
						errorMessage = `Item #${itemNum}: Harga Beli harus diisi dan lebih dari 0`;
						hasError = true;
						break;
					}
					if (nomorSeriArray.length === 0) {
						errorMessage = `Item #${itemNum}: Nomor Seri harus diisi (minimal 1)`;
						hasError = true;
						break;
					}

					itemsData.push({
						jenis_logam: itemCard.querySelector('.item-jenis-logam').value,
						brand: itemCard.querySelector('.item-brand').value,
						nama_produk: itemCard.querySelector('.item-nama-produk').value,
						berat: parseFloat(itemCard.querySelector('.item-berat').value) || 0,
						harga_gram: parseCurrencyInput(itemCard.querySelector('.item-harga-gram')),
						nomor_seri: nomorSeriArray,
						deskripsi: itemCard.querySelector('.item-deskripsi').value
					});
				}

				if (hasError) {
					e.preventDefault();
					alert(errorMessage);
					return;
				}

				if (itemsField) {
					itemsField.value = JSON.stringify(itemsData);
				}
			});
		}

		// Handle "Set ke Complete" button
		const setCompleteBtn = document.getElementById('setCompleteBtn');
		if (setCompleteBtn) {
			setCompleteBtn.addEventListener('click', function (e) {
				e.preventDefault();
				
				// Show confirmation
				if (!confirm('Anda yakin ingin menyelesaikan edit dan set status ke COMPLETE?\n\nSetelah ini, item tidak bisa diubah lagi.')) {
					return;
				}

				// Set status to complete
				if (statusSelect) {
					statusSelect.value = 'complete';
				}

				// Submit form
				form.submit();
			});
		}

		updateRemoveButtons();
		updateTotals();
		syncEditMode();
	</script>
@endsection
