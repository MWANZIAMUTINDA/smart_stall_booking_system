<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function handleChat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array'
        ]);

        $userMessage = $request->input('message');
        $history = $request->input('history', []);

        $systemPrompt = "You are the Smart Stall Booking Assistant for a market stall booking system.
Your job is to help users book stalls, understand payments, manage reservations, and solve common problems politely and clearly.

Rules:
* Be short, clear, and friendly.
* Support both English and simple Swahili when possible.
* If a user is confused, guide them step by step.
* Never invent booking information or payment confirmations.
* If you do not know something, tell the user to contact support.
* Help users with:
  * Stall booking
  * Stall availability
  * Payments and M-Pesa confirmations
  * Booking cancellation
  * Account login problems
  * Password reset guidance
  * Booking status tracking
  * Market rules and policies

Example behavior:
User: \"How do I book a stall?\"
Assistant:
1. Login to your account.
2. Open the \"Available Stalls\" page.
3. Select your preferred stall.
4. Confirm the booking duration.
5. Complete payment via M-Pesa.
6. Wait for confirmation SMS or email.

User: \"My payment is pending\"
Assistant:
\"Your payment may still be processing. Please wait a few minutes and refresh your booking page. If the issue continues, contact support with your M-Pesa transaction code.\"

Always remain professional and helpful.";

        $apiKey = env('GEMINI_API_KEY');

        // Format history for Gemini API
        $contents = [];
        if (is_array($history)) {
            foreach ($history as $msg) {
                if (isset($msg['role']) && isset($msg['content'])) {
                    $contents[] = [
                        'role' => $msg['role'] === 'assistant' ? 'model' : 'user',
                        'parts' => [
                            ['text' => $msg['content']]
                        ]
                    ];
                }
            }
        }

        $contents[] = [
            'role' => 'user',
            'parts' => [
                ['text' => "SYSTEM PERSONA:\n" . $systemPrompt . "\n\nUSER MESSAGE:\n" . $userMessage]
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey, [
                'contents' => $contents
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "I'm sorry, I couldn't process that request.";
                return response()->json(['reply' => $reply]);
            }

            Log::error('Gemini API Error: ' . $response->body());
            return response()->json(['reply' => "I'm sorry, my system is currently unavailable. Please try again later."], 500);

        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage());
            return response()->json(['reply' => "I'm having trouble connecting right now. Please try again later."], 500);
        }
    }
}
