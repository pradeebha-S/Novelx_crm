<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PpsTransactions extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_type',
        'points',
        'reason',
        'remark',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
