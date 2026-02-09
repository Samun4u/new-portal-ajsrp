<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientProfessionalDetails extends Model
{
    use HasFactory;

    protected $fillable = [
       "user_id",
       "title",
       "title_spacify",
       "highest_degree",
       "diploma_or_certifiction_spacify",
       "address",
       "country",
       "current_institution",
       "professional_bio",
    ];
}
