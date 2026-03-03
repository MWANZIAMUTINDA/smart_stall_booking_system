<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ViolationController extends Controller
{
    /**
     * Display a list of violations for the logged-in officer.
     */
    public function index()
    {
        $violations = Violation::where('officer_id', auth()->id())
            ->latest()
            ->get();

        return view('officer.violations.index', compact('violations'));
    }

    /**
     * Show the form to create a new violation.
     */
    public function create()
    {
        $traders = User::where('role', 'trader')->get();
        return view('officer.violations.create', compact('traders'));
    }

    /**
     * Store a new violation and generate AI disciplinary letter using Gemini 2.0 Flash.
     */
    public function store(Request $request)
    {
        // 1. Create the initial violation record
        $violation = Violation::create([
            'trader_id'      => $request->trader_id,
            'officer_id'     => auth()->id(),
            'violation_type' => $request->violation_type,
            'officer_notes'  => $request->officer_notes,
            'status'         => 'pending_ai', // pending until AI completes
        ]);

        // 2. Prepare the AI prompt and API key
        $apiKey = env('GEMINI_API_KEY');
        $prompt = "You are the Market Registrar. Write a formal disciplinary letter for a {$violation->violation_type}. Officer notes: {$violation->officer_notes}. Use a firm tone.";

        // 3. Call Gemini 2.0 Flash via stable v1 endpoint
        $response = Http::post(
            "https://generativelanguage.googleapis.com/v1/models/gemini-2.0-flash:generateContent?key=" . $apiKey,
            [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
            ]
        );

        // 4. Parse the response safely
        $data = $response->json();

        if ($response->successful() && isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            $aiText = $data['candidates'][0]['content']['parts'][0]['text'];
            $status = 'draft_ready';
        } else {
            $status = 'pending_ai'; // Keep pending if AI fails
            $errorMsg = $data['error']['message'] ?? 'Check API Key or Model Access';
            $aiText = 'AI Failed: ' . $errorMsg;
        }

        // 5. Update the violation record with AI output
        $violation->update([
            'ai_raw_message' => $aiText,
            'status'         => $status,
        ]);

        // 6. Redirect to preview page
        return redirect()->route('officer.violations.preview', $violation->id);
    }

    /**
     * Show the AI-generated violation preview.
     */
    public function preview($id)
    {
        $violation = Violation::findOrFail($id);
        return view('officer.violations.preview', compact('violation'));
    }
}