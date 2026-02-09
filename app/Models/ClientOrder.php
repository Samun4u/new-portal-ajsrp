<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientOrder extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'client_invoice_id',
        'client_id',
        'file',
    ];

    public function client(){
        return $this->belongsTo(User::class,'client_id', 'id');
    }

    public function service(){
        return $this->belongsTo(Service::class,'service_id', 'id');
    }

    public function client_order_items(){
        return $this->hasMany(ClientOrderItem::class,'order_id');
    }
    public function conversation(){
        return $this->hasMany(ClientOrderConversation::class,'order_id');
    }

    public function assignee()
    {
        return $this->hasMany(ClientOrderAssignee::class, 'order_id');
    }
    public function notes()
    {
        return $this->hasMany(ClientOrderNote::class, 'order_id');
    }
    
    public function submission_reviewer_notes()
    {
        return $this->hasMany(SubmissionReviewerNotes::class, 'order_id','order_id');
    }
    
    public function client_order_submission(){
        return $this->hasOne(ClientOrderSubmission::class,'client_order_id','order_id');
    }
}
