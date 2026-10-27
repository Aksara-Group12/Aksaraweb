@extends('layouts.horizon')

@section('page_title', 'Buat Stock Adjustment')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Buat Stock Adjustment</h2>
			<p class="hz-top-sub">Input stok fisik untuk penyesuaian.</p>
		</div>
	</div>

	<form id="adjustmentForm" action="{{ route('stock-adjustment.store') }}" method="POST" style="margin-top: 24px;">
		@csrf
		<section class="hz-secondary-grid">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Informasi Adjustment</p>
				</div>
				<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 16px;">
					<div style="display: flex; flex-direction: column; gap: 6px;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Tanggal <span style="color: #fbbf24;">*</span></label>
						<input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
					</div>
					<div style="display: flex; flex-direction: column; gap: 6px; grid-column: 1 / -1;">
						<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Keterangan</label>
						<textarea name="keterangan" rows="2" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem; resize: vertical;"></textarea>
					</div>
				</div>
			</div>
		</section>

		<section class="hz-secondary-grid" style="margin-top: 20px;">
			<div class="hz-card" style="grid-column: 1 / -1;">
				<div class="hz-card-header" style="margin-bottom: 16px;">
					<p class="hz-card-title">Detail Produk</p>
				</div>
				<div id="itemsContainer" style="display: flex; flex-direction: column; gap: 16px;">
					<div class="item-card" style="border: 1px solid rgba(251,191,36,0.3); border-radius: 14px; padding: 18px; background: rgba(15,23,42,0.7);">
						<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
							<p style="font-size: 0.9rem; font-weight: 600; color: #fbbf24;">Item #1</p>
							<button type="button" class="remove-item-btn" style="display: none; padding: 6px 12px; border-radius: 8px; border: 1px solid rgba(239,68,68,0.4); background: rgba(239,68,68,0.1); color: #fca5a5; font-size: 0.8rem; cursor: pointer;">Hapus</button>
						</div>
						<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
							<div style="display: flex; flex-direction: column; gap: 6px;">
								<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Produk <span style="color: #fbbf24;">*</span></label>
								<select class="product-select" name="items[0][product_id]" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
									<option value="">-- Pilih Produk --</option>
									@foreach ($products as $prod)
										<option value="{{ $prod->id }}" data-stock="{{ $prod->stock }}">{{ $prod->nama_produk }} (Stok: {{ $prod->stock }})</option>
									@endforeach
								</select>
							</div>
							<div style="display: flex; flex-direction: column; gap: 6px;">
								<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Stok Sistem</label>
								<div class="stok-sistem-display" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #9ca3af; font-size: 0.9rem;">-</div>
							</div>
							<div style="display: flex; flex-direction: column; gap: 6px;">
								<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Stok Fisik <span style="color: #fbbf24;">*</span></label>
								<input type="number" class="stok-fisik-input" name="items[0][stok_fisik]" min="0" step="0.01" required placeholder="0" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
							</div>
							<div style="display: flex; flex-direction: column; gap: 6px;">
								<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Selisih</label>
								<div class="selisih-display" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #fbbf24; font-weight: 600; font-size: 0.9rem;">0</div>
							</div>
							<div style="display: flex; flex-direction: column; gap: 6px;">
								<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Nilai Selisih (Rp)</label>
								<div class="nilai-selisih-display" style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(148,163,184,0.4); background: rgba(15,23,42,0.6); color: #fbbf24; font-weight: 600; font-size: 0.9rem;">Rp 0</div>
							</div>
							<div style="display: flex; flex-direction: column; gap: 6px;">
								<label style="font-size: 0.85rem; font-weight: 500; color: #e5e7eb;">Alasan <span style="color: #fbbf24;">*</span></label>
								<select name="items[0][alasan]" required style="padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
									<option value="selisih_hitung">Selisih Hitung</option>
									<option value="rusak">Barang Rusak</option>
									<option value="kadaluarsa">Kadaluarsa</option>
									<option value="hilang">Hilang</option>
									<option value="lainnya">Lainnya</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<button type="button" id="addItemBtn" style="margin-top: 16px; padding: 10px 16px; border-radius: 10px; border: 1px solid rgba(34,197,94,0.4); background: rgba(34,197,94,0.1); color: #86efac; font-size: 0.9rem; cursor: pointer;">+ Tambah Produk</button>
				
				<!-- Ringkasan Total Nilai Selisih -->
				<div style="margin-top: 20px; padding: 16px; background: rgba(15,23,42,0.6); border-radius: 10px; border: 1px solid rgba(251,191,36,0.3);">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<span style="color: #e5e7eb; font-weight: 600; font-size: 0.95rem;">Total Nilai Selisih</span>
						<span id="totalNilaiSelisih" style="color: {{ 0 >= 0 ? '#86efac' : '#fca5a5' }}; font-weight: 700; font-size: 1.1rem;">Rp 0</span>
					</div>
				</div>
			</div>
		</section>
		<div style="margin-top: 24px; display: flex; gap: 8px;">
			<button type="submit" class="hz-btn hz-btn-primary">Simpan Adjustment</button>
			<a href="{{ route('stock-adjustment.index') }}" class="hz-btn hz-btn-secondary">Batal</a>
		</div>
	</form>
