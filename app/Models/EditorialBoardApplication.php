<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditorialBoardApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'full_name',
        'email',
        'phone',
        'country',
        'linkedin',
        'degree',
        'specialization',
        'title',
        'institution',
        'experience',
        'publications',
        'supporting_doc_file_id',
        'editorial_board_exp',
        'editorial_details',
        'peer_reviewer_exp',
        'reviewer_details',
        'interests',
        'other_interest',
        'purpose',
        'cv_file_id',
        'photo_file_id',
        'acknowledgment'
    ];

    protected $casts = [
        'interests' => 'array',
        'editorial_board_exp' => 'boolean',
        'peer_reviewer_exp' => 'boolean',
        'acknowledgment' => 'boolean'
    ];
}
