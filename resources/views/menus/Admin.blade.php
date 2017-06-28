
  <nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="{!! url('/')!!}">Inicio</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">


        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            Importar Datos <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{!!url('/importar/datos/Aspirantes')!!}">Aspirantes</a></li>
            <li><a href="{!!url('/importar/datos/Registrados')!!}">Resultantes Registrados</a></li>
            <li><a href="{!!url('/importar/datos/Ceneval')!!}">Ceneval</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            Gestionar Grupos <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href='{!! url('/grupos/Crear'); !!}'>Crear Grupos</a></li>
            <li><a href='{!! url('/grupos/Asignar'); !!}'>Asignar Maestros</a></li>
            <li><a href="{!! url('/grupos/modificar')!!}">Modificar Asignación Maestros</a></li>
            <li><a href='{!! url('/grupos'); !!}'>Ver Grupos</a></li>
          </ul>
        </li>


        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            Generar Listas <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{!!url('/listas/ceneval/')!!}">Lista Aspirantes Ceneval</a></li>
            <li><a href="{!!url('/listas/curso/')!!}">Lista Curso de Inducción</a></li>
            <li><a href="{!!url('/listas/promedio/')!!}">Lista de Promedio mal</a></li>
            <li><a href="{!!url('/listas/aceptados/')!!}">Lista Aceptados</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            Gestionar Calificaciones <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{!!url('/calificaciones/registrar')!!}">Registrar Calificaciones</a></li>
            <li><a href="{!!url('/calificaciones/ver')!!}">Ver Calificaciones</a></li>
            <li><a href="{!!url('/calificaciones/modificar')!!}">Modificar Calificaciones</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            Gestionar Configuraciones <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{!!url('/config/registrar')!!}">Registrar Configuración</a></li>
            <li><a href="{!!url('/config/ver')!!}">Ver Configuraciones</a></li>
            <li><a href="{!! url('/config/modificar') !!}">Modificar Configuraciones</a></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            Funciones Secundarias <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{!!url('/materias/nuevo')!!}">Registrar Materia</a></li>
            <li><a href="{!! url('/materias/modificar') !!}">Modificar Materia</a></li>
             <li role="separator" class="divider"></li>
             <li><a href="{!!url('/aspirante/modificar')!!}">Cambio de carrera</a></li>
             <li><a href="{!! url('/aspirante/modificar/promedio')!!}">Modificar Promedio Bachillerato</a></li>
             <li><a href="{!! url('/aspirante/modificar/promedio/archivo')!!}">Subir archivo de promedios modificados</a></li>
             {{-- <li role="separator" class="divider"></li> --}}
             {{-- <li><a href="{!! url('/maestros/nuevo') !!}">Registrar Maestro</a></li> --}}
             <li role="separator" class="divider"></li>
             <li><a href="{!! url('/procesos/confirmarProceso') !!}">Iniciar Nuevo Proceso</a></li>
          </ul>
        </li>


      </ul>

      <ul class="nav navbar-nav navbar-right">

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            Opciones <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="{{ url('/auth/register') }}">Crear Nuevo Usuario</a></li>
            {{-- <li><a href="{!! url('/maestros/nuevo') !!}">Registrar Maestro</a></li> --}}

            <li>
              <a href="{{ url('/auth/logout') }}"
                  onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                  Cerrar Sesión
              </a>

              <form id="logout-form" action="{{ url('/auth/logout') }}" method="GET" style="display: none;">

                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
              </form>
            </li>


          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
