<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Feedback extends Model
{
    protected $fillable = [
        'user_id',
        'positive_feedback',
        'negative_feedback',
        'suggestions',
        'additional_feedback',
        'status',
    ];
     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
