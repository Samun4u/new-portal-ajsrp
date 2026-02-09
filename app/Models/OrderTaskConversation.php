<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderTaskConversation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_task_id',
        'user_id',
        'conversation_text',
        'attachment',
        'type'
    ];

    public function orderTask()
    {
        return $this->belongsTo(OrderTask::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seens()
    {
        return $this->hasMany(OrderTaskConversationSeen::class);
    }
}
