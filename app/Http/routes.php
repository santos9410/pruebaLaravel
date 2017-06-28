<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => ['auth']], function() {

  Route::get('/', function () {
      return view('home');
  });


  Route::group(['prefix' => 'importar/datos','middleware' =>['ability']], function () {

    Route::get('/Aspirantes', 'ImportarDatosController@AspirantesView');
    Route::post('/DataAspirantes', 'ImportarDatosController@DataAspirantesC');

    Route::get('/Registrados', 'ImportarDatosController@FoliosView');
    Route::post('/DataRegistrados', 'ImportarDatosController@DataRegistradosC');

    Route::get('/Ceneval', 'ImportarDatosController@CenevalView');
    Route::post('/DataCeneval', 'ImportarDatosController@DataCenevalC');

  });
  // Route::get('/curso', 'ListaCursoController@getListaOne');

  Route::get('/carreras/all', 'CarrerasController@getCarrerasAll');
  Route::get('/carreras/ing', 'CarrerasController@getCarreras');
  Route::get('/carrera/{id}', 'CarrerasController@getCarrera');
  // Route::get('/curso/grupos', 'ListaCursoController@crearGrupos');
  Route::get('/maestro/{nombre}', 'MaestrosController@getMaestro_Autocomplete');
  Route::get('/materias/all', 'MateriasController@getMaterias');

  Route::get('/carreras_grupo', 'ListaAceptadosController@getCarreras_grupo');


  Route::group(['prefix' => 'listas'], function () {

    Route::group(['prefix' => 'ceneval', 'middleware' =>['ability']], function () {
        Route::get('/','ListaCenevalController@getView');
        Route::get('/listasTodas','ListaCenevalController@getListas');
        Route::get('/one','ListaCenevalController@getListaOne');


    });

    Route::group(['prefix' => 'promedio', 'middleware' =>['ability']], function () {
        Route::get('/','SecundariasController@getView');
        Route::post('/','SecundariasController@generarLista');

    });

    Route::group(['prefix' => 'aceptados', 'middleware' =>['ability']], function () {
        Route::get('/','ListaAceptadosController@getView');
        Route::get('/one','ListaAceptadosController@getListaOne');
        // Route::get('/listasTodas','ListaAceptadosController@getListas');

    });
    Route::get('descargas/listaAceptados','ListaAceptadosController@getFile')->middleware('ability');

    Route::group(['prefix' => 'curso'], function () {
      Route::get('/','ListaCursoController@getView');
      Route::get('/Todas','ListaCursoController@getListas');
      Route::get('/one','ListaCursoController@getListaOne');
    });

  });
  Route::group(['prefix' => 'grupos', 'middleware' =>['ability']], function () {
    Route::get('/','GruposController@getView');
    Route::get('/Crear','GruposController@getViewCrear');
    Route::post('/CrearG','GruposController@crearGruposC');
    Route::get('/Asignar','Grupos2Controller@getViewAsignar');
    Route::get('/grupo/{carrera}','GruposController@getGrupos');

    Route::get('/creados','Grupos2Controller@getGrupos');
    Route::post('/asignar','Grupos2Controller@asignar');

    Route::get('/letras/{carrera}', 'Grupos3Controller@letrasGrupo');
    Route::get('/one', 'Grupos3Controller@oneGrupo');

    Route::get('/modificar','Grupos2Controller@getViewModificar');
    Route::get('/datosM', 'Grupos2Controller@getData_Modificar');
    Route::post('/modificar','Grupos2Controller@modificar');
  });

  Route::group(['prefix' => 'calificaciones'], function () {

    Route::get('/registrar','CalificacionesController@getViewCrear');
    Route::post('/registro','CalificacionesController@registro');

    Route::get('/ver','CalificacionesController@getViewCalif');
    Route::get('/one','CalificacionesController@getCalif');

    Route::get('/modificar','CalificacionesController@getViewModif')->middleware('ability');
    Route::post('/modificacion','CalificacionesController@modificar')->middleware('ability');

  });

  Route::group(['prefix' => 'config', 'middleware' =>['ability']], function () {
    Route::get('/registrar','ConfiguracionesController@registrarView');
    Route::post('/crear','ConfiguracionesController@crearConfig');
    Route::get('/ver','ConfiguracionesController@VerConfig_View');
    Route::get('/todas','ConfiguracionesController@getConfig');
    Route::get('/detalles/{id}','ConfiguracionesController@getDetalles');

    Route::get('/modificar','ConfiguracionesController@modificarView');
    Route::get('/one','ConfiguracionesController@getConfig_autocomplete');

    Route::get('/data_modif','ConfiguracionesController@getDatos_modificar');
    Route::post('/modificar','ConfiguracionesController@modificarConfig');

    Route::get('/one_all','ConfiguracionesController@get_oneConfig');
  });

  Route::group(['prefix' => 'maestros', 'middleware' =>['ability']], function () {
    Route::get('/nuevo','MaestrosController@ViewNuevo');
    Route::post('/crear','MaestrosController@crear');
  });

  Route::group(['prefix' => 'materias', 'middleware' =>['ability']], function () {
    Route::get('/nuevo','MateriasController@ViewNuevo');
    Route::post('/crear','MateriasController@crear');

    Route::get('/one_autocomplete','MateriasController@one_autocomplete');

    Route::get('/modificar','MateriasController@ViewModificar');
    Route::post('/modificar','MateriasController@modificar');

  });

  Route::group(['prefix' => 'aspirante', 'middleware' =>['ability']], function () {

    Route::get('/modificar','AspirantesController@ViewModificar');
    Route::get('/one_autocomplete','AspirantesController@one_autocomplete');
    Route::get('/one','AspirantesController@one_carrera');
    Route::post('/modificar','AspirantesController@Modificar');


    Route::get('/modificar/promedio','AspirantesController@ViewModificarPromedio');
    Route::get('/one/promedio','AspirantesController@one_promedio');
    Route::post('/modificar/promedio','AspirantesController@modificarPromedio');

    Route::get('/modificar/promedio/archivo','AspirantesController@ViewPromedio_Archivo');
    Route::post('/modificar/promedio/archivo','AspirantesController@Promedio_Archivo');

  });

    Route::group(['prefix' => 'procesos', 'middleware' =>['ability']], function () {

      Route::get('/confirmarProceso','NuevoProceso@getView');
      Route::post('/confirmarProceso','NuevoProceso@Reiniciar');
    });

});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
