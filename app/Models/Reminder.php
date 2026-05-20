<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model

{

    protected $fillable = ['title','remind_to','description','reminder_type','date','user_id',

    'added_by',

    'is_active'];

}

