@extends('layouts.horizon')

@section('page_title', 'Invoice / AR')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Invoice / AR</h2>
			<p class="hz-top-sub">Daftar Tagihan Piutang & Account Receivable.</p>
		</div>
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px;">
			<form method="GET" action="{{ route('invoice.index') }}" style="display:flex; align-items:center; gap:6px;">
				<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor invoice/pelanggan..." style="border-radius:999px; border:1px solid rgba(148,163,184,0.5); background:#020617; padding:6px 10px; font-size:0.8rem; color:#e5e7eb; min-width:200px;">
			</form>
			<a href="{{ route('invoice.createManual') }}" class="hz-btn hz-btn-secondary">
				+ Buat Manual
			</a>
		</div>
	</div>

	@if ($message = Session::get('success'))
		<div style="margin-top: 20px; padding: 14px 16px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 10px; color: #86efac;">
			{{ $message }}
		</div>
	@endif

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 12px;">
				<p class="hz-card-title">Daftar Tagihan Piutang ({{ $invoices->count() }})</p>
			</div>
			<div style="margin-top: 8px; overflow-x: auto;">
				@if ($invoices->count() > 0)
					<table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
						<thead>
							<tr style="border-bottom: 1px solid rgba(251, 191, 36, 0.4); color:#9ca3af;">
								<th style="text-align:left; padding:14px 10px;">Nomor Invoice</th>
								<th style="text-align:left; padding:14px 10px;">Pelanggan</th>
								<th style="text-align:left; padding:14px 10px;">Tanggal</th>
								<th style="text-align:right; padding:14px 10px;">Total</th>
								<th style="text-align:right; padding:14px 10px;">Sisa Tagihan</th>
								<th style="text-align:left; padding:14px 10px;">Status</th>
								<th style="text-align:center; padding:14px 10px;">Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($invoices as $item)
								@php
									$invoice = \App\Models\Invoice::where('sales_transaction_id', $item->id)->first();
									$sisaTagihan = $invoice ? $invoice->sisa_tagihan : $item->total_harga;
									$status = $invoice ? $invoice->status : 'unpaid';
								@endphp
								<tr style="border-bottom: 1px solid rgba(15,23,42,0.8);">
									<td style="padding:14px 10px; color: #e5e7eb; font-weight: 500;">{{ $item->invoice_no }}</td>
									<td style="padding:14px 10px; color: #cbd5e1;">{{ $item->customer_name ?? '-' }}</td>
									<td style="padding:14px 10px; color: #cbd5e1;">{{ $item->tanggal_transaksi->format('d/m/Y') }}</td>
									<td style="padding:14px 10px; text-align:right; color: #fbbf24; font-weight: 600;">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
									<td style="padding:14px 10px; text-align:right; color: {{ $sisaTagihan > 0 ? '#fca5a5' : '#86efac' }}; font-weight: 600;">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</td>
									<td style="padding:14px 10px;">
										<span style="padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; background: {{ $status === 'paid' ? 'rgba(34,197,94,0.2)' : ($status === 'partial' ? 'rgba(251,191,36,0.2)' : 'rgba(239,68,68,0.2)') }}; color: {{ $status === 'paid' ? '#86efac' : ($status === 'partial' ? '#fbbf24' : '#fca5a5') }};">
											{{ ucfirst($status) }}
										</span>
									</td>
									<td style="padding:14px 10px; text-align:center;">
										@if ($sisaTagihan > 0)
											<button onclick="openPaymentModal({{ $item->id }}, {{ $sisaTagihan }}, '{{ $item->invoice_no }}')" class="hz-btn hz-btn-primary" style="padding: 6px 12px; font-size: 0.8rem;">Bayar</button>
										@else
											<span style="color: #86efac; font-size: 0.85rem;">Lunas</span>
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
					<div style="padding: 40px; text-align: center; color: #9ca3af;">
						<p>Belum ada tagihan piutang.</p>
					</div>
				@endif
			</div>
		</div>
	</section>

	<!-- Modal Catat Pembayaran -->
	<div id="paymentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center;">
		<div style="background: #0f172a; border: 1px solid rgba(251,191,36,0.4); border-radius: 12px; padding: 24px; width: 400px; max-width: 90%;">
			<h3 style="color: #e5e7eb; font-size: 1.1rem; margin-bottom: 16px;">Catat Pembayaran</h3>
			<p style="color: #9ca3af; font-size: 0.9rem; margin-bottom: 12px;">Invoice: <span id="modalInvoiceNo" style="color: #fbbf24;"></span></p>
			<p style="color: #9ca3af; font-size: 0.9rem; margin-bottom: 16px;">Sisa Tagihan: <span id="modalSisaTagihan" style="color: #fca5a5; font-weight: 600;"></span></p>
			<form method="POST" action="{{ route('invoice.recordPayment', ['id' => ':id']) }}" id="paymentForm">
				@csrf
				<div style="margin-bottom: 16px;">
					<label style="display: block; font-size: 0.85rem; font-weight: 500; color: #e5e7eb; margin-bottom: 6px;">Nominal Bayar</label>
					<input type="number" name="nominal_bayar" id="modalNominalBayar" required min="0" style="width: 100%; padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(251,191,36,0.4); background: rgba(15,23,42,0.6); color: #e5e7eb; font-size: 0.9rem;">
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
