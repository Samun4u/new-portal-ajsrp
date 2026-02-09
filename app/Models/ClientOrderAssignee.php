<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientOrderAssignee extends Model
{
    use SoftDeletes;

    protected  $fillable = [
        'order_id',
        'assigned_to',
        'assigned_by',
        'is_active',
        'due_at',
        'invited_at',
        'responded_at',
        'invitation_status',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'due_at' => 'datetime',
        'invited_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(\App\Models\ClientOrder::class, 'order_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
