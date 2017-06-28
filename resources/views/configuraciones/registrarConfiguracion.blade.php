@extends('layout')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />

  <div class="container">
      <h2 class="text-center">Registrar Configuración</h2>

      <form class="form-horizontal formConfig">
        <div class="form-group nombreC">
          <label  class="control-label col-xs-2">Nombre de la configuración</label>
          <div class="col-xs-10">
            <input type="text" class="form-control" id="nombreConfig" placeholder="Nombre de la configuración" maxlength="50">
          </div>
        </div>
        <div class="form-group descripcion">
          <label class="control-label col-xs-2">Descripción</label>
          <div class="col-xs-10">
            <textarea class="form-control" rows="4" id="desc" maxlength="200"></textarea>
          </div>
        </div>
      </form>

      <div class="col-md-12 detalles">

      <div class="form-group col-md-3">
        <label for="usr">Carrera</label>
        {{-- <input type="text" class="form-control" id="usr"> --}}
        <select class="form-control" id="carreras">
        </select>
      </div>
      <div class="form-group col-md-3 grupo">
        <label for="pwd">Cantidad Grupos</label>
        <input type="number" class="form-control" id="Cgrupos" pattern="[0-9]{0,3}" min="1" required>

      </div>
      <div class="form-group col-md-3 elemento">
        <label for="pwd">Cantidad por Grupo</label>
        <input type="number" class="form-control" id="Celem" min="1" required>
      </div>
      <div class="col-md-2 form-group btnAgregar">
        <button type="button" class="btn" id="Agregar" name="button">Agregar</button>
      </div>
    </div>

<div class="col-md-12" id="tablaDetalles">
  <table class="table table-striped">
    <thead id="tblHead">
      <tr>
        <th>Carrera</th>
        <th>Cantidad de Grupos</th>
        <th>Cantidad por Grupo</th>
      </tr>
    </thead>
    <tbody id="DetallesConfig">
    </tbody>
  </table>

  <div class="col-md-12 ">
    <button type="button" class="btn btn-success pull-right" id="registrar" name="button">Registrar</button>
  </div>
</div>

</div>
<div class="alerts container">
  <div class="alert alert-success text-center alExito" role="alert"><strong>Éxito!</strong> La configuración ha sido creada correctamente</div>
  <div class="alert alert-danger text-center alError" role="alert">ha ocurrido un <strong>Error</strong> </div>
</div>

<style media="screen">
  .formConfig{
    width: 60%;
    margin: 0 auto;
    margin-top: 40px;
  }
  .detalles {
    margin-top: 40px;
  }
  .btnAgregar {
    margin-top: 25px;
  }
  #tablaDetalles{
    margin-top: 40px;
    display: none;
  }
  .alExito , .alError {
    display: none;
  }

  .alerts {
    margin-top: 40px;
  }
</style>

<script src="{!!URL::asset('/js/animate-colors-min.js')!!}" charset="utf-8"></script>
<script src="{!!URL::asset('/js/app/crearConfig.js')!!}" charset="utf-8"></script>
@stop
