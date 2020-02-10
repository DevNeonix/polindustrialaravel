<?php

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', function (Request $request) {
    $usuario = $request->input('usuario');
    $clave = $request->input('clave');
    $user = DB::table('personal')->where('usuario', $usuario)->where('clave', $clave);
    if ($usuario == "" || $clave == "") {
        return response()->json(apiResponse([], "Usuario o contraseña errada"), 403, [], 256);
    }
    if ($user->count() == 0) {
        return response()->json(apiResponse([], "Usuario o contraseña errada"), 403, [], 256);
    } else {

        return response()->json(apiResponse($user->get()[0], "Bienvenido " . $user->get()[0]->nombre), 200, [], 256);
    }
});
Route::get('ots', function () {
    return response()->json(apiResponse(DB::table('orden_trabajo')->get(), "Listado de OT's"), 200, [], 256);
});

Route::get('ots_personal', function (Request $request) {
    $ot = $request->input("ot");
    return response()->json(apiResponse(DB::table('view_orden_trabajo_personal')->where('id_ot', $ot)->get(), "Listado de personal por OT"), 200, [], 256);
})->name('admin.ots_personal');


Route::get('personal_ots', function (Request $request) {
    $dni = $request->input("dni");
    $exec = DB::table("view_orden_trabajo_personal")->where('doc_ide','=',$dni)->get();
    return response()->json($exec, 200, [], 256);
})->name('admin.personal_ots');

Route::get('ots_personal_disponible', function (Request $request) {
    $data = array();
    $ot = $request->input("ot");

    $personal = DB::table('view_orden_trabajo_personal')->where('id_ot', $ot)->get();

    foreach ($personal as $i) {
        $validacion = DB::select(DB::raw("select mod(count(*),2) as valida  from marcacion where personal=" . $i->id_personal . " and year(fecha)=year(now()) and month(fecha)=month(now()) and day(fecha)=day(now()) and orden_trabajo <> '" . $ot . "' "));


        if ($validacion[0]->valida == 0) {
            $ingresos = DB::select(DB::raw("select * from marcacion where orden_trabajo='" . $ot . "' and personal = " . $i->id_personal . " and year(fecha)=year(now()) and month(fecha)=month(now()) and day(fecha)=day(now()) "));
            $arrIngresos = array();
//            foreach ($ingresos as $ing){
//                array_push()
//            }
            $i->ingresos = $ingresos;

            array_push($data, $i);
        }


    }


    return response()->json(apiResponse($data, "Listado de personal por OT"), 200, [], 256);
})->name('admin.ots_personal');


function apiResponse($data = [], $message = "")
{
    return ["data" => $data, "message" => $message];
}