<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aspirantes;
use App\Carreras;
use Excel;
use Zipper;

class ListaCenevalController extends Controller
{
    private $num = 1;
    public function getView() {
      $data = Carreras::where('idCar','!=',0)->get();
      return view('listas/listaCeneval',compact('data'));
    }

    public function getListaOne(Request $request) {
      if(isset($request['carrera'])) {
        $idCar = $request['carrera'];
        $nomFile = $this->crearArchivo($idCar);
        return response()->download(storage_path('excel/listasCeneval/' .$nomFile));
      }

      return back()->with('error','Ha ocurrido un error al intentar crear los archivos');
    }


    public function getListas() {
      $dataCarreras = Aspirantes::distinct()->select('carcve1')->groupBy('carcve1')->get();

      if (!empty($dataCarreras)) {
        $zipper = Zipper::make('descargas/ListasCeneval.zip');
          foreach ($dataCarreras as $valor) {

              $idCar = $valor['carcve1'];

              $nomFile = $this->crearArchivo($idCar);
              $file = glob(storage_path('excel/listasCeneval/' .$nomFile));

              $zipper->add($file);

            }
            $zipper->close();

            return response()->download(public_path('descargas/ListasCeneval.zip'));
      }


      	return back()->with('error','Ha ocurrido un error al intentar crear los archivos');



    }

    private function crearArchivo($idCar) {

        $data = Aspirantes::join('carreras', 'carreras.idCar', '=', 'DFICHA.carcve1')
            ->where('DFICHA.carcve1', '=', $idCar)->select('DFICHA.*', 'carreras.nombcar')
            ->orderBy('alucve','ASC')->orderBy('alufic','ASC')->get();

        if (!empty($data))
        {
          // echo json_encode($data);
          // return "";
          $Grupos = $this->CrearGrupos($data);
          $sub = $this->CrearSub_Grupos($Grupos);
          $letras = ['A','B','C','D','E','F','G','H','I','J'];
          $subnumero = 0;
          $g = 0;
          $r = 0;
          $this->num = 1;

           Excel::create('Filename', function($file) use($sub,$letras, $subnumero, $g, $r)
           {
             foreach ($sub as $valores)
             {
               $subnumero ++;
               if($subnumero > 2)
               {
                 $subnumero = 1;
                 $g++;
                 $this->num = 1;
               }

               $file->sheet('Grupo '. $letras[$g]. $subnumero , function($sheet)  use($valores, $subnumero, $r, $file)
               {
                  $fila = 8;
                  // $num = 1;


                  $sheet->setCellValue('E2', 'TECNOLÓGICO DE CD. GUZMÁN');
                  $sheet->setCellValue('E3', 'LISTA DE ASPIRANTES');
                  $sheet->setCellValue('I2', 'AULA ' . $valores[0]['alucve']);
                  $r++;

                  $sheet->setCellValue('B6', 'FICHA');
                  $sheet->setCellValue('C6', 'APELLIDO P');
                  $sheet->setCellValue('D6', 'APELLIDO M');
                  $sheet->setCellValue('E6', 'NOMBRE (S)');
                  $sheet->setCellValue('F6', 'FOLIO');
                  $sheet->setCellValue('G6', 'CARRERA');
                  $sheet->setCellValue('H6', 'VERSION');
                  $sheet->setCellValue('I6', 'FIRMA DE ENTRADA');
                  $sheet->setCellValue('J6', 'FIRMA DE SALIDA');

                  $this->carrera = $valores[0]['nombcar'];
                    foreach ($valores as $valor) {
                        $sheet->setHeight($fila, 30);
                        $sheet->setAutoSize(true);

                          /************************/
                          $sheet->setCellValue('A'.$fila ,$this->num);
                          $sheet->cells('A'.$fila, function($cells) {
                            $cells->setAlignment('left');
                          });
                          /************************/
                          $sheet->setCellValue('B'.$fila ,$valor['alufic']);
                          $sheet->cells('B'.$fila, function($cells) {
                            $cells->setAlignment('left');
                          });
                          /*************************/
                          $sheet->setCellValue('C'.$fila ,$valor['aluapp']);
                          $sheet->cells('C'.$fila, function($cells) {
                            $cells->setAlignment('left');
                          });

                          $sheet->setCellValue('D'.$fila ,$valor['aluapm']);
                          $sheet->cells('D'.$fila, function($cells) {
                            $cells->setAlignment('left');
                          });

                          $sheet->setCellValue('E'.$fila ,$valor['alunom']);
                          $sheet->cells('E'.$fila, function($cells) {
                            $cells->setAlignment('left');
                          });
                          // $folio  = rand(100000000,999999999);
                          //**********************************************
                          $sheet->setCellValue('F'.$fila ,$valor['folio']);
                          $sheet->cells('F'.$fila, function($cells) {
                            $cells->setAlignment('left');
                          });
                          //***********************************************
                          $sheet->setCellValue('G'.$fila ,$valor['carcve1']. ' ' .$valor['nombcar']);
                          $sheet->setCellValue('H'.$fila ,'_________');
                          $sheet->setCellValue('I'.$fila ,'__________________________');
                          $sheet->setCellValue('J'.$fila ,'__________________________');

                          $fila ++;
                          $this->num ++;
                    }

                    /*******estilos************/
                    $sheet->setOrientation('landscape');
                    // Set top, right, bottom, left
                    $sheet->setPageMargin(array(
                        0.8, 0.8, 0.8, 0.8
                    ));


                    // Set all borders (top, right, bottom, left)
                    $sheet->cells('A1:J'.($fila), function($cells) {
                      $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });

                    //
                    /**********************/
                    $file->setFilename($this->carrera);
                });
              }
            })->store('xls', storage_path('excel/listasCeneval'));
          }

          return $this->carrera . '.xls';
      }


  private function CrearGrupos($data) {
      $i = 0;
      $dataGrupos;

      foreach ($data as $valor) {

        $aux[] = $valor;
        $i++;

        if($i == 39) { // cada grupo es generado por 40 personas
          $dataGrupos[] = $aux;
          unset($aux);
          $i = 0;
        }

      }

      if(isset($aux)) {
        $dataGrupos[] = $aux;
      }


      $Grupos;
      $aulaAux = '';
      foreach ($dataGrupos as $datag => $valores) {

            //subGrupo de  items por grupo
          foreach ($valores as $valor) {

            $aula = $valor->alucve;

            if($aula != $aulaAux && $aulaAux != '' ) {
                $Grupos[] = $auxG;
                unset($auxG);
                $auxG[] = $valor;
            }
            else {
              $auxG[] = $valor;
            }
            $aulaAux = $aula;
          }


      }
      if(isset($auxG)) {
        $Grupos[] = $auxG;
        unset($auxG);
      }

      return $Grupos;

    }
  private function CrearSub_Grupos($Grupos) {
    $subGrupo;
    $letra = ['A','B','C','D','E','F','G','H', 'I','J'];
    $p = 0;
    foreach ($Grupos as $key ) {
      $cant = count($key);
      if($cant>19)
        $sg1 = round(($cant /2), 0, PHP_ROUND_HALF_UP);   // 10
      else
        $sg1 = $cant;

      $filas = 0;
      foreach ($key as $valor) {
        $auxSub[] = $valor;
        $filas ++;
        if($filas == $sg1) {
            $subGrupo[$letra[$p]] = $auxSub;
            unset($auxSub);

        }
      }
      if(isset($auxSub)) {
        $subGrupo[$letra[$p].'1'] = $auxSub;
        unset($auxSub);
      }
      $p ++;
    }
    return $subGrupo;
  }
}
