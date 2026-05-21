<?php

namespace App\Models;
use App\Models\User;

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

    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
}
