<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    protected $fillable = [
        'name', 'email', 'password', 'tipo', 'estado'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];
    public $timestamps = false;
}
