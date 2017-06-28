@extends('layout')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

  <div class="container">
      <h2 class="text-center">Registrar Nueva Materia</h2>

      <form class="form-horizontal" id="formMateria" autocomplete="off">
        <div class="form-group nombreM">
          <label  class="control-label col-xs-3">Nombre de la materia</label>
          <div class="col-xs-9">
            <input type="text" class="form-control" id="nombreMateria" placeholder="Nombre de la Materia" maxlength="45">
          </div>
        </div>
        <div class="col-md-12">
          <button type="submit" id="registrar" class="btn btn-success pull-right" name="button">Registrar Nueva Materia</button>
        </div>
      </form>



</div>
<div class="alerts container">
  <div class="alert alert-success text-center alExito" role="alert"><strong>Éxito!</strong> La materia  ha sido registrada correctamente</div>
  <div class="alert alert-danger text-center alError" role="alert">ha ocurrido un <strong>Error</strong> </div>
</div>

<style media="screen">
  #formMateria{
    width: 60%;
    margin: 0 auto;
    margin-top: 40px;
  }
  #registrar {
    margin-top: 20px;
  }

  .alExito , .alError {
    display: none;
    margin-top: 40px;
  }


</style>

<script src="{!!URL::asset('/js/animate-colors-min.js')!!}" charset="utf-8"></script>
<script src="{!!URL::asset('/js/app/registrarMateria.js')!!}" charset="utf-8"></script>
@stop
