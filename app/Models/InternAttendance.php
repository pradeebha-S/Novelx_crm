<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternAttendance extends Model
{
    protected $fillable = [
        'user_id',
        'check_in',
        'check_out',
        'image',
        'remark',
        'type',
        'late_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
