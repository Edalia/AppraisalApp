<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use Notifiable;

    public $table = "employee";
    protected $guard = 'employee';

    protected $fillable = [
        'fname',
        'lname',
        'phone',
        'isEvaluator',
        'isSuspended',
        'manager_id',
        'jobtitle',
        'email',
        'password',
        'updated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    
}
