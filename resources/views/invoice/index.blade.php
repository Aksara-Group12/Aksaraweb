@extends('layouts.horizon')

@section('page_title', 'Invoice & Piutang')
@section('page_subtitle', 'Daftar tagihan piutang pelanggan & status pelunasan pembayaran.')

@section('content')
	<div class="hz-top-row" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px; flex-wrap:wrap; gap:12px;">
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; width:100%; justify-content:space-between;">
			<form method="GET" action="{{ route('invoice.index') }}" style="display:flex; align-items:center; gap:8px;">
				<div style="position:relative; display:inline-flex; align-items:center;">
					<svg style="position:absolute; left:12px; width:15px; height:15px; color:#94A3B8; pointer-events:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
					<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor invoice / pelanggan..." style="padding-left:36px; min-width:240px;">
				</div>
			</form>
			<a href="{{ route('invoice.createManual') }}" class="hz-btn hz-btn-secondary">
				<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
					<line x1="12" y1="5" x2="12" y2="19"></line>
					<line x1="5" y1="12" x2="19" y2="12"></line>
				</svg>
				<span>Buat Manual</span>
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

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1; padding: 24px;">
			<div class="hz-card-header" style="margin-bottom: 16px;">
				<p class="hz-card-title">Daftar Tagihan Piutang ({{ $invoices->total() ?? $invoices->count() }})</p>
			</div>
			<div style="margin-top: 8px; overflow-x: auto;">
				@if ($invoices->count() > 0)
					<table>
						<thead>
							<tr>
								<th style="text-align:left;">Nomor Invoice</th>
								<th style="text-align:left;">Pelanggan</th>
								<th style="text-align:left;">Tanggal</th>
								<th style="text-align:right;">Total</th>
								<th style="text-align:right;">Sisa Tagihan</th>
								<th style="text-align:center;">Status</th>
								<th style="text-align:center;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($invoices as $item)
								@php
									$invoice = \App\Models\Invoice::where('sales_transaction_id', $item->id)->first();
									$sisaTagihan = $invoice ? $invoice->sisa_tagihan : $item->total_harga;
									$status = $invoice ? $invoice->status : 'unpaid';
								@endphp
								<tr>
									<td style="font-weight: 600; color: #0F223D;">{{ $item->invoice_no }}</td>
									<td style="color: #475569;">{{ $item->customer_name ?? '-' }}</td>
									<td style="color: #475569;">{{ $item->tanggal_transaksi->format('d/m/Y') }}</td>
									<td style="text-align:right; font-weight: 700; color: #0F223D;">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
									<td style="text-align:right; font-weight: 700; color: {{ $sisaTagihan > 0 ? '#DC2626' : '#059669' }};">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</td>
									<td style="text-align:center;">
										@if($status === 'paid')
											<span style="background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46; padding: 3px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">Lunas</span>
										@elseif($status === 'partial')
											<span style="background: #FFFBEB; border: 1px solid #FDE68A; color: #92400E; padding: 3px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">Sebagian</span>
										@else
											<span style="background: #FEF2F2; border: 1px solid #FECACA; color: #991B1B; padding: 3px 10px; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">Belum Bayar</span>
										@endif
									</td>
									<td style="text-align:center;">
										@if ($sisaTagihan > 0)
											<button onclick="openPaymentModal({{ $item->id }}, {{ $sisaTagihan }}, '{{ $item->invoice_no }}')" class="hz-btn hz-btn-primary" style="padding: 5px 12px; font-size: 0.8rem;">Bayar</button>
										@else
											<span style="color: #059669; font-weight: 600; font-size: 0.85rem;">✓ Lunas</span>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					<div style="margin-top: 12px; display:flex; justify-content:center;">
						{{ $invoices->links() }}
					</div>
				@else
					<div style="padding: 40px; text-align: center; color: #64748b;">
						<p>Belum ada tagihan piutang.</p>
					</div>
				@endif
			</div>
		</div>
	</section>

	<!-- Modal Catat Pembayaran -->
	<div id="paymentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center;">
		<div style="background: #ffffff72a; border: 1px solid rgba(59,130,246,0.4); border-radius: 12px; padding: 24px; width: 400px; max-width: 90%;">
			<h3 style="color: #1e293b; font-size: 1.1rem; margin-bottom: 16px;">Catat Pembayaran</h3>
			<p style="color: #64748b; font-size: 0.9rem; margin-bottom: 12px;">Invoice: <span id="modalInvoiceNo" style="color: #3b82f6;"></span></p>
			<p style="color: #64748b; font-size: 0.9rem; margin-bottom: 16px;">Sisa Tagihan: <span id="modalSisaTagihan" style="color: #dc2626; font-weight: 600;"></span></p>
			<form method="POST" action="{{ route('invoice.recordPayment', ['id' => ':id']) }}" id="paymentForm">
				@csrf
				<div style="margin-bottom: 16px;">
					<label style="display: block; font-size: 0.85rem; font-weight: 500; color: #1e293b; margin-bottom: 6px;">Nominal Bayar</label>
					<input type="number" name="nominal_bayar" id="modalNominalBayar" required min="0" style="width: 100%; padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(59,130,246,0.4); background: rgba(255,255,255,0.95); color: #1e293b; font-size: 0.9rem;">
				</div>
				<div style="display: flex; gap: 8px;">
					<button type="submit" class="hz-btn hz-btn-primary" style="flex: 1;">Simpan</button>
					<button type="button" onclick="closePaymentModal()" class="hz-btn hz-btn-secondary">Batal</button>
				</div>
			</form>
		</div>
	</div>
@endsection

@push('scripts')
<script>
	function openPaymentModal(id, sisaTagihan, invoiceNo) {
		document.getElementById('paymentModal').style.display = 'flex';
		document.getElementById('modalInvoiceNo').textContent = invoiceNo;
		document.getElementById('modalSisaTagihan').textContent = 'Rp ' + sisaTagihan.toLocaleString('id-ID');
		document.getElementById('modalNominalBayar').value = sisaTagihan;
		document.getElementById('paymentForm').action = '{{ route('invoice.recordPayment', ['id' => ':id']) }}'.replace(':id', id);
	}

	function closePaymentModal() {
		document.getElementById('paymentModal').style.display = 'none';
	}
</script>
@endpush
