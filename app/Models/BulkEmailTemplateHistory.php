<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BulkEmailTemplateHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'to', 'bcc', 'subject', 'body', 'status', 
        'api_response', 'admin_id'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
