@extends('layout')
@section('content')

<script src="{!!URL::asset('/js/jquery.simplePagination.js')!!}"></script>

    <h3 class="text-center">Ver Configuraciones</h3>

    <div class="container">
      <div class="table-responsive" style="margin-top:40px;margin-bottom:40px;">

          <table class="table table-striped" id="tabla1Config" style="display:none;">
            <thead>
                <tr>
                  <td>#</td>
                  <td>Nombre</td>
                  <td>Descripción</td>
                  <td>Detalles</td>

                </tr>
            </thead>
            <tbody id="tblConfig"></tbody>
          </table>


  </div>
  <div class="modal fade" id="ModalConfig">
<div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h3 class="modal-title">Detalles de las Configuraciones</h3>
        </div>
        <div class="modal-body">
		  {{-- <h5 class="text-center">Hello. Some text here.</h5> --}}
          <table class="table table-striped" id="tblGrid">
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
		</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
        </div>

      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <style media="screen">
    #tabla1Config {
      margin-bottom: 40px;
    }
  </style>
    <script src="{!!URL::asset('/js/app/ver_Config.js')!!}" charset="utf-8"></script>
@stop
