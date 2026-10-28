@extends('layouts.horizon')

@section('page_title', 'Buat Transaksi Masuk')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Buat Transaksi Masuk</h2>
			<p class="hz-top-sub">Pencatatan pembelian barang untuk menambah persediaan.</p>
		</div>
	</div>

	<!-- ERROR / SUCCESS MESSAGES -->
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

	@if ($message = Session::get('success'))
		<div style="margin-top: 20px; padding: 14px 16px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 10px; color: #16a34a;">
			{{ $message }}
		</div>
	@endif

	<form id="transactionForm" action="{{ route('transaksi.masuk.store') }}" method="POST" style="margin-top: 24px;">
		@csrf
		<input type="hidden" name="status" value="complete">
		<input type="hidden" name="items" id="itemsInput">

		<!-- HEADER SECTION -->
		<section class="hz-secondary-grid">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Informasi Transaksi</p>
				</div>

				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Tanggal Transaksi <span style="color: #3b82f6;">*</span></label>
						<input type="date" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>
				</div>
			</div>
		</section>

		<!-- SUPPLIER SECTION -->
		<section class="hz-secondary-grid" style="margin-top: 20px;">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Informasi Supplier</p>
				</div>

				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Nama Supplier <span style="color: #3b82f6;">*</span></label>
						<input type="text" name="supplier_name" placeholder="Nama supplier/pemasok..." required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Kontak <span style="color: #3b82f6;">*</span></label>
						<input type="text" name="supplier_contact" placeholder="No. HP atau email..." required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Lokasi (Kota/Kabupaten)</label>
						<input type="text" name="lokasi" placeholder="Kota atau kabupaten..." style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>
				</div>
			</div>
		</section>

		<!-- ITEMS SECTION -->
		<section class="hz-secondary-grid" style="margin-top: 20px;">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Detail Barang</p>
				</div>

				<div id="itemsContainer" style="display: flex; flex-direction: column; gap: 16px;">
					<!-- Item 1 (Template) -->
					<div class="item-card" style="border: 1px solid rgba(59,130,246,0.3); border-radius: 14px; padding: 18px; background: rgba(255,255,255,0.95);">
						<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
							<p style="font-size: 0.9rem; font-weight: 600; color: #3b82f6;">Item #1</p>
							<button type="button" class="remove-item-btn" style="display: none; padding: 6px 12px; border-radius: 8px; border: 1px solid rgba(239,68,68,0.4); background: rgba(239,68,68,0.1); color: #dc2626; font-size: 0.8rem; cursor: pointer;">Hapus</button>
						</div>

						<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
							<div style="display: flex; flex-direction: column; gap: 6px;">
								<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Produk <span style="color: #3b82f6;">*</span></label>
								<select class="product-select" name="product_id" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
									<option value="">-- Pilih Produk --</option>
									@foreach (auth()->user()->products ?? [] as $prod)
										<option value="{{ $prod->id }}" data-unit="{{ $prod->unit }}" data-harga="{{ $prod->harga_beli ?? 0 }}">
											{{ $prod->nama_produk }} ({{ $prod->unit }})
										</option>
									@endforeach
								</select>
							</div>

							<div style="display: flex; flex-direction: column; gap: 6px;">
								<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Jumlah <span style="color: #3b82f6;">*</span></label>
								<input type="number" class="qty-input" name="qty" min="0.01" step="0.01" required placeholder="0" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
							</div>

							<div style="display: flex; flex-direction: column; gap: 6px;">
								<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Harga Beli <span style="color: #3b82f6;">*</span></label>
								<input type="number" class="harga-beli-input" name="harga_beli" min="0" step="0.01" required placeholder="0" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
							</div>

							<div style="display: flex; flex-direction: column; gap: 6px;">
								<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Diskon</label>
								<input type="number" class="diskon-input" name="diskon" min="0" step="0.01" value="0" placeholder="0" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
							</div>

							<div style="display: flex; flex-direction: column; gap: 6px;">
								<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Subtotal</label>
								<div class="subtotal-display" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(255,255,255,0.95); color: #3b82f6; font-size: 0.9rem; font-weight: 600;">Rp 0</div>
							</div>
						</div>

						<div style="display: flex; flex-direction: column; gap: 6px; margin-top: 12px;">
							<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Keterangan</label>
							<input type="text" name="deskripsi" placeholder="Keterangan tambahan..." style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
						</div>
					</div>
				</div>

				<button type="button" id="addItemBtn" style="margin-top: 16px; padding: 10px 16px; border-radius: 10px; border: 1px solid rgba(34,197,94,0.4); background: rgba(34,197,94,0.1); color: #16a34a; font-size: 0.9rem; cursor: pointer;">+ Tambah Barang</button>
			</div>
		</section>

		<!-- PAYMENT SECTION -->
		<section class="hz-secondary-grid" style="margin-top: 20px;">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Pembayaran</p>
				</div>

				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Metode Pembayaran <span style="color: #3b82f6;">*</span></label>
						<select name="metode_pembayaran" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
							<option value="cash">Tunai</option>
							<option value="non-cash">Non-Tunai (Transfer/Kredit)</option>
						</select>
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Jumlah Dibayar</label>
						<input type="number" name="nominal_bayar" min="0" step="0.01" placeholder="0" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Kembalian</label>
						<input type="number" name="kembalian" min="0" step="0.01" placeholder="0" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					</div>
				</div>
			</div>
		</section>

		<!-- TOTAL SECTION -->
		<section class="hz-secondary-grid" style="margin-top: 20px;">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Ringkasan Transaksi</p>
				</div>

				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Total Harga</label>
						<div id="totalHargaDisplay" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #3b82f6; font-size: 1.1rem; font-weight: 600;">Rp 0</div>
					</div>

					<div style="display: flex; flex-direction: column; gap: 6px; grid-column: 1 / -1;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Catatan</label>
						<textarea name="catatan" rows="3" placeholder="Catatan tambahan..." style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem; resize: vertical;"></textarea>
					</div>
				</div>
			</div>
		</section>

		<!-- ACTION BUTTONS -->
		<div style="margin-top: 24px; display: flex; gap: 8px;">
			<button type="submit" class="hz-btn hz-btn-primary">
				Simpan Transaksi
			</button>
			<a href="{{ route('transaksi.masuk') }}" class="hz-btn hz-btn-secondary">
				Batal
			</a>
		</div>
	</form>
