<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderFormService extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'tenant_id',
        'order_form_id',
        'service_id',
        'price',
        'discount',
        'quantity',
    ];
}
