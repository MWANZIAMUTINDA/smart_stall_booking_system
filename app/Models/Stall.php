<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Stall extends Model
{
    use HasFactory;

    /**
     * Buffer window in hours before a booking during which
     * no new bookings are allowed (preparation/security window).
     */
    const BUFFER_HOURS = 24;

    protected $fillable = [
        'stall_number',
        'location_desc',
        'price',
        'status', // 'available', 'booked', 'maintenance', 'inactive'
        'is_blocked',
        'blocked_reason',
        'blocked_at',
        'blocked_by_admin_id',
    ];

    protected $casts = [
        'is_blocked'  => 'boolean',
        'blocked_at'  => 'datetime',
    ];

    // =========================================================
    // 🔒 ADMIN BLOCK HELPERS
    // =========================================================

    /**
     * Return true when an admin has manually disabled this stall.
     */
    public function isBlocked(): bool
    {
        return (bool) $this->is_blocked;
    }

    /**
     * Block the stall with a mandatory reason.
     */
    public function block(string $reason, int $adminId): void
    {
        $this->update([
            'is_blocked'          => true,
            'blocked_reason'      => $reason,
            'blocked_at'          => now(),
            'blocked_by_admin_id' => $adminId,
        ]);
    }

    /**
     * Unblock the stall — clears all block metadata.
     */
    public function unblock(): void
    {
        $this->update([
            'is_blocked'          => false,
            'blocked_reason'      => null,
            'blocked_at'          => null,
            'blocked_by_admin_id' => null,
        ]);
    }

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
        return !$this->hasActiveBooking();
    }

    /**
     * Check if this stall has an active (confirmed + paid + unexpired) booking *right now*
     */
    public function hasActiveBooking(): bool
    {
        return $this->bookings()
            ->where('status', 'confirmed')
            ->where('payment_status', 'paid')
            ->where('start_time', '<=', now())
            ->where('end_time', '>', now())
            ->exists();
    }

    /**
     * Get the current active booking for this stall (if any)
     */
    public function activeBooking()
    {
        return $this->bookings()
            ->where('status', 'confirmed')
            ->where('payment_status', 'paid')
            ->where('start_time', '<=', now())
            ->where('end_time', '>', now())
            ->first();
    }

    /**
     * Get upcoming confirmed/paid bookings (starting in the future)
     */
    public function upcomingBookings()
    {
        return $this->bookings()
            ->where('status', 'confirmed')
            ->where('payment_status', 'paid')
            ->where('start_time', '>', now())
            ->orderBy('start_time', 'asc');
    }

    // =========================================================
    // 🧠 SMART AVAILABILITY ENGINE
    // =========================================================

    /**
     * Get the next future booking (confirmed or pending) for this stall.
     * Works with eager-loaded bookings collection.
     */
    public function getNextFutureBooking()
    {
        $now = now();

        // If bookings are eager-loaded, use the collection
        if ($this->relationLoaded('bookings')) {
            return $this->bookings
                ->filter(function ($b) use ($now) {
                    return in_array($b->status, ['confirmed', 'pending'])
                        && $b->start_time > $now;
                })
                ->sortBy('start_time')
                ->first();
        }

        // Fallback to query
        return $this->bookings()
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('start_time', '>', $now)
            ->orderBy('start_time', 'asc')
            ->first();
    }

    /**
     * Get the current active booking (in-progress right now).
     * Works with eager-loaded bookings collection.
     */
    public function getCurrentBooking()
    {
        $now = now();

        if ($this->relationLoaded('bookings')) {
            return $this->bookings
                ->first(function ($b) use ($now) {
                    return in_array($b->status, ['confirmed', 'pending'])
                        && $b->start_time <= $now
                        && $b->end_time > $now;
                });
        }

        return $this->bookings()
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('start_time', '<=', $now)
            ->where('end_time', '>', $now)
            ->first();
    }

    /**
     * 🧠 SMART AVAILABILITY STATUS
     * Returns an object describing this stall's current availability state.
     *
     * Possible statuses:
     *   - 'occupied'        → Currently in use (active booking right now)
     *   - 'booked_soon'     → Next booking starts within BUFFER_HOURS (blocked)
     *   - 'available_until' → Available now, but has a future booking beyond buffer
     *   - 'available'       → Fully open, no restrictions
     *
     * Privacy: Never exposes WHO booked it — only WHEN.
     */
    public function getSmartAvailability(): object
    {
        $now = now();
        $bufferHours = self::BUFFER_HOURS;

        // 0. 🔒 Admin block takes absolute priority — shown to traders with reason
        if ($this->isBlocked()) {
            $label = ($this->status === 'maintenance')
                ? 'Under Maintenance'
                : 'Blocked / Unavailable';

            return (object) [
                'status'          => 'blocked',
                'label'           => $label,
                'color'           => 'slate',
                'can_book'        => false,
                'message'         => $this->blocked_reason ?? 'This stall is temporarily unavailable.',
                'detail'          => $this->blocked_at
                                        ? 'Since: ' . $this->blocked_at->format('d M Y, H:i')
                                        : null,
                'end_time'        => null,
                'next_booking'    => null,
                'available_until' => null,
                'blocked_reason'  => $this->blocked_reason,
            ];
        }

        // 1. Check if occupied RIGHT NOW
        $currentBooking = $this->getCurrentBooking();
        if ($currentBooking) {
            return (object) [
                'status'          => 'occupied',
                'label'           => 'Occupied',
                'color'           => 'rose',
                'can_book'        => false,
                'message'         => 'Currently in use',
                'detail'          => 'Vacant at: ' . $currentBooking->end_time->format('d M, H:i'),
                'end_time'        => $currentBooking->end_time,
                'next_booking'    => null,
                'available_until' => null,
                'blocked_reason'  => null,
            ];
        }

        // 2. Find the next future booking
        $nextBooking = $this->getNextFutureBooking();

        if ($nextBooking) {
            $hoursUntilNext = $now->diffInHours($nextBooking->start_time, false);

            // Case B: Booking is within buffer window (≤ 24 hours away) → BLOCKED
            if ($hoursUntilNext <= $bufferHours) {
                return (object) [
                    'status'          => 'booked_soon',
                    'label'           => 'Booked (Starting Soon)',
                    'color'           => 'amber',
                    'can_book'        => false,
                    'message'         => 'Within ' . $bufferHours . 'hr preparation window',
                    'detail'          => 'Starts: ' . $nextBooking->start_time->format('d M, H:i'),
                    'end_time'        => null,
                    'next_booking'    => $nextBooking,
                    'available_until' => null,
                    'blocked_reason'  => null,
                ];
            }

            // Case A: Booking is far away (> 24 hours) → AVAILABLE with limit
            $safeUntil = $nextBooking->start_time->copy()->subHours($bufferHours);

            return (object) [
                'status'          => 'available_until',
                'label'           => 'Available Now',
                'color'           => 'emerald',
                'can_book'        => true,
                'message'         => 'Booked on ' . $nextBooking->start_time->format('d M, H:i'),
                'detail'          => 'Book until: ' . $safeUntil->format('d M, H:i'),
                'end_time'        => null,
                'next_booking'    => $nextBooking,
                'available_until' => $safeUntil,
                'blocked_reason'  => null,
            ];
        }

        // 3. No bookings at all → Fully open
        return (object) [
            'status'          => 'available',
            'label'           => 'Available Now',
            'color'           => 'emerald',
            'can_book'        => true,
            'message'         => 'Fully open — No restrictions',
            'detail'          => null,
            'end_time'        => null,
            'next_booking'    => null,
            'available_until' => null,
            'blocked_reason'  => null,
        ];
    }

    /**
     * Check if the stall is available for a proposed time slot.
     * Includes a BUFFER_HOURS buffer BEFORE any existing booking.
     */
    public function isAvailableForTimeSlot($start, $end): bool
    {
        // Parse dates if they are strings
        $startTime = Carbon::parse($start);
        $endTime = Carbon::parse($end);

        // Find any booking that overlaps with the proposed slot.
        // We consider both 'confirmed' and 'pending' bookings as "occupying" the slot.
        $overlap = $this->bookings()
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('end_time', '>', now()) // Only care about future/current bookings
            ->where(function ($query) use ($startTime, $endTime) {
                // Mathematically, two intervals [s1, e1] and [s2, e2] overlap if:
                // s1 < e2 AND e1 > s2
                
                // Buffer Rule: We treat every existing booking as starting BUFFER_HOURS EARLIER
                // than its actual start_time to preserve the preparation/security window.
                
                // Existing: [start_time - BUFFER_HOURS, end_time]
                // Proposed: [startTime, endTime]
                
                $bufferHours = self::BUFFER_HOURS;
                $query->where(DB::raw("DATE_SUB(start_time, INTERVAL {$bufferHours} HOUR)"), '<', $endTime)
                      ->where('end_time', '>', $startTime);
            })
            ->exists();

        return !$overlap;
    }

    /**
     * Get the maximum allowed end_time for a new booking on this stall.
     * Returns null if there's no restriction (fully open).
     * Returns a Carbon date if there's a future booking limiting availability.
     */
    public function getMaxBookingEndTime(): ?Carbon
    {
        $nextBooking = $this->getNextFutureBooking();

        if (!$nextBooking) {
            return null; // No restriction
        }

        // The latest a new booking can end is BUFFER_HOURS before the next booking starts
        return $nextBooking->start_time->copy()->subHours(self::BUFFER_HOURS);
    }
}
