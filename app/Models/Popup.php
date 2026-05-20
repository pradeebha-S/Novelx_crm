<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'count',
        'popup_status',
        'done_status'
    ];
}
