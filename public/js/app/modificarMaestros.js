var materia;
var idMateria;
var validado = true;
$(document).ready(function() {


  $("#btnBuscar").on("click", function() {
    materia = $('select[name=materias] option:selected').text();
    idMateria = $('select[name=materias] option:selected').val();

    if(materia !== "") {
    $.ajax({
      type: "GET",
      url: API_URL + 'grupos/datosM',
      dataType:'json',
      data: {"id":idMateria},
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




  $("#formModificar").submit(function(event) {

    validado = true;
    var data = recorrerCampos();

    if (validado) {
      $("#modificar").prop( "disabled", true );
      $.ajax({
        type: "POST",
        url: API_URL + 'grupos/modificar',
        dataType: "json",
        data: {
          "idMateria": idMateria,
          "datos": data
        },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {

          if (data['accion'] === 0) {
            limpiarCampos();
            $(".alExito").show();
            $(".alExito").fadeOut(5000, function() {});
            $(".alError").hide();
          }
          else if(data['accion'] === 2) {
            $(".alError").show();
            $(".alError").fadeOut(5000, function() {});
            $(".alExito").hide();
            $("#modificar").prop( "disabled", false );
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

});// fin del document on ready

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
        var fila2 = fila[j];
      html += "<div class='row plegable contenedorGrupos'>" +
        "<div class='row divideG'>" +
        "<div class='col-sm-3 grupos'>" +
        "<p>Grupo " + fila2.letraGrupo + "</p>" +
        "</div>" +
        "<div class='col-sm-9'>" +
        "<div class='form-group'>" +
        "<label  class='col-sm-2 control-label'>" + materia + "</label>" +
        "<div class='col-sm-10 camposActualizar'>" +
        "<input type='hidden' name='idMateriaG' class='idMateriaG' value='"+ fila2.idMateriaG +"' >" +

        "<input type='text' class='form-control maestro' class='maestro'  placeholder='Maestro' value='"+ fila2.maestro+"' id='" + item + "'>" +
        "<input type='hidden'  class='idMaestro' value='"+ fila2.idMaestro +"' id='" + (item + .1) + "'>" +

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
    "<button type='submit' class='btn btn-success' id='modificar'>Modificar Asignación de Maestros</button>" +
    "</div>";

  $("#formModificar").empty();
  $("#formModificar").prepend(html);

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

function recorrerCampos() {
  var i = 0;
  var x = 0;
  var y = 0;

  var datos = {};
  var idMateriaG, idMaestro;
  $(".camposActualizar").each(function(index){
      $(this).children("input").each(function(index2) {
        switch (index2) {
          case 0:
            idMateriaG = $(".idMateriaG")[x].value;
            if(idMateriaG === "") {
              validado = false;
            }
            x++;
            break;

          case 2:
            idMaestro = $(".idMaestro")[y].value;
            if(idMaestro === "") {
              validado = false;
            }
            y++;
          break;
          }
      });

      datos[i] = {
        "idMateriaG": idMateriaG,
        "idMaestro": idMaestro
      };
      i++;

    });
    return datos;
}
function limpiarCampos() {
  $("#formModificar").empty();
  $("#modificar").prop( "disabled",false );
}
