<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolEmpleado extends Model
{
    protected $table="rol_empleado";
    protected $fillable = ['id', 'detalle'];
}
