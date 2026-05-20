<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BugLogs extends Model
{
     protected $fillable = [
        'bug_id',
        'user_id',
        'action',
        'status',
        'comment'
    ];

    public function bug()
    {
        return $this->belongsTo(Bugs::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    }
