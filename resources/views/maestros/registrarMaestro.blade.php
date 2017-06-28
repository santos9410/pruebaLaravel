@extends('layout')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

  <div class="container">
      <h2 class="text-center">Registrar Nuevo Maestro</h2>

      <form class="form-horizontal" id="formMaestro" autocomplete="off">
        <div class="form-group nombreM">
          <label  class="control-label col-xs-3">Nombre del Maestro</label>
          <div class="col-xs-9">
            <input type="text" class="form-control" id="nombreMaestro" placeholder="Nombre del maestro" maxlength="70">
          </div>
        </div>
        <div class="col-md-12">
          <button type="submit" id="registrar" class="btn btn-success pull-right" name="button">Registrar Nuevo Maestro</button>
        </div>
      </form>



</div>
<div class="alerts container">
  <div class="alert alert-success text-center alExito" role="alert"><strong>Éxito!</strong> El maestro  ha sido registrado correctamente</div>
  <div class="alert alert-danger text-center alError" role="alert">ha ocurrido un <strong>Error</strong> </div>
</div>

<style media="screen">
  #formMaestro{
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
<script src="{!!URL::asset('/js/app/registrarMaestro.js')!!}" charset="utf-8"></script>
@stop
