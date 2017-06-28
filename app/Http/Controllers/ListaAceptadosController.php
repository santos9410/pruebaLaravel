<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aspirantes;
use App\Grupos;
use App\Calif;
use App\Materias;
use App\Config;
use App\D_Config;
use App\Carreras;
use Excel;
use Zipper;

class ListaAceptadosController extends Controller
{
  private $num = 1;
  private $fechaInicio;
  private $horaInicio;
  private $horaInicio2;


  public function getView() {

    // $data = $this->getCarreras_grupo();
    return view('listas/listaAceptados');
  }

  public function getFile(Request $request) {
    $input = $request->all();
    if(isset($input['nombFile'])) {
      $nombFile = $input['nombFile'];
      if(file_exists(public_path() . '/descargas/' . $nombFile )) {

        return response()->download(public_path('descargas/' . $nombFile))
        ->deleteFileAfterSend(true);
      }
      else {
        return json_encode(['accion'=>2, 'message'=>'ha ocurrido un error al intentar descargar los archivos']);
      }
    }
      return json_encode(['accion'=>2, 'message'=>'ha ocurrido un error al intentar descargar los archivos']);

  }

  public function getCarreras_grupo() {

    $data = Carreras::where('idCar','!=',0)->get();
    foreach ($data as $key) {
      $idCar = $key['idCar'];
      $anio = date('y');
      $idGrupo = $anio. '-'. $idCar.'%';
      $dataGrupos = Grupos::where('idGrupo','like',$idGrupo)->get();
      if(count($dataGrupos) > 0) {
        $carreras[] = $key;
      }

    }

    return $carreras;
  }


  public function getListaOne(Request $request) {
    $input = $request->all();


    if( isset($input['fechas']) && isset($input['carreras']) && isset($input['aulas'])) {
      $fechas = $input['fechas'];
      $carreras_lig = $input['carreras'];
      $aulas = $input['aulas'];

      if(count($carreras_lig) == 1) {

        $this->fechaInicio = $fechas['fechaInicio'];
        $this->fechaInicio = str_ireplace('/','-',$this->fechaInicio);
        $this->fechaInicio = date('Y-m-d',strtotime($this->fechaInicio));
        $this->horaInicio = $fechas['horaInicio'];
        $this->horaInicio2 = $fechas['horaInicio'];

        $nomZip = 'listaAceptados('. date('i:s'). ').zip';
        $zipper = Zipper::make('descargas/' . $nomZip);


        $id = $carreras_lig[0];
        $grupos = $this->crearGrupos($id);
        if(isset($grupos['accion'])) {
            $zipper->close();
            return json_encode($grupos);
        }

        $aceptados_arr = $this->aceptados_puntaje($grupos, $id, $aulas);

        $ordenados_puntaje = $aceptados_arr[0];
        $ordenados_nombre = $aceptados_arr[1];
        $faltantes = $aceptados_arr[2];



        $terminados_arr = $this->asignarFechas($fechas, $ordenados_puntaje,$ordenados_nombre);
        $aux_puntaje = $terminados_arr[0];
        $aux_nombre = $terminados_arr[1];


        $asp = Carreras::where('idCar','=',$id)->get()->first();
        $nombcar = $asp['nombcar'];

        $nomFile =  $this->crearArchivo($aux_nombre, $nombcar, 'nombre');
        $file = storage_path('excel/listasAceptados/' .$nomFile);
        $zipper->add($file);


        $nomFile = $this->crearArchivo($aux_puntaje, $nombcar, 'puntaje');
        $file = storage_path('excel/listasAceptados/' .$nomFile);
        $zipper->add($file);

        if(count($faltantes) > 0) {
          $nomFile = $this->crearArchivo_faltantes($faltantes, $nombcar);
          $file = storage_path('excel/listasAceptados/' .$nomFile);
          $zipper->add($file);
        }
        $zipper->close();

        echo json_encode(['file'=> $nomZip]);

      }
      else if(count($carreras_lig) > 1) {

          $this->fechaInicio = $fechas['fechaInicio'];
          $this->fechaInicio = str_ireplace('/','-',$this->fechaInicio);
          $this->fechaInicio = date('Y-m-d',strtotime($this->fechaInicio));
          $this->horaInicio = $fechas['horaInicio'];
          $this->horaInicio2 = $fechas['horaInicio'];

          $nomZip = 'listaAceptados('. date('i:s'). ').zip';
          $zipper = Zipper::make('descargas/' . $nomZip);

          foreach ($carreras_lig as $key) {
              $id = $key;
              $grupos = $this->crearGrupos($id);
              if(isset($grupos['accion'])) {
                  $zipper->close();
                  return json_encode($grupos);
                  // return back()->with('accion',$grupos['message']);
              }

              $aceptados_arr = $this->aceptados_puntaje($grupos, $id, $aulas);
              $ordenados_puntaje = $aceptados_arr[0];
              $ordenados_nombre = $aceptados_arr[1];
              $faltantes = $aceptados_arr[2];

              $terminados_arr = $this->asignarFechas($fechas, $ordenados_puntaje,$ordenados_nombre);
              $aux_puntaje = $terminados_arr[0];
              $aux_nombre = $terminados_arr[1];


              $asp = Carreras::where('idCar','=',$id)->get()->first();
              $nombcar = $asp['nombcar'];

              $nomFile =  $this->crearArchivo($aux_nombre, $nombcar, 'nombre');
              $file = storage_path('excel/listasAceptados/' .$nomFile);
              $zipper->add($file);

              $nomFile = $this->crearArchivo($aux_puntaje, $nombcar, 'puntaje');
              $file = storage_path('excel/listasAceptados/' .$nomFile);
              $zipper->add($file);

              if(count($faltantes) > 0) {
                $nomFile = $this->crearArchivo_faltantes($faltantes, $nombcar);
                $file = storage_path('excel/listasAceptados/' .$nomFile);
                $zipper->add($file);
              }

          }
           $zipper->close();
           echo json_encode(['file'=> $nomZip]);
      }
     }


  }

