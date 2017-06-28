@extends('layout')
@section('content')
    <div class="container">
      <h3 class="text-center">Generar lista cuyo promedio del bachillerato es menor a 60</h3>
      <div class="row">
        <form class="" action="{!!url('/listas/promedio/')!!}" method="post" id="form">
        {{-- {{ csrf_field() }} --}}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" name="button" class="btn btn-success" id="btnG">Generar</button>
      </form>
      </div>
    </div>
    <style media="screen">
      #form {
        margin: 0 auto;
        width: 40%;
        text-align: center;
        margin-top: 40px;
      }
      #btnG {
        width: 45%;;
      }
    </style>
@stop
