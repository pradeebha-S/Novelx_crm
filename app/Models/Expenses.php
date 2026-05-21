<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $fillable=[
        'type',
        'month',
        'expense_type',
        'project',
        'amount',
        'proof',
        'status',
        'remarks'
        
    ];

    public function projectDetails()
{
    return $this->belongsTo(Project::class, 'project');
}
}
