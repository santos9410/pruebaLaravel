@extends('layout')
@section('content')

<script src="{!!URL::asset('/js/jquery.simplePagination.js')!!}"></script>

    <h3 class="text-center">Ver grupos por Carrera</h3>
    <div class="container">
      <div class="form-group" id="selectCar">
        <label  class="col-sm-3 control-label">Selecciona una Carrera</label>
        <div class="col-sm-9">
          <select class="form-control" name="carrera" id="selectCarrera">
            @foreach ($data as $item)
                <<option value="{{$item->idCar}}">{{$item->nombcar}}</option>
            @endforeach

          </select>
        </div>
      </div>
       <button type="submit" class="btn btn-primary" id="btnBuscar">Buscar</button>
    </div>

    <div class="container">
      <div class="table-responsive" style="margin-top:40px;margin-bottom:40px;">

          <table class="table table-striped" id="tabla1" style="display:none;">
            <thead>
                <tr>
                  <td>#</td>
                  <td>Ficha</td>
                  <td>Nombre</td>
                  <td>Apellido Paterno</td>
                  <td>Apellido Materno</td>
                  <td>Carrera</td>
                  <td>Grupo</td>
                  <td>Aula</td>

                </tr>
            </thead>
            <tbody id="tblGrupos"></tbody>
          </table>


  </div>
  <div class="alert alert-info text-center alInfo" role="alert">No se han creado grupos!! </div>

    <style media="screen">
      #selectCar {
        width: 70%;
        margin: 0 auto;
        margin-top: 30px;
      }
      .alInfo {
        display: none;
      }
    </style>


    <script src="{!!URL::asset('/js/app/grupos.js')!!}" charset="utf-8"></script>
@stop
