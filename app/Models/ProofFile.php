<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\FileManager;
use App\Models\User;

class ProofFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_order_submission_id',
        'file_id',
        'version',
        'notes',
        'status',
        'corrections_requested',
        'uploaded_by',
        'reviewed_by',
        'reviewed_at',
        // Reviewer assignment & workflow
        'review_type',            // author, editor, reviewer
        'assigned_reviewer_id',
        'review_notes',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function submission()
    {
        return $this->belongsTo(ClientOrderSubmission::class, 'client_order_submission_id');
    }

    public function file()
    {
        return $this->belongsTo(FileManager::class, 'file_id');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function assignedReviewer()
    {
        return $this->belongsTo(User::class, 'assigned_reviewer_id');
    }
}
