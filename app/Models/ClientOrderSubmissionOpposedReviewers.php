<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOrderSubmissionOpposedReviewers extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_order_submission_id',
        'first_name',
        'last_name',
        'email',
        'affiliation'
    ];
}
