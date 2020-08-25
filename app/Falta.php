<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Falta extends Model
{
    protected $table="faltas";
    protected $fillable = ['id', 'personal', 'ot', 'fecha'];
    public $timestamps = false;

}
