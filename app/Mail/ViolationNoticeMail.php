<?php

namespace App\Mail;

use App\Models\Violation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class ViolationNoticeMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Violation */
    public $violation;

    /** @var string */
    public $finalLetter;

    /** @var string */
    public $subjectLine;

    /**
     * Create a new message instance.
     */
    public function __construct(Violation $violation, string $finalLetter, string $subjectLine)
    {
        $this->violation = $violation;
        $this->finalLetter = $finalLetter;
        $this->subjectLine = $subjectLine;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $letterData = json_decode($this->violation->ai_raw_message, true) ?? [];

        // Generate the PDF version of the same letter on-the-fly
        $pdf = Pdf::loadView('officer.violations.pdf', [
            'violation'  => $this->violation,
            'letterData' => $letterData,
        ])->setPaper('a4', 'portrait');

        return $this->subject($this->subjectLine)
                    ->view('emails.violation_notice_html')
                    ->text('emails.violation_notice_plain')
                    ->attachData($pdf->output(), "Violation_Notice_{$this->violation->case_id}.pdf", [
                        'mime' => 'application/pdf',
                    ])
                    ->with([
                        'violation'   => $this->violation,
                        'finalLetter' => $this->finalLetter,
                    ]);
    }
}
