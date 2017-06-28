var carreras = [];
var ligadas = [];
var cant_ligadas = 2;
var i2 = 0;

$(document).ready(function() {

  $("#fechaInicio").datepicker({
    dateFormat: "dd/mm/yy",
    changeMonth: true,
    numberOfMonths: 1,
    onClose: function(selectedDate) {

    }
  });
  $("#fechaInicio").keypress(function(e) {

    return false;
  });




  $.ajax({
    type: "GET",
    url: API_URL + 'carreras_grupo',
    data: '',
    dataType: 'json',
    success: function(data) {

      $("#selectCarrera").empty();
      var i = 0;
      for (var valor of data) {
        var id = valor.idCar;
        var nombre = valor.nombcar;
        $("#selectCarrera").append("<option value=" + id + ">" + nombre + "</option>");
        carreras[i] = {
          "id": id,
          "nombre": nombre
        };
        i++;
      }
      var idPrimero = $("#selectCarrera option:selected").val();
      if (ligadas.length === 0) {
        ligadas.push(idPrimero);
      }

    },
    error: function(data) {
      console.log('Error:', data);
    }
  });

  $("#btnMas").on('click', function() {

    var idPrimero = $("#selectCarrera option:selected").val();

    var html = '<div class="contenedor"> <div class="col-sm-10" style="margin-bottom:20px;">' +
      '<select class="form-control col-sm-8 carreras_opt" id="carreraLigada' + i2 + '"  name="carrera" ></select>' +
      '</div>' +
      '<div class="col-sm-1">' +
      '<button class="btn btn-danger btn-xs eliminar" data-title="Eliminar"  type="button">' +
      '<span class="glyphicon glyphicon-trash"></span></button>' +
      '</div>' +
      '</div>';

    if (ligadas.length < carreras.length && ligadas.length < cant_ligadas) {
      $("#divCarreras2").append(html);

    } else {
      return false;
    }

    if (ligadas.length === 0) {
      ligadas.push(idPrimero);
    }

    for (item of carreras) {
      var agregar = true;
      for (lig of ligadas) {

        if (parseInt(item.id) === parseInt(lig)) {
          agregar = false;
        }

      }
      if (agregar === true) {
        $("#carreraLigada" + i2).append("<option value=" + item.id + ">" + item.nombre + "</option>");

      }

    }

    var valor = $("#carreraLigada" + i2).prop("option", "select").val();
    var push = true;
    for (lig of ligadas) {

      if (parseInt(valor) === parseInt(lig)) {
        push = false;
      }

    }
    if (push === true) {
      ligadas.push(valor);

    }

    i2++;

  });

  $(document).on('change', '.carreras_opt', function() {
    recorrerSelect();
  });


  $(document).on("click", ".eliminar", function() {
    var parent = $(this).parents().get(0);

    var contenedor = $(parent).parents().get(0);
    var aux = $(contenedor).children().get(0);
    var aux2 = $(aux).children().get(0);

    var id = $(aux2).val();
    if (id !== "" || id !== null) {
      ligadas.splice($.inArray(id, ligadas), 1);
    }
    contenedor.remove();

  });


  $("#btnGenerar").on('click', function() {

    if( validarCampos() === true )  {

      asignarAulas();
    }


  });

  $("#btnEnviar").on('click', function(){
    var aulas = {};
    var seguir = true;
    $("#formAulas").serializeArray().map(function(x){
      if(x.value === "" || x.value === null) {
        seguir = false;
      }
      aulas[x.name] = x.value;
    });

    if(seguir === true) {
      $(".msgAulas").hide();

      recorrerSelect();
      var fechas = recorrerCampos();

      var datos = {"fechas":fechas, "carreras":ligadas, "aulas":aulas};

      $('#ModalAulas').modal('hide');

      generarLista(datos);

    }
    else {
      if(!$(".msgAulas").length ) {
        $("#camposAdd").append("<span style='color:red;display:block;' class='text-center help-inline msgAulas'>Campos de texto  Vacíos!!</span>");
      }
    }


  });

}); // fin del document on ready

function recorrerSelect() {
  ligadas.length = 0;
  var idPrimero = $("#selectCarrera option:selected").val();
  ligadas.push(idPrimero);
  $(".carreras_opt").each(function() {
    var valor = $(this).prop("option", "select").val();
    var push = true;

    for (lig of ligadas) {

      if (parseInt(valor) === parseInt(lig)) {
        push = false;
      }

    }
    if (push === true) {

      ligadas.push(valor);

    }
  });


}