  private function crearGrupos($idCar) {

    $anio = date('y');
    $idGrupo = $anio. '-'. $idCar.'%';
    $dataGrupos = Grupos::join('DFICHA','DFICHA.alufic','=','grupos.alufic')
    ->where('idGrupo','like',$idGrupo)->select('grupos.alufic','alupro','calificacionCeneval')->get();

    $materias = Materias::get();
    $cantMaterias = count($materias);

    if(count($dataGrupos) > 0) {
      foreach ($dataGrupos as $key) {
          $alufic = $key['alufic'];
          $ceneval = $key['calificacionCeneval'];
          $promedio = $key['alupro'];
          $data3 = Calif::where('alufic','=',$alufic)->get();
          $calif = 0;
          if(count($data3) == $cantMaterias) {
            for ($i = 0; $i < $cantMaterias; $i++) {
              $calif += ($data3[$i]['calif'] * 0.30);
            }

          }
          else {
            return ['accion'=>3,'message'=>'faltan calificaciones por registrar!!'];
          }

          $ceneval = (($ceneval - 600) * 100) / 700;
          $general = ($ceneval * 0.4) + ($promedio * 0.3) + ($calif * 0.3);


          $ordenados[$alufic] = $general;
          // $this->ordenados_puntaje[$alufic] = $general;
      }
      arsort($ordenados);

      return $ordenados;
    }
    else {
      return ['accion'=>2,'message'=>'Grupos no creados!!'];
    }
  }


