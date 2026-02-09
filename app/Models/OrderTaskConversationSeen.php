<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTaskConversationSeen extends Model
{
    protected $fillable = [
        'order_task_id',
        'order_task_conversation_id',
        'user_id'
    ];

    public function conversation()
    {
        return $this->belongsTo(OrderTaskConversation::class, 'order_task_conversation_id');
    }
}
