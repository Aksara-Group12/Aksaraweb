@extends('layouts.horizon')

@section('page_title', 'POS Kasir')
@section('page_subtitle', 'Point of Sale - Layanan transaksi kasir penjualan toko.')

@section('content')
	<style>
		.pos-header-title {
			font-family: 'Newsreader', Georgia, serif;
			font-size: 2.25rem;
			font-weight: 500;
			margin: 0 0 6px 0;
			color: #0F223D;
			letter-spacing: -0.01em;
			line-height: 1.2;
		}

		.pos-header-sub {
			font-size: 0.95rem;
			color: #64748B;
			margin: 0;
		}

		/* Shift Banner */
		.shift-status-banner {
			margin-top: 20px;
			padding: 14px 20px;
			background: #FFFFFF;
			border: 1px solid #E2E8F0;
			border-radius: 14px;
			display: flex;
			justify-content: space-between;
			align-items: center;
			box-shadow: 0 2px 8px rgba(15, 34, 61, 0.03);
			flex-wrap: wrap;
			gap: 12px;
		}

		.shift-status-badge {
			display: inline-flex;
			align-items: center;
			gap: 6px;
			background: #ECFDF5;
			color: #065F46;
			border: 1px solid #A7F3D0;
			padding: 3px 10px;
			border-radius: 9999px;
			font-size: 0.75rem;
			font-weight: 600;
		}

		.shift-status-badge .dot {
			width: 6px;
			height: 6px;
			border-radius: 50%;
			background: #10B981;
		}

		.pos-card {
			background: #FFFFFF;
			border-radius: 18px;
			border: 1px solid rgba(226, 232, 240, 0.95);
			padding: 22px 24px;
			box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04);
		}

		.pos-card-title {
			font-family: 'Newsreader', Georgia, serif;
			font-size: 1.25rem;
			font-weight: 600;
			color: #0F223D;
			margin: 0 0 16px 0;
		}

		.pos-input {
			padding: 10px 14px;
			border-radius: 10px;
			border: 1px solid #CBD5E1;
			background: #FFFFFF;
			color: #0F223D;
			font-size: 0.875rem;
			outline: none;
			transition: all 0.15s ease;
			font-family: inherit;
		}

		.pos-input:focus {
			border-color: #0F223D;
			box-shadow: 0 0 0 3px rgba(15, 34, 61, 0.08);
		}

		.product-card {
			border: 1px solid #E2E8F0;
			border-radius: 14px;
			padding: 14px;
			background: #FAF8F5;
			cursor: pointer;
			transition: all 0.15s ease;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
		}

		.product-card:hover {
			background: #FFFFFF;
			border-color: rgba(197, 160, 89, 0.6);
			box-shadow: 0 0 16px rgba(197, 160, 89, 0.2);
		}

		.product-name-txt {
			font-weight: 600;
			color: #0F223D;
			font-size: 0.9rem;
			margin-bottom: 4px;
			line-height: 1.3;
		}

		.product-sku-txt {
			color: #64748B;
			font-size: 0.775rem;
			margin-bottom: 8px;
		}

		.product-price-txt {
			color: #0F223D;
			font-weight: 700;
			font-size: 0.95rem;
		}

		.product-stock-badge {
			display: inline-block;
			font-size: 0.75rem;
			font-weight: 600;
			margin-top: 6px;
			padding: 2px 8px;
			border-radius: 6px;
			width: fit-content;
		}

		.stock-available {
			background: #ECFDF5;
			color: #065F46;
		}

		.stock-empty {
			background: #FEF2F2;
			color: #991B1B;
		}

		.btn-navy {
			border-radius: 10px;
			border: none;
			background: #0F223D;
			color: #FFFFFF;
			padding: 11px 18px;
			font-family: inherit;
			font-size: 0.875rem;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.15s ease;
			text-align: center;
			box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15);
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 8px;
		}

		.btn-navy:hover {
			background: #162C50;
			box-shadow: 0 0 12px rgba(15, 34, 61, 0.25);
		}

		.btn-secondary-clean {
			border-radius: 10px;
			border: 1px solid #CBD5E1;
			background: #FFFFFF;
			color: #475569;
			padding: 10px 16px;
			font-family: inherit;
			font-size: 0.85rem;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.15s ease;
			text-align: center;
			text-decoration: none;
			display: inline-flex;
			align-items: center;
			justify-content: center;
		}

		.btn-secondary-clean:hover {
			background: #F8FAFC;
			color: #0F223D;
			border-color: #94A3B8;
		}

		@media (max-width: 1024px) {
			.pos-layout-grid {
				grid-template-columns: 1fr !important;
			}
		}
	</style>

	<!-- Shift Status Header -->
	<div class="shift-status-banner" style="margin-top: 0;">
		<div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
			<div class="shift-status-badge">
				<span class="dot"></span>
				<span>Shift Aktif</span>
			</div>
			<span style="color: #0F223D; font-weight: 600; font-size: 0.875rem;">{{ session('admin_name', auth()->user()->name) }}</span>
			<span style="color: #CBD5E1;">|</span>
			<span style="color: #64748B; font-size: 0.85rem;">Modal Awal:</span>
			<span style="color: #0F223D; font-weight: 700; font-size: 0.875rem;">Rp {{ number_format(session('modal_awal', 0), 0, ',', '.') }}</span>
		</div>
		<div>
			@if (session('active_shift_id'))
				<a href="{{ route('shift.close', session('active_shift_id')) }}" class="btn-secondary-clean" style="padding: 6px 14px; font-size: 0.8rem;">Tutup Shift</a>
			@else
				<a href="{{ route('shift.create') }}" class="btn-navy" style="padding: 6px 14px; font-size: 0.8rem;">Buka Shift</a>
			@endif
		</div>
	</div>

	@if ($errors->any())
		<div style="margin-top: 16px; padding: 14px 18px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 14px; color: #991B1B; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.05);">
			<div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="12" cy="12" r="10"></circle>
					<line x1="12" y1="8" x2="12" y2="12"></line>
					<line x1="12" y1="16" x2="12.01" y2="16"></line>
				</svg>
				<p style="font-weight: 600; margin: 0; font-size: 0.9rem;">Perhatian Pengisian Transaksi:</p>
			</div>
			<ul style="margin: 0; padding-left: 26px; font-size: 0.85rem; line-height: 1.5;">
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	@if ($message = Session::get('error'))
		<div style="margin-top: 16px; padding: 14px 18px; background: #FEF2F2; border: 1px solid #FECACA; border-radius: 14px; color: #991B1B; font-size: 0.875rem; display: flex; align-items: center; gap: 8px;">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<circle cx="12" cy="12" r="10"></circle>
				<line x1="12" y1="8" x2="12" y2="12"></line>
				<line x1="12" y1="16" x2="12.01" y2="16"></line>
			</svg>
			<span>{{ $message }}</span>
		</div>
	@endif

	@if ($message = Session::get('success'))
		<div style="margin-top: 16px; padding: 14px 18px; background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 14px; color: #065F46; font-size: 0.875rem; font-weight: 500; display: flex; align-items: center; gap: 8px;">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
				<polyline points="22 4 12 14.01 9 11.01"></polyline>
			</svg>
			<span>{{ $message }}</span>
		</div>
	@endif

	<form id="posForm" action="{{ route('transaksi.keluar.store') }}" method="POST" style="margin-top: 20px;">
		@csrf
		<input type="hidden" name="items" id="itemsInput">
		<input type="hidden" name="tanggal_transaksi" id="tanggalTransaksiInput" value="{{ date('Y-m-d') }}">

		<!-- POS LAYOUT: Panel Kiri Katalog, Panel Kanan Keranjang -->
		<div class="pos-layout-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
			
			<!-- PANEL KIRI: KATALOG & SEARCH -->
			<div class="pos-card">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
					<p class="pos-card-title" style="margin: 0;">Katalog Produk</p>
					<span style="font-size: 0.8rem; color: #64748B;">Klik produk untuk masukkan ke keranjang</span>
				</div>

				<!-- Search & Filter -->
				<div style="display: grid; grid-template-columns: 1fr auto; gap: 12px; margin-bottom: 20px;">
					<input type="text" id="searchProduct" placeholder="Cari nama produk atau SKU..." class="pos-input">
					<select id="filterCategory" class="pos-input" style="min-width: 160px;">
						<option value="">Semua Kategori</option>
					</select>
				</div>

				<!-- Product Grid -->
				<div id="productGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 14px; max-height: 520px; overflow-y: auto; padding-right: 4px;">
					@foreach ($products as $product)
						<div class="product-card" data-id="{{ $product->id }}" data-name="{{ $product->nama_produk }}" data-sku="{{ $product->code ?? '' }}" data-unit="{{ $product->unit }}" data-stock="{{ $product->stock }}" data-price="{{ $product->harga_jual ?? 0 }}" onclick="addToCart({{ $product->id }})">
							<div>
								<div class="product-name-txt">{{ $product->nama_produk }}</div>
								<div class="product-sku-txt">{{ $product->code ?? '-' }} • {{ $product->unit }}</div>
							</div>
							<div>
								<div class="product-price-txt">Rp {{ number_format($product->harga_jual ?? 0, 0, ',', '.') }}</div>
								<span class="product-stock-badge {{ $product->stock > 0 ? 'stock-available' : 'stock-empty' }}">
									Stok: {{ $product->stock }}
								</span>
							</div>
						</div>
					@endforeach
				</div>
			</div>

			<!-- PANEL KANAN: KERANJANG & PEMBAYARAN -->
			<div style="display: flex; flex-direction: column; gap: 20px;">
				
				<!-- Info Pelanggan -->
				<div class="pos-card">
					<p class="pos-card-title">Pelanggan</p>
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.8rem; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.04em;">Pilih Pelanggan</label>
						<select name="customer_name" id="customerSelect" class="pos-input">
							<option value="Pelanggan Umum">Pelanggan Umum</option>
							@foreach ($pelanggan as $pel)
								<option value="{{ $pel->nama }}">{{ $pel->nama }} ({{ $pel->kontak ?? '-' }})</option>
							@endforeach
						</select>
					</div>
				</div>

				<!-- Keranjang Belanja -->
				<div class="pos-card">
					<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
						<p class="pos-card-title" style="margin: 0;">Keranjang Belanja</p>
						<span id="cartCountBadge" style="font-size: 0.75rem; font-weight: 700; background: rgba(197, 160, 89, 0.15); color: #9E7E36; padding: 2px 8px; border-radius: 999px;">0 Item</span>
					</div>
					
					<div id="cartItems" style="max-height: 280px; overflow-y: auto; margin-bottom: 14px; padding-right: 4px;">
						<div style="text-align: center; color: #94A3B8; padding: 30px 10px; font-size: 0.85rem;">
							Keranjang belanja masih kosong
						</div>
					</div>

					<!-- Summary -->
					<div style="border-top: 1px solid #F1F5F9; padding-top: 14px;">
						<div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
							<span style="color: #64748B; font-size: 0.85rem;">Subtotal</span>
							<span id="subtotalDisplay" style="color: #0F223D; font-weight: 600; font-size: 0.875rem;">Rp 0</span>
						</div>
						<div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
							<span style="color: #64748B; font-size: 0.85rem;">Diskon</span>
							<span id="diskonDisplay" style="color: #EF4444; font-weight: 600; font-size: 0.875rem;">Rp 0</span>
						</div>
						<div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
							<span style="color: #64748B; font-size: 0.85rem;">PPN (11%)</span>
							<span id="ppnDisplay" style="color: #0F223D; font-weight: 600; font-size: 0.875rem;">Rp 0</span>
						</div>
						<div style="display: flex; justify-content: space-between; margin-top: 10px; padding-top: 12px; border-top: 1px solid #E2E8F0;">
							<span style="color: #0F223D; font-weight: 700; font-size: 1rem;">Total Pembayaran</span>
							<span id="totalDisplay" style="color: #0F223D; font-weight: 800; font-size: 1.25rem;">Rp 0</span>
						</div>
					</div>
				</div>

				<!-- Pembayaran -->
				<div class="pos-card">
					<p class="pos-card-title">Pembayaran</p>
					<div style="display: flex; flex-direction: column; gap: 14px;">
						<div style="display: flex; flex-direction: column; gap: 6px;">
							<label style="font-size: 0.8rem; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.04em;">Metode Pembayaran</label>
							<select name="metode_pembayaran" id="paymentMethod" class="pos-input">
								<option value="cash">Cash (Tunai)</option>
								<option value="non-cash">Non-Cash (Transfer / QRIS)</option>
							</select>
						</div>

						<div style="display: flex; align-items: center; gap: 8px;">
							<input type="checkbox" name="is_invoice" id="isInvoice" style="width: 16px; height: 16px; cursor: pointer;">
							<label for="isInvoice" style="font-size: 0.85rem; font-weight: 500; color: #0F223D; cursor: pointer;">Bayar Nanti / Piutang (Invoice)</label>
						</div>

						<div style="display: flex; flex-direction: column; gap: 6px;">
							<label style="font-size: 0.8rem; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.04em;">Nominal Diterima</label>
							<input type="number" name="nominal_bayar" id="nominalBayar" placeholder="0" class="pos-input">
						</div>

						<div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 14px; background: #FAF8F5; border-radius: 10px; border: 1px solid #E2E8F0;">
							<span style="color: #64748B; font-size: 0.85rem; font-weight: 500;">Kembalian:</span>
							<span id="kembalianDisplay" style="color: #059669; font-weight: 700; font-size: 1rem;">Rp 0</span>
						</div>
					</div>
				</div>

				<!-- Action Buttons -->
				<div style="display: flex; gap: 10px;">
					<button type="submit" class="btn-navy" style="flex: 1; padding: 13px;">
						<i data-lucide="check-circle-2" style="width: 18px; height: 18px;"></i>
						<span>Proses Transaksi</span>
					</button>
					<a href="{{ route('transaksi.keluar') }}" class="btn-secondary-clean" style="padding: 13px 18px;">Batal</a>
				</div>
			</div>
		</div>
	</form>
