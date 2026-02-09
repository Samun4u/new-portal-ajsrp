<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ProofFile;

class ClientOrderSubmission extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_order_id',
        'journal_id',
        'article_type_id',
        'article_title',
        'article_abstract',
        'article_keywords',
        'full_article_file',
        'covert_letter_file',
        'has_author',
        'has_conflict_of_interest',
        'conflict_details',
        'has_funding',
        'has_data_availability_statement',
        'data_availability_statement',
        'data_availability_url',
        'add_reviewers',
        'suggested_reviewers',
        'has_opposed_reviewers',
        'final_submit_success',
        'approval_status',
        'language',
        // OJS workflow fields
        'acceptance_date',
        'decision_by_user_id',
        'metadata_status',
        'workflow_stage',
        'final_manuscript_file_id',
        'acceptance_certificate_file_id',
        'issue_id',
        'scheduled_publication_date',
        'publication_date',
        'ojs_article_id',
        'ojs_article_url',
        'certificate_sent_at',
    ];

    protected $casts = [
        'acceptance_date' => 'datetime',
        'scheduled_publication_date' => 'date',
        'publication_date' => 'date',
        'certificate_sent_at' => 'datetime',
    ];

    public function client_order()
    {
        return $this->belongsTo(ClientOrder::class, 'client_order_id', 'order_id');
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'journal_id');
    }

    public function article_type()
    {
        return $this->belongsTo(ArticleType::class, 'article_type_id');
    }

    public function supplyment_material_files()
    {
        return $this->hasMany(SupplymentMaterialFile::class, 'client_order_submission_id');
    }

    public function authors()
    {
        return $this->hasMany(AuthorDetails::class, 'client_order_submission_id');
    }

    public function authors_roles()
    {
        return $this->hasMany(AuthorContributorRole::class, 'client_order_submission_id');
    }

    public function declarations()
    {
        return $this->hasMany(ClientOrderSubmissionDeclarations::class, 'client_order_submission_id');
    }

    public function funders()
    {
        return $this->hasMany(ClientOrderSubmissionFundingDetails::class, 'client_order_submission_id');
    }

    public function __suggested_reviewers()
    {
        return $this->hasMany(ClientOrderSubmissionReviewers::class, 'client_order_submission_id');
    }

    public function __opposed_reviewers()
    {
        return $this->hasMany(ClientOrderSubmissionOpposedReviewers::class, 'client_order_submission_id');
    }

    public function research()
    {
        return $this->hasOne(\App\Models\Research::class, 'client_order_id', 'client_order_id');
    }

    public function revisions()
    {
        return $this->hasMany(ClientOrderSubmissionRevision::class, 'client_order_submission_id');
    }

    public function latestRevision()
    {
        return $this->hasOne(ClientOrderSubmissionRevision::class, 'client_order_submission_id')->latestOfMany();
    }

    public function workflowHistories()
    {
        return $this->hasMany(ClientOrderSubmissionWorkflowHistory::class, 'client_order_submission_id')
            ->orderByDesc('created_at');
    }

    public function issue()
    {
        return $this->belongsTo(Issue::class, 'issue_id');
    }

    public function decisionBy()
    {
        return $this->belongsTo(User::class, 'decision_by_user_id');
    }

    public function finalMetadata()
    {
        return $this->hasOne(FinalMetadata::class, 'client_order_submission_id');
    }

    public function proofFiles()
    {
        return $this->hasMany(ProofFile::class, 'client_order_submission_id');
    }

    public function galleyFiles()
    {
        return $this->hasMany(GalleyFile::class, 'client_order_submission_id');
    }

    public function certificateHistory()
    {
        return $this->hasMany(CertificateHistory::class, 'client_order_submission_id');
    }

    public function activeCertificate()
    {
        return $this->hasOne(CertificateHistory::class, 'client_order_submission_id')
            ->where('is_active', true);
    }
}
