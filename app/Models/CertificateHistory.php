<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_order_submission_id',
        'file_id',
        'journal_name',
        'volume',
        'issue',
        'acceptance_date',
        'publication_date',
        'editor_in_chief',
        'custom_data',
        'is_active',
        'issued_by',
        'issued_at',
    ];

    protected $casts = [
        'acceptance_date' => 'date',
        'publication_date' => 'date',
        'issued_at' => 'datetime',
        'is_active' => 'boolean',
        'custom_data' => 'array',
    ];

    public function submission()
    {
        return $this->belongsTo(ClientOrderSubmission::class, 'client_order_submission_id');
    }

    public function file()
    {
        return $this->belongsTo(FileManager::class, 'file_id');
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
