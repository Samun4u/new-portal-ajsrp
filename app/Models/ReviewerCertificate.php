<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewerCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_order_id',
        'reviewer_id',
        'title',
        'affiliations',
        'paper_title',
        'journal_name',
        'language',
        'chief_editor_name',
        'chief_editor_name_ar',
        'signature_image',
        'logo_image',
        'status',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id')->withTrashed();
    }

    public function client_order()
    {
        return $this->belongsTo(ClientOrder::class, 'client_order_id', 'order_id');
    }
}
