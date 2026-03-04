<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stall extends Model
{
    use HasFactory;

    protected $fillable = [
        'stall_number',
        'location_desc',
        'price',
        'status', // 'available', 'booked'
    ];

    /**
     * Relationship to bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if the stall is TRULY available right now
     * Usage: if($stall->isActuallyAvailable()) { ... }
     */
    public function isActuallyAvailable()
    {
        // If status is already available, it's free.
        if ($this->status === 'available') {
            return true;
        }

        // Even if status is 'booked', check if the booking has expired based on system time
        $activeBooking = $this->bookings()
            ->where('status', 'confirmed')
            ->where('end_time', '>', now())
            ->exists();

        return !$activeBooking;
    }
}
