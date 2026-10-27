<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiKeluar extends Model
{
	protected $table = 'transaksi_keluar';

	protected $fillable = [
		'admin_id',
		'user_id',
		'invoice_no',
		'customer_name',
		'customer_contact',
		'lokasi',
		'tanggal_transaksi',
		'status',
		'total_harga',
		'metode_pembayaran',
		'nominal_bayar',
		'kembalian',
		'catatan',
		'shift_id',
		'is_invoice',
		'is_void',
		'void_reason',
		'voided_at',
	];

	public function admin()
	{
		return $this->belongsTo(Admin::class, 'admin_id');
	}

	protected $casts = [
		'tanggal_transaksi' => 'date',
		'total_harga' => 'decimal:2',
		'nominal_bayar' => 'decimal:2',
		'kembalian' => 'decimal:2',
		'is_void' => 'boolean',
		'voided_at' => 'datetime',
	];

	/**
	 * Relationship: TransaksiKeluar has many items
	 */
	public function items()
	{
		return $this->hasMany(TransaksiKeluarItem::class, 'transaksi_keluar_id');
	}

	public function biayaTambahan()
	{
		return $this->hasMany(TransaksiBiayaTambahan::class, 'transaksi_keluar_id');
	}

	/**
	 * Generate invoice number
	 */
	public static function generateNumber()
	{
		$count = static::where('created_at', '>=', now()->startOfDay())->count() + 1;
		return 'OUT-' . date('Ymd') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
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
