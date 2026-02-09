<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderTask extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'client_order_id',
        'taskId',
        'task_name',
        'description',
        'start_date',
        'end_date',
        'progress',
        'priority',
        'client_access',
        'created_by',
        'last_reply_id',
        'last_reply_by',
        'last_reply_time',
        'status'
    ];

    public function conversations()
    {
        return $this->hasMany(OrderTaskConversation::class);
    }

    public function assignees()
    {
        return $this->hasMany(OrderTaskAssignee::class);
    }

    public function attachments()
    {
        return $this->hasMany(OrderTaskAttachment::class);
    }

    public function order()
    {
        return $this->belongsTo(ClientOrder::class, 'client_order_id');
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'label_order_task');
    }
}
