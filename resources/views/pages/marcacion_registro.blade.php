@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-12 ">
            <h5>Marcacion para el Producto <b>{{$ot->producto_fabricar}}</b> del cliente <b>{{$ot->cliente}}</b>
            </h5>
        </div>

        <?php

        $empleados = DB::table('view_orden_trabajo_personal')->where('id_ot', $ot->id)->get();

        ?>
        <table class="table table-responsive-stack">
            <thead>
            <tr>
                <th>Nombre</th>
                <th colspan="1">Registro de horario</th>
            </tr>
            </thead>
            <tbody>
            @foreach($empleados as $i)
                <tr>
                    <td>{{$i->nombre}}</td>
                    <td>

                        <button class="btn btn-primary"
                                onclick="enviarRegistro(1,'{{$i->nombre}}',{{$i->id_personal}})">
                            Ingreso
                        </button>

                    </td>
                    <td>

                        <button class="btn btn-danger"
                                onclick="enviarRegistro(2,'{{$i->nombre}}',{{$i->id_personal}})">
                            Salida
                        </button>

                    </td>
                </tr>
            </tbody>
            @endforeach
        </table>
    </div>

@endsection
@section('scripts')
    <script>

        function enviarRegistro(tipo, nombre, personal) {
            switch (tipo) {
                case 1:
                    if (confirm("¿Esta seguro de registrar un ingreso para " + nombre + "?")) {
                        $.ajax({
                            url: '{{route("admin.marcacion.insert")}}',
                            type: 'post',
                            data: {
                                orden_trabajo:{{$ot->id}},
                                personal: personal
                            },
                            cache: false,
                            success: function (res) {
                                alert(res.message);
                            }
                        });
                    }
                    break;
                case 2:
                    if (confirm("¿Esta seguro de registrar una salida para " + nombre + "?")) {
                        $.ajax({
                            url: '{{route("admin.marcacion.insert")}}',
                            type: 'post',
                            data: {
                                orden_trabajo:{{$ot->id}},
                                personal: personal
                            },
                            cache: false,
                            success: function (res) {
                                alert(res.message);
                            }
                        });
                    }
                    break;
            }
        }

    </script>
@endsection