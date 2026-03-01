<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Stall;

class StallController extends Controller
{
    /**
     * Display a listing of available stalls for traders.
     */
    public function index()
    {
        // Get all stalls where status is 'available'
        $stalls = Stall::where('status', 'available')->get();

        // Return the view with available stalls
        return view('trader.stalls.index', compact('stalls'));
    }
}