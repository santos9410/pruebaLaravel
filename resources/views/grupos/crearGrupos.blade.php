@extends('layout')
@section('content')

  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <div class="container">
    <h2 class="text-center">Crear Grupos</h2>
    @if ($crear == false)
      <div class="alert alert-info text-center" role="alert"><h2 class="display-4"><strong>Atención!</strong> Los grupos sólo se crean una vez</h2></div>
    @else

      <div class="row">
        <form class="" action="{!! url('/grupos/CrearG')!!}" method="post">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <button type="submit" class="btn btn-primary btn-lg btn-block crear" id="btnCrearGrupos">Crearlos Automáticamente</button>
        </form>
      </div>

    @endif
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
    .crear {
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
  {{-- <script src="/js/app/crearGrupos.js" charset="utf-8"></script> --}}
@stop
