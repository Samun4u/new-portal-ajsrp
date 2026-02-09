<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOrderSubmissionWorkflowHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_order_submission_id',
        'event_type',
        'field',
        'from_value',
        'to_value',
        'meta',
        'actor_id',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function submission()
    {
        return $this->belongsTo(ClientOrderSubmission::class, 'client_order_submission_id');
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}



