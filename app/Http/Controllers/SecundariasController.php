<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aspirantes;
use Excel;

class SecundariasController extends Controller
{
    public function getView() {
      return view('listas/listaPromedioMal');
    }

    public function generarLista() {
      $data = Aspirantes::join('carreras','carreras.idCar','=','DFICHA.carcve1')->where('alupro','<','60')->get();
      if(count($data) > 0) {
        Excel::create('Promedios_Mal', function($excel) use($data) {

          $excel->sheet('Hoja1', function($sheet)  use ($data){

            $sheet->setCellValue('A1', 'No.');
            $sheet->setCellValue('B1', 'Ficha');
            $sheet->setCellValue('C1', 'Nombre');
            $sheet->setCellValue('D1', 'Carrera');
            $sheet->setCellValue('E1', 'Promedio');

            $num = 1;
            $fila = 2;
            foreach ($data as $valor) {

              $sheet->setHeight($fila, 15);
              $sheet->setCellValue('A'. $fila, $num );
              $sheet->setCellValue('B'. $fila, $valor['alufic'] );
              $sheet->setCellValue('C'. $fila, $valor['aluapp']. ' '. $valor['aluapm']. ' '. $valor['alunom'] );
              $sheet->setCellValue('D'. $fila, $valor['nombcar'] );
              $sheet->setCellValue('E'. $fila, $valor['alupro'] );

              $sheet->cells('A'.$fila, function($cells) {
                $cells->setAlignment('center');
              });

              $sheet->cells('E'.$fila, function($cells) {
                $cells->setAlignment('center');
              });
              $num++;
              $fila++;
            }

            $sheet->setPageMargin(array(
                0.8, 0.8, 0.8, 0.8
            ));

              $sheet->setBorder('A1:E'.($fila-1), 'thin');
          });

      })->export('xls');
    }
  }
}
