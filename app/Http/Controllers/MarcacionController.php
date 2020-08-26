<?php

namespace App\Http\Controllers;

use App\Exports\VMarcacionExport;
use App\Marcacion;
use App\MarcacionObs;
use App\Util\myResponse;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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

    public function asistencia()
    {
        if (empty(\request("f1")) || empty(\request("f2"))) {
            $f1 = date("Y-m-d");
            $f2 = new DateTime('+1 day');
        } else {
            $f1 = \request("f1");
            $f2 = new DateTime(\request("f2"));
            $f2->modify('+1 day');

        }


        $asistencias = \App\VOrdenTrabajoPersonal::join("marcacion", function ($join) {
            $join->on("marcacion.personal", "=", "id_personal");
            $join->on("marcacion.orden_trabajo", "=", "id_ot");
        })->whereBetween("fecha", [$f1, $f2->format('Y-m-d')])->orderBy('nombre')->get();

        return view('pages.reportes.asistencia')->with('data', $asistencias);
    }

    public function export()
    {
        return Excel::download(new VMarcacionExport(request("f1"),request("f2")), 'marcacion.xlsx');
    }
}
