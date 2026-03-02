<?php

namespace App\Http\Controllers\Trader;

use App\Http\Controllers\Controller;
use App\Models\Stall;
use App\Models\Feedback;
use Illuminate\Http\Request;

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

    /**
     * Store trader feedback
     */
    public function storeFeedback(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:160',
        ]);

        // Create feedback linked to the logged-in user
        auth()->user()->feedbacks()->create([
            'message' => $request->message,
            'status'  => 'pending',
        ]);

        return back()->with('success', 'Feedback sent successfully!');
    }
}