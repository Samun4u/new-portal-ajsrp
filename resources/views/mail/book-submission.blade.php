<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Book Submission</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 100%; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f8fafc; padding: 30px; border-radius: 0 0 10px 10px; }
        .detail-box { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #3b82f6; }
        .label { font-weight: bold; color: #0f172a; }
        .footer { text-align: center; margin-top: 30px; padding: 20px; color: #64748b; font-size: 14px; }
        .attachments { background: #e8f4fd; padding: 15px; border-radius: 6px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Book Submission</h1>
            <p>A new book has been submitted for review</p>
        </div>
        
        <div class="content">
            <div class="detail-box">
                <h2>Book Information</h2>
                <p><span class="label">Title:</span> {{ $submission->title }}</p>
                <p><span class="label">Author:</span> {{ $submission->author }}</p>
                <p><span class="label">Genre:</span> {{ $submission->genre ?? 'Not specified' }}</p>
                <p><span class="label">Language:</span> {{ $submission->language ?? 'Not specified' }}</p>
                <p><span class="label">Publication Year:</span> {{ $submission->publication_year ?? 'Not specified' }}</p>
                <p><span class="label">Contact Email:</span> {{ $submission->email }}</p>
                
                @if($submission->summary)
                <p><span class="label">Summary:</span><br>{{ $submission->summary }}</p>
                @endif
                
                <p><span class="label">Public Display Authorized:</span> {{ $submission->allow_public ? 'Yes' : 'No' }}</p>
            </div>
            
            <div class="detail-box">
                <h2>Submitter Information</h2>
                <p><span class="label">Name:</span> {{ $user->name }}</p>
                <p><span class="label">Email:</span> {{ $user->email }}</p>
                <p><span class="label">Submission Date:</span> {{ $submission->created_at->format('F j, Y \a\t g:i A') }}</p>
            </div>
            
            <div class="detail-box">
                <h2>Attachments</h2>
                <div class="attachments">
                    <p><strong>📄 Book Manuscript:</strong> Attached to this email</p>
                    @if($submission->cover_image_file_id)
                    <p><strong>🖼️ Cover Image:</strong> Attached to this email</p>
                    @else
                    <p><strong>🖼️ Cover Image:</strong> No cover image provided</p>
                    @endif
                </div>
                
                <p style="margin-top: 15px; font-size: 14px; color: #64748b;">
                    <strong>Note:</strong> The book manuscript and cover image (if provided) are attached to this email as separate files.
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from the Book Submission System.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>