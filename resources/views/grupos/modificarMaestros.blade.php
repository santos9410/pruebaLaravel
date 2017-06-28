@extends('layout')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{!!URL::asset('/css/jquery-ui.css')!!}">
{{-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> --}}
  <div class="container">
    <h2 class="text-center">Modificar la asignación de  maestros para el curso de inducción</h2>


    <div class="form-group" id="selectMat">
      <label  class="col-sm-4 control-label">Selecciona una Materia del curso</label>
      <div class="col-sm-8">
        <select class="form-control" name="materias" id="selectMateria">
          @foreach ($materias as $key => $item)
              <option value="{{$item['idMateria']}}">{{$item['nombre_Mat']}}</option>

          @endforeach

        </select>
      </div>
    </div>
    <button type="submit" class="btn btn-primary" id="btnBuscar">Buscar</button>

    <div class="datos">
      <form class="form-horizontal" id="formModificar">

      </form>
    </div>
    <div class="alerts">
    <div class="alert alert-success text-center alExito" role="alert"><strong>Éxito!</strong> Los Maestros asignados fueron actualizados correctamente</div>
    <div class="alert alert-info text-center alInfo" role="alert">No se han creado grupos!! </div>
    <div class="alert alert-danger text-center alError" role="alert">ha ocurrido un <strong>Error</strong> </div>
    </div>
  </div>

    <link rel="stylesheet" href="{!!URL::asset('css/app/modificarMaestros.css')!!}">
    <script src="{!!URL::asset('/js/jquery-ui.js')!!}" charset="utf-8"></script>
    {{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}
    <script src="{!!URL::asset('/js/app/modificarMaestros.js')!!}" charset="utf-8"></script>
@stop
