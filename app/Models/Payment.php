<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'mpesa_transaction_id',
        'phone_number',
        'amount',
        'payment_status',
        'payment_method',
        'daraja_checkout_id',
        'daraja_callback_data',
        'payment_time',
        'confirmed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'daraja_callback_data' => 'array',
        'payment_time' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    /**
     * Relationship: Payment belongs to a Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope: Only successful payments
     */
    public function scopeSuccessful(Builder $query)
    {
        return $query->where('payment_status', 'success');
    }

    /**
     * Scope: Only pending payments
     */
    public function scopePending(Builder $query)
    {
        return $query->where('payment_status', 'pending');
    }
}
