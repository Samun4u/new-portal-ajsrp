<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_order_submission_id',
        'first_name',
        'last_name',
        'email',
        'orcid',
        'nationality',
        'whatsapp_number',
        'date_of_birth',
        'affiliation',
        'corresponding_author',
    ];

    protected $casts = [
        'affiliation' => 'array',
        'corresponding_author' => 'boolean',
        'date_of_birth' => 'date',
    ];
}
