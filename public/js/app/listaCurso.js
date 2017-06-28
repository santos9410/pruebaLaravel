$(document).ready(function() {
  console.log("documento listo");
  var v = false;
  var v2 = false;
  var i = 0;
  var primero = true;
  var carrera;

    $( "#btnEspecificar" ).on( "click", function() {
      if(v2) {
        $(".formTodas").hide();
        v2 = false;
      }
      if(!v) {
        $(".formCarreras").show();
        v = true;
      }
      else  {
        $(".formCarreras").hide();
        v = false;
      }
    });

    $( "#btnTodas" ).on( "click", function() {
      if(v) {
        $(".formCarreras").hide();
        v = false;
      }
      if(!v2) {
        $(".formTodas").show();
        v2 = true;
      }
      else  {
        $(".formTodas").hide();
        v2 = false;
      }
    });

    $.ajax({
      type: "GET",
      url: API_URL + 'carreras/all',
      data: '',
      dataType: 'JSON',
      success: function(data) {
        $("#selectCarrera").empty();
        for (var valor of data) {
          if (primero) {
            carrera = valor.idCar;
            primero = false;

          }
          $("#selectCarrera").append("<option value=" + valor.idCar + ">" + valor.nombcar + "</option>");

        }
        getLetras(carrera);

      },
      error: function(data) {
        console.log('Error:', data);
      }
    });

    $.ajax({
      type: "GET",
      url: API_URL + 'materias/all',
      data: '',
      dataType: 'JSON',
      success: function(data) {
        for (var valor of data) {
          $(".selectMat").append("<option value=" + valor.idMateria + ">" + valor.nombre_Mat + "</option>");
        }
      },
      error: function(data) {
        console.log('Error:', data);
      }
    });

    $("#selectCarrera").on('change', function() {
      carrera = $(this).val();
      getLetras(carrera);
    });


}); // fin del document on ready

function getLetras(carrera) {

  $("#selectLetra").empty();
  $.ajax({
    type: "GET",
    url: API_URL + 'grupos/letras/' + carrera,
    data: '',
    // dataType: 'JSON',
    success: function(data) {

      if (data[0] !== null) {
        for (var valor of data) {
          $("#selectLetra").append("<option value=" + valor.letraGrupo + ">" + valor.letraGrupo + "</option>");

        }
        $("#buscar").prop('disabled', false);
      } else {
        $(".alInfo2").show();
        $("#buscar").prop('disabled', true);
      }
    },
    error: function(data) {
      console.log('Error:', data);
    }
  });
}
