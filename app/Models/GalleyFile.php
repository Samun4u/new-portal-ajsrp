<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleyFile extends Model
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
}
