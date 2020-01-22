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
        <table class="table">
            <thead>
            <tr>
                <th style="width: 10px"><input type="checkbox" id="allChk" onclick="seleccionaTodos(this.checked)"></th>
                <th>Nombre</th>
            </tr>
            </thead>
            <tbody>
            @foreach($empleados as $i)
                <tr>
                    <td style="width: 10px">
                        <input id_personal="{{$i->id_personal}}" class="chkAsistencia" type="checkbox">
                    </td>
                    <?php
                    $k = 0
                    ?>
                    <td>
                        {{$i->nombre}}
                        <?php
                        $x = DB::select(DB::raw("select * from marcacion where personal = " . $i->id_personal . " and year(fecha)=year(now()) and month(fecha)=month(now()) and day(fecha)=day(now()) "));
                        ?>
                        @foreach($x as $j)
                            <p class="text-muted text-italic">

                                <?php
                                $k = $k + 1;
                                ?>
                                <b>{{$k%2==0?"Salió":'Ingresó' }}</b>: {{\Carbon\Carbon::parse($j->fecha)->diffForHumans(\Carbon\Carbon::now())}}</p>
                        @endforeach
                    </td>

                </tr>
            </tbody>
            @endforeach
            <tr>
                <td colspan="2">
                    <button class="btn btn-primary" onclick="enviarRegistro()">Registrar asistencia</button>
                </td>
            </tr>
        </table>
    </div>

@endsection
@section('scripts')
    <script>

        function seleccionaTodos(v) {
            $('.chkAsistencia').prop('checked', v);
        }

        function enviarRegistro() {
            var personal = [];
            $(".chkAsistencia").each(function (i) {
                if (this.checked) {
                    personal.push($(this).attr("id_personal"));
                }
            });

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
                    window.location.reload();
                }
            });
        }

    </script>
@endsection