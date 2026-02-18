<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;

class ResearchConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $research;
    public $isArabic;
    public $emailSubject;
    public $emailContent;

    public function __construct($research, $isArabic = false, $subject = '')
    {
        $this->research = $research;
        $this->isArabic = $isArabic;
        
        // Fetch dynamic content from database
        $template = EmailTemplate::where('slug', 'research-confirmation')->first();
        
        if ($template) {
            // Use language-specific content
            $rawSubject = $isArabic ? ($template->subject_ar ?? $template->subject) : $template->subject;
            $rawContent = $isArabic ? ($template->body_ar ?? $template->body) : $template->body;
            
            // Replace placeholders with actual research data
            $placeholders = $this->getPlaceholders();
            
            foreach ($placeholders as $key => $value) {
                $rawSubject = str_replace($key, $value, $rawSubject);
                $rawContent = str_replace($key, $value, $rawContent);
            }
            
            $this->emailSubject = $rawSubject;
            $this->emailContent = $rawContent;
        } else {
            // Fallback to provided subject
            $this->emailSubject = $subject;
            $this->emailContent = null;
        }
    }
    
    /**
     * Get placeholder values for replacement
     */
    private function getPlaceholders()
    {
        $researchTitle = $this->isArabic && !empty($this->research->arabic_title) 
            ? $this->research->arabic_title 
            : $this->research->english_title;
            
        return [
            '{{research_title}}' => $researchTitle,
            '{{research_field}}' => $this->research->field ?? '',
            '{{journal}}' => $this->research->journal ?? '',
            '{{research_id}}' => $this->research->id ?? '',
        ];
    }

    public function build()
    {
        return $this->from(getOption('MAIL_FROM_ADDRESS'), getOption('app_name'))
            ->subject($this->emailSubject)
            ->view('mail.research-submission-confirmation')
            ->with([
                'research' => $this->research,
                'isArabic' => $this->isArabic,
                'emailContent' => $this->emailContent,
            ]);
    }
}
