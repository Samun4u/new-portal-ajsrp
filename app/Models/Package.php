<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'number_of_client',
        'number_of_order',
        'others',
        'monthly_price',
        'yearly_price',
        'status',
        'is_default',
        'is_trail',
    ];
}
