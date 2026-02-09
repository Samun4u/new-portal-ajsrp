<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoService
{
    protected $apiKey;
    protected $apiUrl;
    protected $senderEmail;
    protected $senderName;

    public function __construct()
    {
        $this->apiKey = config('email.brevo.api_key');
        $this->apiUrl = config('email.brevo.api_url');
        $this->senderEmail = config('email.brevo.sender_email');
        $this->senderName = config('email.brevo.sender_name');
    }

    public function sendEmail($to, $bcc, $subject, $htmlContent)
    {
        // Validate recipient emails
        $toEmails = $this->parseEmails($to);
        if (empty($toEmails)) {
            return ['success' => false, 'error' => 'No valid recipient emails provided'];
        }

        $payload = [
            'sender' => [
                'name' => $this->senderName,
                'email' => $this->senderEmail
            ],
            'to' => $toEmails,
            'subject' => $subject,
            'htmlContent' => $htmlContent
        ];

        // Add BCC if provided
        if (!empty($bcc)) {
            $bccEmails = $this->parseEmails($bcc);
            if (!empty($bccEmails)) {
                $payload['bcc'] = $bccEmails;
            }
        }

        try {
            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->timeout(30)->post($this->apiUrl, $payload);

            $result = $response->json();

            if ($response->successful()) {
                return [
                    'success' => true, 
                    'data' => $result,
                    'message_id' => $result['messageId'] ?? null
                ];
            } else {
                $error = $result['message'] ?? 'Unknown error from Brevo API';
                Log::error('Brevo API Error: ' . $error, ['response' => $result]);
                return ['success' => false, 'error' => $error];
            }

        } catch (\Exception $e) {
            Log::error('Brevo Service Exception: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function parseEmails($emailsString)
    {
        $emails = array_map('trim', explode(',', $emailsString));
        $validEmails = [];

        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $validEmails[] = ['email' => $email];
            }
        }

        return $validEmails;
    }

    public function validateApiKey()
    {
        try {
            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'Accept' => 'application/json'
            ])->get('https://api.brevo.com/v3/account');

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}