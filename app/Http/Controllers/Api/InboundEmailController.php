<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketConversation;
use App\Models\FileManager;
use App\Models\OrderTaskConversation;
use App\Models\SubmissionReviewerNotesConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InboundEmailController extends Controller
{
    /**
     * Handle inbound email webhook from email service provider
     * Supports: SendGrid, Mailgun, AWS SES
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        try {
            // Detect email service provider
            $provider = $this->detectProvider($request);

            // Parse email based on provider
            $emailData = $this->parseEmail($request, $provider);

            if (!$emailData) {
                return response()->json(['error' => 'Unable to parse email'], 400);
            }

            // Extract reference from email
            $reference = $this->extractReference($emailData);

            if (!$reference) {
                Log::warning('Inbound email received without reference', ['from' => $emailData['from']]);
                return response()->json(['message' => 'No reference found'], 200);
            }

            // Route email to appropriate conversation
            $result = $this->routeEmailToConversation($reference, $emailData);

            return response()->json([
                'success' => true,
                'message' => 'Email processed successfully',
                'data' => $result
            ], 200);

        } catch (\Exception $e) {
            Log::error('Inbound email processing error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Detect email service provider from request
     */
    private function detectProvider(Request $request)
    {
        // SendGrid
        if ($request->has('from') && $request->has('to') && $request->has('text')) {
            return 'sendgrid';
        }

        // Mailgun
        if ($request->has('sender') && $request->has('recipient')) {
            return 'mailgun';
        }

        // AWS SES (via SNS)
        if ($request->has('Type') && $request->has('Message')) {
            return 'aws_ses';
        }

        return 'unknown';
    }

    /**
     * Parse email data from different providers
     */
    private function parseEmail(Request $request, $provider)
    {
        switch ($provider) {
            case 'sendgrid':
                return $this->parseSendGridEmail($request);

            case 'mailgun':
                return $this->parseMailgunEmail($request);

            case 'aws_ses':
                return $this->parseAwsSesEmail($request);

            default:
                return null;
        }
    }

    /**
     * Parse SendGrid inbound email
     */
    private function parseSendGridEmail(Request $request)
    {
        $attachments = [];

        // Process attachments
        $attachmentCount = (int) $request->input('attachments', 0);
        for ($i = 1; $i <= $attachmentCount; $i++) {
            if ($request->hasFile("attachment{$i}")) {
                $file = $request->file("attachment{$i}");
                $attachments[] = $this->storeAttachment($file);
            }
        }

        return [
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'subject' => $request->input('subject'),
            'text' => $request->input('text'),
            'html' => $request->input('html'),
            'attachments' => $attachments,
            'message_id' => $request->input('headers')['Message-ID'] ?? null,
        ];
    }

    /**
     * Parse Mailgun inbound email
     */
    private function parseMailgunEmail(Request $request)
    {
        $attachments = [];

        // Process attachments
        if ($request->has('attachment-count')) {
            $count = (int) $request->input('attachment-count');
            for ($i = 1; $i <= $count; $i++) {
                if ($request->hasFile("attachment-{$i}")) {
                    $file = $request->file("attachment-{$i}");
                    $attachments[] = $this->storeAttachment($file);
                }
            }
        }

        return [
            'from' => $request->input('sender'),
            'to' => $request->input('recipient'),
            'subject' => $request->input('subject'),
            'text' => $request->input('body-plain'),
            'html' => $request->input('body-html'),
            'attachments' => $attachments,
            'message_id' => $request->input('Message-Id'),
        ];
    }

    /**
     * Parse AWS SES email (via SNS)
     */
    private function parseAwsSesEmail(Request $request)
    {
        // AWS SES sends email via SNS, need to parse JSON message
        $message = json_decode($request->input('Message'), true);

        if (!$message) {
            return null;
        }

        // Extract email content from S3 or inline
        // This is simplified - actual implementation depends on SES configuration

        return [
            'from' => $message['mail']['source'] ?? null,
            'to' => $message['mail']['destination'][0] ?? null,
            'subject' => $message['mail']['commonHeaders']['subject'] ?? null,
            'text' => $message['content'] ?? null,
            'html' => null,
            'attachments' => [],
            'message_id' => $message['mail']['messageId'] ?? null,
        ];
    }

    /**
     * Store email attachment
     */
    private function storeAttachment($file)
    {
        $fileManager = new FileManager();
        $uploadedFile = $fileManager->upload('email_attachments', $file);

        return [
            'id' => $uploadedFile->id,
            'name' => $file->getClientOriginalName(),
            'path' => $uploadedFile->path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ];
    }

    /**
     * Extract reference ID from email (ticket ID, task ID, etc.)
     * Usually embedded in subject line or reply-to address
     */
    private function extractReference($emailData)
    {
        $subject = $emailData['subject'] ?? '';
        $to = $emailData['to'] ?? '';

        // Try to extract from subject line
        // Format: "Re: Ticket #123 - ..."
        if (preg_match('/Ticket\s*#(\d+)/i', $subject, $matches)) {
            return ['type' => 'ticket', 'id' => $matches[1]];
        }

        // Format: "Re: Task #456 - ..."
        if (preg_match('/Task\s*#(\d+)/i', $subject, $matches)) {
            return ['type' => 'task', 'id' => $matches[1]];
        }

        // Format: "Re: Submission Review #789 - ..."
        if (preg_match('/Submission\s*Review\s*#(\d+)/i', $subject, $matches)) {
            return ['type' => 'submission_review', 'id' => $matches[1]];
        }

        // Try to extract from reply-to email address
        // Format: ticket+123@portal.ajsrp.com
        if (preg_match('/ticket\+(\d+)@/i', $to, $matches)) {
            return ['type' => 'ticket', 'id' => $matches[1]];
        }

        if (preg_match('/task\+(\d+)@/i', $to, $matches)) {
            return ['type' => 'task', 'id' => $matches[1]];
        }

        return null;
    }

    /**
     * Route email to appropriate conversation system
     */
    private function routeEmailToConversation($reference, $emailData)
    {
        switch ($reference['type']) {
            case 'ticket':
                return $this->addToTicketConversation($reference['id'], $emailData);

            case 'task':
                return $this->addToTaskConversation($reference['id'], $emailData);

            case 'submission_review':
                return $this->addToSubmissionReviewConversation($reference['id'], $emailData);

            default:
                Log::warning('Unknown reference type', $reference);
                return null;
        }
    }

    /**
     * Add email reply to ticket conversation
     */
    private function addToTicketConversation($ticketId, $emailData)
    {
        $ticket = Ticket::find($ticketId);

        if (!$ticket) {
            Log::warning("Ticket not found: {$ticketId}");
            return null;
        }

        // Create conversation entry
        $conversation = new TicketConversation();
        $conversation->ticket_id = $ticket->id;
        $conversation->user_id = $ticket->client_id; // Reply from client
        $conversation->message = $emailData['text'];
        $conversation->message_from = 'client';
        $conversation->save();

        // Attach files if any
        foreach ($emailData['attachments'] as $attachment) {
            // Logic to attach files to conversation
            // Depends on your attachment storage structure
        }

        // Notify admin about new reply
        ticketConversationNotify($ticket->id, $conversation->id);

        return [
            'ticket_id' => $ticket->id,
            'conversation_id' => $conversation->id
        ];
    }

    /**
     * Add email reply to task conversation
     */
    private function addToTaskConversation($taskId, $emailData)
    {
        // Similar implementation for order tasks
        // TODO: Implement based on your task conversation structure

        return ['task_id' => $taskId];
    }

    /**
     * Add email reply to submission reviewer notes conversation
     */
    private function addToSubmissionReviewConversation($reviewId, $emailData)
    {
        // Similar implementation for submission reviews
        // TODO: Implement based on your structure

        return ['review_id' => $reviewId];
    }
}

