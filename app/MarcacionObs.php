<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarcacionObs extends Model
{
    protected $table = "marcacion_obs";
    protected $fillable = ['marcacion_id', 'viatico', 'obs'];
    protected $primaryKey = 'marcacion_id';
}
