<?php

namespace App\Http\Controllers;

use App\Marcacion;
use App\MarcacionObs;
use App\Util\myResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarcacionController extends Controller
{

    public function registro(Request $request)
    {
        $personal = $request->input('personal');
        $orden_trabajo = $request->input('orden_trabajo');
        $usr = $request->input('usr');
        $viatico = $request->input('viatico');
        $obs = $request->input('obs');
        $validacion = DB::select(DB::raw("select mod(count(*),2) as valida  from marcacion where personal=" . $personal . " and year(fecha)=year(now()) and month(fecha)=month(now()) and day(fecha)=day(now()) and orden_trabajo <> '" . $orden_trabajo . "' "));


        if ($validacion[0]->valida == 0) {


            $marcacion = Marcacion::create([
                "personal" => $personal,
                "orden_trabajo" => $orden_trabajo,
                "fecha" => \Carbon\Carbon::now(),
                "usuario_registra" => $usr,
            ]);

            MarcacionObs::create([
                "marcacion_id" => $marcacion->id,
                "viatico" => $viatico,
                "obs" => $obs,
            ]);

            return response()->json(myResponse::apiResponse([], "Asistencia registrada correctamente"));
        } else {
            return response()->json(myResponse::apiResponse([], "No puede generarse la asistencia si esta en otra OT."));
        }
    }

}
