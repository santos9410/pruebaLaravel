<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aspirantes;
use App\Carreras;
use App\Materias;
use App\Grupos;
use App\Materia_Grupo;
use App\Config;
use App\D_Config;
use Excel;

class GruposController extends Controller
{
    private $acomodados;

    public function getView() {
      $data = Carreras::where('idCar','!=',0)->get();
      return view('grupos/grupos',compact('data'));
    }

    public function getGrupos(Request $request,$carrera = null) {
      if($carrera != null) {
          $anio = date('y');
          $id = $anio. '-'. $carrera.'%';
          $grupos = Grupos::where('idGrupo','like',$id)->get();

          if(count($grupos) > 0) {
            foreach ($grupos as $key) {
              $alufic = $key['alufic'];
              $letra = $key['letraGrupo'];

              $aux = Aspirantes::join('carreras','carreras.idCar','=','DFICHA.carcve1')->where('alufic','=',$alufic)
              ->select('aluapp','aluapm','alunom','alucve','nombcar')
              ->get()->first();
              $data[] = ['alufic'=>$alufic, 'aluapp'=>$aux['aluapp'],
              'aluapm'=>$aux['aluapm'], 'alunom'=>$aux['alunom'],
              'nombcar'=>$aux['nombcar'], 'letra'=>$letra, 'alucve'=>$aux['alucve']];

            }

            return $data;
          }
          else {
              return json_encode(array('accion' => 2));
            }

      }
      else
          return json_encode(array('accion' => 1));
    }


    public function getViewCrear() {
      $grupos = Grupos::get()->first();
      if($grupos == null) {
        return view('grupos/crearGrupos', ['crear' => true]);
      }
      else {
        return view('grupos/crearGrupos', ['crear' => false]);
      }
    }
    public function crearGruposC() {
      $grupos = Grupos::get()->first();
      if(count($grupos) == 0) {
          $dataCarreras = Aspirantes::distinct()->select('carcve1')->groupBy('carcve1')->get();
          $letras = ['A', 'B', 'C', 'D', 'E', 'F', 'G','H'];
          $i = 0;

          $anio = date('y');

          foreach ($dataCarreras as $carrera) {

            $i = 0;
            $idCar = $carrera->carcve1;

            $dataGrupos = $this->crearGrupos($idCar);
            if($dataGrupos != null) {
              foreach ($dataGrupos as $key) {
                foreach ($key as $valor) {

                    $letra = $letras[$i];
                    $idGrupo = $anio . '-' . $idCar . $letra;
                    $alufic = $valor['alufic'];

                  $insert[] = [ 'idGrupo' => $idGrupo, 'alufic' => $alufic, 'letraGrupo' => $letra];
                }
                $i++;
              }
            }
          }

          if(!empty($insert)) {
              Grupos::insert($insert);
              if(count($this->acomodados) > 0) {
              $this->crearArchivoCambios();

              }
              return redirect()->back()->with('exito', 'Grupos creados correctamente');
              // return json_encode(array('accion' => 0,'file'=>$file));
          }
          return redirect()->back()->with('error', 'Ha ocurrido un problema!!');
      }
      else {
           return redirect()->back()->with('info', 'Los grupos ya están creados!!');

      }

    }

    private function crearArchivoCambios() {
      Excel::create('Cambios_de_aula', function($file)
      {
        $file->sheet('Hoja1' , function($sheet)
        {
           $sheet->setCellValue('A1', 'FICHA');
           $sheet->setCellValue('B1', 'Apellido Paterno');
           $sheet->setCellValue('C1', 'Apellido Materno');
           $sheet->setCellValue('D1', 'Nombre');
           $sheet->setCellValue('E1', 'Aula Anterior');
           $sheet->setCellValue('F1', 'Aula posterior');
           $sheet->setCellValue('G1', 'Carrera');

           $i = 2;
           foreach ($this->acomodados as $key) {
            foreach ($key as $valor) {
              $sheet->setCellValue('A'.$i, $valor['alufic']);
              $sheet->setCellValue('B'.$i, $valor['aluapp']);
              $sheet->setCellValue('C'.$i, $valor['aluapm']);
              $sheet->setCellValue('D'.$i, $valor['alunom']);
              $sheet->setCellValue('E'.$i, $valor['aulaA']);
              $sheet->setCellValue('F'.$i, $valor['aulaP']);
              $sheet->setCellValue('G'.$i, $valor['nombcar']);
              $i ++;
            }
              $i ++;
              $sheet->setCellValue('A'.$i, '');
              $sheet->setCellValue('B'.$i, '');
              $sheet->setCellValue('C'.$i, '');
              $sheet->setCellValue('D'.$i, '');
              $sheet->setCellValue('E'.$i, '');
              $sheet->setCellValue('F'.$i, '');
              $sheet->setCellValue('G'.$i, '');
              $i ++;
           }


         });
       })->store('xls', storage_path('excel/cambiosGrupo'))->download('xls');
      }


