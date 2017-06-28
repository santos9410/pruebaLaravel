@extends('layout')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
{{-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> --}}
<link rel="stylesheet" href="{!!URL::asset('/css/jquery-ui.css')!!}">

  <div class="container">
      <h2 class="text-center">Modificar Configuración</h2>

      <div class="row">
        <div class="divCaja">
          <label class="label-control col-md-4">Nombre de la configuración</label>
          <div class="col-md-6">
            {{-- <input type="text" name="nombre" id="nombreConfig" class="form-control" placeholder="Ingrese un nombre"> --}}
            <select class="form-control" name="select" id="selectConfig">

            </select>
          </div>
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-primary" name="button" id="buscar">Buscar Datos</button>
        </div>
      </div>

  </div>

  <div class="container" id="divTablas">
    <div class="table-responsive">
      <table class="table" id="tabla1">
        <thead>
          <tr>
            <td class="col-md-2 text-center"><strong>id Configuración</strong></td>
            <td class="col-md-4 text-center"><strong>Nombre de configuración</strong></td>
            <th class="col-md-6 text-center">Descripción</th>
          </tr>
        </thead>
        <tbody id="tblConfig"></tbody>
      </table>
    </div>

    <div class="table-responsive">
      <table class="table" id="tabla2">
        <thead>
          <tr>
            <td class="col-md-4"><strong>Carrera</strong></td>
            <td class="col-md-4"><strong>Cantidad de grupos<strong></td>
            <th class="col-md-4"><strong>Cantidad de elementos por grupo<strong></th>
          </tr>
        </thead>
        <tbody id="tblDetalles"></tbody>
      </table>
      <div class="col-md-12" style="margin-bottom:40px;">
        <button type="button" name="button" class="btn btn-primary pull-right" id="modificar">Modificar Configuración</button>
      </div>
    </div>

  </div>
  <div class="alerts container">
    <div class="alert alert-success text-center alExito" role="alert"><strong>Éxito!</strong> La configuración ha sido modificada correctamente</div>
    <div class="alert alert-danger text-center alError" role="alert">ha ocurrido un <strong>Error</strong> </div>
    <div class="alert alert-info text-center alInfo" role="alert">no existen datos!! </div>
  </div>
<style media="screen">
  .divCaja {
    margin: 0 auto;
    width: 70%;
    margin-top: 40px;
  }
  #divTablas {
    margin-top: 40px;
    display: none;
  }
  #tabla1 {
    margin: 0 auto;
    width: 70%;
    margin-top: 30px;
    margin-bottom: 40px;

  }
  .alExito , .alError , .alInfo {
    margin-top: 40px;
    display: none;
  }
</style>
  {{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}
  <script src="{!!URL::asset('/js/jquery-ui.js')!!}" charset="utf-8"></script>
<script src="{!!URL::asset('/js/app/modificarConfig.js')!!}" charset="utf-8"></script>
@stop
