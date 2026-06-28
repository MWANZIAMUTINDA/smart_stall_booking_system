<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stall;
use App\Models\Booking;
use Illuminate\Http\Request;

class StallController extends Controller
{
    /**
     * Admin stall management overview.
     * Shows all stalls with their real-time availability + block status.
     */
    public function index()
    {
        $stalls = Stall::with(['bookings' => function ($q) {
            $q->whereIn('status', ['confirmed', 'pending'])
              ->where('end_time', '>', now())
              ->orderBy('start_time', 'asc');
        }])->orderBy('zone')->orderBy('stall_number')->get();

        // Compute smart availability for every stall (respects block status)
        $stalls = $stalls->map(function ($stall) {
            $stall->availability = $stall->getSmartAvailability();
            return $stall;
        });

        // Aggregate stats for the header bar
        $stats = [
            'total'       => $stalls->count(),
            'available'   => $stalls->where('availability.can_book', true)->count(),
            'occupied'    => $stalls->where('availability.status', 'occupied')->count(),
            'blocked'     => $stalls->where('is_blocked', true)->count(),
            'booked_soon' => $stalls->where('availability.status', 'booked_soon')->count(),
        ];

        return view('admin.stalls.index', compact('stalls', 'stats'));
    }

    /**
     * Block a stall — admin must provide a reason.
     */
    public function block(Request $request, Stall $stall)
    {
        $request->validate([
            'blocked_reason' => 'required|string|min:5|max:500',
            'mark_maintenance' => 'sometimes|boolean',
        ]);

        // Optionally mark the underlying status as maintenance
        $newStatus = $request->boolean('mark_maintenance') ? 'maintenance' : $stall->status;

        $stall->update(['status' => $newStatus]);
        $stall->block($request->blocked_reason, auth()->id());

        return back()->with('success',
            "Stall #{$stall->stall_number} has been blocked. Traders will see: \"{$request->blocked_reason}\""
        );
    }

    /**
     * Unblock a stall — restores it to normal availability.
     */
    public function unblock(Stall $stall)
    {
        if (!$stall->isBlocked()) {
            return back()->with('error', "Stall #{$stall->stall_number} is not currently blocked.");
        }

        // Restore status to 'available' if it was set to 'maintenance'
        if ($stall->status === 'maintenance') {
            $stall->update(['status' => 'available']);
        }

        $stall->unblock();

        return back()->with('success',
            "Stall #{$stall->stall_number} has been unblocked and is now available for booking."
        );
    }

    /**
     * Mark stall as under maintenance (blocks it + sets maintenance status).
     */
    public function markMaintenance(Request $request, Stall $stall)
    {
        $request->validate([
            'blocked_reason' => 'required|string|min:5|max:500',
        ]);

        $stall->update(['status' => 'maintenance']);
        $stall->block($request->blocked_reason, auth()->id());

        return back()->with('success',
            "Stall #{$stall->stall_number} is now marked as Under Maintenance. Traders cannot book it."
        );
    }
}
