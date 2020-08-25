<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table = "personal";
    protected $fillable = ["id", "nombres", "apellidos", "doc_ide", "tipo"];
    public $timestamps = false;
}