function generarLista(datos) {
  $("#btnGenerar").prop('disabled',true);
  $.ajax({
    type: "GET",
    url: API_URL + 'listas/aceptados/one',
    data: datos,
    dataType: 'json',
    success: function(data) {
      
      $("#btnGenerar").prop('disabled',false);

      if(data['accion'] === 2) {
        $(".alError").show();
      }
      else if(data['accion'] === 3) {
        $(".alWarning").show();
        $(".alWarning").text(data['message']);
      }
      else if(data['file']) {

        var file = data['file'];
        document.location.href = API_URL +'listas/descargas/listaAceptados?' + 'nombFile=' + file;


        $(".alError").hide();
        $(".alWarning").hide();
      }

    },
    error: function(data) {
      console.log('Error:', data);
      $("#btnGenerar").prop('disabled',false);
      $(".alError").show();
    }
  });


}

function validarCampos() {
  var validador = true;
  var fi = $("#fechaInicio").val();


  if(fi.length === 0 ) {

    if(fi.length === 0) {
      $(".msgFI").show();
    }
    else {
      $(".msgFI").hide();
    }

    validador = false;
  }
  else {
    $(".msgFI").hide();

  }

    var hi = $("#horaInicio").val();
    var hf = $("#horaFinal").val();
    if(hi.length === 0 || hf.length === 0) {

      if(hi.length === 0) {
        $(".msgHI").show();
      }
      else {
        $(".msgHI").hide();
      }
      if(hf.length === 0) {
        $(".msgHF").show();
      }
      else {
        $(".msgHF").hide();
      }
      validador = false;
    }
    else {
        $(".msgHI").hide();
        $(".msgHF").hide();

      if(hf <= hi) {
          $(".msgHF_val").show();
          validador = false;
      }
      else {
          $(".msgHF_val").hide();
      }
    }

    var di = $("#descansoInicio").val();
    var df = $("#descansoFinal").val();

    if(di.length === 0 || df.length === 0) {
      if(di.length === 0) {
        $(".msgDI").show();
      }
      else {
        $(".msgDI").hide();
      }
      if(df.length === 0) {
        $(".msgDF").show();
      }
      else {
        $(".msgDF").hide();
      }
      validador = false;
    }
    else {

      $(".msgDI").hide();
      $(".msgDF").hide();

      if(df <= di) {
        validador = false;
        $(".msgDF_val").show();
      }
      else {
        $(".msgDF_val").hide();
      }
    }

    var tiempo = $("#tiempo").val();
    if(tiempo === null || tiempo === "") {
      $(".msgtiempo").show();
      validador = false;
    }
    else {
      $(".msgtiempo").hide();
    }

  return validador;
}

function recorrerCampos() {
  var fi = $("#fechaInicio").val();

  var hi = $("#horaInicio").val();
  var hf = $("#horaFinal").val();
  var di = $("#descansoInicio").val();
  var df = $("#descansoFinal").val();
  var tiempo = $("#tiempo option:selected").val();

  var data = [];

  data = {'fechaInicio':fi, 'horaInicio':hi, 'horaFinal':hf,
  'descansoInicio':di, 'descansoFinal':df, 'tiempo':tiempo};

  return data;
}

function asignarAulas() {

  $("#camposAdd").empty();

    recorrerSelect();

  for (var id of ligadas) {
    var grupos = buscar_configGrupos(id);
    var cant_Grupos = grupos['cant_Grupos'];
    var nombcar = grupos['nombcar'];
    $("#camposAdd").append("<h4 class='text-center'>"+ nombcar +" </h4>");
    for (var i = 0; i < cant_Grupos; i++) {
      var html = '<div class="form-group">'+
         '<label>Aula ' + (i + 1) +' </label>'+
           '<input type="text" class="form-control"'+
           'name ="'+ id + '_' + i + '" placeholder="Ingrese un nombre de aula"/>'+
       '</div>';

      $("#camposAdd").append(html);
    }

    }

  $('#ModalAulas').modal('show');
}

function buscar_configGrupos(id) {
  var datos;
  $.ajax({
    type: "GET",
    async:false,
    url: API_URL + 'config/one_all',
    data: {'nombre':'ACEPTADOS', 'id':id},
    dataType: 'json',
    success: function(data) {
      datos = data;

    },
    error: function(data) {
      console.log('Error:', data);
    }
  });
  return datos;
}
