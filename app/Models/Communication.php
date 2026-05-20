<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
   protected $fillable = [
        'user_id',
        'communication_type',
        'priority_level',
        'reply_needed',
        'subject',
        'content',
        'status',
           'is_replied',

        'is_viewed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(CommunicationAttachment::class);
    }
    public function replies()
{
    return $this->hasMany(
        CommunicationReply::class
    )->latest();
}
}
