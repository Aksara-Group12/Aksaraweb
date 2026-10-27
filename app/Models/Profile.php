<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
	use HasFactory;

	protected $fillable = [
		'user_id',
		'store_name',
		'logo_path',
		'owner_name',
		'phone',
		'email',
		'address',
		'city',
		'postal_code',
	];

	/**
	 * Get the user that owns this profile.
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}
}

