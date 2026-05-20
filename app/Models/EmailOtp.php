<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{protected $fillable = [
        'email',
        'otp',
        'expires_at',
        'last_sent_at',
        'attempts',
        'is_verified'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_sent_at' => 'datetime',
        'is_verified' => 'boolean',
    ];  //
}
