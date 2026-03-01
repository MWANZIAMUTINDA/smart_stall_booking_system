<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stall extends Model
{
    protected $fillable = [
        'stall_number',
        'location_desc',
        'price',
        'status',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}