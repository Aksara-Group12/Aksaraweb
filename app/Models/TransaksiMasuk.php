<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiMasuk extends Model
{
	protected $table = 'transaksi_masuk';

	protected $fillable = [
		'user_id',
		'reference_no',
		'supplier_name',
		'supplier_contact',
		// kolom ini sudah di-rename menjadi `lokasi` melalui migration
		'lokasi',
		'po_invoice',
		'tanggal_transaksi',
		'status',
		'total_harga',
		'metode_pembayaran',
		'nominal_bayar',
		'kembalian',
		'catatan'
	];

	protected $casts = [
		'tanggal_transaksi' => 'date',
		'total_harga' => 'decimal:2',
		'nominal_bayar' => 'decimal:2',
		'kembalian' => 'decimal:2',
	];

	/**
	 * Relationship: TransaksiMasuk has many items
	 */
	public function items()
	{
		return $this->hasMany(TransaksiMasukItem::class, 'transaksi_masuk_id');
	}

	public function biayaTambahan()
	{
		return $this->hasMany(TransaksiBiayaTambahan::class, 'transaksi_masuk_id');
	}

	/**
	 * Relationship: Audit log untuk tracking perubahan
	 */
	public function auditLogs()
	{
		return $this->hasMany(TransaksiMasukAuditLog::class, 'transaksi_masuk_id')->orderBy('created_at', 'desc');
	}

	/**
	 * Generate reference number
	 */
	public static function generateNumber()
	{
		$count = static::where('created_at', '>=', now()->startOfDay())->count() + 1;
		return 'IN-' . date('Ymd') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
	}

	/**
	 * Update total harga from items
	 */
	public function updateTotal()
	{
		$this->total_harga = $this->items()->sum('total_harga');
		return $this->save();
	}

	/**
	 * Scope untuk status complete
	 */
	public function scopeComplete($query)
	{
		return $query->where('status', 'complete');
	}

	/**
	 * Scope untuk status pending
	 */
	public function scopePending($query)
	{
		return $query->where('status', 'pending');
	}

	/**
	 * Scope untuk tanggal range
	 */
	public function scopeDateRange($query, $startDate, $endDate)
	{
		return $query->whereBetween('tanggal_transaksi', [$startDate, $endDate]);
	}
}
