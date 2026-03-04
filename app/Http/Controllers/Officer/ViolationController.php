<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ViolationController extends Controller
{
    public function index()
    {
        $violations = Violation::where('officer_id', auth()->id())
            ->latest()
            ->get();

        return view('officer.violations.index', compact('violations'));
    }

    // ✅ UPDATED: Only fetch ACTIVE traders with confirmed & unexpired bookings
    public function create()
    {
        $activeTraders = User::join('bookings', 'users.id', '=', 'bookings.user_id')
            ->join('stalls', 'bookings.stall_id', '=', 'stalls.id')
            ->where('bookings.status', 'confirmed')
            ->where('bookings.end_time', '>', now()) // Only unexpired
            ->where('bookings.start_time', '<=', now()) // Already started
            ->select(
                'users.id as user_id',
                'users.name as trader_name',
                'stalls.stall_number',
                'bookings.id as booking_id'
            )
            ->get();

        return view('officer.violations.create', compact('activeTraders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trader_id'      => 'required|exists:users,id',
            'violation_type' => 'required|string|max:255',
            'officer_notes'  => 'required|string',
            'amount_due'     => 'nullable|numeric',
        ]);

        $violation = Violation::create([
            'trader_id'      => $request->trader_id,
            'officer_id'     => auth()->id(),
            'violation_type' => $request->violation_type,
            'officer_notes'  => $request->officer_notes,
            'status'         => 'pending_ai',
        ]);

        $trader  = User::find($request->trader_id);
        $officer = auth()->user();
        $today   = now()->format('F d, Y');
        $amountDue = $request->amount_due ?? 'As per market records';

        $prompt = "
Generate a formal violation notice in STRICT JSON format with fields:
subject, salutation, opening_statement, violation_details, legal_consequences, closing.

Trader Name: {$trader->name}
Stall Number: {$trader->stall_number}
Violation Type: {$violation->violation_type}
Officer Notes: {$violation->officer_notes}
Payment Period: Daily
Amount Due: {$amountDue}
Officer Name: {$officer->name}
Date: {$today}
Tone: Legal, strict, official, Kenyan.
";

        $apiKey = trim(env('GEMINI_API_KEY'));
        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key={$apiKey}";

        $aiText = null;
        $status = 'pending_ai';

        try {
            $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->timeout(30)
                ->retry(2, 3000)
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]);

            $data = $response->json();
            $aiText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if ($response->successful() && $aiText) {
                $status = 'draft_ready';
            }

        } catch (\Exception $e) {
            $aiText = "Connection Error: " . $e->getMessage();
        }

        if (empty($aiText) || !is_string($aiText)) {

            $fallbackLetter = [
                'subject' => "Notice on {$violation->violation_type}",
                'salutation' => "Dear {$trader->name},",
                'opening_statement' => "This letter serves to formally notify you of a violation observed at your stall.",
                'violation_details' => $violation->officer_notes,
                'legal_consequences' => "Amount Due: {$amountDue}. Payment Period: Daily. Failure to comply may lead to penalties as per market regulations.",
                'closing' => "Kindly comply with the market regulations immediately. Sincerely, {$officer->name}, Market Officer.",
            ];

            $aiText = json_encode($fallbackLetter, JSON_PRETTY_PRINT);
            $status = 'draft_ready';
        }

        $violation->update([
            'ai_raw_message' => $aiText,
            'final_letter'   => null,
            'status'         => $status,
        ]);

        return redirect()->route('officer.violations.preview', $violation->id);
    }

    public function preview($id)
    {
        $violation = Violation::with(['trader', 'officer'])->findOrFail($id);

        $letterData = null;

        if ($violation->ai_raw_message) {
            $decoded = json_decode($violation->ai_raw_message, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $letterData = $decoded;
            } else {
                $letterData = [
                    'subject' => 'Violation Notice',
                    'salutation' => '',
                    'opening_statement' => $violation->ai_raw_message,
                    'violation_details' => '',
                    'legal_consequences' => '',
                    'closing' => '',
                ];
            }
        }

        return view('officer.violations.preview', compact('violation', 'letterData'));
    }

    public function approve($id)
    {
        $violation = Violation::findOrFail($id);

        $violation->update([
            'final_letter' => $violation->ai_raw_message,
            'status'       => 'approved',
        ]);

        return redirect()->back()->with('success', 'Violation letter approved successfully.');
    }
}