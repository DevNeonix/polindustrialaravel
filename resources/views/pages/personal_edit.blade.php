@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-12 col-md-5">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.personal.update',$personal->id)}}" method="post">
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="nombre" value="{{$personal->nombre}}">
                        </div>
                        <div class="form-group">
                            <label>Doc. Identidad</label>
                            <input type="text" class="form-control" name="doc_ide" value="{{$personal->doc_ide}}">
                        </div>
                        <div class="form-group">
                            <label>Tipo</label>
                            <select class="form-control" id="tipo" name="tipo" onchange="cambiaTipo()">
                                <option value="0" {{$personal->tipo == '0'?'selected':''}}>Personal</option>
                                <option value="1" {{$personal->tipo == '1'?'selected':''}}>Administrativo</option>
                                <option value="2" {{$personal->tipo == '2'?'selected':''}}>Supervisor</option>
                            </select>
                        </div>
                        <div id="supervisor-auth">
                            <div class="form-group">
                                <label>Usuario</label>
                                <input type="text" class="form-control" name="usuario" value="{{$personal->usuario}}">
                            </div>
                            <div class="form-group">
                                <label>Clave</label>
                                <input type="password" class="form-control" name="clave" value="{{$personal->clave}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-block" type="submit">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function cambiaTipo() {
            var tipo = $("#tipo").val();
            if (tipo == 0) {
                $("#supervisor-auth").css("display", "none")
            } else {
                $("#supervisor-auth").css("display", "block")
            }
        }

        cambiaTipo();
    </script>
@endsection