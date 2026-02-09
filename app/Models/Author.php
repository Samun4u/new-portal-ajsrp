<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_id',
        'title_ar',
        'title_en',
        'title_value',
        'name_ar',
        'name_en',
        'email',
        'phone',
        'degree_ar',
        'degree_en',
        'degree_value',
        'affiliation_ar',
        'affiliation_en',
        'orcid',
        'is_corresponding'
    ];

    /**
     * Get the research that owns the author.
     */
    public function research(): BelongsTo
    {
        return $this->belongsTo(Research::class);
    }
}