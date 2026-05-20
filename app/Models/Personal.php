<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{

    protected $fillable = ['title','description','remark','is_replied'];
}