@endsection

@push('scripts')
<script>
	let cart = [];
	let ppnRate = 0.11;

	function addToCart(productId) {
		const productCard = document.querySelector(`.product-card[data-id="${productId}"]`);
		const product = {
			id: productId,
			name: productCard.dataset.name,
			sku: productCard.dataset.sku,
			unit: productCard.dataset.unit,
			stock: parseFloat(productCard.dataset.stock),
			price: parseFloat(productCard.dataset.price),
			qty: 1,
			diskon: 0
		};

		const existingItem = cart.find(item => item.id === productId);
		if (existingItem) {
			if (existingItem.qty < existingItem.stock) {
				existingItem.qty++;
			} else {
				alert('Stok tidak mencukupi');
				return;
			}
		} else {
			cart.push(product);
		}

		renderCart();
	}

	function removeFromCart(productId) {
		cart = cart.filter(item => item.id !== productId);
		renderCart();
	}

	function updateQty(productId, newQty) {
		const item = cart.find(item => item.id === productId);
		if (item) {
			if (newQty > 0 && newQty <= item.stock) {
				item.qty = newQty;
			}
		}
		renderCart();
	}

	function updateDiskon(productId, newDiskon) {
		const item = cart.find(item => item.id === productId);
		if (item) {
			item.diskon = parseFloat(newDiskon) || 0;
		}
		renderCart();
	}

	function renderCart() {
		const cartContainer = document.getElementById('cartItems');
		const badge = document.getElementById('cartCountBadge');
		if (badge) badge.textContent = cart.length + ' Item';
		
		if (cart.length === 0) {
			cartContainer.innerHTML = '<div style="text-align: center; color: #94A3B8; padding: 30px 10px; font-size: 0.85rem;">Keranjang belanja masih kosong</div>';
		} else {
			let html = '';
			cart.forEach(item => {
				const subtotal = (item.qty * item.price) - item.diskon;
				html += `
					<div style="border-bottom: 1px solid #F1F5F9; padding: 12px 0;">
						<div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
							<span style="color: #0F223D; font-weight: 600; font-size: 0.875rem;">${item.name}</span>
							<button type="button" onclick="removeFromCart(${item.id})" style="color: #EF4444; background: none; border: none; cursor: pointer; font-size: 1rem; line-height: 1; padding: 0 4px;" title="Hapus">×</button>
						</div>
						<div style="display: flex; justify-content: space-between; align-items: center; gap: 8px;">
							<input type="number" value="${item.qty}" min="1" max="${item.stock}" onchange="updateQty(${item.id}, this.value)" style="width: 65px; padding: 5px 8px; border-radius: 8px; border: 1px solid #CBD5E1; background: #FFFFFF; color: #0F223D; font-size: 0.85rem;">
							<span style="color: #64748B; font-size: 0.8rem;">× Rp ${item.price.toLocaleString('id-ID')}</span>
							<span style="color: #0F223D; font-weight: 700; font-size: 0.875rem;">Rp ${subtotal.toLocaleString('id-ID')}</span>
						</div>
						<div style="margin-top: 6px;">
							<input type="number" value="${item.diskon}" placeholder="Diskon (Rp)" onchange="updateDiskon(${item.id}, this.value)" style="width: 100%; padding: 4px 8px; border-radius: 6px; border: 1px solid #E2E8F0; background: #FFFFFF; color: #0F223D; font-size: 0.8rem;">
						</div>
					</div>
				`;
			});
			cartContainer.innerHTML = html;
		}

		calculateTotals();
	}

	function calculateTotals() {
		let subtotal = 0;
		let totalDiskon = 0;

		cart.forEach(item => {
			subtotal += item.qty * item.price;
			totalDiskon += item.diskon;
		});

		const subtotalAfterDiskon = Math.max(0, subtotal - totalDiskon);
		const ppn = subtotalAfterDiskon * ppnRate;
		const total = subtotalAfterDiskon + ppn;

		document.getElementById('subtotalDisplay').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
		document.getElementById('diskonDisplay').textContent = 'Rp ' + totalDiskon.toLocaleString('id-ID');
		document.getElementById('ppnDisplay').textContent = 'Rp ' + ppn.toLocaleString('id-ID');
		document.getElementById('totalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');

		// Calculate kembalian
		const nominalBayar = parseFloat(document.getElementById('nominalBayar').value) || 0;
		const kembalian = nominalBayar - total;
		document.getElementById('kembalianDisplay').textContent = 'Rp ' + (kembalian > 0 ? kembalian.toLocaleString('id-ID') : 0);
	}

	document.getElementById('nominalBayar').addEventListener('input', calculateTotals);

	// Search filter
	document.getElementById('searchProduct').addEventListener('input', function(e) {
		const search = e.target.value.toLowerCase();
		document.querySelectorAll('.product-card').forEach(card => {
			const name = card.dataset.name.toLowerCase();
			const sku = card.dataset.sku.toLowerCase();
			if (name.includes(search) || sku.includes(search)) {
				card.style.display = 'flex';
			} else {
				card.style.display = 'none';
			}
		});
	});

	document.getElementById('posForm').addEventListener('submit', function(e) {
		if (cart.length === 0) {
			e.preventDefault();
			alert('Keranjang belanja masih kosong! Silakan klik produk dari katalog untuk menambahkannya ke keranjang.');
			return;
		}

		const items = cart.map(item => ({
			product_id: item.id,
			qty_keluar: item.qty,
			harga_jual: item.price,
			diskon: item.diskon
		}));

		document.getElementById('itemsInput').value = JSON.stringify(items);
	});
</script>
@endpush
