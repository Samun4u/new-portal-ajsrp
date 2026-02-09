<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['client_name', 'email', 'description', 'discount', 'expire_date', 'address'];

    public function quotation_items(){
        return $this->hasMany(QuotationItem::class);
    }
}
