<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOrderSubmissionRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_order_submission_id',
        'client_order_id',
        'author_id',
        'version',
        'manuscript_file_id',
        'response_file_id',
        'response_summary',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function submission()
    {
        return $this->belongsTo(ClientOrderSubmission::class, 'client_order_submission_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function manuscriptFile()
    {
        return $this->belongsTo(FileManager::class, 'manuscript_file_id');
    }

    public function responseFile()
    {
        return $this->belongsTo(FileManager::class, 'response_file_id');
    }

    public function attachments()
    {
        return $this->hasMany(ClientOrderSubmissionRevisionFile::class, 'revision_id');
    }
}

