$(document).ready(function() {

    $("#formMaestro").submit(function(event) {
      event.preventDefault();
      var maestro = $("#nombreMaestro").val();
      if(maestro == null || maestro === "") {
        $(".nombreM").addClass('has-error');

      }
      else {
        $.ajax({
          type: "POST",
          url: API_URL + 'maestros/crear',
          dataType: "json",
          data: {
            "maestro":maestro
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
  $("#nombreMaestro").val("");
}
