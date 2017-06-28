@extends('layout')
@section('content')
    <div class="container">
      <h3 class="text-center">Listas Examen Ceneval</h3>
      <div class="row">
        <div class="col-sm-6">
          {{-- @php --}}
            {{-- $listas = URL::to('/listas/ceneval/listasTodas'); --}}
            {{-- echo $listas; --}}
          {{-- @endphp --}}

          <button type="button" class="btn btn-success todas" name="todas" style="float:right; margin: 20px;"
           onclick="window.location.href='{{URL::to('/listas/ceneval/listasTodas')}}'" >Generar Todas las listas</button>
        </div>
        <div class="col-sm-6">
            <button type="button" class="btn btn-primary" id="btnEspecificar" name="button" style="margin:20px;">Especificar Lista</button>
        </div>

      </div>
      <div class="formCarreras">
      <form action="{{ URL::to('/listas/ceneval/one') }}" class="form-horizontal" method="get">
      <div class="form-group" id="selectCar">
        <label  class="col-sm-3 control-label">Selecciona una Carrera</label>
        <div class="col-sm-6">
          <select class="form-control" name="carrera" id="selectCarrera">
            @foreach ($data as $item)
                <<option value="{{$item->idCar}}">{{$item->nombcar}}</option>
            @endforeach

          </select>
        </div>
        <button type="submit" class="btn btn-primary" id="btnBuscar">Generar Lista</button>
      </div>
      </form>
     </div>

    </div>
    </div>

<style media="screen">
  .formCarreras {
    width: 70%;
    margin: 0 auto;
    margin-top: 60px;
    display: none;
  }
</style>
<script type="text/javascript">
$(document).ready(function() {
  var v = false;

    $( "#btnEspecificar" ).on( "click", function() {
      if(!v) {
        $(".formCarreras").show();
        v = true;
      }
      else  {
        $(".formCarreras").hide();
        v = false;
      }
    });

  });
</script>
@stop
