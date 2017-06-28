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

  $("#formAsp_modificarP").submit(function(event) {
    event.preventDefault();
    var promedio = $('#promedio').val();
    if (id === -1 || promedio ==="") {
      alert("datos vacíos");

    } else {
      $.ajax({
        type: "POST",
        url: API_URL + 'aspirante/modificar/promedio',
        dataType: "json",
        data: {
          "id": id,
          "promedio":promedio
        },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {

          if (data['accion'] === 0) {
            limpiarCampos();
            $(".alExito").show();
            $(".alExito").text(data['message']);
            $(".alExito").fadeOut(5000, function() {});
            $(".alError").hide();

          } else if (data['accion'] === 1) {
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
      url: API_URL + 'aspirante/one/promedio',
    })
    .done(function(data, textStatus, jqXHR) {

      if (data[0] !== null) {
        $("#promedio").val(data['alupro']);
        $(".divModificar").show();

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
  $("#promedio").val('');
  id = -1;
}
