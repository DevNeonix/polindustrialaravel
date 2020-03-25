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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return redirect()->to('login');
});
Route::get('login', function () {
    return view('pages.login');
})->name('login');


Route::post('login', function (\App\Http\Requests\LoginRequest $request) {
    $usuario = $request->input('usuario');
    $clave = $request->input('clave');
    $user = DB::table('personal')->where('usuario', $usuario)->where('clave', $clave);
    if ($user->count() == 0) {
        return redirect()->to('login')->withErrors('Las creedenciales no corresponden con nuestros registros.');
    } else {
        Session::put('usuario', $user->get()[0]->id . '');
        return redirect()->to(route('admin.marcacion'));
    }
})->name('loginsubmit');


Route::group(['prefix' => 'admin', 'middleware' => 'usuario'], function () {
    Route::get('close', function () {
        Session::flush();
        return redirect()->to(route('login'));
    })->name('admin.close');


    Route::get('home', function () {
        return view('pages.home');
    })->name('admin.home');

    Route::get('personal', function (Request $request) {
        $data = DB::table('personal');
        if (!empty($request->input('buscar'))) {
            $data = $data->where('nombres', 'like', '%' . $request->input('buscar') . '%');
        }
        $data = $data->orderBy('apellidos', 'asc')->paginate()->appends(request()->query());

        return view('pages.personal')->with('data', $data);
    })->name('admin.personal');

    Route::get('personal/create', function () {
        return view('pages.personal_create');
    })->name('admin.personal.create');

    Route::post('personal', function (\App\Http\Requests\PersonalStoreRequest $request) {

        $nombres = $request->input('nombres');
        $apellidos = $request->input('apellidos');
        $doc_ide = $request->input('doc_ide');
        $tipo = $request->input('tipo');
        $usuario = $request->input('usuario');
        $clave = $request->input('clave');

        $id = DB::table('personal')->insertGetId([
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'doc_ide' => $doc_ide,
            'tipo' => $tipo,
            'usuario' => $usuario,
            'clave' => $clave
        ], 'id');


        return redirect()->route('admin.personal')->with('success', "El personal #${id} ha sido registrado correctamente.");
    })->name('admin.personal.store');


    Route::get('personal/edit/{id}', function ($id) {
        $personal = DB::table('personal')->where('id', $id)->get()[0];
        return view('pages.personal_edit')->with(compact('personal'));
    })->name('admin.personal.edit');


    Route::post('personal/edit/{id}', function ($id, \App\Http\Requests\PersonalUpdateRequest $request) {

        $nombres = $request->input('nombres');
        $apellidos = $request->input('apellidos');
        $doc_ide = $request->input('doc_ide');
        $tipo = $request->input('tipo');
        $usuario = $request->input('usuario');
        $clave = $request->input('clave');
        DB::table('personal')->where('id', $id)->update([
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'doc_ide' => $doc_ide,
            'tipo' => $tipo,
            'usuario' => $usuario,
            'clave' => $clave
        ], 'id');
        return redirect()->route('admin.personal')->with('success', "El personal  ha sido modificado correctamente.");
    })->name('admin.personal.update');

    Route::get('ots', function (Request $request) {
        $data = DB::table('orden_trabajo');
        if (!empty($request->input('buscar'))) {
            $data = $data->where('producto_fabricar', 'like', '%' . $request->input('buscar') . '%');
            $data = $data->orWhere('cliente', 'like', '%' . $request->input('buscar') . '%');
        }
        $data = $data->paginate()->appends(request()->query());

        return view('pages.ots')->with('data', $data);
    })->name('admin.ots');

    Route::get('ots/create', function () {
        return view('pages.ots_create');
    })->name('admin.ots.create');
    Route::post('ots/create', function (\App\Http\Requests\OtsStoreRequest $request) {
        $nro_orden = $request->input('nro_orden');
        $producto_fabricar = $request->input('producto_fabricar');
        $cliente = $request->input('cliente');
        $id = DB::table('orden_trabajo')->insertGetId([
            'nro_orden' => $nro_orden,
            'producto_fabricar' => $producto_fabricar,
            'cliente' => $cliente,
            'estado' => '1'
        ], 'id');
        return redirect()->route('admin.ots')->with('success', "La OT #${id} ha sido registrado correctamente.");
    })->name('admin.ots.store');


    Route::get('ots/edit/{id}', function ($id) {
        $ot = DB::table('orden_trabajo')->where('id', $id)->get()[0];
        return view('pages.ots_edit')->with(compact('ot'));
    })->name('admin.ots.edit');


    Route::post('ots/edit/{id}', function ($id, \App\Http\Requests\OtsUpdateRequest $request) {

        $nro_orden = $request->input('nro_orden');
        $producto_fabricar = $request->input('producto_fabricar');
        $cliente = $request->input('cliente');
        $estado = $request->input('estado');
        $id = DB::table('orden_trabajo')->where('id', $id)->update([
            'nro_orden' => $nro_orden,
            'producto_fabricar' => $producto_fabricar,
            'cliente' => $cliente,
            'estado' => $estado
        ], 'id');
        return redirect()->route('admin.ots')->with('success', "La OT ha sido modificado correctamente.");
    })->name('admin.ots.update');


    Route::get('ots_personal', function (Request $request) {
        $data = DB::table('orden_trabajo');
        if (!empty($request->input('buscar'))) {
            $data = $data->where('producto_fabricar', 'like', '%' . $request->input('buscar') . '%');
            $data = $data->orWhere('cliente', 'like', '%' . $request->input('buscar') . '%');
        }
        $data = $data->paginate()->appends(request()->query());

        return view('pages.ots_personal')->with('data', $data);
    })->name('admin.ots_personal');

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
        if (!empty($personal)) {
            foreach ($personal as $personal_item) {
                DB::table('marcacion')->insert([
                    "personal" => $personal_item,
                    "orden_trabajo" => $orden_trabajo,
                    "fecha" => \Carbon\Carbon::now(),
                    "usuario_registra" => Session::get("usuario"),
                ]);
            }
        }

        return response()->json(["message" => "Marcación registrada correctamente"]);
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
        return redirect()->back();
    })->name('admin.marcacion.faltas.registro');
    Route::get('reportes/asistencia', function () {
        #"SELECT * FROM `view_orden_trabajo_personal` as vp inner join marcacion on marcacion.personal=vp.id_personal and marcacion.orden_trabajo=vp.id_ot""

        if (empty(\request("f1")) || empty(\request("f2"))) {
            $f1 = date("Y-m-d");
            $f2 = new DateTime('+1 day');
        } else {
            $f1 = \request("f1");
            $f2 = new DateTime(\request("f2"));
            $f2->modify('+1 day');

        }


        $asistencias = DB::table("view_orden_trabajo_personal")->join("marcacion", function ($join) {
            $join->on("marcacion.personal", "=", "id_personal");
            $join->on("marcacion.orden_trabajo", "=", "id_ot");
        })->whereBetween("fecha", [$f1, $f2->format('Y-m-d')])->get();

        return view('pages.reportes.asistencia')->with('data', $asistencias);
    })->name("admin.reporte.asistencia");
});