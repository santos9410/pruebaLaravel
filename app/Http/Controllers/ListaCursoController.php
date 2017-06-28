<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aspirantes;
use App\Carreras;
use App\Grupos;
use App\Materias;
use App\Materia_Grupo;
use Excel;
use Zipper;

class ListaCursoController extends Controller
{
    public function getView() {
      $data = Carreras::where('idCar','!=',0)->get();
      return view('listas/listaCurso',compact('data'));
    }

    public function getListaOne(Request $request) {

      if(isset($request['carrera']) && isset($request['materia']) && isset($request['letra'])) {
        $idCar = $request['carrera'];
        $materia = $request['materia'];
        $letra = $request['letra'];
        $nomFile = $this->crearArchivo($idCar,$materia,$letra);
        if($nomFile != null) {


          return response()->download(storage_path('excel/listasCurso/' .$nomFile));
        }
        else
          return back()->with('info','no existen grupos creados!!');

      }

      return back()->with('error','Ha ocurrido un error al intentar crear los archivos');
  }
  public function getListas(Request $request) {
    if(isset($request['materia'])) {
      $materias = $request['materia'];
      // $dataCarreras = Aspirantes::distinct()->select('carcve1')->groupBy('carcve1')->get();
      $dataGrupos = Grupos::distinct()->select('idGrupo')->groupBy('idGrupo')->get();
      if(count($dataGrupos) > 0) {
        $dataMaterias = Materias::where('idMateria','=',$materias)->get()->first();
        $nombre_Mat = $dataMaterias->nombre_Mat;

        $nombre_Mat =  mb_convert_encoding($nombre_Mat, "ASCII");
        $nomZip = 'descargas/ListasDCurso'.$nombre_Mat. '.zip';

        $zipper = Zipper::make($nomZip);
        foreach ($dataGrupos as $key) {
            $id = $key['idGrupo'];
            $dataGrupos2 = Grupos::join('DFICHA','DFICHA.alufic','=','grupos.alufic')
            ->where('idGrupo','=',$id)->select('carcve1')->get()->first();

            $idCar = $dataGrupos2['carcve1'];
            $nomFile = $this->crearArchivo($idCar,$materias);
            if($nomFile != null) {
              $file = storage_path('excel/listasCurso/' .$nomFile);
              $zipper->add($file);
            }

        }
        $zipper->close();

        return response()->download(public_path($nomZip))
              ->deleteFileAfterSend(true);
      }

    }
    return back()->with('error','Ha ocurrido un error al intentar crear los archivos');



  }

