<?php

namespace App\Mail;

use App\Models\ClientOrder;
use App\Models\ClientOrderSubmission;
use App\Models\Reviews;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewerInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public ClientOrder $order;
    public ClientOrderSubmission $submission;
    public Reviews $review;
    public User $reviewer;
    public string $invitationLink;

    /**
     * Create a new message instance.
     */
    public function __construct(ClientOrder $order, ClientOrderSubmission $submission, Reviews $review, User $reviewer, string $invitationLink)
    {
        $this->order = $order;
        $this->submission = $submission;
        $this->review = $review;
        $this->reviewer = $reviewer;
        $this->invitationLink = $invitationLink;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        $subject = __('Invitation to review ":title"', ['title' => $this->submission->article_title ?? __('a manuscript')]);

        return $this->subject($subject)
            ->view('mail.reviewer-invitation');
    }
}

