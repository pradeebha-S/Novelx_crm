<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
   protected $fillable = [
        'category',
        'candidate_name',
        'technology',
        'work_status',
        'experience',
        'resume',
        'notice_period',
        'current_salary',
        'expected_salary',
        'phone_number',
        'alternate_phone_number',
        'email',
        'state',
        'city',
        'ready_to_reallocate',
        'team_management',
        'client_management',
    ];
}
