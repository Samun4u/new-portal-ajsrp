<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOrderSubmissionRevisionFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'revision_id',
        'file_id',
        'label',
    ];

    public function revision()
    {
        return $this->belongsTo(ClientOrderSubmissionRevision::class, 'revision_id');
    }

    public function file()
    {
        return $this->belongsTo(FileManager::class, 'file_id');
    }
}

