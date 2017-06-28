@extends('layout')
@section('content')
  <link rel="stylesheet" href="{!!URL::asset('/css/jquery-ui.css')!!}">

    <div class="container" style="margin-bottom:60px;">
      <h3 class="text-center">Listas de Aceptados</h3>

      <div class="col-lg-12" style="margin-top:60px;">
        <div class="container1">
        <div class="row">

        <div class="form-group col-lg-6 text-center">
          <label for="prestamo" class="col-sm-4 control-label">Fecha de Inicio</label>
          <div class="col-sm-8">
            <input type="text" class="form-control" id="fechaInicio" placeholder="Fecha de Inicio" name="fechaPrestamo" required autocomplete="off">
            <span class="help-inline msgFI">Este campo es requerido</span>
          </div>
        </div>

        <div class="form-group col-lg-6 text-center">
          <label for="prestamo" class="col-sm-4 control-label">Intervalo de Tiempo Atendiendo </label>
          <div class="col-sm-8">
            <select class="form-control" name="" id="tiempo">
              <option value="5">5 minutos</option>
              <option value="10">10 minutos</option>
              <option value="15">15 minutos</option>
              <option value="20">20 minutos</option>
              <option value="25">25 minutos</option>
              <option value="30">30 minutos</option>
              <option value="35">35 minutos</option>
              <option value="40">40 minutos</option>
              <option value="45">45 minutos</option>

            </select>
            {{-- <input type="time" class="form-control" id="tiempo" title="proporciona los minutos que pasan entre alumno y alumno"  required autocomplete="off"> --}}
            <span class="help-inline msgtiempo">Este campo es requerido</span>
          </div>
        </div>


      </div>
      <div class="row">

        <div class="form-group col-lg-6 text-center">
          <label for="prestamo" class="col-sm-4 control-label">Hora de inicio</label>
          <div class="col-sm-8">
            <input type="time" class="form-control" id="horaInicio" placeholder="Hora de Inicio" name="fechaPrestamo" required autocomplete="off">
            <span class="help-inline msgHI">Este campo es requerido</span>
          </div>
        </div>

        <div class="form-group col-lg-6 text-center">
          <label for="prestamo" class="col-sm-4 control-label">Hora Terminación</label>
          <div class="col-sm-8">
            <input type="time" class="form-control" id="horaFinal" placeholder="Hora de Terminacion" name="fechaPrestamo" required autocomplete="off">
            <span class="help-inline msgHF">Este campo es requerido</span>
            <span class="help-inline msgHF_val">La hora final es menor a la inicial</span>
          </div>
        </div>

      </div>
      <div class="row">

        <div class="form-group col-lg-6 text-center">
          <label for="prestamo" class="col-sm-4 control-label">Inicio de Hora de Descanso </label>
          <div class="col-sm-8">
            <input type="time" class="form-control" id="descansoInicio" placeholder="Inicio de Hora de Descanso"  required autocomplete="off">
            <span class="help-inline msgDI">Este campo es requerido</span>
          </div>
        </div>

        <div class="form-group col-lg-6 text-center">
          <label for="prestamo" class="col-sm-4 control-label">Terminación de Hora de Descanso</label>
          <div class="col-sm-8">
            <input type="time" class="form-control" id="descansoFinal" placeholder="Terminación de Hora de Descanso"  required autocomplete="off">
            <span class="help-inline msgDF">Este campo es requerido</span>
            <span class="help-inline msgDF_val">La hora final es menor a la inicial</span>
          </div>
        </div>

      </div>


      </div>

    </div>

    <div class="col-lg-12">

      <div class="formCarreras">
      <form role="form" class="form-horizontal">
      <div class="form-group" id="selectCar">
        <label  class="col-sm-4 control-label">Selecciona una Carrera</label>
        <div class="col-sm-7">
          <select class="form-control" name="carrera" id="selectCarrera"></select>
        </div>

      </div>
      <div class="form-group" style="margin-top:30px;">
        <div class="col-sm-4">
          <label class="col-sm-9 control-label">Ligar Carreras</label>
          <div class="form-group col-md-2">
            {{-- <p data-placement="top" data-toggle="tooltip" title="Agregar"> --}}
              <button class="btn btn-success btn-sm agregar" id="btnMas" type="button"  data-title="Agregar" >
                <span class="glyphicon glyphicon-plus">
                </span>
              </button>
            {{-- </p> --}}
          </div>

        </div>
        <div class="col-sm-8" id="divCarreras2">

      </div>

      <div class="col-sm-12 text-center" style="margin-top:50px;">
        <button type="button" class="btn btn-primary" id="btnGenerar">Generar Lista</button>
      </div>
      </form>
     </div>
   </div>

    </div>


  </div>

  <div class="row">
    <div class="alert alert-success text-center alExito" role="alert"><strong>Éxito!</strong> se han creado las listas correctamente</div>
    <div class="alert alert-danger text-center alError" role="alert">ha ocurrido un <strong>Error</strong> </div>
    <div class="alert alert-info text-center alWarning" role="alert">ha ocurrido un <strong>Error</strong> </div>
  </div>


  <!-- Modal -->
  <div class="modal fade" id="ModalAulas" tabindex="-1" role="dialog"
       aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                  <button type="button" class="close"
                     data-dismiss="modal">
                         <span aria-hidden="true">&times;</span>
                         <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel">
                    Asignar Aulas
                  </h4>
              </div>

              <!-- Modal Body -->
              <div class="modal-body">

                  <form role="form" id="formAulas">
                    <div id="camposAdd">

                  </div>

                    {{-- <button type="submit" class="btn btn-default">Submit</button> --}}
                  </form>


              </div>

              <!-- Modal Footer -->
              <div class="modal-footer">
                  <button type="button" class="btn btn-default"
                          data-dismiss="modal">
                              Cancelar
                  </button>
                  <button type="button" class="btn btn-primary" id="btnEnviar">
                      Generar Lista
                  </button>
              </div>
          </div>
      </div>
  </div>
{{-- fin del modal --}}

<style media="screen">
  .formCarreras {
    width: 60%;
    margin: 0 auto;
    margin-top: 60px;
    /*display: none;*/
  }

  .container1 {
    /*width: 60%;*/
    /*margin: 0 auto;*/

  }
  .container1 span {
    color:red;
    display: none;
  }
  .alExito , .alError , .alWarning {
    margin: 0 auto;
    width: 80%;
    display: none;
    margin-top: 20px;
    margin-bottom: 20px;
  }

  .divAulas {
    margin: 0 auto;
    width: 70%;
  }
</style>

<script src="{!!URL::asset('/js/jquery-ui.js')!!}" charset="utf-8"></script>
<script src="{!!URL::asset('/js/datepicker_es.js')!!}" charset="utf-8"></script>
<script src="{!!URL::asset('/js/app/listaAceptados.js')!!}" charset="utf-8"></script>
@stop
