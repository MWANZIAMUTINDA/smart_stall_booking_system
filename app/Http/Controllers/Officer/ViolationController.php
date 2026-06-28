<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Mail\ViolationNoticeMail;
use App\Models\ViolationLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ViolationController extends Controller
{

    public function index(Request $request)
    {
        $query = Violation::where('officer_id', auth()->id());

        // Advanced Filtering
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('violation_type')) {
            $query->where('violation_type', $request->violation_type);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->date_from)->startOfDay(),
                Carbon::parse($request->date_to)->endOfDay()
            ]);
        }

        $violations = $query->latest()->get();

        return view('officer.violations.index', compact('violations'));
    }


    public function create()
    {
        $activeTraders = User::join('bookings', 'users.id', '=', 'bookings.user_id')
            ->join('stalls', 'bookings.stall_id', '=', 'stalls.id')
            ->where('bookings.status', 'confirmed')
            // Filter by currently occupying OR recently vacated (e.g., within 24 hours)
            ->where(function ($q) {
                $q->where('bookings.end_time', '>', now())
                  ->orWhere('bookings.end_time', '>=', now()->subDay());
            })
            ->where('bookings.start_time', '<=', now())
            ->select(
                'users.id as user_id',
                'users.name as trader_name',
                'stalls.stall_number',
                'bookings.id as booking_id',
                'bookings.end_time'
            )
            ->distinct()
            ->orderBy('stalls.stall_number')
            ->get();

        // Officer productivity stats
        $officerId = auth()->id();
        $violationsToday = Violation::where('officer_id', $officerId)
            ->whereDate('created_at', today())->count();
        $violationsThisWeek = Violation::where('officer_id', $officerId)
            ->where('created_at', '>=', now()->startOfWeek())->count();
        $violationsTotal = Violation::where('officer_id', $officerId)->count();

        return view('officer.violations.create', compact(
            'activeTraders', 'violationsToday', 'violationsThisWeek', 'violationsTotal'
        ));
    }


    public function store(Request $request)
    {

        $request->validate([
            'trader_id' => 'required|exists:users,id',
            'violation_type' => 'required|string|max:255',
            'officer_notes' => 'required|string',
            'amount_due' => 'nullable|numeric',
            'payment_period' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png|max:4096',
        ]);


        $trader = User::findOrFail($request->trader_id);
        $officer = auth()->user();

        $isoDate = Carbon::now()->toDateString();
        $amountDue = $request->amount_due ?? "As per official market tariffs";
        $paymentPeriod = $request->payment_period ?? 'Daily';

        $stallNumber = $this->getCurrentStallNumberForTrader($trader->id) ?? 'N/A';

        // Robust Case ID Generation
        $caseID = "NCC-MKT-" . strtoupper(substr(hash('crc32', uniqid(rand(), true)), 0, 6));
        
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('violations/photos', 'public');
        }

        $violation = Violation::create([
            'trader_id' => $trader->id,
            'officer_id' => $officer->id,
            'violation_type' => $request->violation_type,
            'officer_notes' => $request->officer_notes,
            'case_id' => $caseID,
            'photo_path' => $photoPath,
            'status' => 'pending_ai',
        ]);
        
        ViolationLog::create([
            'violation_id' => $violation->id,
            'user_id' => $officer->id,
            'action' => 'Violation Created (Pending AI)',
        ]);


        $legalMapping = [

            'Waste Management' =>
            'Nairobi City County Solid Waste Management Act 2015 Section 34 requiring traders to maintain sanitation standards.',

            'Late Payment' =>
            'Nairobi City County Finance Act Section 15 requiring daily or periodic payment of market cess.',

            'Unauthorized Stall Use' =>
            'Nairobi City County Markets Act governing approved business operations within allocated stalls.',

            'Subletting Stall' =>
            'Market Tenancy Bylaws prohibiting stall transfer without county authorization.',

            'Obstructing Walkway' =>
            'Nairobi City County Public Roads and Markets Bylaws regarding obstruction of public passageways.',

            'Encroaching Stall Space' =>
            'Market Operations Regulations requiring traders to operate within allocated stall boundaries.',

            'Selling Unlicensed Goods' =>
            'Trade Licensing Act and Nairobi City County Licensing Act requiring a valid Single Business Permit.',

            'Food Hygiene Violation' =>
            'Public Health Act Cap 242 governing food sanitation standards.',

            'Noise Violation' =>
            'NEMA Noise and Excessive Vibration Pollution Regulations.',

            'Illegal Electricity Connection' =>
            'Energy Act 2019 and Kenya Power safety regulations prohibiting unauthorized power tapping.',

            'Damage to Market Property' =>
            'Nairobi City County Markets Act protecting county infrastructure.'
        ];


        $selectedLaw = $legalMapping[$request->violation_type] ?? 'Nairobi City County Market Regulations.';


        $schema = [

            'letter_head' => 'string',
            'reference_number' => 'string',
            'case_id' => 'string',
            'subject' => 'string',
            'date_of_observation' => 'YYYY-MM-DD',
            'recipient_name' => 'string',
            'recipient_address' => 'string',
            'stall_number' => 'string',
            'opening_statement' => 'string',
            'violation_type' => 'string',
            'violation_details' => 'string',
            'law_reference' => 'string',
            'community_impact' => 'string',
            'compliance_deadline' => 'string',
            'legal_consequences' => 'string',
            'instructions' => 'string',
            'appeal_rights' => 'string',
            'payment_period' => 'string',
            'amount_due' => 'string',
            'officer_name' => 'string',
            'officer_title' => 'string',
            'signature_block' => 'string',
            'contact_details' => 'string',
            'cc_section' => 'string',
            'official_stamp_section' => 'string',
            'status' => 'draft_ready|approved|sent'
        ];


        $prompt = "

