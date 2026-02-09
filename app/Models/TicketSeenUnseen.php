<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSeenUnseen extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'created_by',
        'tenant_id',
        'is_seen',
    ];
}
