<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Bugs extends Model
{
     protected $fillable = [
         'project_id',
        'identified_by',
        'panel',
        'bug_type',
        'bug_title',
        'attachment',
        'module',
        'user_id',
        'debug_by',
        'priority',
        'testing_scenario',
        'current_output',
        'expected_output',
        'reopen_count',
        'status',
        'solved_by',
        'suggestion'
    ];
public function user()
{
    return $this->belongsTo(User::class, 'identified_by');
}
public function moduleData()
{
    return $this->belongsTo(Modules::class, 'module');
}
public function logs()
{
    return $this->hasMany(BugLogs::class, 'bug_id');
}
}
