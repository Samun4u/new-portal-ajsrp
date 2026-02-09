<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CustomEmailNotify extends Mailable
{
    use Queueable, SerializesModels;

    protected $typeData;
    protected $template;
    protected $userData;
    protected $link;
    protected $type;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($type, $typeData, $template, $userData = null, $link = null)
    {
        $this->type = $type;
        $this->template = $template;
        $this->typeData = $typeData;
        $this->userData = $userData;
        $this->link = $link;
    }


    public function build()
    {
        $view = '';
        if ($this->type == 'ticket') {
            $subject = getCustomEmailTemplate($this->type, $this->template, 'subject', $this->link, $this->typeData, '');
            $view = 'mail.custom-ticket-email-notify';
        }else if ($this->type == 'ebm-application') {
            $subject = getCustomEmailTemplate($this->type, $this->template, 'subject', $this->link, $this->typeData, '');
            $view = 'mail.custom-ebm-application-email-notify';
        }else if ($this->type == 'reviewer-application') {
            $subject = getCustomEmailTemplate($this->type, $this->template, 'subject', $this->link, $this->typeData, '');
            $view = 'mail.custom-reviewer-application-email-notify';
        }else if ($this->type == 'quotation' || $this->type == 'invoice' || $this->type == 'reset-password') {
            $subject = getCustomEmailTemplate($this->type, $this->template, 'subject',$this->link, '', '');
            $view = 'mail.custom-email-notify';
        }else if($this->type == 'submission-order'){
            // Detect language from client_order_submission
            $language = 'en';
            if (isset($this->typeData['client_order_submission'])) {
                $submission = $this->typeData['client_order_submission'];
                // Check if there's a language field or detect from research
                if (isset($submission['language'])) {
                    $language = $submission['language'];
                } elseif ($submission->research && $submission->research->language) {
                    $language = $submission->research->language;
                }
            }

            $subject = $language === 'ar'
                ? "إشعار تقديم طلبك [{$this->typeData['order']->order_id}]"
                : "Submission Notification of Your Order [{$this->typeData['order']->order_id}]";
            $view = 'mail.custom-order-submission-notify-bilingual';
        }else if($this->type == 'common'){
            $subject = getCustomEmailTemplate($this->type, $this->template, 'subject', $this->link, $this->typeData, '');
            $view = 'mail.custom-email-notify';
        }
        else if($this->type == 'article-accepted-for-publication'){
            $subject = getCustomEmailTemplate($this->type, $this->template, 'subject', $this->link, $this->typeData, '');
            $view = 'mail.custom-email-notify';
        }else if($this->type == 'abstract-order'){
            $subject = getCustomEmailTemplate($this->type, $this->template, 'subject', '', $this->typeData, '');
            $view = 'mail.custom-email-notify';
        }
        else{
            $subject = getCustomEmailTemplate($this->type, $this->template, 'subject', $this->link, '', '');
            $view = 'mail.custom-email-notify';
        }

        // Prepare additional data for submission emails
        $additionalData = [];
        if ($this->type == 'submission-order') {
            $submission = $this->typeData['client_order_submission'];
            $journal = $submission->journal ?? null;

            // Detect language
            $language = 'en';
            if (isset($submission->language)) {
                $language = $submission->language;
            } elseif ($submission->research && $submission->research->language) {
                $language = $submission->research->language;
            }

            // Get correct journal title and site
            $journalTitle = $journal ? $journal->title : 'N/A';
            if ($language === 'ar' && $journal && !empty($journal->arabic_title)) {
                $journalTitle = $journal->arabic_title;
            }

            // Clean journal site URL (remove leading slashes, ensure proper format)
            $journalSite = $journal->website ?? null;
            if ($journalSite) {
                $journalSite = ltrim($journalSite, '/');
                if (!preg_match('/^https?:\/\//', $journalSite)) {
                    $journalSite = 'https://' . $journalSite;
                }
            }

            $additionalData = [
                'isArabic' => $language === 'ar',
                'journalTitle' => $journalTitle,
                'journalSite' => $journalSite,
            ];
        }

        return $this->from(getOption('MAIL_FROM_ADDRESS'), getOption('app_name'))
            ->subject($subject)
            ->markdown($view)
            ->with(array_merge([
                'subject' => $subject,
                'type' => $this->type,
                'typeData' => $this->typeData,
                'template' => $this->template,
                'userData' => $this->userData,
                'link' => $this->link,
            ], $additionalData));
    }
}
