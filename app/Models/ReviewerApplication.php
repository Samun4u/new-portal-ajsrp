<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewerApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'full_name',
        'email',
        'institution',
        'country',
        'orcid',
        'profile_links',
        'qualification',
        'field_of_study',
        'position',
        'experience_years',
        'subject_areas',
        'keywords',
        'review_experience',
        'cv_file_id',
        'photo_file_id',
        'agreement',
        'consent_acknowledgment',
        'status',
        'approved_user_id',
        'approved_at',
        'approved_by',
        'rejection_reason',
    ];

    protected $casts = [
        'profile_links' => 'array',
        'subject_areas' => 'array',
        'keywords' => 'array',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the CV file.
     */
    public function cvFile()
    {
        return $this->belongsTo(\App\Models\FileManager::class, 'cv_file_id');
    }

    /**
     * Get the photo file.
     */
    public function photoFile()
    {
        return $this->belongsTo(\App\Models\FileManager::class, 'photo_file_id');
    }

    /**
     * Get the approved user account.
     */
    public function approvedUser()
    {
        return $this->belongsTo(User::class, 'approved_user_id');
    }

    /**
     * Get the admin who approved/rejected.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
