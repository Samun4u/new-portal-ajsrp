<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Research extends Model
{
    use HasFactory;

    protected $fillable = [
        'arabic_title',
        'english_title',
        'field',
        'other_field',
        'journal',
        'keywords',
        'paper_id_ar',
        'paper_id_en',
        'thesis_answer',
        'manuscript_path',
        'feedback',
        'client_order_id',
        'user_id',
        'approval_status',
        'approved_at',
        'approved_by',
        'admin_notes',
        'language',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the authors for the research.
     */
    public function authors(): HasMany
    {
        return $this->hasMany(Author::class);
    }

    /**
     * Get the corresponding author for the research.
     */
    public function correspondingAuthor()
    {
        return $this->hasOne(Author::class)->where('is_corresponding', true);
    }

    /**
     * Get the user who submitted the research.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who approved the research.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the client order associated with this research.
     */
    public function clientOrder()
    {
        return $this->belongsTo(ClientOrder::class, 'client_order_id', 'order_id');
    }

    /**
     * Get the primary certificate for this research.
     */
    public function primaryCertificate()
    {
        return $this->hasOne(PrimaryCertificate::class);
    }
}
