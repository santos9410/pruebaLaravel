@extends('layout')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="stylesheet" href="{!!URL::asset('/css/jquery-ui.css')!!}">
  <div class="container">
      <h2 class="text-center">Modificar Materia</h2>

      <form class="form-horizontal" id="formMateria" autocomplete="off">
        <div class="form-group nombreM">
          <label  class="control-label col-xs-3">Nombre de la materia</label>
          <div class="col-xs-9">
            <input type="text" class="form-control" id="nombreMateria" placeholder="Escribe el nombre de la Materia" maxlength="45">
          </div>
        </div>
        <div class="form-group nombreM2" >
          <label  class="control-label col-xs-3">Nombre modificado de la Materia</label>
          <div class="col-xs-9">
            <input type="text" class="form-control" id="nombreMateria2" placeholder="Escribe el nombre modificado de la Materia" maxlength="45">
          </div>
        </div>
        <div class="col-md-12">
          <button type="submit" id="modificar" class="btn btn-success pull-right" name="button">Modificar Materia</button>
        </div>
      </form>



</div>
<div class="alerts container">
  <div class="alert alert-success text-center alExito" role="alert"><strong>Éxito!</strong> La materia  ha sido modificada correctamente</div>
  <div class="alert alert-danger text-center alError" role="alert">ha ocurrido un <strong>Error</strong> </div>
</div>

<style media="screen">
  #formMateria{
    width: 60%;
    margin: 0 auto;
    margin-top: 40px;
  }
  #modificar {
    margin-top: 20px;
  }
  .nombreM2 {
    display: none;
  }
  .alExito , .alError {
    display: none;
    margin-top: 40px;
  }


</style>

<script src="{!!URL::asset('/js/animate-colors-min.js')!!}" charset="utf-8"></script>
<script src="{!!URL::asset('/js/jquery-ui.js')!!}" charset="utf-8"></script>
<script src="{!!URL::asset('/js/app/modificarMateria.js')!!}" charset="utf-8"></script>
@stop
