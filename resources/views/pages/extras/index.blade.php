@extends('layouts.admin')
@section('content')

    <div class="col-12">
        <h1 class="h3">Asignación de Horas Extras</h1>
        <div class="form-group col-md-6">
            <label for="">Seleccion Personal</label>
            <select class="form-control select2" onchange="buscaOt(this.value)">
                <option value=""></option>
                @foreach($personal as $p)
                    <option value="{{$p->id}}">{{$p->apellidos." ".$p->nombres}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="">Seleccion OT</label>
            <select id="ots" class="form-control select2">
                <option value=""></option>

            </select>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
        $(".select2").select2();

        function buscaOt(id) {
            let o = `<option value=""></option>`;
            document.getElementById("ots").innerHTML = "";
            $.ajax({
                url: "{{route('api.ots_personal2')}}",
                data: "id=" + id,
                type: 'GET',
                cache: false,
                success: function (res) {
                    console.log(res)
                    var ots = res.data;
                    var ox = "";
                    for (let ot of ots) {
                        ox = ox + `<option value="${ot.id_ot}">${ot.nro_orden + ' ' + ot.cliente + ' ' + ot.producto_fabricar}</option>`
                    }
                    document.getElementById("ots").innerHTML = o + ox;
                },
                error: function (e) {
                    alert('Error: ' + e);
                }
            });
        }
    </script>
@endsection