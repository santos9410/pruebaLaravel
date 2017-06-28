$(document).ready(function() {



    $( "#btnBuscar" ).on( "click", function() {
        var carrera = $('select[name=carrera]').val();
        $.ajax({
            type: "GET",
            url: API_URL + 'grupos/grupo/' + carrera,
            data:'',
            dataType:'json',
            success: function (data) {

                if(data['accion'] === 2) {
                  $(".alInfo").show();
                  $("#tabla1").hide();
                  $(".pagi").hide();

                }
                else {
                  $(".alInfo").hide();
                  llenarTabla(data);
              }

            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

});//fin del document ready

function llenarTabla(data) {
  var $tabla = $("#tblGrupos");
   $tabla.find("tr").remove();

  var fila;
  var i = 1;


  // var grupos = ["A", "B", "C","D","E","F","G","H"];
  for (var idx in data)
  {
     fila = data[idx];

     var html =  "<tr>"+
       "<td>" + i + "</td>"+
         "<td>" + fila.alufic+ "</td>"+
         "<td>" + fila.alunom + "</td>"+
         "<td>" + fila.aluapp + "</td>";
         if(fila.aluapm === null)
            html += "<td></td>";
        else
            html += "<td>" + fila.aluapm + "</td>";

         html += "<td>" + fila.nombcar + "</td>"+
            "<td>" + fila.letra + "</td>"+
            "<td>" + fila.alucve + "</td>"+
     "</tr>";
    $tabla.append(html);
    i++;


  }

$("#tabla1").show();
if($('.pagi').length )         // use this if you are using id to check
{
  $(".pagi").remove();
}

$("#tabla1").simplePagination({
    containerClass: 'pagi',
    perPage: 40,
    previousButtonClass: "btn btn-danger",
    nextButtonClass: "btn btn-danger",
    previousButtonText: "Anterior",
    nextButtonText: "Siguiente"

  });

}
