<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Modules extends Model
{
 protected $fillable = ['module_name', 'project_id','module_type'];
}
