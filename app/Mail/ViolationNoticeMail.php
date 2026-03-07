<?php

namespace App\Mail;

use App\Models\Violation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
        return $this->subject($this->subjectLine)
                    ->view('emails.violation_notice_html')
                    ->text('emails.violation_notice_plain')
                    ->with([
                        'violation' => $this->violation,
                        'finalLetter' => $this->finalLetter,
                    ]);
    }
}