  private function aceptados_puntaje($grupos, $idCar, $aulas) {


    $dataConfig = Config::join('detalles_config as dc','dc.idConfig','=','config.idConfig')
    ->where('nombre','=','ACEPTADOS')->where('idCar','=',$idCar)->get()->first();

    if(count($dataConfig) > 0) {
      $cant_Grupos = $dataConfig['cant_Grupos'];
      $cant_Elem = $dataConfig['cant_Elem_Grupo'];

    }
    else {
        return ['accion'=>2,'message'=>'Configuración no establecida!!'];
    }

    $i_grupo = 1;
    $j_elem = 1;
    $letras = ['A','B','C','D','E','F','G','H','I','J'];

    $recorrido = 0;

      // foreach ($aux_nombre as $key => $value) {

    foreach ($grupos as $key => $value) { // clasificado por puntaje lo ideal

        $alufic = $key;

        if($recorrido == $cant_Grupos) {
          $recorrido = 0;
        }
        // $letra = $letras[$recorrido];
        $recorrido ++;
        $value = $grupos[$alufic];

        if($i_grupo > $cant_Grupos ) {
          $puntaje = (($value * 700) / 100) + 600;

          $asp = Aspirantes::where('alufic','=',$alufic)->get()->first();
          $nombre = ($asp['aluapp'] . ' ' . $asp['aluapm'] . ' ' . $asp['alunom']);

          $faltaron[] = ['alufic'=>$alufic, 'nombre'=>$nombre, 'puntaje'=>$puntaje] ;
          // break;
        }
        else {
          $puntaje = (($value * 700) / 100) + 600;

          $asp = Aspirantes::where('alufic','=',$alufic)->get()->first();
          $nombre = ($asp['aluapp'] . ' ' . $asp['aluapm'] . ' ' . $asp['alunom']);

          $ordenados_puntaje[$alufic] = ['alufic'=>$alufic, 'nombre'=>$nombre,
           'puntaje'=>$puntaje, 'letra' =>'', 'aula'=>'', 'hora'=>'', 'dia'=>''];
            // $aux_puntaje[$alufic] = $puntaje;

            // $ordenados_nombre[$alufic] = ['alufic'=>$alufic, 'nombre'=>$nombre,
            // 'puntaje'=>$puntaje, 'letra' =>$letra,'hora'=>'','dia'=>''];
          $aux_nombre[$alufic] = $nombre;

          $j_elem ++;

        }

        if($j_elem > $cant_Elem) {
          $j_elem = 1;
          $i_grupo ++;
        }

    }


    // if(isset($aux_puntaje)) {
    //   unset($aux_nombre);
    // }

    asort($aux_nombre); //ordenamos la lista por nombre
    //
    $recorrido = 0;

    foreach ($aux_nombre as $key => $value) {
      if($recorrido == $cant_Grupos) {
        $recorrido = 0;
      }
      $letra = $letras[$recorrido];
      $puntero = $idCar . '_' . $recorrido;
      $aula = $aulas[$puntero];
      $recorrido ++;

      $alufic = $key;
      $dato = $ordenados_puntaje[$alufic];
      $ordenados_puntaje[$alufic]['letra'] = $letra;
      $ordenados_puntaje[$alufic]['aula'] = $aula;

      $ordenados_nombre[$alufic] = ['alufic'=>$alufic, 'nombre'=>$dato['nombre'],
      'puntaje'=>$dato['puntaje'],'letra'=>$letra, 'aula'=>$aula, 'hora'=>'','dia'=>''];

    }


      if(isset($faltaron)) {
        return [$ordenados_puntaje, $ordenados_nombre, $faltaron];
      }
      else {
        return [$ordenados_puntaje, $ordenados_nombre,[]];
      }

  }

  private function asignarFechas($fechas, $ordenados_puntaje, $ordenados_nombre) {


          $horaFinal = $fechas['horaFinal'];
          $descansoI = $fechas['descansoInicio'];
          $descansoF = $fechas['descansoFinal'];
          $tiempo = $fechas['tiempo'];

          $fechaInicio = $this->fechaInicio;
          $horaInicio = $this->horaInicio2;

            $meses = array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");

            $nuevafecha = $fechaInicio;
            $nuevaHora = $horaInicio;


            $keys = array_keys($ordenados_nombre);

            for($i=0; $i < count($keys); $i ++) {


            if( date('N',strtotime($nuevafecha)) !=6 && (date('N',strtotime($nuevafecha))!=7 ) ) {

              $key = $keys[$i];
               if(strtotime($nuevaHora) < strtotime($horaFinal) ) {


                 if(strtotime($nuevaHora) > strtotime('-'.$tiempo.' minutes',strtotime( $descansoI)) && strtotime($nuevaHora) < strtotime($descansoF)) {
                  // $descansoF = strtotime('+'.$tiempo.' minutes', strtotime($descansoF));
                  // $descansoF = date('H:i',$descansoF);

                  $nuevaHora = $descansoF;
                  $i --;
                 }
                 else {
                  //  echo $i . " -> ";
                  //  echo $nuevaHora . " " ;
                  //  echo $nuevafecha . "\n";


                   $ordenados_puntaje[$key]['hora'] = $nuevaHora;

                   $aux_fecha = strtotime($nuevafecha);
                   $dia = (date('d',$aux_fecha) . " " . $meses[date('n',$aux_fecha) -1]);

                   $ordenados_puntaje[$key]['dia'] = $dia;

                   $alufic = $key;

                   $ordenados_nombre[$alufic]['hora'] = $nuevaHora;
                   $ordenados_nombre[$alufic]['dia'] = $dia;

                   $nuevaHora = strtotime('+'.$tiempo.' minutes', strtotime ( $nuevaHora ) );
                   $nuevaHora = date('H:i',$nuevaHora);
                 }

               }
               else {

                    $nuevafecha = strtotime ( '+1 day' , strtotime ( $nuevafecha ) ) ;
                    $nuevafecha = date('Y-m-d', $nuevafecha);

                    $nuevaHora = $this->horaInicio;
                    $i --;
               }
             }
             else {
                   $nuevafecha = strtotime ( '+1 day' , strtotime ( $nuevafecha ) ) ;
                   $nuevafecha = date('Y-m-d', $nuevafecha);
                   $i --;
                 }
           }

           $this->fechaInicio = $nuevafecha;
           $this->horaInicio2 = $nuevaHora;

          return [$ordenados_puntaje, $ordenados_nombre];

  }

