<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', 'AdminController@toLogin');
Route::get('login', 'UserController@loginview')->name('login');
Route::post('login', 'UserController@login')->name('loginsubmit');

Route::group(['prefix' => 'admin', 'middleware' => 'usuario'], function () {


    Route::get('users', 'UserController@list')->name('admin.users');
    Route::get('user/create', 'UserController@create')->name('admin.user.create');
    Route::post('user', 'UserController@store')->name('admin.user.store');
    Route::get('user/edit/{id}', 'UserController@edit')->name('admin.user.edit');
    Route::post('user/edit/{id}', 'UserController@update')->name('admin.user.update');
    Route::get('close', 'UserController@cerrarsesion')->name('admin.close');
    Route::get('home', 'AdminController@toHome')->name('admin.home');
    Route::get('personal', 'PersonalController@index')->name('admin.personal');
    Route::get('personal/create', 'PersonalController@create')->name('admin.personal.create');
    Route::post('personal', 'PersonalController@store')->name('admin.personal.store');
    Route::get('personal/edit/{id}', 'PersonalController@edit')->name('admin.personal.edit');
    Route::post('personal/edit/{id}', 'PersonalController@update')->name('admin.personal.update');


    Route::get('ots', 'OrdenTrabajoController@index')->name('admin.ots');
    Route::get('ots/create', 'OrdenTrabajoController@create')->name('admin.ots.create');
    Route::post('ots/create', 'OrdenTrabajoController@store')->name('admin.ots.store');
    Route::get('ots/edit/{id}', 'OrdenTrabajoController@edit')->name('admin.ots.edit');
    Route::post('ots/edit/{id}', 'OrdenTrabajoController@update')->name('admin.ots.update');


    Route::get('ots_personal', 'OrdenTrabajoController@listOts')->name('admin.ots_personal');


    Route::get('ots_personal/edit/{id}', function ($id, Request $request) {
        $data = DB::table('view_orden_trabajo_personal')->where('id_ot', $id)->get();
        return view('pages.ots_personal_edit')->with('data', $data)->with('ot', DB::table('orden_trabajo')->where('id', $id)->get());
    })->name('admin.ots_personal.edit');
    Route::get('ots_personal/registrar', function (Request $request) {
        $personal = $request->input('personal');
        $ot = $request->input('ot');
        DB::table('orden_trabajo_personal')->insert(['personal' => $personal, 'orden_trabajo' => $ot]);
        return redirect()->route("admin.ots_personal.edit", $ot);
    })->name('admin.ots_personal.store');
    Route::get('ots_personal/elimina', function (Request $request) {
        $personal = $request->input('personal');
        $ot = $request->input('ot');
        DB::table('orden_trabajo_personal')->where('personal', $personal)->where('orden_trabajo', $ot)->delete();
        return redirect()->route("admin.ots_personal.edit", $ot);
    })->name('admin.ots_personal.delete');


    Route::get('marcacion', function (Request $request) {
        $data = DB::table('orden_trabajo')->where('estado', '1');
        if (!empty($request->input('buscar'))) {
            $data = $data->where('producto_fabricar', 'like', '%' . $request->input('buscar') . '%');
            $data = $data->orWhere('cliente', 'like', '%' . $request->input('buscar') . '%');
        }
        $data = $data->paginate()->appends(request()->query());

        return view('pages.marcacion')->with('data', $data);
    })->name('admin.marcacion');
    Route::get('marcacion/registro/{id}', function ($id) {
        $ot = DB::table('orden_trabajo')->where("id", $id)->get()[0];
        return view('pages.marcacion_registro')->with('ot', $ot);
    })->name('admin.marcacion.registro');
    Route::post('marcacion/registro', function (Request $request) {
        $personal = $request->input('personal');
        $orden_trabajo = $request->input('orden_trabajo');
        $errores = "";
        if (!empty($personal) && !empty($orden_trabajo)) {
            foreach ($personal as $personal_item) {

                //VALIDAR DUPLICADOS DE ASISTENCIAS (RESTRICCION DE 5 MIN)

                $validaMarcacionDoble = \App\Marcacion::where('personal', $personal_item)->where('orden_trabajo', $orden_trabajo)->orderBy("id", "desc")->first();
                if ($validaMarcacionDoble == null) {
                    DB::table('marcacion')->insert([
                        "personal" => $personal_item,
                        "orden_trabajo" => $orden_trabajo,
                        "fecha" => \Carbon\Carbon::now(),
                        "usuario_registra" => Session::get("usuario"),
                    ]);
                } else {

                    if (\Carbon\Carbon::now()->diffInSeconds($validaMarcacionDoble->fecha) >= 300) {
                        DB::table('marcacion')->insert([
                            "personal" => $personal_item,
                            "orden_trabajo" => $orden_trabajo,
                            "fecha" => \Carbon\Carbon::now(),
                            "usuario_registra" => Session::get("usuario"),
                        ]);
                    } else {
                        $errores = $errores . ". La marcación del personal " . $personal_item . " esta duplicada, se ignorará. ";
                    }
                }

            }
        }

        return response()->json(["message" => "Marcación registrada correctamente " . $errores]);
    })->name('admin.marcacion.insert');
    Route::delete('marcacion/registro', function (Request $request) {
        $id = $request->input('id');

        DB::table('marcacion')->where('id', $id)->delete();

        return response()->json(["message" => "Marcación eliminada correctamente"]);
    })->name('admin.marcacion.delete');
    Route::get('marcacion/faltas/{id}', function ($id, Request $request) {
        $ot = DB::table('orden_trabajo')->where("id", $id)->get()[0];
        return view('pages.marcacion_faltas')->with('ot', $ot);

    })->name('admin.marcacion.faltas');
    Route::get('marcacion/faltas', function (Request $request) {
        $ot = $request->input('ot');
        $personal = $request->input('personal');
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');
        for ($i = $desde; $i <= $hasta; $i = date("Y-m-d", strtotime($i . "+ 1 days"))) {

            DB::table('faltas')->insert([
                'ot' => $ot,
                'personal' => $personal,
                'fecha' => $i,
            ]);

        }
        return redirect()->back()->with(['success' => 'Falta registrada correctamente']);
    })->name('admin.marcacion.faltas.registro');


    Route::get('reportes/asistencia', '.@asistencia')->name("admin.reporte.asistencia");

    Route::get('reportes/asistencia/export', 'MarcacionController@export')->name("admin.reporte.asistencia.export");

    Route::get('reportes/asistencia-dia', 'VMarcacionDiaController@index')->name('admin.marcacion.asistenciadia');
    Route::get('reportes/asistencia-dia/export', 'MarcacionController@export2')->name("admin.reporte.asistenciadia.export");
});
