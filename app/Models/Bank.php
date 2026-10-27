<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = [
        'account_holder',
        'bank_name',
        'account_number',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscribe::class);
    }
}
