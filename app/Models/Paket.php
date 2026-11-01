<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
	protected $table = 'paket';

	protected $fillable = [
		'nama_paket',
		'deskripsi',
		'harga',
		'harga_diskon',
		'durasi_hari',
		'is_active',
	];

	protected $casts = [
		'harga' => 'integer',
		'harga_diskon' => 'integer',
		'durasi_hari' => 'integer',
		'is_active' => 'boolean',
	];
}
