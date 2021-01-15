<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvaluatedObjective extends Model 
{
    protected $table = 'evaluatated_objective';
    
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'evaluationform',
        'objective',
        'status',
        'rating',
    ];
}
