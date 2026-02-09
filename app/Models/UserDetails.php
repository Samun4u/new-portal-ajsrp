<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'basic_first_name',
        'basic_middle_name',
        'basic_last_name',
        'basic_phone',
        'basic_email',
        'basic_company',
        'billing_address',
        'billing_zip_code',
        'billing_city',
        'billing_state',
        'billing_country',
        'shipping_address',
        'shipping_zip_code',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_method',
        'basic_info',
        'billing_info',
        'shipping_info',
        'payment',
        'revenue',
    ];
}
