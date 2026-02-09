<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserActivityLog extends Model
{

    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'tenant_id', 'user_id', 'action', 'ip_address', 'source', 'location'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
