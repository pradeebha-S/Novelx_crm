<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_name',

    ];
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}
