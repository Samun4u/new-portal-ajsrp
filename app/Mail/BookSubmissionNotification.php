<?php

namespace App\Mail;

use App\Models\BookSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BookSubmissionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $bookSubmission;
    public $user;

    public function __construct(BookSubmission $bookSubmission)
    {
        $this->bookSubmission = $bookSubmission;
        $this->user = $bookSubmission->client;
    }

    public function build()
    {
        $email = $this->subject('New Book Submission - ' . $this->bookSubmission->title)
                    ->view('mail.book-submission')
                    ->with([
                        'submission' => $this->bookSubmission,
                        'user' => $this->user,
                    ]);

         // Attach book file
        if ($this->bookSubmission->book_file_id) {
            $bookFilePath = getFileData($this->bookSubmission->book_file_id, 'path');
            Log::info('Book file path: ' . $bookFilePath);
            
            try {
                // Get file content from storage
                $bookFileContent = Storage::disk('public')->get($bookFilePath);
                $bookFileName = $this->bookSubmission->title . '_manuscript.' . pathinfo($bookFilePath, PATHINFO_EXTENSION);
                
                // Clean filename
                $bookFileName = $this->cleanFileName($bookFileName);
                
                $email->attachData($bookFileContent, $bookFileName, [
                    'mime' => $this->getMimeType(pathinfo($bookFilePath, PATHINFO_EXTENSION)),
                ]);
                
                Log::info('Book file attached successfully: ' . $bookFileName);
                
            } catch (\Exception $e) {
                Log::error('Failed to attach book file: ' . $e->getMessage());
            }
        }

         // Attach cover image if exists
        if ($this->bookSubmission->cover_image_file_id) {
            $coverFilePath = getFileData($this->bookSubmission->cover_image_file_id, 'path');
            Log::info('Cover file path: ' . $coverFilePath);
            
            try {
                // Get file content from storage
                $coverFileContent = Storage::disk('public')->get($coverFilePath);
                $coverFileName = $this->bookSubmission->title . '_cover.' . pathinfo($coverFilePath, PATHINFO_EXTENSION);
                
                // Clean filename
                $coverFileName = $this->cleanFileName($coverFileName);
                
                $email->attachData($coverFileContent, $coverFileName, [
                    'mime' => $this->getMimeType(pathinfo($coverFilePath, PATHINFO_EXTENSION)),
                ]);
                
                Log::info('Cover file attached successfully: ' . $coverFileName);
                
            } catch (\Exception $e) {
                Log::error('Failed to attach cover file: ' . $e->getMessage());
            }
        }

        return $email;
    }

    /**
     * Get MIME type based on file extension
     */
    private function getMimeType($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'epub' => 'application/epub+zip',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'doc' => 'application/msword',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

     /**
     * Clean filename for safe attachment
     */
    private function cleanFileName($filename)
    {
        // Remove or replace special characters
        $cleanName = preg_replace('/[^\w\s.-]/', '', $filename);
        // Replace spaces with underscores
        $cleanName = str_replace(' ', '_', $cleanName);
        // Limit length
        return substr($cleanName, 0, 100);
    }
}