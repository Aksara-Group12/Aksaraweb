@extends('layouts.horizon')

@section('page_title', 'Tambah Rekening Bank')

@section('content')
<style>
	.page-header-row {
		margin-bottom: 24px;
	}

	.btn-back-circle {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 38px;
		height: 38px;
		border-radius: 10px;
		background: #FFFFFF;
		border: 1px solid #CBD5E1;
		color: #0F223D;
		transition: all 0.15s ease;
		box-shadow: 0 1px 3px rgba(15, 34, 61, 0.04);
		flex-shrink: 0;
		text-decoration: none;
	}

	.btn-back-circle:hover {
		background: #F8FAFC;
		border-color: #0F223D;
		color: #0F223D;
		transform: translateX(-2px);
		box-shadow: 0 2px 8px rgba(15, 34, 61, 0.08);
	}

	.page-title-block h2 {
		font-family: 'Newsreader', Georgia, serif;
		font-size: 2.2rem;
		font-weight: 500;
		color: #0F223D;
		margin: 0;
		line-height: 1.2;
	}

	.page-title-block p {
		font-size: 0.925rem;
		color: #64748B;
		margin: 4px 0 0 52px;
	}

	.form-card {
		background: #FFFFFF;
		border-radius: 18px;
		border: 1px solid rgba(226, 232, 240, 0.95);
		padding: 28px;
		width: 100%;
		max-width: 100%;
		box-shadow: 0 4px 16px -2px rgba(15, 34, 61, 0.04);
	}

	.card-header-line {
		padding-bottom: 16px;
		margin-bottom: 22px;
		border-bottom: 1px solid #F1F5F9;
	}

	.card-header-title {
		font-family: 'Newsreader', Georgia, serif;
		font-size: 1.35rem;
		font-weight: 600;
		color: #0F223D;
		margin: 0;
	}

	.form-group {
		display: flex;
		flex-direction: column;
		gap: 6px;
		margin-bottom: 18px;
	}

	.form-label {
		font-size: 0.825rem;
		font-weight: 600;
		color: #334155;
	}

	.form-label .req {
		color: #EF4444;
	}

	.form-input {
		width: 100%;
		padding: 10px 14px;
		border-radius: 10px;
		border: 1px solid #CBD5E1;
		background: #FFFFFF;
		color: #0F223D;
		font-family: inherit;
		font-size: 0.875rem;
		outline: none;
		transition: all 0.15s ease;
		box-shadow: 0 1px 2px rgba(15, 34, 61, 0.04);
	}

	.form-input:focus {
		border-color: #0F223D;
		box-shadow: 0 0 0 3px rgba(15, 34, 61, 0.08);
	}

	.form-input.is-invalid {
		border-color: #EF4444;
		background: #FEF2F2;
	}

	.field-error {
		font-size: 0.775rem;
		color: #DC2626;
		margin-top: 4px;
	}

	.field-hint {
		font-size: 0.775rem;
		color: #64748B;
		margin-top: 4px;
	}

	.form-actions {
		display: flex;
		align-items: center;
		gap: 12px;
		margin-top: 26px;
		padding-top: 20px;
		border-top: 1px solid #F1F5F9;
	}

	.btn-submit-primary {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 8px;
		padding: 10px 22px;
		border-radius: 10px;
		background: #0F223D;
		color: #FFFFFF;
		font-family: inherit;
		font-size: 0.85rem;
		font-weight: 600;
		border: none;
		cursor: pointer;
		transition: all 0.15s ease;
		box-shadow: 0 2px 6px rgba(15, 34, 61, 0.15);
	}

	.btn-submit-primary:hover {
		background: #162C50;
		transform: translateY(-1px);
		box-shadow: 0 4px 12px rgba(15, 34, 61, 0.2);
	}

	.btn-cancel {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		padding: 10px 18px;
		border-radius: 10px;
		background: #FFFFFF;
		color: #64748B;
		border: 1px solid #CBD5E1;
		font-family: inherit;
		font-size: 0.85rem;
		font-weight: 600;
		text-decoration: none;
		transition: all 0.15s ease;
	}

	.btn-cancel:hover {
		background: #F8FAFC;
		color: #0F223D;
		border-color: #94A3B8;
	}

	.flash-alert-error {
		margin-bottom: 20px;
		padding: 12px 16px;
		border-radius: 10px;
		font-size: 0.85rem;
		display: flex;
		align-items: flex-start;
		gap: 10px;
		background: #FEF2F2;
		border: 1px solid #FECACA;
		color: #991B1B;
		width: 100%;
		max-width: 100%;
	}
</style>

<div class="page-header-row">
	<div class="page-title-block">
		<div style="display: flex; align-items: center; gap: 14px;">
			<a href="{{ route('bank.index') }}" class="btn-back-circle" title="Kembali ke Daftar Bank">
				<i data-lucide="arrow-left" style="width: 18px; height: 18px;"></i>
			</a>
			<h2>Tambah Bank</h2>
		</div>
		<p>Tambahkan rekening bank resmi untuk tujuan pembayaran paket langganan merchant.</p>
	</div>
</div>

@if ($errors->any())
	<div class="flash-alert-error">
		<i data-lucide="alert-circle" style="width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px;"></i>
		<div>
			<strong style="display: block; margin-bottom: 4px;">Terdapat kesalahan pada formulir:</strong>
			<ul style="margin: 0; padding-left: 18px;">
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	</div>
@endif

<div class="form-card">
	<div class="card-header-line">
		<h3 class="card-header-title">Formulir Rekening Baru</h3>
	</div>

	<form method="POST" action="{{ route('bank.store') }}">
		@csrf

		<div class="form-group">
			<label class="form-label" for="bank_name">
				Nama Bank <span class="req">*</span>
			</label>
			<input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}"
				placeholder="Contoh: Bank Central Asia (BCA) / Mandiri / BRI" 
				class="form-input {{ $errors->has('bank_name') ? 'is-invalid' : '' }}" required autofocus>
			@error('bank_name')
				<span class="field-error">{{ $message }}</span>
			@enderror
		</div>

		<div class="form-group">
			<label class="form-label" for="account_holder">
				Nama Pemilik Rekening (A.N.) <span class="req">*</span>
			</label>
			<input type="text" id="account_holder" name="account_holder" value="{{ old('account_holder') }}"
				placeholder="Contoh: PT Aksara Developer Indonesia" 
				class="form-input {{ $errors->has('account_holder') ? 'is-invalid' : '' }}" required>
			<span class="field-hint">Nama pemilik buku tabungan yang terdaftar di sistem bank.</span>
			@error('account_holder')
				<span class="field-error">{{ $message }}</span>
			@enderror
		</div>

		<div class="form-group">
			<label class="form-label" for="account_number">
				Nomor Rekening <span class="req">*</span>
			</label>
			<input type="text" id="account_number" name="account_number" value="{{ old('account_number') }}"
				placeholder="Contoh: 1234567890" 
				style="font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; letter-spacing: 0.03em;"
				class="form-input {{ $errors->has('account_number') ? 'is-invalid' : '' }}" required>
			<span class="field-hint">Hanya nomor tanpa tanda spasi atau strip pemisah.</span>
			@error('account_number')
				<span class="field-error">{{ $message }}</span>
			@enderror
		</div>

		<div class="form-actions">
			<button type="submit" class="btn-submit-primary">
				<i data-lucide="check" style="width: 16px; height: 16px;"></i>
				<span>Simpan Rekening Bank</span>
			</button>
			<a href="{{ route('bank.index') }}" class="btn-cancel">
				Batal
			</a>
		</div>
	</form>
</div>
@endsection
