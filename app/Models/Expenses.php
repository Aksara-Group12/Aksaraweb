<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
	protected $table = 'expenses';

	protected $fillable = [
		'user_id',
		'tanggal',
		'kategori',
		'nominal',
		'metode_pembayaran',
		'keterangan',
		'reference_no',
	];

	protected $casts = [
		'tanggal' => 'date',
		'nominal' => 'decimal:2',
	];
}

