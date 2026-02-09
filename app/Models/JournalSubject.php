<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalSubject extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'arabic_name',
        'slug',
        'status'
    ];
    
    public function journals()
    {
        return $this->belongsToMany(Journal::class, 'journal_subject_journal');
    }
}
