<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'package_id',
        'order_id',
        'duration_type',
        'payment_id',
        'transaction_id',
        'discount',
        'discount_type',
        'amount',
        'tax_amount',
        'tax_type',
        'subtotal',
        'total',
        'transaction_amount',
        'system_currency',
        'gateway_id',
        'gateway_currency',
        'conversion_rate',
        'payment_status',
        'bank_id',
        'bank_deposit_by',
        'bank_deposit_slip_id',
    ];

    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'gateway_id');
    }
}
