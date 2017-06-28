var materia;
var idMateria;
$(document).ready(function() {


  $("#btnBuscar").on("click", function() {
    materia = $('select[name=materias] option:selected').text();
    idMateria = $('select[name=materias] option:selected').val();

    if(materia !== "") {
    $.ajax({
      type: "GET",
      url: API_URL + 'grupos/creados',
      dataType:'json',
      data: '',
      success: function(data) {

        if(data['accion'] === 2) {
          $(".alInfo").show();
        }
        else {
          $(".alInfo").hide();
        llenarCampos(data, materia);
        }
      },
      error: function(data) {
        console.log('Error:', data);
      }
    });
  }
  });


  $(document).on("click", ".verMas", function() {

    var caja = $(this).parent().nextAll();
    if (caja.hasClass("plegable")) {
      caja.removeClass("plegable");
      caja.addClass("desplegado");
      $(this).text("Plegar ");
      $(this).append("<span i class='icono glyphicon glyphicon-menu-up'></span>");
    } else if (caja.hasClass("desplegado")) {
      caja.removeClass("desplegado");
      caja.addClass("plegable");
      $(this).text("Desplegar ");
      $(this).append("<span i class='icono glyphicon glyphicon-menu-down'></span>");
    }
    return false;
  });




  $("#formAsignar").submit(function(event) {
    var data = {};
    var valida = true;
    $("#formAsignar").serializeArray().map(function(x) {

      if (x.value === "") {
        valida = false;
      }
      data[x.name] = x.value;
    });

    if (valida) {

      $.ajax({
        type: "POST",
        url: API_URL + 'grupos/asignar',
        dataType: "json",
        data: {
          "materia": idMateria,
          "datos": data
        },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {

          if (data['accion'] === 0) {
            limpiarCampos();
            $(".alExito").show();
            $(".alExito").fadeOut(3000, function() {
              location.reload();
            });
            $(".alError").hide();
          }
          else if(data['accion'] === 1) {
            $(".alError").show();
            $(".alError").fadeOut(5000, function() {});
            $(".alExito").hide();
          }

        },
        error: function(data) {
          console.log('Error:', data);
        }
      });


    } else {
      alert("faltan datos");
    }
    event.preventDefault();
  });

});

function llenarCampos(data, materia) {
  var html = "";
  var item = 1;
  for (i in data) {

    var fila = data[i];
    var nombcar = data[i][0].nombcar;
    var id = data[i][0].idCar;


    html += " <div class='col-sm-12 divideCar'>" +
      "<h3 class='text-center textoCarrera' id='" + id + "'>" + nombcar + " <a href='#' class='verMas'>Desplegar <span i class='icono glyphicon glyphicon-menu-down'></span></a> </h3>";
    // html += "<input type='hidden' name='carrera' id='"+id +"' value='"+id+"'>";
    for (j in fila) {
      html += "<div class='row plegable contenedorGrupos'>" +
        "<div class='row divideG'>" +
        "<div class='col-sm-3 grupos'>" +
        "<p>Grupo " + fila[j].letraGrupo + "</p>" +
        "</div>" +
        "<div class='col-sm-9'>" +
        "<div class='form-group'>" +
        "<label  class='col-sm-2 control-label'>" + materia + "</label>" +
        "<div class='col-sm-10'>" +
        "<input type='text' class='form-control maestro' class='maestro'  placeholder='Maestro' id='" + item + "'>" +
        "<input type='hidden' name='" + fila[j].idGrupo + "' class='idMaestro' id='" + (item + .1) + "' >" +
        "</div>" +
        "</div>" +
        "</div>" +

        "</div>" +
        "</div>";
      item++;
    }
    html += "</div>";

  }
  html += "<div class='form-group text-center'>" +
    "<button type='submit' class='btn btn-primary'>Registrar Maestros</button>" +
    "</div>";

  $("#formAsignar").empty();
  $("#formAsignar").prepend(html);

  $('.maestro').autocomplete({
    source: function(request, response) {

      buscador(request.term, response);

    },
    select: function(event, ui) {
      event.preventDefault();
      $(this).val(ui.item.label);
      var Div = $(this).attr("id");
      var Div = parseFloat(Div);
      var idDiv = Div + 0.1;

      document.getElementById(idDiv).value = ui.item.value;
    },
    minLength: 1
  });
}

function buscador(nombre, response) {

  $.ajax({
      data: {
        "nombre": nombre
      },
      type: "GET",
      // dataType: "json",
      url: API_URL + 'maestro/' + nombre,
    })
    .done(function(data, textStatus, jqXHR) {

      if (data[0] !== null) {
        response(data);

      }

    })
    .fail(function(jqXHR, textStatus, errorThrown) {
      if (console && console.log) {
        console.log("La solicitud a fallado: " + textStatus);
      }
    });


}

function limpiarCampos() {
  $("#formAsignar").empty();
}
