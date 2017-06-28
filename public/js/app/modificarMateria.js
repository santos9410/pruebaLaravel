var id = -1;
$(document).ready(function() {

  $('#nombreMateria').autocomplete({
    source: function(request, response) {
      $(".nombreM2").hide();
      buscador(request.term, response);

    },
    select: function(event, ui) {
      event.preventDefault();
      id = ui.item.value;

      $(this).val(ui.item.label);
      $(".nombreM2").show();
    },
    minLength: 1
  });


    $("#formMateria").submit(function(event) {
      event.preventDefault();
      var nombre = $("#nombreMateria2").val();
      if(id === -1 || nombre === "") {
        if(id === -1) {
          $(".nombreM").addClass('has-error');
        }
        else {
          $(".nombreM").removeClass('has-error');
        }
        if(nombre === "") {
          $(".nombreM2").addClass('has-error');
        }
        else {
          $(".nombreM2").removeClass('has-error');
        }
      }
      else {
        $(".nombreM").removeClass('has-error');
        $(".nombreM2").removeClass('has-error');
        $.ajax({
          type: "POST",
          url: API_URL + 'materias/modificar',
          dataType: "json",
          data: {
            "idMateria":id,
            "nombre":nombre
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

            }

          },
          error: function(data) {
            console.log('Error:', data);
          }
        });
      }

    });
});

function buscador(nombre, response) {

  $.ajax({
      data: {
        "nombre": nombre
      },
      type: "GET",
      dataType: "json",
      url: API_URL + 'materias/one_autocomplete',
    })
    .done(function(data, textStatus, jqXHR) {

      if (data[0] !== null) {
        response(data);
        
      }
      else {
        id = -1;
          $(".nombreM2").hide();
      }

    })
    .fail(function(jqXHR, textStatus, errorThrown) {
      if (console && console.log) {
        console.log("La solicitud a fallado: " + textStatus);
      }
    });


}

function limpiarCampos() {
  $("#nombreMateria").val("");
  $("#nombreMateria2").val("");
}
