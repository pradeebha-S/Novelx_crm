<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;



class TaskHistory extends Model

{

    protected $fillable = [

        'task_id',

        'project_id',

        'staff_id',

        'status',

        'reopen_type',

        'remark',

        'reassign_to',

        'spending_hour'

    ];



    public function task()

    {

        return $this->belongsTo(Task::class);

    }



    public function project()

    {

        return $this->belongsTo(Project::class, 'project_id');

    }

    

}

