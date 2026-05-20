<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'project_id',
        'document_name',
        'file',
        'content',      
        'pdf_file',     
        'status',      
        'is_emailed',   
        'emailed_at'    
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
