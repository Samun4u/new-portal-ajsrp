<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_id',
        'volume',
        'number',
        'year',
        'title',
        'status',
        'planned_publication_date',
        'publication_date',
        'ojs_issue_id',
    ];

    protected $casts = [
        'planned_publication_date' => 'date',
        'publication_date' => 'date',
    ];

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    public function submissions()
    {
        return $this->hasMany(ClientOrderSubmission::class, 'issue_id');
    }

    public function submissionsCount()
    {
        return $this->submissions()->count();
    }
}
