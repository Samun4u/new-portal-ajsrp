<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResearchRejection extends Mailable
{
    use Queueable, SerializesModels;

    public $research;
    public $isArabic;
    public $emailSubject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($research, $isArabic = false, $subject = '')
    {
        $this->research = $research;
        $this->isArabic = $isArabic;
        $this->emailSubject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(getOption('MAIL_FROM_ADDRESS'), getOption('app_name'))
            ->subject($this->emailSubject)
            ->view('mail.research-rejection')
            ->with([
                'research' => $this->research,
                'isArabic' => $this->isArabic,
            ]);
    }
}

