<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Stall;

class StallController extends Controller
{
    /**
     * Display a listing of ALL stalls for traders (smart availability).
     */
    public function index()
    {
        // Get ALL stalls with bookings for smart availability
        $stalls = Stall::with(['bookings' => function($q) {
            $q->whereIn('status', ['confirmed', 'pending'])
              ->where('end_time', '>', now())
              ->orderBy('start_time', 'asc');
        }])->get();

        // Return the view with all stalls
        return view('trader.stalls.index', compact('stalls'));
    }
}