<?php

namespace App\Http\Controllers\Trader;

use App\Http\Controllers\Controller;
use App\Models\Stall;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch only available stalls and order properly
        $stalls = Stall::where('status', 'available')
                        ->orderBy('stall_number')
                        ->get();

        return view('trader.dashboard', [
            'stalls' => $stalls
        ]);
    }
}