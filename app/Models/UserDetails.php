<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
   protected $fillable = [
        'user_id',
        'aadhar_number',
        'pan_number',
        'account_holder_name',
        'bank_name',
        'branch_name',
        'account_number',
        'ifsc_code',
         'upi',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
