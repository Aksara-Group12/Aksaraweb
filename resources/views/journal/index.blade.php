@extends('layouts.horizon')

@section('page_title', 'Jurnal Finansial')
@section('page_subtitle', 'Pencatatan buku besar dan entri transaksi keuangan toko.')

@section('content')
	<div class="hz-top-row" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px; flex-wrap:wrap; gap:12px;">
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; width:100%; justify-content:space-between;">
			<form method="GET" action="{{ route('journal.index') }}" style="display:flex; align-items:center; gap:6px;">
				<div style="position:relative; display:inline-flex; align-items:center;">
					<svg style="position:absolute; left:12px; width:15px; height:15px; color:#94A3B8; pointer-events:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
					<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor jurnal..." style="padding-left:36px; min-width:240px;">
				</div>
			</form>
		</div>
	</div>

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1; padding: 24px;">
			<div class="hz-card-header" style="margin-bottom: 16px;">
				<p class="hz-card-title">Daftar Jurnal ({{ $journals->total() ?? $journals->count() }})</p>
			</div>
			<div style="margin-top: 8px; overflow-x: auto;">
				@if ($journals->count() > 0)
					<table>
						<thead>
							<tr>
								<th style="text-align:left;">Nomor</th>
								<th style="text-align:left;">Tanggal</th>
								<th style="text-align:left;">Keterangan</th>
								<th style="text-align:right;">Total Debit</th>
								<th style="text-align:right;">Total Kredit</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($journals as $journal)
								<tr>
									<td style="font-weight: 600; color: #0F223D;">{{ $journal->nomor_jurnal }}</td>
									<td style="color: #475569;">{{ $journal->tanggal->format('d/m/Y') }}</td>
									<td style="color: #475569;">{{ $journal->keterangan ?? '-' }}</td>
									<td style="text-align:right; font-weight:700; color: #059669;">Rp {{ number_format($journal->items->sum('debit'), 0, ',', '.') }}</td>
									<td style="text-align:right; font-weight:700; color: #DC2626;">Rp {{ number_format($journal->items->sum('kredit'), 0, ',', '.') }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					@if (method_exists($journals, 'links'))
						<div style="margin-top: 18px; display:flex; justify-content:center;">
							{{ $journals->links() }}
						</div>
					@endif
				@else
					<div style="padding: 48px; text-align: center; color: #64748b;">
						<p>Belum ada data jurnal keuangan.</p>
					</div>
				@endif
			</div>
		</div>
	</section>
@endsection