@endsection

@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function() {
		let itemCount = 1;

		// Function to calculate subtotal
		function calculateSubtotal(itemCard) {
			const qty = parseFloat(itemCard.querySelector('.qty-input').value) || 0;
			const hargaBeli = parseFloat(itemCard.querySelector('.harga-beli-input').value) || 0;
			const diskon = parseFloat(itemCard.querySelector('.diskon-input').value) || 0;
			const subtotal = (qty * hargaBeli) - diskon;
			itemCard.querySelector('.subtotal-display').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
			return subtotal;
		}

		// Function to calculate total
		function calculateTotal() {
			let total = 0;
			document.querySelectorAll('.item-card').forEach(card => {
				total += calculateSubtotal(card);
			});
			document.getElementById('totalHargaDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');
		}

		// Add event listeners for dynamic calculation
		document.getElementById('itemsContainer').addEventListener('input', function(e) {
			if (e.target.classList.contains('qty-input') || 
				e.target.classList.contains('harga-beli-input') || 
				e.target.classList.contains('diskon-input')) {
				calculateSubtotal(e.target.closest('.item-card'));
				calculateTotal();
			}
		});

		// Add item button
		document.getElementById('addItemBtn').addEventListener('click', function() {
			itemCount++;
			const template = document.querySelector('.item-card').cloneNode(true);
			template.querySelector('p').textContent = 'Item #' + itemCount;
			template.querySelector('.remove-item-btn').style.display = 'block';
			template.querySelectorAll('input, select').forEach(input => {
				input.value = input.defaultValue || '';
			});
			template.querySelector('.subtotal-display').textContent = 'Rp 0';
			document.getElementById('itemsContainer').appendChild(template);
		});

		// Remove item button
		document.getElementById('itemsContainer').addEventListener('click', function(e) {
			if (e.target.classList.contains('remove-item-btn')) {
				if (document.querySelectorAll('.item-card').length > 1) {
					e.target.closest('.item-card').remove();
					calculateTotal();
				} else {
					alert('Minimal harus ada 1 item');
				}
			}
		});

		// Auto-fill harga beli from product
		document.getElementById('itemsContainer').addEventListener('change', function(e) {
			if (e.target.classList.contains('product-select')) {
				const selectedOption = e.target.options[e.target.selectedIndex];
				const hargaBeli = selectedOption.getAttribute('data-harga') || 0;
				const card = e.target.closest('.item-card');
				card.querySelector('.harga-beli-input').value = hargaBeli;
				calculateSubtotal(card);
				calculateTotal();
			}
		});

		// Form submission
		document.getElementById('transactionForm').addEventListener('submit', function(e) {
			const items = [];
			document.querySelectorAll('.item-card').forEach((card, index) => {
				const productSelect = card.querySelector('.product-select');
				const qty = parseFloat(card.querySelector('.qty-input').value) || 0;
				const hargaBeli = parseFloat(card.querySelector('.harga-beli-input').value) || 0;
				const diskon = parseFloat(card.querySelector('.diskon-input').value) || 0;
				const deskripsi = card.querySelector('input[name="deskripsi"]').value;

				if (productSelect.value && qty > 0 && hargaBeli > 0) {
					items.push({
						product_id: productSelect.value,
						qty: qty,
						harga_beli: hargaBeli,
						diskon: diskon,
						deskripsi: deskripsi
					});
				}
			});

			if (items.length === 0) {
				e.preventDefault();
				alert('Minimal harus ada 1 item dengan produk, jumlah, dan harga beli yang valid');
				return;
			}

			document.getElementById('itemsInput').value = JSON.stringify(items);
		});
	});
</script>
@endpush
