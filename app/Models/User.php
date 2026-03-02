<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Feedback; // Added import

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'role',
        'status',              // Your existing status
        'account_restriction', // New: none, warned, blocked, banned
        'restriction_reason',  // New: Why the admin acted
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the feedback submitted by the user.
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Restriction Helpers
     */
    public function hasWarning(): bool
    {
        return $this->account_restriction === 'warned';
    }

    public function isBlocked(): bool
    {
        return $this->account_restriction === 'blocked';
    }

    public function isBanned(): bool
    {
        return $this->account_restriction === 'banned';
    }
}