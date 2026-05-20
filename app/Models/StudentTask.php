<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTask extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'topic_id',
        'chapter_id',
        'status'

    ];
    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }

   public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function history()
{
    return $this->hasMany(StudentTaskHistory::class, 'task_id');
}

    
    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'student_id'); // or staff_id if you store staff
    }
}
