<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationQualification extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "qualification",
        "field_in_study",
    ];
}
