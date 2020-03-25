@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-12 ">
            <h5>Reporte de asistencia</b>
            </h5>
        </div>
        <form>

            <div class="form-inline">
                <div class="form-group mx-1">
                    <input type="date" name="f1" class="form-control txtdate" value="{{request("f1")}}">
                </div>
                <div class="form-group mx-1">
                    <input type="date" name="f2" class="form-control txtdate" value="{{request("f2")}}">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
            </div>
        </form>


        <table class="table my-2">
            <thead>
            <tr>
                <th>Personal</th>
                <th>OT</th>
                <th>Fecha</th>
            </tr>
            </thead>

            <tbody>
            @foreach($data as $i)
                <tr>
                    <td>{{$i->nombre}}</td>
                    <td>{!! $i->cliente." <br> ".$i->producto_fabricar!!}</td>
                    <td>{{$i->fecha}}</td>
                </tr>
            @endforeach

            </tbody>

        </table>
    </div>
@endsection
@section('scripts')
    <script>
        if ($(".txtdate").val() == "") {
            $(".txtdate").val(new Date().toISOString().substr(0, 10))
        }
    </script>
@endsection