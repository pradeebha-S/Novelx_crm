<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationAttachment extends Model
{
     protected $fillable = [
        'communication_id',
        'file_name',
        'file_path',
    ];

    public function communication()
    {
        return $this->belongsTo(Communication::class);
    }
}
