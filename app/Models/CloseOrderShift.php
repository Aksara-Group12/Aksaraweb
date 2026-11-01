<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CloseOrderShift extends Model
{
	protected $fillable = [
		'user_id',
		'operator_name',
		'opened_at',
		'closed_at',
		'total_revenue',
		'total_transactions',
		'status',
	];

	protected $casts = [
		'opened_at' => 'datetime',
		'closed_at' => 'datetime',
		'total_revenue' => 'decimal:2',
		'total_transactions' => 'integer',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
