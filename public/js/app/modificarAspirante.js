var id = -1;
var carrera = -1;
$(document).ready(function() {

  $('#ficha').autocomplete({
    source: function(request, response) {

      $(".divModificar").hide();
      buscador(request.term, response);

    },
    select: function(event, ui) {
      // event.preventDefault();
      id = ui.item.label;
      buscador2(id);

    },
    minLength: 1
  });
  $('#selectCarrera').on('change', function() {

    var texto = $(this).find(":selected").text();

    $("#CC").text(texto);
  })

  $("#formAsp_modificar").submit(function(event) {
    event.preventDefault();
    var idCarrera = $('#selectCarrera option:selected').val();
    if (id === -1 || carrera === -1 || idCarrera === "") {


    } else {
      $.ajax({
        type: "POST",
        url: API_URL + 'aspirante/modificar',
        dataType: "json",
        data: {
          "id": id,
          "idCarreraActual": carrera,
          "idCarreraModificado":idCarrera
        },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {

          if (data['accion'] === 0) {
            limpiarCampos();
            $(".alExito").show();
            $(".alExito").text(data['message'] + " cambio de aula a: => " + data['aula']);
            // $(".alExito").fadeOut(5000, function() {});
            $(".alError").hide();

          } else if (data['accion'] === 1) {
            $(".alError").show();
            $(".alError").text(data['message']);
            $(".alError").fadeOut(5000, function() {});
            $(".alExito").hide();
          }
          else if(data['accion' === 2]) {
            $(".alError").show();
            $(".alError").text(data['message']);
            $(".alError").fadeOut(5000, function() {});
            $(".alExito").hide();
          }

        },
        error: function(data) {
          console.log('Error:', data);
        }
      });
    }
  });
}); // fin del document on ready


function buscador(id, response) {

  $.ajax({
      data: {
        "id": id
      },
      type: "GET",
      dataType: "json",
      url: API_URL + 'aspirante/one_autocomplete',
    })
    .done(function(data, textStatus, jqXHR) {

      if (data[0] !== null) {
        response(data);

      } else {
        $(".divModificar").hide();
        id = -1;
      }

    })
    .fail(function(jqXHR, textStatus, errorThrown) {
      if (console && console.log) {
        console.log("La solicitud a fallado: " + textStatus);
      }
    });


}

function buscador2(id) {

  $.ajax({
      data: {
        "id": id
      },
      type: "GET",
      dataType: "json",
      url: API_URL + 'aspirante/one',
    })
    .done(function(data, textStatus, jqXHR) {

      if (data[0] !== null) {
        var datos = data['datos'];
        var carreras = data['carreras'];
        var carreraActual = datos['carcve1'];

        $("#selectCarrera").empty();
        for (var valor of carreras) {
          $("#selectCarrera").append("<option value=" + valor.idCar + ">" + valor.nombcar + "</option>");

        }
        $("#selectCarrera option[value='" + carreraActual + "']").attr("selected", true);
        $("#selectCarrera option[value='" + carreraActual + "']").css('background', '#ff0');
        carrera = carreraActual;
        $(".divModificar").show();
        nombcar = $("#selectCarrera option:selected").text();
        $("#CA").text(nombcar);
        $("#CC").text(nombcar);
      }

    })
    .fail(function(jqXHR, textStatus, errorThrown) {
      if (console && console.log) {
        console.log("La solicitud a fallado: " + textStatus);
      }
    });


}

function limpiarCampos() {
  $(".divModificar").hide();
  $("#ficha").val('');
  id = -1;
}
