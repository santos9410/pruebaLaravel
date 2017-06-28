var idC = -1;
var validado = true;
var validado2 = true;
var token = $('meta[name="csrf-token"]').attr('content');


$(document).ready(function() {


  $.ajax({
      type: "GET",
      dataType: "json",
      url: API_URL + 'config/todas',
    })
    .done(function(data, textStatus, jqXHR) {

      if (data[0] !== null) {
        $("#selectConfig").empty();
        for(var valor of data ) {

          $("#selectConfig").append("<option value=" + valor.idConfig + ">" + valor.nombre + "</option>");
        }

      }
      else {
        idC = -1;
        $(".alInfo").show();
      }

    })
    .fail(function(jqXHR, textStatus, errorThrown) {
      if (console && console.log) {
        console.log("La solicitud a fallado: " + textStatus);
      }
    });

  $("#buscar").on('click', function(){
    idC = $("#selectConfig option:selected").val();
    buscarDatos(idC);
    // var letra =  $("#selectLetra option:selected").val();

  });

  $("#modificar").on('click',function(){

    var config = recorrerTablaConfig();
    var detalles = recorrerTablaDetalles();
    if(validado && idC !== -1) {
      var datos = {"config":config,'detalles':detalles};

      $.ajax({
        type: "POST",
        url: API_URL + 'config/modificar',
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
          else if(data['accion'] === 2) {
            $(".alError").show();
            $(".alError").fadeOut(5000, function() {});
          }

        },
        error: function(data) {
          console.log('Error:', data);
        }
      });
    }
  });
});


 function buscarDatos(id) {
  $.ajax({
      data: {
        "id": id
      },
      type: "GET",
      dataType: "json",
      url: API_URL + 'config/data_modif',
    })
    .done(function(data, textStatus, jqXHR) {
      if(data[0] !== null) {
        llenarTabla(data);
      }


    })
    .fail(function(jqXHR, textStatus, errorThrown) {
      if (console && console.log) {
        console.log("La solicitud a fallado: " + textStatus);
      }
    });
}

function llenarTabla(data) {

  var config = data[0]['config'];
  var detalles = data[0]['detalles'];
  var html = "";
  for(i in config) {
    var fila = config[i];
    html +="  <tr>"+
        "<td class='text-center'>"+ fila.idConfig +"</td>"+
        "<td class='text-center'><input type='text' name='grupos' maxlength='50' class='form-control nombre'  disabled value='"+ fila.nombre +"'></td>"+
        "<td class='text-center'><textarea class='form-control desc' rows='4' id='desc' maxlength='200' >"+ fila.descripcion+"</textarea></td>"+
        "</tr>";
  }
  $("#tblConfig").empty();
  $("#tblConfig").append(html);

  var html2 = "";
  for(j in detalles) {
    var fila2 = detalles[j];
    html2 += "<tr>" +
      "<td style='display:none;'>" + fila2.idCar + "</td>"+
      "<td>" + fila2.nombcar + "</td>"+
      "<td><input type='number' name='grupos' maxlength='5' class='form-control grupos' value='"+ fila2.cant_Grupos +"'></td>"+
      "<td><input type='number' name='elem' maxlength='5' class='form-control elem' value='"+ fila2.cant_Elem_Grupo +"'></td>" +
    "</tr>";
  }
  $("#tblDetalles").empty();
  $("#tblDetalles").append(html2);
  $("#divTablas").show();
}

function recorrerTablaConfig() {
  var i = 0;
  var k = 0;
  var l = 0;
  var datos = {};
  $("#tblConfig tr").each(function(index) {
    var idConfig, nombre, desc;
    $(this).children("td").each(function(index2) {
      switch (index2) {
        case 0:
          idConfig = $(this).text();
          break;
        case 1:
          nombre = $(".nombre")[k].value;
          k++;
          if(nombre === "") {
            validado = false;

          }
          break;
        case 2:
          desc = $(".desc")[l].value;
          if(desc === "") {
            validado = false;
          }
          l++;
          break;

      }

    });
    datos[i] = {
      "idConfig": idConfig,
      "nombre": nombre,
      "desc": desc
    };
    i++;
  });

  return datos;
}

function recorrerTablaDetalles() {
  var i = 0;
  var k = 0;
  var l = 0;
  var datos = {};
  $("#tblDetalles tr").each(function(index) {
    var idCar, grupos, elem;
    $(this).children("td").each(function(index2) {
      switch (index2) {
        case 0:
          idCar = $(this).text();
          if(idCar === "") {
            validado2 = false;
          }
          break;
        case 2:
          grupos = $(".grupos")[k].value;
          k++;
          if(grupos === "") {
            validado2 = false;

          }
          break;
        case 3:
          elem = $(".elem")[l].value;
          if(elem === "") {
            validado2 = false;
          }
          l++;
          break;

      }

    });
    datos[i] = {
      "idCar": idCar,
      "grupos": grupos,
      "elem": elem
    };
    i++;
  });

  return datos;
}

function limpiar() {
  $("#tblConfig").empty();
  $("#tblDetalles").empty();
  $("#divTablas").hide();
  idC = -1;
  $("#nombreConfig").val("");
}
