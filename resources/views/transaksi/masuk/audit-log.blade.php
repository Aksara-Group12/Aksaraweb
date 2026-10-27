@extends('layouts.horizon')

@section('page_title', 'Riwayat Perubahan - ' . ($transaksiMasuk->reference_no ?? 'Transaksi'))

@section('content')
	<div class="hz-top-row">
		<div>
			<h2 class="hz-top-title">Riwayat Perubahan Transaksi</h2>
			<p class="hz-top-sub">{{ $transaksiMasuk->reference_no }} - {{ $transaksiMasuk->supplier_name }}</p>
		</div>
		<a href="{{ route('transaksi.masuk.edit', $transaksiMasuk->id) }}" class="hz-btn-primary" style="align-self: flex-start;">
			<span>← Kembali</span>
		</a>
	</div>

	@if($auditLogs->isEmpty())
		<div style="margin-top: 24px; padding: 40px; text-align: center; background: rgba(15,23,42,0.7); border-radius: 12px; border: 1px solid rgba(148,163,184,0.2);">
			<p style="color: #9ca3af; font-size: 0.95rem;">Tidak ada riwayat perubahan untuk transaksi ini.</p>
		</div>
	@else
		<div style="margin-top: 24px; display: flex; flex-direction: column; gap: 16px;">
			@foreach($auditLogs as $log)
				<div style="background: rgba(15,23,42,0.7); border: 1px solid rgba(148,163,184,0.2); border-radius: 12px; padding: 20px; transition: all 0.2s;">
					<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
						<div>
							<p style="font-size: 0.9rem; font-weight: 600; color: #fbbf24; margin-bottom: 4px;">
								@switch($log->action_type)
									@case('items_rebuilt')
										📦 Item Diubah
										@break
									@case('status_changed')
										🔄 Status Berubah
										@break
									@case('header_updated')
										✏️ Informasi Diperbarui
										@break
									@case('created')
										✨ Transaksi Dibuat
										@break
									@default
										📝 {{ ucfirst(str_replace('_', ' ', $log->action_type)) }}
								@endswitch
							</p>
							<p style="font-size: 0.8rem; color: #cbd5e1;">{{ $log->description }}</p>
						</div>
						<div style="text-align: right;">
							<p style="font-size: 0.75rem; color: #9ca3af; margin-bottom: 4px;">
								{{ $log->created_at->diffForHumans() }}
							</p>
							<p style="font-size: 0.75rem; color: #64748b;">
								oleh: <span style="color: #94a3b8;">{{ $log->user?->name ?? $log->changed_by ?? 'System' }}</span>
							</p>
						</div>
					</div>

					<!-- Detail perubahan -->
					@if($log->action_type === 'status_changed' && $log->old_data && $log->new_data)
						<div style="margin-top: 12px; padding: 12px; background: rgba(15,23,42,0.4); border-radius: 8px; border-left: 3px solid #fbbf24;">
							<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 0.85rem;">
								<div>
									<p style="color: #9ca3af; margin-bottom: 4px;">Dari:</p>
									<p style="color: #fca5a5; font-weight: 600;">{{ ucfirst($log->old_data['status'] ?? '-') }}</p>
								</div>
								<div>
									<p style="color: #9ca3af; margin-bottom: 4px;">Menjadi:</p>
									<p style="color: #10b981; font-weight: 600;">{{ ucfirst($log->new_data['status'] ?? '-') }}</p>
								</div>
							</div>
						</div>
					@endif

					@if($log->action_type === 'header_updated' && $log->old_data && $log->new_data)
						<div style="margin-top: 12px; padding: 12px; background: rgba(15,23,42,0.4); border-radius: 8px; border-left: 3px solid #fbbf24;">
							@php
								$oldData = $log->old_data;
								$newData = $log->new_data;
								$fieldsToShow = ['supplier_name', 'supplier_contact', 'nominal_bayar', 'catatan'];
							@endphp
							
							<p style="font-size: 0.8rem; color: #cbd5e1; margin-bottom: 8px; font-weight: 600;">Field yang berubah:</p>
							<div style="display: grid; gap: 8px;">
								@foreach($fieldsToShow as $field)
									@if(($oldData[$field] ?? null) !== ($newData[$field] ?? null))
										<div style="display: grid; grid-template-columns: 120px 1fr; gap: 12px; font-size: 0.8rem; padding: 8px; background: rgba(0,0,0,0.2); border-radius: 6px;">
											<div style="color: #9ca3af; word-break: break-word;">
												<strong>{{ str_replace('_', ' ', ucfirst($field)) }}</strong>
											</div>
											<div style="display: flex; gap: 8px; align-items: center;">
												<span style="color: #fca5a5; text-decoration: line-through;">{{ $oldData[$field] ?? '-' }}</span>
												<span style="color: #9ca3af;">→</span>
												<span style="color: #10b981; font-weight: 600;">{{ $newData[$field] ?? '-' }}</span>
											</div>
										</div>
									@endif
								@endforeach
							</div>
						</div>
					@endif

					@if($log->action_type === 'items_rebuilt' && $log->old_data && $log->new_data)
						<div style="margin-top: 12px;">
							<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
								<div style="padding: 12px; background: rgba(15,23,42,0.4); border-radius: 8px; border-left: 3px solid #fca5a5;">
									<p style="font-size: 0.8rem; color: #fca5a5; margin-bottom: 6px; font-weight: 600;">Sebelumnya ({{ $log->old_data['item_count'] ?? 0 }} item)</p>
									<ul style="font-size: 0.8rem; color: #cbd5e1; list-style: none; padding: 0; margin: 0;">
										@forelse($log->old_data['items'] ?? [] as $item)
											<li style="padding: 4px 0; border-bottom: 1px solid rgba(148,163,184,0.1);">
												{{ $item['product']['brand'] ?? '-' }} | {{ $item['product']['nama_produk'] ?? '-' }}
											</li>
										@empty
											<li style="color: #9ca3af;">Tidak ada item</li>
										@endforelse
									</ul>
								</div>
								<div style="padding: 12px; background: rgba(15,23,42,0.4); border-radius: 8px; border-left: 3px solid #10b981;">
									<p style="font-size: 0.8rem; color: #10b981; margin-bottom: 6px; font-weight: 600;">Sekarang ({{ $log->new_data['item_count'] ?? 0 }} item)</p>
									<ul style="font-size: 0.8rem; color: #cbd5e1; list-style: none; padding: 0; margin: 0;">
										@forelse($log->new_data['items'] ?? [] as $item)
											<li style="padding: 4px 0; border-bottom: 1px solid rgba(148,163,184,0.1);">
												{{ $item['product']['brand'] ?? '-' }} | {{ $item['product']['nama_produk'] ?? '-' }}
											</li>
										@empty
											<li style="color: #9ca3af;">Tidak ada item</li>
										@endforelse
									</ul>
								</div>
							</div>
						</div>
					@endif
				</div>
			@endforeach
		</div>
	@endif

	<style>
		[style*="border-left: 3px solid"] {
			transition: all 0.2s;
		}

		[style*="background: rgba(15,23,42,0.7)"] {
			transition: all 0.2s;
		}

		[style*="background: rgba(15,23,42,0.7)"]:hover {
			border-color: rgba(148,163,184,0.4);
		}
	</style>
@endsection
