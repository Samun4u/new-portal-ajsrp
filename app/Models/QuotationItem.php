<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['quotation_id', 'service_id', 'service_name', 'price', 'quantity', 'duration',];

    public function quotation(){
        return $this->belongsTo(Quotation::class,'quotation_id', 'id');
    }
}
