<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ClientInvoice extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'client_id',
        'tenant_id',
        'order_id',
        'due_date',
        'payable_amount',
        'payment_id',
        'invoice_id',
        'subscription_id',
        'total_amount',
        'setup_fees',
        'shipping_charge',
        'is_mailed',
        'is_recurring',
        'payment_status',
        'gateway_id',
    ];

    public function client(){
        return $this->belongsTo(User::class,'client_id', 'id');
    }

    public function clientOrderItems(){
        return $this->hasMany(ClientOrderItem::class, 'order_id', 'order_id');
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'gateway_id', 'id');
    }
    public function order()
    {
        return $this->belongsTo(ClientOrder::class, 'order_id', 'id');
    }

}
