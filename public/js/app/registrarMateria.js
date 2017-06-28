$(document).ready(function() {

    $("#formMateria").submit(function(event) {
      event.preventDefault();
      var materia = $("#nombreMateria").val();
      if(materia == null || materia === "") {
        $(".nombreM").addClass('has-error');

      }
      else {
        $.ajax({
          type: "POST",
          url: API_URL + 'materias/crear',
          dataType: "json",
          data: {
            "materia":materia
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
      }

    });
});

function limpiarCampos() {
  $("#nombreMateria").val("");
}
