<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
	protected $table = 'pengeluaran';

	protected $fillable = [
		'user_id',
		'tanggal',
		'kategori',
		'nominal',
		'keterangan',
		'reference_no',
	];

	protected $casts = [
		'tanggal' => 'date',
		'nominal' => 'decimal:2',
	];
}

