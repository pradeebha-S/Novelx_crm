<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;



class ProjectDocuments extends Model

{

    protected $fillable = [

    'project_id',
        'platform',

        'user_id',

        'password',

           'password_hint',

        'document',

    ];
    public function getPlatformAttribute($value)
{
    if (!$value) return null;

    try {
        return Crypt::decryptString($value);
    } catch (\Exception $e) {
        return $value;
    }
}

public function getUserIdAttribute($value)
{
    if (!$value) return null;

    try {
        return Crypt::decryptString($value);
    } catch (\Exception $e) {
        return $value;
    }
}

}

