
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport"    content="width=device-width, initial-scale=1.0">
    <title>SISENI</title>


    <link rel="stylesheet" href="{!!URL::asset('/css/bootstrap.css')!!}" >
    <link rel="stylesheet" href="{!!URL::asset('/css/app/mainAdmin.css')!!}" >


      <script src="{!!URL::asset('/js/jquery.min.js')!!}" charset="utf-8"></script>
      <script src="{!!URL::asset('/js/bootstrap.min.js')!!}" charset="utf-8"></script>


  </head>
  <body>
    <!-- Header -->
  <header class="header header1">
  <div class="container-fluid">

    <div class="row">

        <div class="col-xs-12 col-sm-2" id="img1">
          <img src="{!!URL::asset('/img/logoSep2.jpg')!!}"  class="imgHeader img-responsive" alt="Responsive image" />
        </div>
        <div class="col-xs-12 col-sm-8" id="texto">
          <h1>TECNOLÓGICO NACIONAL DE MÉXICO</h1>
          <h2>INSTITUTO TECNOLÓGICO DE CIUDAD GUZMÁN</h2>
          <h3>Sistema de Selección de Nuevo Ingreso </h3>
        </div>
  <!-- Optional: clear the XS cols if their content doesn't match in height -->
      <div class="clearfix visible-xs-block"></div>
      <div class="col-xs-12 col-sm-2" id="img2">
        <img src="{!!URL::asset('/img/logoItcg.png')!!}"  class="img-responsive imgHeader imgItcg" alt="Responsive image" />
      </div>
    </div>


  </div>
  </header>
  <!-- /Header -->

    @if (Auth::check())

     @if(Auth::user()->role == "admin")
       @include('menus.Admin')
      @endif
    @endif
  <div class="container">
    @if (Auth::check())
      <h2 class="text-center">Bienvenido {{ Auth::user()->role }}</h2>
    @endif

    {{-- @include('menus.Admin') --}}
  </div>
<!-- ***Sección principal*** -->
    <section class="container home">


          <div class="row">
            <div class="col-xs-6 col-md-3">
              {{-- <a  class="thumbnail"> --}}
              <div class="col-md-12" style="border:1px solid #ddd">

                <img class="text-center img img-responsive" style="margin:0 auto;" src="{!!URL::asset('/img/subir-archivos.png')!!}" alt="cargando..."/>
                <h5>Importar Datos</h5>
              </div>
              {{-- </a> --}}
            </div>
            <div class="col-xs-6 col-md-3">
              {{-- <a  class="thumbnail"> --}}
              <div class="col-md-12" style="border:1px solid #ddd">
                <img class="text-center img img-responsive" style="margin:0 auto;" src="{!!URL::asset('/img/grupos.png')!!}" alt="cargando..." />
                <h5>Gestionar Grupos</h5>
              {{-- <</a> --}}
            </div>
            </div>
            <div class="col-xs-6 col-md-3">
              {{-- <a  class="thumbnail"> --}}
              <div class="col-md-12" style="border:1px solid #ddd">
                <img class="text-center img img-responsive" style="margin:0 auto;"  src="{!!URL::asset('/img/listas.png')!!}" alt="cargando..." />
                <h5>Generar Listas</h5>
              {{-- </a> --}}
            </div>
            </div>
            <div class="col-xs-6 col-md-3">
              {{-- <a  class="thumbnail"> --}}
              <div class="col-md-12" style="border:1px solid #ddd">
                <img class="text-center img img-responsive" style="margin:0 auto;" src="{!!URL::asset('/img/calif.png')!!}" alt="cargando..." />
                <h5>Gestionar Calificaciones</h5>
              {{-- </a> --}}
            </div>
            </div>
            <div class="col-xs-6 col-md-3" style="margin-top:20px;">
              {{-- <a  class="thumbnail"> --}}
              <div class="col-md-12" style="border:1px solid #ddd">
                <img class="text-center img img-responsive" style="margin:0 auto;" src="{!!URL::asset('/img/config.png')!!}" alt="cargando..." />
                <h5>Gestionar Configuraciones</h5>
              {{-- </a> --}}
            </div>
            </div>
            <div class="col-xs-6 col-md-3" style="margin-top:20px;">
              {{-- <a  class="thumbnail"> --}}
              <div class="col-md-12" style="border:1px solid #ddd">
                <img class="text-center img img-responsive" style="margin:0 auto;" src="{!!URL::asset('/img/cambios.png')!!}" alt="cargando..." />
                <h5>Funciones Secundarias</h5>
              {{-- </a> --}}
              </div>
            </div>

          </div>

    </section>
  </body>
</html>
