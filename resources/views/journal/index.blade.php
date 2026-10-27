@extends('layouts.horizon')

@section('page_title', 'Jurnal Finance')

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Jurnal Finance</h2>
			<p class="hz-top-sub">Buku besar akuntansi.</p>
		</div>
		<div class="hz-top-actions" style="display:flex; align-items:center; gap:10px;">
			<form method="GET" action="{{ route('journal.index') }}" style="display:flex; align-items:center; gap:6px;">
				<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor jurnal..." style="border-radius:999px; border:1px solid rgba(148,163,184,0.5); background:#020617; padding:6px 10px; font-size:0.8rem; color:#e5e7eb; min-width:200px;">
			</form>
		</div>
	</div>

	<section class="hz-secondary-grid" style="margin-top: 24px;">
		<div class="hz-card" style="grid-column: 1 / -1;">
			<div class="hz-card-header" style="margin-bottom: 12px;">
				<p class="hz-card-title">Daftar Jurnal ({{ $journals->count() }})</p>
			</div>
			<div style="margin-top: 8px; overflow-x: auto;">
				@if ($journals->count() > 0)
					<table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
						<thead>
							<tr style="border-bottom: 1px solid rgba(251, 191, 36, 0.4); color:#9ca3af;">
								<th style="text-align:left; padding:14px 10px;">Nomor</th>
								<th style="text-align:left; padding:14px 10px;">Tanggal</th>
								<th style="text-align:left; padding:14px 10px;">Keterangan</th>
								<th style="text-align:right; padding:14px 10px;">Total Debit</th>
								<th style="text-align:right; padding:14px 10px;">Total Kredit</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($journals as $journal)
								<tr style="border-bottom: 1px solid rgba(15,23,42,0.8);">
									<td style="padding:14px 10px; color: #e5e7eb; font-weight: 500;">{{ $journal->nomor_jurnal }}</td>
									<td style="padding:14px 10px; color: #cbd5e1;">{{ $journal->tanggal->format('d/m/Y') }}</td>
									<td style="padding:14px 10px; color: #cbd5e1;">{{ $journal->keterangan ?? '-' }}</td>
									<td style="padding:14px 10px; text-align:right; color: #86efac;">Rp {{ number_format($journal->items->sum('debit'), 0, ',', '.') }}</td>
									<td style="padding:14px 10px; text-align:right; color: #fca5a5;">Rp {{ number_format($journal->items->sum('kredit'), 0, ',', '.') }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					<div style="margin-top: 12px; display:flex; justify-content:center;">
						{{ $journals->links() }}
					</div>
				@else
					<div style="padding: 40px; text-align: center; color: #9ca3af;">
						<p>Belum ada data jurnal.</p>
					</div>
				@endif
			</div>
		</div>
	</section>
@endsection
