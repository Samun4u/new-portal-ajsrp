<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'title',
        'author',
        'genre',
        'language',
        'publication_year',
        'email',
        'summary',
        'book_file_id',
        'cover_image_file_id',
        'allow_public',
        'status',
    ];

    protected $casts = [
        'allow_public' => 'boolean',
        'publication_year' => 'integer',
    ];
    
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}