<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorContributorRole extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_order_submission_id',
        'author_details_id',
        'contributor_role_id',
    ];

    public function author_details()
    {
        return $this->belongsTo(AuthorDetails::class, 'author_details_id');
    }

    public function contributor_role()
    {
        return $this->belongsTo(ContributorRole::class, 'contributor_role_id');
    }
}
