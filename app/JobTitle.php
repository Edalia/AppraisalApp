<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
    protected $table = 'jobtitle';

    protected $fillable = [
        'manager_id',
        'titlename',
    ];
}
