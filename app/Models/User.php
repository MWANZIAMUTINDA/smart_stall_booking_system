<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Feedback; // Added import

class User extends Authenticatable implements MustVerifyEmail
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
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if the user already has a confirmed or pending booking during this slot.
     * Prevents a trader from booking multiple stalls for the same time.
     */
    public function isBusyDuring($start, $end): bool
    {
        $startTime = \Carbon\Carbon::parse($start);
        $endTime = \Carbon\Carbon::parse($end);

        return $this->bookings()
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('end_time', '>', now())
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            })
            ->exists();
    }
}