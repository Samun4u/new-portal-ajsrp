<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplymentMaterialFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_order_submission_id',
        'file_id',
    ];
}
