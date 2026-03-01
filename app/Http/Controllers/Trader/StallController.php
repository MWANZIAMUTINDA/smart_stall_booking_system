<?php

namespace App\Http\Controllers\Trader;

use App\Http\Controllers\Controller;
use App\Models\Stall;

class StallController extends Controller
{
    public function index()
    {
        $stalls = Stall::where('status', 'available')->get();

        return view('trader.stalls.index', compact('stalls'));
    }
}