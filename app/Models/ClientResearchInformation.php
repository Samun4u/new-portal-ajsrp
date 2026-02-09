<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientResearchInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "research_interest",
        "orcid_id",
        "google_scholar_profile",
    ];
}
