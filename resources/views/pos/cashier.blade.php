@extends('layouts.horizon')

@section('page_title', 'POS Kasir')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">POS Kasir</h2>
			<p class="hz-top-sub">Point of Sale - Penjualan dengan layout kasir.</p>
		</div>
	</div>

	<!-- Shift Status Header -->
	<div style="margin-top: 16px; padding: 12px 16px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 10px; display: flex; justify-content: space-between; align-items: center;">
		<div style="display: flex; align-items: center; gap: 8px;">
			<span style="color: #16a34a; font-weight: 600; font-size: 0.9rem;">Shift Aktif:</span>
			<span style="color: #1e293b; font-size: 0.9rem;">{{ session('admin_name', auth()->user()->name) }}</span>
			<span style="color: #64748b; font-size: 0.85rem;">|</span>
			<span style="color: #16a34a; font-weight: 600; font-size: 0.9rem;">Modal Awal:</span>
			<span style="color: #1e293b; font-size: 0.9rem;">Rp {{ number_format(session('modal_awal', 0), 0, ',', '.') }}</span>
		</div>
		@if (session('active_shift_id'))
			<a href="{{ route('shift.close', session('active_shift_id')) }}" class="hz-btn hz-btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;">Tutup Shift</a>
		@else
			<a href="{{ route('shift.create') }}" class="hz-btn hz-btn-primary" style="padding: 6px 12px; font-size: 0.8rem;">Buka Shift</a>
		@endif
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

	@if ($message = Session::get('success'))
		<div style="margin-top: 20px; padding: 14px 16px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 10px; color: #16a34a;">
			{{ $message }}
		</div>
	@endif

	<form id="posForm" action="{{ route('transaksi.keluar.store') }}" method="POST" style="margin-top: 24px;">
		@csrf
		<input type="hidden" name="items" id="itemsInput">

		<!-- POS LAYOUT: Panel Kiri Katalog, Panel Kanan Keranjang -->
		<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
			
			<!-- PANEL KIRI: KATALOG & SEARCH -->
			<div class="hz-card">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Katalog Produk</p>
				</div>

				<!-- Search & Filter -->
				<div style="display: grid; grid-template-columns: 1fr auto; gap: 12px; margin-bottom: 16px;">
					<input type="text" id="searchProduct" placeholder="Cari nama produk atau SKU..." style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
					<select id="filterCategory" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
						<option value="">Semua Kategori</option>
					</select>
				</div>

				<!-- Product Grid -->
				<div id="productGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; max-height: 500px; overflow-y: auto;">
					@foreach ($products as $product)
						<div class="product-card" data-id="{{ $product->id }}" data-name="{{ $product->nama_produk }}" data-sku="{{ $product->code ?? '' }}" data-unit="{{ $product->unit }}" data-stock="{{ $product->stock }}" data-price="{{ $product->harga_jual ?? 0 }}" style="border: 1px solid rgba(59,130,246,0.3); border-radius: 12px; padding: 12px; background: rgba(255,255,255,0.95); cursor: pointer; transition: all 0.2s;" onclick="addToCart({{ $product->id }})">
							<div style="font-weight: 600; color: #1e293b; font-size: 0.9rem; margin-bottom: 4px;">{{ $product->nama_produk }}</div>
							<div style="color: #64748b; font-size: 0.8rem; margin-bottom: 4px;">{{ $product->code ?? '-' }} | {{ $product->unit }}</div>
							<div style="color: #3b82f6; font-weight: 600; font-size: 0.95rem;">Rp {{ number_format($product->harga_jual ?? 0, 0, ',', '.') }}</div>
							<div style="color: {{ $product->stock > 0 ? '#86efac' : '#fca5a5' }}; font-size: 0.8rem; margin-top: 4px;">Stok: {{ $product->stock }}</div>
						</div>
					@endforeach
				</div>
			</div>

			<!-- PANEL KANAN: KERANJANG & PEMBAYARAN -->
			<div style="display: flex; flex-direction: column; gap: 16px;">
				
				<!-- Info Pelanggan -->
				<div class="hz-card">
					<div class="hz-card-header" style="margin-bottom: 12px;">
						<p class="hz-card-title">Pelanggan</p>
					</div>
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Pilih Pelanggan</label>
						<select name="customer_name" id="customerSelect" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
							<option value="">Pelanggan Umum</option>
							@foreach ($pelanggan as $pel)
								<option value="{{ $pel->nama }}">{{ $pel->nama }} ({{ $pel->kontak ?? '-' }})</option>
							@endforeach
						</select>
					</div>
				</div>

				<!-- Keranjang Belanja -->
				<div class="hz-card">
					<div class="hz-card-header" style="margin-bottom: 12px;">
						<p class="hz-card-title">Keranjang Belanja</p>
					</div>
					
					<div id="cartItems" style="max-height: 300px; overflow-y: auto; margin-bottom: 12px;">
						<div style="text-align: center; color: #64748b; padding: 20px;">Keranjang kosong</div>
					</div>

					<!-- Summary -->
					<div style="border-top: 1px solid rgba(59,130,246,0.3); padding-top: 12px;">
						<div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
							<span style="color: #64748b; font-size: 0.9rem;">Subtotal</span>
							<span id="subtotalDisplay" style="color: #1e293b; font-weight: 600;">Rp 0</span>
						</div>
						<div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
							<span style="color: #64748b; font-size: 0.9rem;">Diskon</span>
							<span id="diskonDisplay" style="color: #1e293b; font-weight: 600;">Rp 0</span>
						</div>
						<div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
							<span style="color: #64748b; font-size: 0.9rem;">PPN (11%)</span>
							<span id="ppnDisplay" style="color: #1e293b; font-weight: 600;">Rp 0</span>
						</div>
						<div style="display: flex; justify-content: space-between; margin-top: 12px; padding-top: 12px; border-top: 1px solid rgba(59,130,246,0.3);">
							<span style="color: #3b82f6; font-weight: 600; font-size: 1rem;">Total</span>
							<span id="totalDisplay" style="color: #3b82f6; font-weight: 700; font-size: 1.2rem;">Rp 0</span>
						</div>
					</div>
				</div>

				<!-- Pembayaran -->
				<div class="hz-card">
					<div class="hz-card-header" style="margin-bottom: 12px;">
						<p class="hz-card-title">Pembayaran</p>
					</div>
					<div style="display: flex; flex-direction: column; gap: 12px;">
						<div style="display: flex; flex-direction: column; gap: 6px;">
							<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Metode Pembayaran</label>
							<select name="metode_pembayaran" id="paymentMethod" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
								<option value="cash">Cash</option>
								<option value="non-cash">Non-Cash (Transfer/QRIS)</option>
							</select>
						</div>
						<div style="display: flex; align-items: center; gap: 8px; padding: 8px 0;">
							<input type="checkbox" name="is_invoice" id="isInvoice" style="width: 16px; height: 16px; cursor: pointer;">
							<label for="isInvoice" style="font-size: 0.85rem; font-weight: 500; color: #1e293b; cursor: pointer;">Bayar Nanti / Invoice</label>
						</div>
						<div style="display: flex; flex-direction: column; gap: 6px;">
							<label style="font-size: 0.85rem; font-weight: 500; color: #1e293b;">Jumlah Bayar</label>
							<input type="number" name="nominal_bayar" id="nominalBayar" placeholder="0" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
						</div>
						<div style="display: flex; justify-content: space-between;">
							<span style="color: #64748b; font-size: 0.9rem;">Kembalian</span>
							<span id="kembalianDisplay" style="color: #16a34a; font-weight: 600;">Rp 0</span>
						</div>
					</div>
				</div>

				<!-- Action Buttons -->
				<div style="display: flex; gap: 8px;">
					<button type="submit" class="hz-btn hz-btn-primary" style="flex: 1;">Proses Transaksi</button>
					<a href="{{ route('transaksi.keluar') }}" class="hz-btn hz-btn-secondary">Batal</a>
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
		
		if (cart.length === 0) {
			cartContainer.innerHTML = '<div style="text-align: center; color: #64748b; padding: 20px;">Keranjang kosong</div>';
		} else {
			let html = '';
			cart.forEach(item => {
				const subtotal = (item.qty * item.price) - item.diskon;
				html += `
					<div style="border-bottom: 1px solid rgba(59,130,246,0.2); padding: 10px 0;">
						<div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
							<span style="color: #1e293b; font-weight: 500; font-size: 0.9rem;">${item.name}</span>
							<button type="button" onclick="removeFromCart(${item.id})" style="color: #dc2626; background: none; border: none; cursor: pointer; font-size: 0.8rem;">×</button>
						</div>
						<div style="display: flex; justify-content: space-between; align-items: center; gap: 8px;">
							<input type="number" value="${item.qty}" min="1" max="${item.stock}" onchange="updateQty(${item.id}, this.value)" style="width: 60px; padding: 4px 8px; border-radius: 6px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.85rem;">
							<span style="color: #64748b; font-size: 0.8rem;">× Rp ${item.price.toLocaleString('id-ID')}</span>
							<span style="color: #3b82f6; font-weight: 600; font-size: 0.9rem;">Rp ${subtotal.toLocaleString('id-ID')}</span>
						</div>
						<div style="margin-top: 4px;">
							<input type="number" value="${item.diskon}" placeholder="Diskon" onchange="updateDiskon(${item.id}, this.value)" style="width: 100%; padding: 4px 8px; border-radius: 6px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.8rem;">
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

		const subtotalAfterDiskon = subtotal - totalDiskon;
		const ppn = subtotalAfterDiskon * ppnRate;
		const total = subtotalAfterDiskon + ppn;

		document.getElementById('subtotalDisplay').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
		document.getElementById('diskonDisplay').textContent = 'Rp ' + totalDiskon.toLocaleString('id-ID');
		document.getElementById('ppnDisplay').textContent = 'Rp ' + ppn.toLocaleString('id-ID');
		document.getElementById('totalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');

		// Calculate kembalian
		const nominalBayar = parseFloat(document.getElementById('nominalBayar').value) || 0;
		const kembalian = nominalBayar - total;
		document.getElementById('kembalianDisplay').textContent = 'Rp ' + kembalian.toLocaleString('id-ID');
	}

	document.getElementById('nominalBayar').addEventListener('input', calculateTotals);

	// Search filter
	document.getElementById('searchProduct').addEventListener('input', function(e) {
		const search = e.target.value.toLowerCase();
		document.querySelectorAll('.product-card').forEach(card => {
			const name = card.dataset.name.toLowerCase();
			const sku = card.dataset.sku.toLowerCase();
			if (name.includes(search) || sku.includes(search)) {
				card.style.display = 'block';
			} else {
				card.style.display = 'none';
			}
		});
	});

	document.getElementById('posForm').addEventListener('submit', function(e) {
		if (cart.length === 0) {
			e.preventDefault();
			alert('Keranjang tidak boleh kosong');
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
