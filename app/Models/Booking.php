<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder; // Add this
use App\Models\Stall;
use App\Models\User;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stall_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'booking_date' => 'date',
    ];

    /**
     * SCOPE: Only get bookings that are currently active (within time)
     * Usage: Booking::currentOccupants()->get();
     */
    public function scopeCurrentOccupants(Builder $query)
    {
        return $query->where('status', 'confirmed')
                     ->where('start_time', '<=', now())
                     ->where('end_time', '>=', now());
    }

    /**
     * SCOPE: Get bookings that have officially expired but are still marked 'confirmed'
     * Usage: Booking::expiredButStillConfirmed()->get();
     */
    public function scopeExpiredButStillConfirmed(Builder $query)
    {
        return $query->where('status', 'confirmed')
                     ->where('end_time', '<', now());
    }

    public function stall()
    {
        return $this->belongsTo(Stall::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
