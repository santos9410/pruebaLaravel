@extends('layout')
@section('content')
    <div class="container">
      <h3 class="text-center">Listas del Curso de Inducción</h3>
      <div class="row">
        <div class="col-sm-6">

          <button type="button" class="btn btn-success " name="button"
          id="btnTodas" style="float:right; margin: 20px;">Generar Todas las listas por materia</button>
        </div>
        <div class="col-sm-6">
            <button type="button" class="btn btn-primary" id="btnEspecificar" name="button" style="margin:20px;">Especificar Lista</button>
        </div>

      </div>
      <div class="formCarreras">

      <form action="{{ URL::to('/listas/curso/one') }}" class="form-horizontal" method="get">
        <div class="col-xs-12 col-sm-4	col-md-4	col-lg-4 ">
          <label  class="col-sm-12 control-label">Selecciona una Carrera</label>
          <div class="col-sm-12">
            <select class="form-control" name="carrera" id="selectCarrera"></select>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4	col-md-4	col-lg-4 ">
          <label  class="col-sm-12 control-label">Selecciona una Materia</label>
          <div class="col-sm-12">
            <select class="form-control selectMat" name="materia" ></select>
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
          <button type="submit" class="btn btn-success" name="button" id="buscar">Generar</button>
        </div>
      </form>
     </div>

     <div class="formTodas">
       {{--  onclick="window.location.href='/listas/curso/Todas'" --}}
     <form action="{{ URL::to('/listas/curso/Todas') }}" class="form-horizontal" method="get">

       <div class="col-xs-12 col-sm-10	col-md-10	col-lg-10">
         <label  class="col-sm-12 control-label">Selecciona una Materia</label>
         <div class="col-sm-12">
           <select class="form-control selectMat" name="materia"></select>
         </div>
       </div>

       <div class="col-xs-12 col-sm-1	col-md-1	col-lg-1">
         <br>
         <button type="submit" class="btn btn-success" name="button" id="buscar">Generar</button>
       </div>
     </form>
    </div>

    <div class="">
      @if (session('error'))
        <div class="alert alert-error">
          {{ session('error') }}
      </div>
    @endif
    </div>
    </div>


<style media="screen">
  .formCarreras , .formTodas {
    /*width: 70%;*/
    /*margin: 0 auto;*/
    margin-top: 60px;

    display: none;
  }
  .control-label {
    text-align: center !important;
  }
  #buscar {
    margin-top: 5px;
  }
  .formTodas {
    width: 60%;
    margin: 0 auto;
    margin-top: 60px;
  }
</style>
<script type="text/javascript">

</script>
<script src="{!!URL::asset('/js/app/listaCurso.js')!!}" charset="utf-8"></script>
@stop