    private function crearArchivo($idCar = 0,$materia = 0, $letra = '') {

      $Grupos = $this->crearGrupos($idCar, $letra);
      if($Grupos == null) {
        return null;
      }
      $letraA = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
      $p = 0;
      $dataMaterias = Materias::where('idMateria','=',$materia)->get()->first();
      $nombre_Mat = $dataMaterias->nombre_Mat;


      Excel::create('Filename', function($file) use($Grupos, $letraA, $letra, $p, $nombre_Mat,$materia)
      {
        $p = -1;
        foreach ($Grupos as $grupo ) {
          if($letra == '') {
            $p ++;
            $letra2 = $letraA[$p];
          }
          else {
            $letra2 = $letra;
          }
          $file->sheet('Grupo '. $letra2 , function($sheet) use ($grupo, $letra2, $file, $nombre_Mat,$materia)
          {

            $this->carrera = $grupo[0]['nombcar'];

            $sheet->setHeight(1, 25);
            $sheet->mergeCells('A1:S1');
            $sheet->setCellValue('A1', "Instituto Tecnológico de Ciudad Guzmán");


            $sheet->cells('A1', function($cells) {
              $cells->setAlignment('center');
              $cells->setFontSize(16);
            });

            $sheet->getStyle('A1')->getFont()->setBold(true);

            $sheet->setHeight(2, 25);
            $sheet->mergeCells('A2:S2');
            $sheet->setCellValue('A2', $grupo[0]['nombcar'] . " \"{$letra2}\"");


            $sheet->cells('A2', function($cells) {
              $cells->setAlignment('center');
              $cells->setFontSize(16);
            });

            $sheet->getStyle('A2')->getFont()->setBold(true);


            $sheet->setHeight(3, 20);
            $sheet->mergeCells('A3:S3');
            $sheet->setCellValue('A3', $nombre_Mat);
            $sheet->cells('A3', function($cells) {$cells->setAlignment('center');});
            $sheet->getStyle('A3')->getFont()->setBold(true);

            $idGrupo = $grupo[0]['idGrupo'];
            $dataMG = Materia_Grupo::join('maestros','maestros.idMaestro','=','materia_Grupo.idMaestro')
            ->where('idGrupo','=',$idGrupo)->where('idMateria','=',$materia)
            ->select('nombre_Maestro')->get()->first();
            if(count($dataMG) > 0) {
              $sheet->mergeCells('A4:K4');
              $sheet->setCellValue('A4', 'Profesor: '. $dataMG['nombre_Maestro'] );
              $sheet->getStyle('A4')->getFont()->setBold(true);
            }
            else {
              $sheet->mergeCells('A4:K4');
              $sheet->setCellValue('A4', 'Profesor: Academia');
              $sheet->getStyle('A4')->getFont()->setBold(true);
            }
          $sheet->setHeight(4, 23);
          $sheet->mergeCells('L4:S4');
          $sheet->setCellValue('L4', "  Aula: " . $grupo[0]['alucve']);

          $sheet->cells('L4', function($cells) {
            $cells->setFontSize(13);
          });

          $sheet->setHeight(5, 17);
          $sheet->setCellValue('A5', 'No.');
          $sheet->setCellValue('B5', 'Ficha');
          $sheet->setCellValue('C5', 'Nombre');
          $sheet->setCellValue('D5', '1 ');
          $sheet->setCellValue('E5', '2 ');
          $sheet->setCellValue('F5', '3 ');
          $sheet->setCellValue('G5', '4 ');
          $sheet->setCellValue('H5', '5 ');
          $sheet->setCellValue('I5', '6 ');
          $sheet->setCellValue('J5', '7 ');
          $sheet->setCellValue('K5', '8 ');
          $sheet->setCellValue('L5', '9 ');
          $sheet->setCellValue('M5', '10');
          $sheet->setCellValue('N5', '11');
          $sheet->setCellValue('O5', '12');
          $sheet->setCellValue('P5', '13');
          $sheet->setCellValue('Q5', '14');
          $sheet->setCellValue('R5', '15');
          $sheet->setCellValue('S5', 'Evaluación Final');

          $fila = 5;
          $num = 1;

          foreach ($grupo as $valor) {
              $fila++;

              $sheet->setHeight($fila, 17);
              $sheet->setCellValue('A'. $fila, $num );
              $sheet->setCellValue('B'. $fila, $valor['alufic'] );
              $sheet->setCellValue('C'. $fila, $valor['aluapp']. ' '. $valor['aluapm']. ' '. $valor['alunom'] );

              $num++;
          }
          if($num < 40) {
            for ($i = $num; $i <40 ; $i++) {
              $fila ++;
              $sheet->setHeight($fila, 25);
              $sheet->setCellValue('A'. $fila, "");
              $sheet->setCellValue('B'. $fila, "" );
              $sheet->setCellValue('C'. $fila, "");
            }
          }

          $sheet->setBorder('A1:S1', 'thin');
          $sheet->setBorder('A2:S2', 'thin');
          $sheet->setBorder('A3:S3', 'thin');
          $sheet->setBorder('A4:S4', 'thin');
          $sheet->setBorder('A4:S'.$fila, 'thin');
          // Set top, right, bottom, left
          $sheet->setPageMargin(array(0.79, 0.6, 0.6, 0.6));

          $nombre_Mat =  mb_convert_encoding($nombre_Mat, "ASCII");
          $file->setFilename($this->carrera. "-".$nombre_Mat);
         });
       }



     })->store('xls', storage_path('excel/listasCurso'));
     if(isset($Grupos)) {
       unset($Grupos);
     }
     $nombre_Mat =  mb_convert_encoding($nombre_Mat, "ASCII");
     return $this->carrera. "-".$nombre_Mat.   '.xls';
    }

    private function crearGrupos($idCar, $letra = '') {

      $anio = date('y');
      if($letra != '') {
        $id = $anio. '-'. $idCar. $letra;
      }
      else {
        $id = $anio. '-'. $idCar.'%';
      }

      $aulaAux = '';
      if(isset($Grupos)) {
        unset($Grupos);
      }

      $grupos = Grupos::where('idGrupo','like',$id)->get();
      if(count($grupos) > 0) {
          foreach ($grupos as $key) {

            $alufic = $key['alufic'];

            $data = Aspirantes::join('carreras', 'carreras.idCar', '=', 'DFICHA.carcve1')
            ->where('alufic','=',$alufic)->select('DFICHA.*', 'carreras.nombcar')
            ->get()->first();

            $aula = $data->alucve;

            if($aula != $aulaAux && $aulaAux != '' ) {
                $Grupos[] = $auxG;
                unset($auxG);
                $auxG[] = ['alufic'=>$alufic, 'aluapp' => $data['aluapp'], 'aluapm' =>$data['aluapm'],
                  'alunom'=>$data['alunom'],'alucve' =>$data['alucve'], 'idGrupo' => $key['idGrupo'],
                   'nombcar' => $data['nombcar']];
            }
            else {
                // $auxG[] = $data;
                $auxG[] = ['alufic'=>$alufic, 'aluapp' => $data['aluapp'], 'aluapm' =>$data['aluapm'],
                  'alunom'=>$data['alunom'],'alucve' =>$data['alucve'], 'idGrupo' => $key['idGrupo'],
                   'nombcar' => $data['nombcar']];
            }
            $aulaAux = $aula;
          }

          if(isset($auxG)) {
            $Grupos[] = $auxG;
            unset($auxG);
          }

          return $Grupos;
      }
      else {
        return null;
      }

    }
}
