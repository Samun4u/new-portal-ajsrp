<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalMetadata extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_order_submission_id',
        'final_title',
        'short_title',
        'final_abstract',
        'final_keywords',
        'funding_statement',
        'conflict_statement',
        'acknowledgements',
        'notes_for_layout',
        'author_confirmed',
    ];

    protected $casts = [
        'author_confirmed' => 'boolean',
    ];

    public function submission()
    {
        return $this->belongsTo(ClientOrderSubmission::class, 'client_order_submission_id');
    }
}
