<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    protected $table = 'objective';

    protected $fillable = [
        'description',
        'manager_id',
        'jobtitle',
        'isIndividual',
        'isActive',
        'target',
        'skill',
        'objective_priority',
        'updated_at',
    ];
}