@endsection

@push('scripts')
<script>
	let itemCount = 1;

	document.getElementById('addItemBtn').addEventListener('click', function() {
		itemCount++;
		const template = document.querySelector('.item-card').cloneNode(true);
		template.querySelector('p').textContent = 'Item #' + itemCount;
		template.querySelector('.remove-item-btn').style.display = 'block';
		template.querySelectorAll('input, select').forEach(input => {
			input.value = input.defaultValue || '';
			input.name = input.name.replace(/\[\d+\]/, '[' + (itemCount - 1) + ']');
		});
		template.querySelector('.stok-sistem-display').textContent = '-';
		template.querySelector('.selisih-display').textContent = '0';
		template.querySelector('.nilai-selisih-display').textContent = 'Rp 0';
		document.getElementById('itemsContainer').appendChild(template);
	});

	document.getElementById('itemsContainer').addEventListener('click', function(e) {
		if (e.target.classList.contains('remove-item-btn')) {
			if (document.querySelectorAll('.item-card').length > 1) {
				e.target.closest('.item-card').remove();
			} else {
				alert('Minimal harus ada 1 item');
			}
		}
	});

	document.getElementById('itemsContainer').addEventListener('change', function(e) {
		if (e.target.classList.contains('product-select')) {
			const selectedOption = e.target.options[e.target.selectedIndex];
			const card = e.target.closest('.item-card');
			card.querySelector('.stok-sistem-display').textContent = selectedOption.getAttribute('data-stock');
			calculateSelisih(card);
		}
		if (e.target.classList.contains('stok-fisik-input')) {
			calculateSelisih(e.target.closest('.item-card'));
		}
	});

	function calculateSelisih(card) {
		const stokSistem = parseFloat(card.querySelector('.stok-sistem-display').textContent) || 0;
		const stokFisik = parseFloat(card.querySelector('.stok-fisik-input').value) || 0;
		const selisih = stokFisik - stokSistem;
		card.querySelector('.selisih-display').textContent = selisih;
		card.querySelector('.selisih-display').style.color = selisih < 0 ? '#fca5a5' : '#86efac';

		// Hitung nilai selisih (selisih * harga_beli produk)
		// Note: Harga beli tidak tersedia di frontend, jadi set 0 untuk sekarang
		// Backend akan menghitung berdasarkan harga_beli dari database
		const nilaiSelisih = 0;
		card.querySelector('.nilai-selisih-display').textContent = 'Rp ' + nilaiSelisih.toLocaleString('id-ID');
		card.querySelector('.nilai-selisih-display').style.color = nilaiSelisih < 0 ? '#fca5a5' : '#86efac';

		// Update total nilai selisih semua item
		updateTotalNilaiSelisih();
	}

	function updateTotalNilaiSelisih() {
		let total = 0;
		document.querySelectorAll('.item-card').forEach(card => {
			const selisih = parseFloat(card.querySelector('.selisih-display').textContent) || 0;
			// Note: Nilai selisih akan dihitung di backend
			// Frontend hanya menampilkan placeholder 0
		});
		document.getElementById('totalNilaiSelisih').textContent = 'Rp 0 (Dihitung di Backend)';
	}
</script>
@endpush
