<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_order_id',
        'author_names',
        'author_affiliations',
        'paper_title',
        'journal_name',
        'volume',
        'issue',
        'date',
        'certificate_number',
        'language',
        'issn',
        'issn_online',
        'impact_factor',
        'chief_editor',
        'chief_editor_ar',
        'managing_editor',
        'managing_editor_ar',
        'signature_path',
        'managing_editor_signature_path',
        'stamp_path',
        'pdf_path',
        'doi',
    ];

    public function client_order()
    {
        return $this->belongsTo(ClientOrder::class, 'client_order_id', 'order_id');
    }

}
