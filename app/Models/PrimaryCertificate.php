<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimaryCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_order_id',
        'author_names',
        'author_affiliations',
        'paper_title',
        'journal_name',
        'certificate_sent',
        'sent_at',
        'research_id',
    ];

    protected $casts = [
        'certificate_sent' => 'boolean',
        'sent_at' => 'datetime',
    ];

    public function client_order()
    {
        return $this->belongsTo(ClientOrder::class, 'client_order_id', 'order_id');
    }

    public function research()
    {
        return $this->belongsTo(Research::class);
    }
}
