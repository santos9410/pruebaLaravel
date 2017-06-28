$(document).ready(function() {
var i = 0;
var token = $('meta[name="csrf-token"]').attr('content');
  $.ajax({
    type: "GET",
    url: API_URL + 'carreras/all',
    data: '',
    dataType: 'JSON',
    success: function(data) {
      for (var valor of data) {
        $("#carreras").append("<option value=" + valor.idCar + ">" + valor.nombcar + "</option>");
      }
    },
    error: function(data) {
      console.log('Error:', data);
    }
  });

  $("#Agregar").on('click', function() {

    if (validarDetalles()) {
      var id = $("#carreras option:selected").val();
      var carrera = $("#carreras option:selected").text();
      var grupos = $("#Cgrupos").val();
      var elem = $("#Celem").val();

      if (!$("#" + id).length) {

        var fila = "<tr id='" + id + "'>" +
          "<td style='display:none;'>" + id + "</td>" +
          "<td>" + carrera + "</td>" +
          "<td>" + grupos + "</td>" +
          "<td>" + elem + "</td>" +
          "<td class='eliminar' style='width:10%'><p data-placement='top' data-toggle='tooltip' title='Eliminar'>" +
          "<button class='btn btn-danger btn-xs' data-title='Eliminar'  type='button'>" +
          "<span class='glyphicon glyphicon-trash'></span></button></p></td></tr>";

        $("#DetallesConfig").append(fila);

        $("#tablaDetalles").show();
        i++;

        limpiarCampos();
      } else {

        $("#" + id).animate({"background-color": "#CDE7B0"}, 2000, function() {
            $('#' + id).css("background-color", "#fff");
        });
      }

    }
  });

  $(document).on("click", ".eliminar", function() {
    var parent = $(this).parents().get(0);
    $(parent).remove();

    i--;
    if (i === 0) {
      $("#tablaDetalles").hide();
    }
  });

  $("#registrar").on('click', function() {
    if (validarConfig()) {
      var config = obtenerDatos();
      var detalles = recorrerTabla();
      var datos = {
        config,
        detalles
      };
      $.ajax({
          type: "POST",
          url: API_URL + 'config/crear',
          data: datos,
          dataType: 'JSON',
          headers: {
              'X-CSRF-TOKEN': token
          },
          success: function (data) {
              
              if(data['accion'] === 0) {
                limpiar();
                $(".alExito").show();
                $(".alExito").fadeOut(5000, function() {});
                $(".alError").hide();
              }

          },
          error: function (data) {
              console.log('Error:', data);
              $(".alError").show();
              $(".alExito").hide();
          }
      });

    }
  });
});

function recorrerTabla() {
  var i = 0;
  var datos = {};
  $("#DetallesConfig tr").each(function(index) {
    var campo1, campo2, campo3;
    $(this).children("td").each(function(index2) {
      switch (index2) {
        case 0:
          campo1 = $(this).text();
          break;
        case 2:
          campo2 = $(this).text();
          break;
        case 3:
          campo3 = $(this).text();
          break;

      }

    });
    datos[i] = {
      "idCar": campo1,
      "grupos": campo2,
      "elementos": campo3
    };
    i++;
  });
  return datos;
}

function obtenerDatos() {

  var datos = {};
  var nombre = $("#nombreConfig").val();
  var desc = $("#desc").val();

  datos[0] = {
    "nombreConfig": nombre,
    "descripcion": desc
  };
  return datos;
}

function validarDetalles() {
  var id = $("#carreras option:selected").val();
  var grupos = $("#Cgrupos").val();
  var elem = $("#Celem").val();
  var validado = true;

  if (id.length === 0) {
    $(".grupo").addClass("has-error");
  } else $(".grupo").removeClass("has-error");

  if (grupos.length === 0) {
    $(".elemento").addClass("has-error");
  } else $(".elemento").removeClass("has-error");


  if (id.length === 0 || grupos.length === 0 || elem.length === 0) {
    validado = false;
  }
  else {
    if(parseInt(grupos) < 1) {
      validado = false;
      $(".grupo").addClass("has-error");
    }
    else {

      $(".grupo").removeClass("has-error");
    }
    if(parseInt(elem) < 1) {
        validado = false;
      $(".elemento").addClass("has-error");
    }
    else {

      $(".elemento").removeClass("has-error");
    }
  }
  return validado;
}

function validarConfig() {

  var nombre = $("#nombreConfig").val();
  var desc = $("#desc").val();
  var validado = true;

  if (nombre.length === 0) {
    $(".nombreC").addClass("has-error");
  } else $(".nombreC").removeClass("has-error");

  if (desc.length === 0) {
    $(".descripcion").addClass("has-error");
  } else $(".descripcion").removeClass("has-error");


  if (nombre.length === 0 || desc.length === 0) {
    validado = false;
  }
  return validado;
}

function limpiarCampos() {
   $("#Cgrupos").val("");
  $("#Celem").val("");
}
function limpiarCampos_Config() {
  $("#nombreConfig").val("");
  $("#desc").val("");


}

function limpiar() {
  limpiarCampos();
  limpiarCampos_Config();
  $("#DetallesConfig tr").remove();
  $("#tablaDetalles").hide();

}
