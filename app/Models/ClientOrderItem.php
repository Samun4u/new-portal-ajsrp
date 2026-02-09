<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ClientOrderItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'tenant_id',
        'service_id',
        'user_id',
        'client_invoice_id',
        'price',
        'order_id',
        'quantity',
        'discount',
        'sub_total',
        'original_price',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function client_order()
    {
        return $this->belongsTo(ClientOrder::class);
    }

    public function clientInvoice(){
        return $this->belongsTo(ClientInvoice::class);
    }
}
