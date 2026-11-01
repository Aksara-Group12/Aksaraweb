<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'owner_id',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user has verified email
     */
    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Mark user email as verified
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Get the subscriptions for this user.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscribe::class);
    }

    /**
     * Get the owner of this user.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the users owned by this user (for owners).
     */
    public function ownedUsers()
    {
        return $this->hasMany(User::class, 'owner_id');
    }

    /**
     * Get the role of this user.
     */
    public function userRole()
    {
        return $this->belongsTo(Role::class, 'role', 'name');
    }

    /**
     * Check if user has specific permission.
     */
    public function hasPermission($permission)
    {
        $role = $this->userRole;
        if (!$role || !$role->is_active) {
            return false;
        }

        return in_array($permission, $role->permissions ?? []);
    }
}
