@extends('layout')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

<div class="container">
    <h2 class="text-center">Registrar calificaciones</h2>

    <div class="row" style="margin-top:40px;">
      <div class="col-xs-12 col-sm-4	col-md-4	col-lg-4 ">
        <label  class="col-sm-12 control-label">Selecciona una Carrera</label>
        <div class="col-sm-12">
          <select class="form-control" name="carrera" id="selectCarrera"></select>
        </div>
      </div>
      <div class="col-xs-12 col-sm-4	col-md-4	col-lg-4 ">
        <label  class="col-sm-12 control-label">Selecciona una Materia</label>
        <div class="col-sm-12">
          <select class="form-control" name="materia" id="selectMat"></select>
        </div>
      </div>
      <div class="col-xs-12 col-sm-3	col-md-3	col-lg-3 ">
        <label  class="col-sm-12 control-label">letra Grupo</label>
        <div class="col-sm-12">
          <select class="form-control" name="letra" id="selectLetra"></select>
        </div>
      </div>
      <div class="col-xs-12 col-sm-1	col-md-1	col-lg-1">
        <br>
        <button type="button" class="btn btn-success" name="button" id="buscar">Buscar</button>
      </div>
    </div>

    <div class="table-responsive divTable" style="margin-top:60px;display:none;margin-bottom:40px;">
      <table class="table">
        <thead>
          <tr>
            <td class="text-center">Ficha</td>
            <td class="text-center">Nombre</td>
            <td class="text-center">calificación</td>
          </tr>
        </thead>
        <tbody id="tblCalif">

        </tbody>
      </table>
      <div class="col-md-12">
        <button type="button" class="btn btn-primary pull-right" name="button" id="registrar">Registrar Calificaciones</button>
      </div>
    </div>
    <div class="alerts container">
      <div class="alert alert-success text-center alExito" role="alert"><strong>Éxito!</strong> Las calificaciones han sido registradas correctamente</div>
      <div class="alert alert-danger text-center alError" role="alert">ha ocurrido un <strong>Error</strong> </div>
      <div class="alert alert-info text-center alInfo" role="alert"></div>
      <div class="alert alert-info text-center alInfo2" role="alert">No existen datos!!</div>
    </div>

</div>
<style media="screen">
  .alExito , .alError, .alInfo , .alInfo2 {
    display: none;
    margin-top: 20px;
  }
</style>

<script src="{!!URL::asset('/js/bootbox.min.js')!!}" charset="utf-8"></script>
<script src="{!!URL::asset('/js/app/registrarCalif.js')!!}" charset="utf-8"></script>
@stop
