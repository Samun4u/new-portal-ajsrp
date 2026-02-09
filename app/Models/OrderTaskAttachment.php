<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTaskAttachment extends Model
{

    protected $fillable = [
        'order_task_id',
        'file',
    ];

    public function orderTask()
    {
        return $this->belongsTo(OrderTask::class);
    }

    public function filemanager()
    {
        return $this->belongsTo(FileManager::class, 'file', 'id');
    }
}