  private function crearArchivo($aux, $nombcar, $clasif) {
    $items = 1;


    foreach ($aux as $key ) {

      $aux2[] = $key;
      $items ++;
      if($items > 50) {
        $data[] = $aux2;
        unset($aux2);
        $items = 1;
      }

    }
    if(isset($aux2)) {
      $data[] = $aux2;
      unset($aux2);
    }


    Excel::create($nombcar.'_por_'.$clasif, function($excel) use ($nombcar,$clasif, $data) {
      $hoja = 0;
      $this->num = 1;

      foreach ($data as $key ) {
        $hoja ++;

        $excel->sheet('Aceptados Hoja'.$hoja, function($sheet) use($nombcar,$hoja, $key) {

          $sheet->setAutoSize(array('A', 'C'));

          $sheet->setHeight(1, 40);
          $sheet->mergeCells('A1:H1');
          $sheet->setCellValue('A1', "Instituto Tecnológico de Ciudad Guzmán".PHP_EOL. $nombcar);

          $sheet->cells('A1', function($cells) {$cells->setAlignment('center');});
          $sheet->getStyle('A1')->getFont()->setBold(true);

          $sheet->setHeight(2, 15);
          $sheet->mergeCells('A2:F2');
          $sheet->setCellValue('A2', 'LISTA DE ACEPTADOS');
          $sheet->cells('A2', function($cells) {$cells->setAlignment('');});
          $sheet->getStyle('A2')->getFont()->setBold(true);

          $sheet->setHeight(2, 15);
          $sheet->setWidth('G', 12);
          $sheet->setWidth('H', 12);
          $sheet->mergeCells('G2:H2');
          $sheet->setCellValue('G2', 'Entrega de Documentos');
          $sheet->cells('G2', function($cells) {$cells->setAlignment('right');});
          $sheet->getStyle('G2')->getFont()->setBold(true);

            $sheet->setHeight(3, 15);
          $sheet->setCellValue('A3', 'No.');

          $sheet->setCellValue('B3', 'Ficha');
          $sheet->cells('B3', function($cells) {$cells->setAlignment('center');});

          $sheet->setCellValue('C3', 'Nombre');
          $sheet->cells('C3', function($cells) {$cells->setAlignment('center');});

          $sheet->setCellValue('D3', 'Puntaje');
          $sheet->cells('D3', function($cells) {$cells->setAlignment('center');});

          $sheet->setCellValue('E3', 'Grupo');
          $sheet->cells('E3', function($cells) {$cells->setAlignment('center');});

          $sheet->setCellValue('F3', 'Aula');
          $sheet->cells('F3', function($cells) {$cells->setAlignment('center');});

          $sheet->setCellValue('G3', 'Hora');
          $sheet->cells('G3', function($cells) {$cells->setAlignment('center');});

          $sheet->setCellValue('H3', 'Día');
          $sheet->cells('H3', function($cells) {$cells->setAlignment('center');});


          $fila = 4;

           foreach ($key as $item) {
             $sheet->setHeight($fila, 20);
             $sheet->setCellValue('A'. $fila, $this->num );
             $sheet->setCellValue('B'. $fila, $item['alufic'] );

             $sheet->setCellValue('C'. $fila, $item['nombre'] );

             $sheet->setCellValue('D'. $fila, $item['puntaje'] );
             $sheet->cells('D'.$fila, function($cells) {$cells->setAlignment('center');});

             $sheet->cells('E'.$fila, function($cells) {$cells->setAlignment('center');});
             $sheet->setCellValue('E'. $fila, $item['letra'] );

             $sheet->cells('F'.$fila, function($cells) {$cells->setAlignment('center');});
             $sheet->setCellValue('F'. $fila, $item['aula'] );

             $sheet->cells('G'.$fila, function($cells) {$cells->setAlignment('center');});
             $sheet->setCellValue('G'. $fila, $item['hora'] );

             $sheet->cells('H'.$fila, function($cells) {$cells->setAlignment('center');});
             $sheet->setCellValue('H'. $fila, $item['dia'] );

             $fila ++;
             $this->num ++;
           }



           $sheet->setBorder('A1:H1', 'thin');
           $sheet->setBorder('A2:H2', 'thin');

           $sheet->setBorder('A3:H'.($fila -1), 'thin');

           $sheet->setPageMargin(array(0.8, 0.8, 0.8, 0.6));


        });

      }

      // if(count($faltantes) > 0) {
      //     $this->crearHoja_faltantes($excel, $nombcar, $faltantes);
      // }

    })->store('xls', storage_path('excel/listasAceptados'));

  //   if(isset($aux)) {
  //     unset($aux);
  //   }
  //   if(isset($faltantes)) {
  //     unset($faltantes);
  //   }
    return $nombcar.'_por_'.$clasif.'.xls';
  }

