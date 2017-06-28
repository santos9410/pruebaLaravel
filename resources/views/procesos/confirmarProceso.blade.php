@extends('layout')
@section('content')

  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <div class="container">
    <h2 class="text-center">Nuevo Proceso</h2>

      <div class="row">
        <form class="" id="confirmar" action="{!! url('/procesos/confirmarProceso')!!}" method="post">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <button type="submit" class="btn btn-danger btn-lg btn-block nuevo" id="btnNuevo">Iniciar Nuevo Proceso</button>
        </form>
      </div>

    <div class="alerts">

      @if (session('exito'))
        <div class="alert alert-success text-center alExito" role="alert"> {{ session('exito') }}</div>
      @endif
      @if (session('info'))
        {{-- <div class="alert alert-info text-center alInfo" role="alert"> {{ session('info') }}</div> --}}
      @endif
      @if (session('error'))
        <div class="alert alert-danger text-center alError" role="alert">{{ session('error') }} </div>
      @endif
  </div>
  </div>
  <style media="screen">
    .nuevo {
      width: 30%;
      margin: 0 auto;
      margin-top: 40px;
    }
    /*.alExito , .alError {
      display: none;
    }*/

    .alerts {
      margin-top: 40px;
    }
  </style>
  <script src="{!!URL::asset('/js/bootbox.min.js')!!}" charset="utf-8"></script>
  <script type="text/javascript">
  $(document).ready(function() {

      $("#btnNuevo").on('click', function(e){
          e.preventDefault();
          bootbox.confirm({
            title: "Reiniciar?",
            message: "Esta acción hara que los datos actuales sean borrados, <strong>desea continuar?</strong> ",
            buttons: {
              cancel: {
                  label: '<i class="fa fa-times"></i> Cancelar'
              },
              confirm: {
                label: '<i class="fa fa-check"></i>Confirmar',
                className: 'btn-primary confirmar'
              }
            },
            callback: function(result) {

              if(result) {
                $('#confirmar').submit();
              }

            }
          });
      });
  });
  </script>
@stop
