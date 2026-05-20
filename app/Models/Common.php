<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Common extends Model
{
        protected $fillable = ['title','description','remark','is_replied'];
}