    private function crearGrupos($idCar) {
      $data = Aspirantes::join('carreras', 'carreras.idCar', '=', 'DFICHA.carcve1')
          ->where('DFICHA.carcve1', '=', $idCar)->where('calificacionCeneval','!=','null')
          ->select('alufic','aluapp','aluapm','alunom','alucve','carcve1','nombcar')
          ->orderBy('alucve','ASC')->orderBy('alufic')->get();
      if(count($data) > 0) {

          $d_config = D_Config::join('config','config.idConfig','=','detalles_config.idConfig')
          ->where('idCar','=',$idCar)->where('nombre','=','CURSO')->get()->first();
          if(count($d_config) > 0) {
            $cant_elem = $d_config['cant_Elem_Grupo'];

          }
          else {
            $cant_elem = 50;
          }

          $aulaAux = '';
          $i = 0;
          foreach ($data as $valores ) {
              $aula = $valores->alucve;

              if($aula != $aulaAux && $aulaAux != '' ) {
                  $Grupos[] = $auxG;
                  unset($auxG);
                  $auxG[] = $valores;
              }
              else {
                  $auxG[] = $valores;
              }
              $aulaAux = $aula;
          }

          if(isset($auxG)) {
            if(!isset($Grupos)) {
              $Grupos[] = $auxG;
              unset($auxG);
            }
            else {
              $Grupos = $this->acomodarGrupos($Grupos, $auxG, $cant_elem);
            }
          }
          return $Grupos;
      }
      return null;

    }

    private function acomodarGrupos($Grupos, $auxG, $cant_elem) {
      $cantF = count($auxG); //cantidad de faltantes por agrupar
      $cambiar = false;
      $ng = 0;  //contabiliza el numero de grupos
      $total = 0; // contabiliza el numero total de elementos de todos los grupos formados

      foreach ($Grupos as $key) {
        $total += count($key);
        $ng ++;
      }

      // echo "total ". $total. " faltan ". $cant. "<br>";
      $promF = round($cantF/$ng); //se determina el promedio de sobrante que le tocarian a cada grupo ya formado
      $promG = round($total/$ng); // se determina el promedio del tamaño de cada grupo;

      if(($promG + $promF) <= $cant_elem) {

        $gr = 0; // variable para controlar el arrayy $grupos
        $p = 0; //pivote para controlar cuando se cambia de grupo

        for ($c = 0; $c < $cantF; $c++) {
          if($p == $promF) {
            $gr ++;
            $p = 0;
          }
          $p ++;

          $aulaAnterior = $auxG[$c]->alucve;
          $aulaCambio = $Grupos[$gr][0]->alucve;
          $alufic = $auxG[$c]->alufic;
          $idCar = $Grupos[$gr][0]->carcve1;
          $aluapp = $auxG[$c]->aluapp;
          $aluapm = $auxG[$c]->aluapm;
          $alunom = $auxG[$c]->alunom;
          $nombcar = $auxG[$c]->nombcar;

          $this->acomodados[$idCar][] = ['alufic'=>$alufic,'aluapp' =>$aluapp,
          'aluapm' =>$aluapm,'alunom'=>$alunom,'nombcar'=>$nombcar,
           'aulaA'=>$aulaAnterior,'aulaP' =>$aulaCambio];

          $auxG[$c]->alucve = $aulaCambio;


          Aspirantes::where('alufic','=',$alufic)->update(['alucve'=>$aulaCambio]);
          array_push($Grupos[$gr],$auxG[$c]);
        }
      }
      else {
        $Grupos[] = $auxG;
        unset($auxG);
      }

      return $Grupos;
  }
}
