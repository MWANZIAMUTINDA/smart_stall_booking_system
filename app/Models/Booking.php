<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Stall;
use App\Models\User;
use App\Models\Payment;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stall_id',
        'booking_date',
        'start_time',
        'end_time',
        'duration_days',
        'amount',
        'status',
        'payment_status',
        'receipt_number',
        'booked_by_admin_id',
        'payment_prompt_sent_at',
        'admin_notes',
    ];

    protected $casts = [
        'start_time'              => 'datetime',
        'end_time'                => 'datetime',
        'booking_date'            => 'date',
        'payment_prompt_sent_at'  => 'datetime',
        'duration_days'           => 'integer',
        'amount'                  => 'decimal:2',
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

    /**
     * The admin/officer who manually created this booking
     */
    public function bookedByAdmin()
    {
        return $this->belongsTo(User::class, 'booked_by_admin_id');
    }

    /**
     * Relationship: Booking has many payments (attempts)
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if this booking has been paid for
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Generate a unique receipt number
     * Format: MUTH-YYYY-XXXXX
     */
    public static function generateReceiptNumber(): string
    {
        $year = now()->format('Y');
        $lastBooking = static::whereNotNull('receipt_number')
            ->where('receipt_number', 'like', "MUTH-{$year}-%")
            ->orderByDesc('id')
            ->first();

        if ($lastBooking && preg_match('/MUTH-\d{4}-(\d+)/', $lastBooking->receipt_number, $matches)) {
            $nextNumber = (int) $matches[1] + 1;
        } else {
            $nextNumber = 1;
        }

        return sprintf('MUTH-%s-%05d', $year, $nextNumber);
    }
}
