<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'call_status',
        'remarks'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}
