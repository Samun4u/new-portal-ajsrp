<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTaskAssignee extends Model
{
    protected $fillable = [
        'tenant_id',
        'order_task_id',
        'assign_to',
        'assign_by',
    ];

    public function orderTask()
    {
        return $this->belongsTo(OrderTask::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'assign_to');
    }
}
