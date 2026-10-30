<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
	protected $table = 'users';

	protected $fillable = [
		'name',
		'email',
		'password',
		'role', // owner, admin, kasir
		'is_active',
	];
}