  private function crearArchivo_faltantes($faltantes, $nombcar) {

    $this->num = 1;

      Excel::create('no_aceptados_'.$nombcar, function($excel) use($faltantes,$nombcar) {

        $excel->sheet('NO ACEPTADOS', function($sheet) use($nombcar, $faltantes) {

          $sheet->setAutoSize(array('A', 'C'));

          $sheet->setHeight(1, 40);
          $sheet->mergeCells('A1:D1');
          $sheet->setCellValue('A1', "Instituto Tecnológico de Ciudad Guzmán".PHP_EOL. $nombcar);

          $sheet->cells('A1', function($cells) {$cells->setAlignment('center');});
          $sheet->getStyle('A1')->getFont()->setBold(true);

          $sheet->setHeight(2, 15);
          $sheet->mergeCells('A2:D2');
          $sheet->setCellValue('A2', 'LISTA DE NO ACEPTADOS');
          $sheet->cells('A2', function($cells) {$cells->setAlignment('');});
          $sheet->getStyle('A2')->getFont()->setBold(true);


          $sheet->setCellValue('A3', 'No.');

          $sheet->setCellValue('B3', 'Ficha');
          $sheet->cells('B3', function($cells) {$cells->setAlignment('center');});

          $sheet->setCellValue('C3', 'Nombre');
          $sheet->cells('C3', function($cells) {$cells->setAlignment('center');});

          $sheet->setCellValue('D3', 'Puntaje');
          $sheet->cells('D3', function($cells) {$cells->setAlignment('center');});



        $fila = 4;

         foreach ($faltantes as $item) {
           $sheet->setHeight($fila, 20);
           $sheet->setCellValue('A'. $fila, $this->num );
           $sheet->setCellValue('B'. $fila, $item['alufic'] );

           $sheet->setCellValue('C'. $fila, $item['nombre'] );

           $sheet->setCellValue('D'. $fila, $item['puntaje'] );
           $sheet->cells('D'.$fila, function($cells) {$cells->setAlignment('center');});




           $fila ++;
           $this->num ++;
         }



         $sheet->setBorder('A1:D1', 'thin');
         $sheet->setBorder('A2:D2', 'thin');

         $sheet->setBorder('A3:D'.($fila -1), 'thin');

         $sheet->setPageMargin(array(0.8, 0.8, 0.8, 0.6));
     });
   })->store('xls', storage_path('excel/listasAceptados'));

   return 'no_aceptados_'. $nombcar.'.xls';
  }

}
