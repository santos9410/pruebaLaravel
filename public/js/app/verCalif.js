
$(document).ready(function() {
var i = 0;
var primero = true;
var carrera;
  $.ajax({
    type: "GET",
    url: API_URL + 'carreras_grupo',
    data: '',
    dataType: 'JSON',
    success: function(data) {
      for (var valor of data) {
        if(primero) {
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
        $("#selectMat").append("<option value=" + valor.idMateria + ">" + valor.nombre_Mat + "</option>");
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

  $("#buscar").on('click',function() {
    var materia = $("#selectMat option:selected").val();
    var letra =  $("#selectLetra option:selected").val();
    var datos = {'carrera':carrera, 'materia':materia, 'letra':letra};
    $.ajax({
      type: "GET",
      url: API_URL + 'calificaciones/one' ,
      data: datos,
      dataType: 'JSON',
      success: function(data) {
        
          if(data['accion'] === 2) {
            $(".divTable").hide();
            $(".alInfo").empty();
            $(".alInfo").text(data['message']);
            $(".alInfo").show();
          }
          else {
            $(".alInfo").hide();
            llenarCampos(data);
          }

      },
      error: function(data) {
        console.log('Error:', data);
      }
    });
  });


});

function getLetras(carrera) {

  $("#selectLetra").empty();
  $.ajax({
    type: "GET",
    url: API_URL + 'grupos/letras/' + carrera ,
    data: '',
    dataType: 'JSON',
    success: function(data) {
        for (var valor of data) {
            $("#selectLetra").append("<option value=" + valor.letraGrupo + ">" + valor.letraGrupo + "</option>");
        }
    },
    error: function(data) {
      console.log('Error:', data);
    }
  });
}

function llenarCampos(data) {
  var html = "";

  for (i in data) {

    var fila = data[i];
    var nombre = fila.alunom + " " + fila.aluapp + " " + fila.aluapm;

    html +="  <tr>"+
        "<td class='text-center'>"+ fila.alufic +"</td>"+
        "<td class='text-center'>" + nombre + " </td>"+
        "<td class='text-center'>"+ fila.calif +"</td>"+
        "</tr>";
  }
  $("#tblCalif").empty();
  $("#tblCalif").append(html);
  $(".divTable").show();
}
