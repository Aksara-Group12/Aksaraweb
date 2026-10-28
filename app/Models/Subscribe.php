<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscribe extends Model
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'user_id',
        'paket_id',
        'paket_name',
        'price',
        'duration_days',
        'start_date',
        'end_date',
        'bank_id',
        'bukti_transaksi',
        'status',
        'approval_status',
        'approved_at',
        'approved_by',
        'admin_notes',
        'is_trial',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'is_trial' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if subscription is still active
     */
    public function isActive()
    {
        return $this->status === 'active' && Carbon::now()->lessThanOrEqualTo($this->end_date);
    }

    /**
     * Check if subscription has expired
     */
    public function isExpired()
    {
        return Carbon::now()->greaterThan($this->end_date);
    }

    /**
     * Get days remaining
     */
    public function getDaysRemaining()
    {
        if ($this->isExpired()) {
            return 0;
        }
        return Carbon::now()->diffInDays($this->end_date);
    }

    /**
     * Get current active subscription for user
     */
    public static function getActiveSubscription($userId)
    {
        return self::where('user_id', $userId)
            ->where('status', 'active')
            ->where('end_date', '>=', Carbon::now()->toDateString())
            ->latest('created_at')
            ->first();
    }

    /**
     * Get package type from subscription
     * Returns: 1, 2, or 3 (default 1 if no subscription)
     */
    public static function getPackageType($userId)
    {
        $subscription = self::getActiveSubscription($userId);
        if (!$subscription) {
            return 1; // Default Paket 1 jika tidak ada subscription
        }
        // Extract paket number from paket_name (e.g., "Paket 1" -> 1)
        if (preg_match('/Paket (\d+)/', $subscription->paket_name, $matches)) {
            return (int) $matches[1];
        }
        return 1; // Default
    }
}