You are the Senior Legal Counsel for Nairobi City County Government MUTHURWA Market Enforcement Department.

Generate a formal **Official Notice of Violation**.

Requirements:

• Official Government header
• Case ID and reference number
• Formal opening statement
• Professional violation description
• Law reference: {$selectedLaw}
• Compliance deadline (24 hours)
• Penalties including City Court prosecution
• Instructions for resolution
• Appeal rights
• Signature block
• Official stamp placeholder
• Contact details
• CC section

Return ONLY valid JSON matching this schema:

".json_encode($schema)."

Case ID:
{$caseID}

Trader: {$trader->name}

Stall Number: {$stallNumber}

Violation Type: {$request->violation_type}

Officer Notes:
{$request->officer_notes}

Officer Name:
{$officer->name}

Date:
{$isoDate}

Payment Period:
{$paymentPeriod}

Amount Due:
{$amountDue}

";


        $apiKey = trim(env('GEMINI_API_KEY'));

        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key={$apiKey}";


        $aiText = null;
        $status = 'pending_ai';


        try {

            $response = Http::timeout(30)->retry(2, 2000)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);


            if ($response->successful()) {

                $data = $response->json();

                $aiText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($aiText) {
                    $status = 'draft_ready';
                }

            }

        } catch (\Exception $e) {

            $aiText = "Connection Error: " . $e->getMessage();

        }


        $parsed = json_decode($aiText, true);


        if (!$parsed) {

            $parsed = [

                'letter_head' =>
                'Nairobi City County Government – Market Enforcement Department',

                'reference_number' => 'NCC/MKT/' . rand(1000,9999),

                'case_id' => $caseID,

                'subject' =>
                "Notice of Violation – {$request->violation_type}",

                'date_of_observation' => $isoDate,

                'recipient_name' => $trader->name,

                'recipient_address' => '',

                'stall_number' => $stallNumber,

                'opening_statement' =>
                'During a routine inspection conducted by the Market Enforcement Office, a violation was identified within your allocated stall.',

                'violation_type' => $request->violation_type,

                'violation_details' => $request->officer_notes,

                'law_reference' => $selectedLaw,

                'community_impact' =>
                'Such violations negatively impact the safety, cleanliness, and orderly operations of the market environment.',

                'compliance_deadline' =>
                'You are required to rectify this violation within 24 hours from the time of this notice.',

                'legal_consequences' =>
                'Failure to comply may lead to prosecution at the Nairobi City Court, revocation of stall allocation, confiscation of goods, and suspension of trading privileges.',

                'instructions' =>
                'Immediately correct the violation and report to the Market Enforcement Office for inspection.',

                'appeal_rights' =>
                'You may submit a written explanation or appeal within 48 hours to the Market Enforcement Office.',

                'payment_period' => $paymentPeriod,

                'amount_due' => $amountDue,

                'officer_name' => $officer->name,

                'officer_title' => 'Market Enforcement Officer',

                'signature_block' =>
                "Yours faithfully,\n{$officer->name}\nMarket Enforcement Officer",

                'contact_details' =>
                "Market Enforcement Office\nMuthurwa Market\nTel: 0710618973\nEmail: info@muthurwamarket.indevs.in",

                'cc_section' =>
                "Market Manager\nEnforcement Department\nFile",

                'official_stamp_section' =>
                "[OFFICIAL MARKET ENFORCEMENT STAMP]",

                'status' => 'draft_ready'

            ];

            $aiText = json_encode($parsed, JSON_PRETTY_PRINT);

            $status = 'draft_ready';

        }


        $finalLetter = $this->renderLetterFromJson($parsed);


        $violation->update([
            'ai_raw_message' => $aiText,
            'final_letter' => $finalLetter,
            'status' => $status
        ]);
        
        ViolationLog::create([
            'violation_id' => $violation->id,
            'user_id' => $officer->id,
            'action' => 'AI Draft Generated',
        ]);

        return redirect()->route('officer.violations.preview', $violation->id);
    }



    public function preview($id)
    {

        $violation = Violation::with(['trader','officer', 'logs.user'])->findOrFail($id);

        $letterData = json_decode($violation->ai_raw_message,true);

        $currentStall = $this->getCurrentStallNumberForTrader($violation->trader->id);

        // Log the view action if not already viewed recently
        ViolationLog::firstOrCreate([
            'violation_id' => $violation->id,
            'user_id' => auth()->id(),
            'action' => 'Viewed Preview',
        ]);

        return view('officer.violations.preview', compact('violation','letterData','currentStall'));
    }



    public function approve(Request $request, $id)
    {
        $violation = Violation::findOrFail($id);
        
        $signaturePath = null;
        if ($request->filled('signature_data')) {
            $image_parts = explode(";base64,", $request->signature_data);
            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'signatures/' . uniqid() . '.png';
                Storage::disk('public')->put($fileName, $image_base64);
                $signaturePath = $fileName;
            }
        }

        $violation->update([
            'status' => 'approved',
            'signature_path' => $signaturePath
        ]);
        
        ViolationLog::create([
            'violation_id' => $violation->id,
            'user_id' => auth()->id(),
            'action' => 'Approved Violation',
        ]);

        return back()->with('success','Violation letter approved and signed.');
    }



    public function sendEmail(Request $request,$id)
    {

        $violation = Violation::with(['trader','officer'])->findOrFail($id);

        $recipientEmail = $this->getTraderEmail($violation->trader);

        if(!$recipientEmail){
            return back()->with('error','Trader has no valid email.');
        }

        try{
            $subjectLine = sprintf(
                '⚠ Official Violation Notice [%s] — %s — Muthurwa Market',
                $violation->case_id,
                $violation->violation_type
            );

            Mail::to($recipientEmail)
                ->send(new ViolationNoticeMail(
                    $violation,
                    $violation->final_letter,
                    $subjectLine
                ));

            $violation->update(['status'=>'sent']);
            
            ViolationLog::create([
                'violation_id' => $violation->id,
                'user_id' => auth()->id(),
                'action' => 'Sent Notice via Email',
            ]);

            // Send SMS alert to trader
            try {
                $sms = new SmsService();
                $sms->sendViolationNotice(
                    $violation->trader->phone_number,
                    $violation->trader->name,
                    $violation->case_id,
                    $violation->violation_type
                );
                Log::info('[SMS] Violation notice SMS sent', [
                    'case_id' => $violation->case_id,
                    'trader'  => $violation->trader->name,
                ]);
            } catch (\Exception $e) {
                Log::warning('[SMS] Violation notice SMS failed', ['error' => $e->getMessage()]);
            }

            return back()->with('success','Violation notice sent via email and SMS.');

        }catch(\Exception $e){

            Log::error('Violation email error',['error'=>$e->getMessage()]);

            return back()->with('error',$e->getMessage());

        }

    }
    
    public function downloadPdf($id)
    {
        $violation = Violation::with(['trader', 'officer'])->findOrFail($id);
        $letterData = json_decode($violation->ai_raw_message, true);

        $pdf = Pdf::loadView('officer.violations.pdf', compact('violation', 'letterData'))
                   ->setPaper('a4', 'portrait');

        ViolationLog::create([
            'violation_id' => $violation->id,
            'user_id'      => auth()->id(),
            'action'       => 'Downloaded PDF Notice',
        ]);

        return $pdf->download("Violation_Notice_{$violation->case_id}.pdf");
    }

    /* ─────────────────────────────────────────────────────────────────
     |  REGENERATE LETTER  (AJAX – POST)
     |  Allows the officer to re-generate the AI letter with optional
     |  custom tone / extra instructions.
     ──────────────────────────────────────────────────────────────── */
    public function regenerateLetter(Request $request, $id)
    {
        $violation = Violation::with(['trader', 'officer'])->findOrFail($id);

        $request->validate([
            'instructions' => 'nullable|string|max:800',
            'tone'         => 'nullable|string|in:formal,strict,final_warning,cordial',
        ]);

        $trader        = $violation->trader;
        $officer       = $violation->officer ?? auth()->user();
        $isoDate       = $violation->created_at->toDateString();
        $stallNumber   = $this->getCurrentStallNumberForTrader($trader->id) ?? 'N/A';
        $existingData  = json_decode($violation->ai_raw_message, true) ?? [];

        $amountDue     = $existingData['amount_due']    ?? 'As per official market tariffs';
        $paymentPeriod = $existingData['payment_period'] ?? 'Daily';

        $legalMapping = [
            'Waste Management'           => 'Nairobi City County Solid Waste Management Act 2015 Section 34.',
            'Late Payment'               => 'Nairobi City County Finance Act Section 15.',
            'Unauthorized Stall Use'     => 'Nairobi City County Markets Act.',
            'Subletting Stall'           => 'Market Tenancy Bylaws.',
            'Obstructing Walkway'        => 'Nairobi City County Public Roads and Markets Bylaws.',
            'Encroaching Stall Space'    => 'Market Operations Regulations.',
            'Selling Unlicensed Goods'   => 'Trade Licensing Act and Nairobi City County Licensing Act.',
            'Food Hygiene Violation'     => 'Public Health Act Cap 242.',
            'Noise Violation'            => 'NEMA Noise and Excessive Vibration Pollution Regulations.',
            'Illegal Electricity Connection' => 'Energy Act 2019 and Kenya Power safety regulations.',
            'Damage to Market Property'  => 'Nairobi City County Markets Act.',
        ];

        $selectedLaw   = $legalMapping[$violation->violation_type] ?? 'Nairobi City County Market Regulations.';
        $tone          = $request->input('tone', 'formal');
        $toneMap       = [
            'formal'        => 'professional and formal, suitable for a first-time notice',
            'strict'        => 'firm and authoritative, making consequences very clear',
            'final_warning' => 'extremely serious final warning tone before legal action',
            'cordial'       => 'firm yet cordial, encouraging voluntary compliance',
        ];
        $toneDescription = $toneMap[$tone] ?? $toneMap['formal'];
        $extraInstructions = $request->input('instructions', '');

        $schema = [
            'letter_head'          => 'string',
            'reference_number'     => 'string',
            'case_id'              => 'string',
            'subject'              => 'string',
            'date_of_observation'  => 'YYYY-MM-DD',
            'recipient_name'       => 'string',
            'recipient_address'    => 'string',
            'stall_number'         => 'string',
            'opening_statement'    => 'string',
            'violation_type'       => 'string',
            'violation_details'    => 'string',
            'law_reference'        => 'string',
            'community_impact'     => 'string',
            'compliance_deadline'  => 'string',
            'legal_consequences'   => 'string',
            'instructions'         => 'string',
            'appeal_rights'        => 'string',
            'payment_period'       => 'string',
            'amount_due'           => 'string',
            'officer_name'         => 'string',
            'officer_title'        => 'string',
            'signature_block'      => 'string',
            'contact_details'      => 'string',
            'cc_section'           => 'string',
            'official_stamp_section' => 'string',
            'status'               => 'draft_ready',
        ];

        $prompt = "
You are the Senior Legal Counsel for Nairobi City County Government MUTHURWA Market Enforcement Department.

Generate a formal **Official Notice of Violation** with a {$toneDescription} tone.

Requirements:
• Official Government letterhead (Nairobi City County Government – Muthurwa Market Enforcement Department)
• Case ID and unique reference number
• Formal opening statement tailored to the violation
• Professional, detailed violation description
• Law reference: {$selectedLaw}
• Clear compliance deadline (24 hours from date of notice)
• Specific penalties including City Court prosecution and stall revocation
• Step-by-step instructions for resolution
• Clear appeal rights (48 hours written appeal)
• Professional signature block
• Official stamp placeholder text
• Contact details for the enforcement office
• CC section (Market Manager, Enforcement Department, File)
" . ($extraInstructions ? "\nAdditional Officer Instructions: {$extraInstructions}\n" : "") . "

Return ONLY valid JSON — no markdown, no code fences, no extra text — matching exactly this schema:

" . json_encode($schema) . "

Case ID: {$violation->case_id}
Trader: {$trader->name}
Stall Number: {$stallNumber}
Violation Type: {$violation->violation_type}
Officer Notes: {$violation->officer_notes}
Officer Name: {$officer->name}
Date: {$isoDate}
Payment Period: {$paymentPeriod}
Amount Due: {$amountDue}
";

        $apiKey  = trim(env('GEMINI_API_KEY'));
        $url     = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key={$apiKey}";
        $parsed  = null;
        $status  = 'draft_ready';

        try {
            $response = Http::timeout(45)->retry(2, 3000)->post($url, [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'temperature'     => 0.3,
                    'maxOutputTokens' => 4096,
                ],
            ]);

            if ($response->successful()) {
                $data    = $response->json();
                $aiText  = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($aiText) {
                    // Strip markdown code fences if present
                    $clean = preg_replace('/^```(?:json)?\s*/i', '', trim($aiText));
                    $clean = preg_replace('/\s*```$/', '', $clean);
                    $parsed = json_decode($clean, true);

                    // Fallback: try to extract first JSON object
                    if (!$parsed) {
                        if (preg_match('/\{.*\}/s', $clean, $matches)) {
                            $parsed = json_decode($matches[0], true);
                        }
                    }

                    if ($parsed) {
                        $finalLetter = $this->renderLetterFromJson($parsed);
                        $violation->update([
                            'ai_raw_message' => json_encode($parsed, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                            'final_letter'   => $finalLetter,
                            'status'         => $status,
                        ]);

                        ViolationLog::create([
                            'violation_id' => $violation->id,
                            'user_id'      => auth()->id(),
                            'action'       => 'AI Letter Regenerated (Tone: ' . $tone . ')',
                        ]);

                        return response()->json([
                            'success'     => true,
                            'letter_data' => $parsed,
                            'message'     => 'Letter regenerated successfully.',
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'AI response could not be parsed. Please try again.',
            ], 422);

        } catch (\Exception $e) {
            Log::error('Gemini Regenerate Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /* ─────────────────────────────────────────────────────────────────
     |  SHOW LETTER  – standalone clean print view
     ──────────────────────────────────────────────────────────────── */
    public function showLetter($id)
    {
        $violation  = Violation::with(['trader', 'officer'])->findOrFail($id);
        $letterData = json_decode($violation->ai_raw_message, true);
        return view('officer.violations.letter', compact('violation', 'letterData'));
    }



    protected function renderLetterFromJson(array $data): string
    {

        return implode("\n",[

            $data['letter_head'],

            "",

            "Reference No: ".$data['reference_number'],

            "Case ID: ".$data['case_id'],

            "Date: ".$data['date_of_observation'],

            "",

            "To: ".$data['recipient_name'],

            "Stall Number: ".$data['stall_number'],

            "",

            "Subject: ".$data['subject'],

            "",

            $data['opening_statement'],

            "",

            "Violation Details:",
            $data['violation_details'],

            "",

            "Legal Reference:",
            $data['law_reference'],

            "",

            "Impact:",
            $data['community_impact'],

            "",

            "Compliance Deadline:",
            $data['compliance_deadline'],

            "",

            "Legal Consequences:",
            $data['legal_consequences'],

            "",

            "Required Actions:",
            $data['instructions'],

            "",

            "Right of Appeal:",
            $data['appeal_rights'],

            "",

            $data['signature_block'],

            "",

            $data['official_stamp_section'],

            "",

            "Contact:",
            $data['contact_details'],

            "",

            "CC:",
            $data['cc_section']

        ]);

    }



    protected function getCurrentStallNumberForTrader(int $traderId): ?string
    {

        $booking = DB::table('bookings')
            ->join('stalls','bookings.stall_id','=','stalls.id')
            ->where('bookings.user_id',$traderId)
            ->where('bookings.status','confirmed')
            ->where('bookings.start_time','<=',now())
            ->where('bookings.end_time','>',now())
            ->select('stalls.stall_number')
            ->first();

        return $booking->stall_number ?? null;
    }



    protected function getTraderEmail($trader): ?string
    {
        return filter_var($trader->email ?? null,FILTER_VALIDATE_EMAIL)
            ? $trader->email
            : null;
    }

}