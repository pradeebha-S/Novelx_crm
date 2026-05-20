<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model

{

    protected $fillable = [

        "project_name",

        'figma_link',

        'sheet_link',

        'document_link',

        'client_mobile',

        'client_email',

        'client_name',

         'project',
 'tester_id',
        'type',
          'address',

    ];
public function tester()
{
    return $this->belongsTo(User::class, 'tester_id');
}
    public function tasks()

    {

        return $this->hasMany(Task::class, 'project_id');

    }

    public function modules()

{

    return $this->hasMany(Modules::class, 'project_id');

}

  public function bugs()

    {

        return $this->hasMany(Bugs::class, 'project_id', 'id');

    }

}

