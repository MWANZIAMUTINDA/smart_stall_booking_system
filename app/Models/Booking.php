<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Stall;
use App\Models\User;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'stall_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
    ];

    /**
     * 1. DATE CASTING (Crucial for Auto-Vacate & Renew Logic)
     * This converts the database strings into Carbon instances automatically.
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'booking_date' => 'date',
    ];

    /**
     * A booking belongs to a stall.
     */
    public function stall()
    {
        return $this->belongsTo(Stall::class);
    }

    /**
     * A booking belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
