<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'arabic_title',
        'slug',
        'website',
        'charges',
        'service_id',
        'status',
        'journal_subject_id',
        // OJS fields
        'short_name',
        'issn_print',
        'issn_online',
        'ojs_context',
        // Certificate settings
        'impact_factor',
        'editor_in_chief',
        'chief_editor_name_ar',
        'managing_editor_name_en',
        'managing_editor_name_ar',
        'signature_path',
        'managing_editor_signature_path',
        'stamp_path',
        'certificate_logo_file_id',
        'certificate_template_settings',
    ];

    protected $casts = [
        'certificate_template_settings' => 'array',
    ];

    public function journal_category()
    {
        return $this->belongsTo(JournalSubject::class, 'journal_subject_id', 'id');
    }

    public function subjects()
    {
        return $this->belongsToMany(JournalSubject::class, 'journal_subject_journal');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    public function certificateLogo()
    {
        return $this->belongsTo(FileManager::class, 'certificate_logo_file_id');
    }
}
