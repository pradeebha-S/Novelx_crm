<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTaskHistory extends Model
{
     protected $fillable = [
        'task_id',
        'chapter_id',
        'course_id',
        'student_id',
        'status',
        'remark',
        'spend_hour'
    ];
public function task()
{
    return $this->belongsTo(StudentTask::class, 'task_id');
}
}
