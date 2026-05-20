<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Permission extends Model
{
    protected $fillable = [
        'user_id',
        'from',
        'to',
        'reason',
        'remark',
        'reply',
        'is_replied',
        'date',
        'mailed',
        'informed_to'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
