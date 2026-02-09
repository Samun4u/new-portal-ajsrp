<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_order_submission_id',
        'client_order_id',
        'reviewer_id',
        'version',
        'round',
        'previous_version_id',
        'comments',
        'admin_comments',
        'admin_status',
        'status',
        'overall_recommendation',
        'rating_originality',
        'rating_methodology',
        'rating_results',
        'rating_clarity',
        'rating_significance',
        // Additional rating criteria
        'rating_literature',
        'rating_data',
        'rating_ethics',
        'comment_strengths',
        'comment_weaknesses',
        'comment_for_authors',
        // Additional structured comments
        'questions_for_authors',
        'minor_issues',
        'major_issues',
        'comment_for_editor',
        'specific_checks',
        'progress',
        'quality_rating',
        'submitted_at',
        'invitation_status',
        'conflict_declared',
        'conflict_details',
        'invited_at',
        'responded_at',
        'invitation_metadata',
    ];

    protected $casts = [
        'specific_checks' => 'array',
        'submitted_at' => 'datetime',
        'progress' => 'integer',
        'quality_rating' => 'float',
        'invitation_metadata' => 'array',
        'invited_at' => 'datetime',
        'responded_at' => 'datetime',
        'conflict_declared' => 'boolean',
    ];

    public function client_order_submission()
    {
        return $this->belongsTo(ClientOrderSubmission::class, 'client_order_submission_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function previousVersion()
    {
        return $this->belongsTo(Reviews::class, 'previous_version_id');
    }

    public function nextVersions()
    {
        return $this->hasMany(Reviews::class, 'previous_version_id');
    }

    /**
     * Get all versions for this review (same submission and reviewer)
     */
    public function allVersions()
    {
        return Reviews::where('client_order_submission_id', $this->client_order_submission_id)
            ->where('reviewer_id', $this->reviewer_id)
            ->orderByRaw('COALESCE(version, 1)')
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Get the latest version number for this reviewer and submission
     */
    public static function getNextVersionNumber($submissionId, $reviewerId)
    {
        $latest = self::where('client_order_submission_id', $submissionId)
            ->where('reviewer_id', $reviewerId)
            ->max('version');

        return ($latest ?? 0) + 1;
    }

    /**
     * Get the current round number for this submission
     * (round is shared across all reviewers for a submission)
     */
    public static function getCurrentRound($submissionId)
    {
        $latest = self::where('client_order_submission_id', $submissionId)
            ->max('round');

        return $latest ?? 1;
    }

    /**
     * Get all reviews with version history for a submission, grouped by reviewer
     */
    public static function getVersionHistoryForSubmission($submissionId)
    {
        $reviews = self::where('client_order_submission_id', $submissionId)
            ->with('reviewer')
            ->orderBy('reviewer_id')
            ->orderByRaw('COALESCE(round, 1)')
            ->orderByRaw('COALESCE(version, 1)')
            ->orderBy('created_at')
            ->get();

        // Group by reviewer_id - this returns a Collection where each value is also a Collection
        return $reviews->groupBy('reviewer_id');
    }
}
