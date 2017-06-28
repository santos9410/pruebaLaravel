@extends('layout')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

<link rel="stylesheet" href="{!!URL::asset('/css/jquery-ui.css')!!}">

<div class="container">
    <h2 class="text-center">Modificar Promedio del Aspirante</h2>

    <form class="form-horizontal" id="formAsp_modificarP" autocomplete="off">
      <div class="form-group nombreM">
        <label  class="control-label col-xs-3">Número de ficha</label>
        <div class="col-xs-9">
          <input type="number" class="form-control" id="ficha" placeholder="Número de ficha" maxlength="6">
        </div>
      </div>
      <div class="divModificar">

        <div class="form-group" style="margin-top:40px;">
          <label class="control-label col-xs-3">Promedio</label>
          <div class="col-xs-9">
            <input type="text" class="form-control" name="" value="" id="promedio">
          </div>
        </div>

        <div class="col-xs-12" style="margin-top:10px;">
          <button type="submit" class="btn btn-success pull-right" name="button">Modificar</button>
        </div>
      </div>
    </form>
</div>
<div class="alerts container">
  <div class="alert alert-success text-center alExito" role="alert"><strong>Éxito!</strong> La materia  ha sido registrada correctamente</div>
  <div class="alert alert-danger text-center alError" role="alert">ha ocurrido un <strong>Error</strong> </div>
</div>

<style media="screen">
  #formAsp_modificarP{
    width: 50%;
    margin: 0 auto;
    margin-top: 40px;
  }


  .alExito , .alError {
    display: none;
    margin-top: 40px;
  }

  .divModificar {
    display: none;
  }


</style>

<script src="{!!URL::asset('/js/jquery-ui.js')!!}" charset="utf-8"></script>
<script src="{!!URL::asset('/js/app/modificarAspirante_Promedio.js')!!}" charset="utf-8"></script>
@stop
