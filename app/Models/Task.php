<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model

{

    protected $fillable = [

        'project_id',

        'module_id',

        'module_type',

        'task_name',

        'estimated_time',

        'spending_hour',

        'due_date',

        'assign_to',

        'dev_complete',

        'testing_complete',

        'remark',

        'task_type',

        'task_status',
        'test_status',
         'tested_by',
          'tester_id',

        'priority',

        'start_date',

        'task_description'

    ];

    public function project()

    {

        return $this->belongsTo(Project::class, 'project_id');

    }

    public function module()

    {

        return $this->belongsTo(Modules::class, 'module_id');

    }

    public function assignedStaff()

    {

        return $this->belongsTo(User::class, 'assign_to', 'id');

    }

    public function histories()

    {

        return $this->hasMany(TaskHistory::class, 'task_id');

    }

}

