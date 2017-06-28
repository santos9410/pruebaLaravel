$(document).ready(function() {

  $.ajax({
      type: "GET",
      url: API_URL + 'config/todas',
      data:'',
      dataType: 'JSON',
      success: function (data) {
          
          llenarTabla(data);

      },
      error: function (data) {
          console.log('Error:', data);
      }
  });

}); //Fin del document ready

function llenarTabla(data) {
  var $tabla = $("#tblConfig");
   $tabla.find("tr").remove();

   var i = 1;
   for (var idx in data)
   {
      fila = data[idx];
      var id = fila.idConfig;
      var html =  "<tr>"+
        "<td>" + i + "</td>"+
          "<td>" + fila.nombre + "</td>"+
          "<td>" + fila.descripcion + "</td>"+
          "<td class='controles'>"+
          "<div class='dropdown'>"+
            "<button class='btn btn-primary dropdown-toggle' type='button' data-toggle='dropdown'>"+
            "Opciones"+
            "<span class='caret'></span></button>"+
            "<ul class='dropdown-menu'>"+
              "<li role='presentation'><a role='menuitem'  href='#myModal' role='button' class='btn btn-custom verConfig' data-toggle='modal' id='"+ id +"'>Ver Detalles</a></li>"+
            "</ul>"+
          "</div>"+

        "</td>"+
      "</tr>";
     $tabla.append(html);
     i++;
   }
   $("#tabla1Config").show();
   $('.verConfig').on('click',function(e){
      var id = e.target.id;
      buscarDetalles(id);


    e.preventDefault();
  });
}

function buscarDetalles(id) {
  $.ajax({
      type: "GET",
      url: API_URL + 'config/detalles/' + id,
      data:'',
      dataType: 'JSON',
      success: function (data) {
          // console.log(data);
          llenarTablaDetalles(data);

      },
      error: function (data) {
          console.log('Error:', data);
      }
  });
}

function llenarTablaDetalles(data) {
  var $tabla = $("#DetallesConfig");
   $tabla.find("tr").remove();

   for (var idx in data)
   {
      fila = data[idx];

      var html =  "<tr>"+
        "<td>" + fila.nombcar + "</td>"+
        "<td>" + fila.cant_Grupos + "</td>"+
        "<td>" + fila.cant_Elem_Grupo + "</td>"+
      "</tr>";
     $tabla.append(html);

   }
   $('#ModalConfig').modal('show');

}
