<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class JournalSignature extends Model
{
    protected $fillable = [
        'journal_abbrev',
        'chief_editor_name',
        'chief_editor_name_ar',
        'managing_editor_name',
        'managing_editor_name_ar',
        'signature_path',
        'managing_editor_signature_path',
        'stamp_path',
    ];

    public function getSignatureUrlAttribute()
    {
        return $this->signature_path ? Storage::url($this->signature_path) : null;
    }

    public function getManagingEditorSignatureUrlAttribute()
    {
        return $this->managing_editor_signature_path ? Storage::url($this->managing_editor_signature_path) : null;
    }

    public function getStampUrlAttribute()
    {
        return $this->stamp_path ? Storage::url($this->stamp_path) : null;
    }
}
