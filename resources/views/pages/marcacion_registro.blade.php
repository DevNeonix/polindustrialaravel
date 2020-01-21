@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-12 col-md-5">
            <div class="card">
                <div class="card-body">
                    <h5 >Marcacion para el Producto <b>{{$ot->producto_fabricar}}</b> del cliente <b>{{$ot->cliente}}</b></h5>
                </div>

                <?php

                $empleados = DB::table('view_orden_trabajo_personal')->where('id_ot',$ot->id)->get();

                ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th colspan="2">Registro de horario</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($empleados as $i)
                    <tr>
                        <td>{{$i->nombre}}</td>
                        <td>
                            <button class="btn btn-primary">Ingreso</button>

                        </td>
                        <td>
                            <button class="btn btn-danger">Salida</button>
                        </td>
                    </tr>
                    </tbody>
                    @endforeach
                </table>
            </div>
        </div>
    </div>

@endsection