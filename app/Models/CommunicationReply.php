<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunicationReply extends Model
{
      protected $fillable = [

        'communication_id',
        'user_id',
        'reply_from',
        'message',
        'attachment',
        'is_read',
    ];

    public function communication()
    {
        return $this->belongsTo(
            Communication::class
        );
    }
      public function user()
    {
        return $this->belongsTo(User::class);
    }
}
