<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonalStoreRequest;
use App\Http\Requests\PersonalUpdateRequest;
use App\Personal;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!empty($request->input('buscar'))) {
            //$data = $data->where('tipo', '>', 0);
            //SELECT * FROM `personal` WHERE nombres like '%mireya%'
            $data = Personal::where('nombres', 'like', '%' . $request->input('buscar') . '%');
            $data = $data->orWhere('apellidos', 'like', '%' . $request->input('buscar') . '%');
            $data = $data->orWhere('doc_ide', 'like', '%' . $request->input('buscar') . '%');
            $data = $data->orderBy('apellidos', 'asc')->paginate()->appends(request()->query());
        } else {
            $data = Personal::orderBy('apellidos', 'asc')->paginate()->appends(request()->query());
        }


        return view('pages.personal')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.personal_create');
    }


    public function store(PersonalStoreRequest $request)
    {
        $nombres = $request->input('nombres');
        $apellidos = $request->input('apellidos');
        $doc_ide = $request->input('doc_ide');
        $tipo = $request->input('tipo');

        $personal = Personal::create([
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'doc_ide' => $doc_ide,
            'tipo' => $tipo,
        ]);


        return redirect()->route('admin.personal')->with('success', "El personal #" . $personal->id . " ha sido registrado correctamente.");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $personal = Personal::where('id', $id)->get()[0];
        return view('pages.personal_edit')->with(compact('personal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PersonalUpdateRequest $request, $id)
    {
        $nombres = $request->input('nombres');
        $apellidos = $request->input('apellidos');
        $doc_ide = $request->input('doc_ide');
        $tipo = $request->input('tipo');

        Personal::where('id', $id)->update([
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'doc_ide' => $doc_ide,
            'tipo' => $tipo,
//
        ]);
        return redirect()->route('admin.personal')->with('success', "El personal  ha sido modificado correctamente.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
