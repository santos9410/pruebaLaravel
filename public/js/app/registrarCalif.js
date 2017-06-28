var validado = true;
var validado2 = true;
var token = $('meta[name="csrf-token"]').attr('content');

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

  $("#buscar").on('click', function() {
    var materia = $("#selectMat option:selected").val();
    var letra = $("#selectLetra option:selected").val();
    var datos = {
      'carrera': carrera,
      'materia': materia,
      'letra': letra
    };
    $.ajax({
      type: "GET",
      url: API_URL + 'grupos/one',
      data: datos,
      dataType: 'JSON',
      success: function(data) {

        if (data['accion'] === 2) {
          $(".divTable").hide();
          $(".alInfo").empty();
          $(".alInfo").text(data['message']);
          $(".alInfo").show();
        } else {
          $(".alInfo").hide();
          llenarCampos(data);
        }

      },
      error: function(data) {
        console.log('Error:', data);
      }
    });
  });

  $("#registrar").on('click', function() {

    validado = true;
    validado2 = true;
    var datos = recorrerTabla();
    if(validado2 === false) {
      alert('calificaciones fuera de rango');
      return false;
    }
    if (validado) {
      bootbox.confirm({
        title: "Registrar?",
        message: "Una vez enviadas las calificaciones no se pueden hacer modificaciones!!",
        buttons: {
          cancel: {
            label: '<i class="fa fa-times"></i> Cancelar'
          },
          confirm: {
            label: '<i class="fa fa-check"></i>Confirmar',
            className: 'btn-primary confirmar'

          }
        },
        callback: function(result) {
          if(result) {
            enviarDatos(datos);

          }
        }
      });


    } else {
      alert("Campos vacíos!!");
    }
    // console.log(datos);

  });
});

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

function llenarCampos(data) {
  var html = "";
  var item = 1;
  var grupo = data['grupo'];
  var materia_g = data['materia_grupo'];
  var idMateriaG = materia_g[0].id_MateriaG;
  for (i in grupo) {

    var fila = grupo[i];
    var nombre = fila.alunom + " " + fila.aluapp + " " + fila.aluapm;

    // var rnd = (Math.floor(Math.random() * 20) + 80);

    html += "  <tr>" +
      "<td style='display:none;'>" + idMateriaG + "</td>" +
      "<td class='text-center'>" + fila.alufic + "</td>" +
      "<td class='text-center'>" + nombre + " </td>" +
      // "<td><input type='number' name='calif' maxlength='5' class='form-control calif'></td>" +
      "<td><input type='number' name='calif' maxlength='5' class='form-control calif' ></td>" +
      "</tr>";
  }
  $("#tblCalif").empty();
  $("#tblCalif").append(html);
  $(".divTable").show();

  $('.calif').keypress(function() {
     if($(this).val().length >= 3) {
        // $(this).val($(this).val().slice(0, 3));
        return false;
    }
});
}

function recorrerTabla() {
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
          if (calif === "") {
            validado = false;
          }
          else {
            var c = parseInt(calif);
            if(c < 0 || c > 100){
              validado2 = false;
            }
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

function enviarDatos(datos) {
  $.ajax({
    type: "POST",
    url: API_URL + 'calificaciones/registro',
    data: datos,
    dataType: 'JSON',
    headers: {
      'X-CSRF-TOKEN': token
    },
    success: function(data) {
      // console.log(data);
      if (data['accion'] === 0) {
        limpiar();
        $(".alExito").show();
        $(".alExito").fadeOut(5000, function() {});
      } else if (data['accion'] === 1) {
        $(".alError").text(data['message']);
        $(".alError").show();
      } else {
        $(".alError").show();
      }
    },
    error: function(data) {
      console.log('Error:', data);
    }
  });
}

function limpiar() {
  $("#tblCalif").empty();
  $(".divTable").hide();
}
