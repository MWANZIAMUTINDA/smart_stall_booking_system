<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Mail\ViolationNoticeMail;

class ViolationController extends Controller
{

    public function index()
    {
        $violations = Violation::where('officer_id', auth()->id())
            ->latest()
            ->get();

        return view('officer.violations.index', compact('violations'));
    }


    public function create()
    {
        $activeTraders = User::join('bookings', 'users.id', '=', 'bookings.user_id')
            ->join('stalls', 'bookings.stall_id', '=', 'stalls.id')
            ->where('bookings.status', 'confirmed')
            ->where('bookings.end_time', '>', now())
            ->where('bookings.start_time', '<=', now())
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
            'trader_id' => 'required|exists:users,id',
            'violation_type' => 'required|string|max:255',
            'officer_notes' => 'required|string',
            'amount_due' => 'nullable|numeric',
            'payment_period' => 'nullable|string',
        ]);


        $trader = User::findOrFail($request->trader_id);
        $officer = auth()->user();

        $isoDate = Carbon::now()->toDateString();
        $amountDue = $request->amount_due ?? "As per official market tariffs";
        $paymentPeriod = $request->payment_period ?? 'Daily';

        $stallNumber = $this->getCurrentStallNumberForTrader($trader->id) ?? 'N/A';


        $caseID = "NCC-MKT-" . strtoupper(Str::random(6));


        $violation = Violation::create([
            'trader_id' => $trader->id,
            'officer_id' => $officer->id,
            'violation_type' => $request->violation_type,
            'officer_notes' => $request->officer_notes,
            'case_id' => $caseID,
            'status' => 'pending_ai',
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


        return redirect()->route('officer.violations.preview', $violation->id);
    }



    public function preview($id)
    {

        $violation = Violation::with(['trader','officer'])->findOrFail($id);

        $letterData = json_decode($violation->ai_raw_message,true);

        $currentStall = $this->getCurrentStallNumberForTrader($violation->trader->id);

        return view('officer.violations.preview', compact('violation','letterData','currentStall'));
    }



    public function approve($id)
    {
        $violation = Violation::findOrFail($id);

        $violation->update(['status'=>'approved']);

        return back()->with('success','Violation letter approved.');
    }



    public function sendEmail(Request $request,$id)
    {

        $violation = Violation::with(['trader','officer'])->findOrFail($id);

        $recipientEmail = $this->getTraderEmail($violation->trader);

        if(!$recipientEmail){
            return back()->with('error','Trader has no valid email.');
        }

        try{

            Mail::to($recipientEmail)
                ->send(new ViolationNoticeMail(
                    $violation,
                    $violation->final_letter,
                    'Official Market Violation Notice'
                ));

            $violation->update(['status'=>'sent']);

            return back()->with('success','Violation notice sent.');

        }catch(\Exception $e){

            Log::error('Violation email error',['error'=>$e->getMessage()]);

            return back()->with('error',$e->getMessage());

        }

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