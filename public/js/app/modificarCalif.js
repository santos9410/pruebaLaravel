var validado = true;
var token = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function() {
var i = 0;
var primero = true;
var carrera;
  $.ajax({
    type: "GET",
    url: API_URL + 'carreras/all',
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

  $("#modificar").on('click',function(e) {

    validado = true;
    var datos = recorrerTabla(validado);
    if(validado) {
      $(this).prop( "disabled", true );
      $.ajax({
        type: "POST",
        url: API_URL + 'calificaciones/modificacion',
        data: datos,
        dataType: 'JSON',
        headers: {
            'X-CSRF-TOKEN': token
        },
        success: function(data) {

          if(data['accion'] === 0) {
            limpiar();
            $(".alExito").show();
            $(".alExito").fadeOut(5000, function() {});
          }
          else if(data['accion'] === 1) {
            $(".alError").text(data['message']);
            $(".alError").show();
            $(this).prop( "disabled", false );
          }
          else {
            $(".alError").show();
          }
        },
        error: function(data) {
          console.log('Error:', data);
          $(this).prop( "disabled", false );
        }
      });
    }
    else {
      alert("Existen campos vacios!!");
    }
    

  });
  e.preventDefault();

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
  var item = 1;


  for (i in data) {

    var fila = data[i];
    var nombre = fila.alunom + " " + fila.aluapp + " " + fila.aluapm;

    html +="  <tr>"+
        "<td style='display:none;'>"+ fila.idMateriaG +"</td>"+
        "<td class='text-center'>"+ fila.alufic +"</td>"+
        "<td class='text-center'>" + nombre + " </td>"+
        "<td><input type='number' name='calif' maxlength='50' class='form-control calif' value = '"+ fila.calif +"'></td>"+
        "</tr>";
  }
  $("#tblCalif").empty();
  $("#tblCalif").append(html);
  $(".divTable").show();
}

function recorrerTabla(callback) {
  var i = 0;
  var k = 0;
  var datos = {};
  $("#tblCalif tr").each(function(index) {
    var idMateriaG, alufic, calif;
    $(this).children("td").each(function(index2) {
      switch (index2) {
        case 0:
          idMateriaG = $(this).text();
          break;
        case 1:
          alufic = $(this).text();
          break;
        case 3:
          calif = $(".calif")[k].value;
          if(calif === "") {
            validado = false;
          }
          k++;
          break;

      }

    });
    datos[i] = {
      "idMateriaG": idMateriaG,
      "alufic": alufic,
      "calif": calif
    };
    i++;
  });

  return datos;
}

function limpiar() {
  $("#tblCalif").empty();
  $(".divTable").hide();
  $("#modificar").prop( "disabled", false );
}
