<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aspirantes;
use App\Carreras;
use Excel;

class ImportarDatosController extends Controller
{
    public function AspirantesView()
     {
      return view('importarDatos/importAspirantes');
    }

    public function CenevalView()
     {
      return view('importarDatos/importCeneval');
    }

    public function FoliosView()
     {
      return view('importarDatos/importResultantes');
    }

    public function DataAspirantesC(Request $request)
     {
      if($request->hasFile('import_file')) {
        $path = $request->file('import_file')->getRealPath();

        $data = Excel::load($path, function($reader) {})->get();
          $i = 0;
        if(!empty($data) && $data->count()) {

          foreach ($data->toArray() as $key => $value) {
            if(!empty($value)) {


                  if(isset($value['alufic']) && isset($value['aluapp']) && isset($value['alunom'])
                      &&  isset($value['alupro']) && isset($value['carcve1'])
                      &&  isset($value['carcve2']) && isset($value['aulcve']) ) {

                      $existe = Aspirantes::where('alufic', '=', $value['alufic'])->get()->first();

                      if($existe == null) {

                        $alufic = $value['alufic'];
                        $alufic = str_replace(' ', '', $alufic);

                        $aluapp = $value['aluapp'];
                        $aluapp = ltrim($aluapp);
                        // $aluapp = str_replace(' ', '', $aluapp);

                        $alunom = $value['alunom'];

                        $alupro = $value['alupro'];
                        $alupro = str_replace(' ', '', $alupro);

                        $carcve1 = $value['carcve1'];
                        $carcve1 = str_replace(' ', '', $carcve1);

                        $carcve2 = $value['carcve2'];
                        $carcve2 = str_replace(' ', '', $carcve2);
                        $alucve = $value['aulcve'];

                        if(isset($value['aluapm'])) {
                          $aluapm = $value['aluapm'];
                          $aluapm = ltrim($aluapm);
                          // $aluapm = str_replace(' ', '', $aluapm);
                        }
                        else {
                          $aluapm = "";
                        }

                        if(intval($alupro)  > 100 ) {
                          $alupro = intval($alupro) / 10;
                        }
                          //alufic, aluapp, aluapm, alunom, alupro, idCar, alucve

                          $insert[] = [ 'alufic' =>$alufic, 'aluapp' => $aluapp
                            , 'aluapm' => $aluapm, 'alunom' => $alunom
                            , 'alupro' => $alupro, 'carcve1' => $carcve1
                            , 'carcve2' => $carcve2, 'alucve' => $alucve ];

                      }
                  }


            }
          }


          if(!empty($insert)) {
              Aspirantes::insert($insert);
              return back()->with('success','Registros guardados correctamente.');
          }
          else {
            return back()->with('warning','Registros sin cambios!');
          }

        }

      }

      return back()->with('error','Por favor revisa tu documento, Algo salio mal.');
    }

    public function DataRegistradosC(Request $request)
     {
      if($request->hasFile('import_file')) {
        $path = $request->file('import_file')->getRealPath();

        $data = Excel::load($path, function($reader) {})->get();
          $i = 0;
        if(!empty($data) && $data->count()) {

          foreach ($data->toArray() as $key => $value) {
            if(!empty($value)) {

                if(isset($value['matricula']) && isset($value['folio'])) {
                  $matricula = $value['matricula'];
                  $folio = $value['folio'];
                  // if(strlen($matricula) < 6) {
                  //     $matricula = str_pad($matricula, 6, '0', STR_PAD_LEFT);
                  // }

                  Aspirantes::where('alufic','=', $matricula)->update(['folio' => $folio]);
                }
                else return back()->with('error','Por Favor revise el documento, Algo salio mal.');


            }
          }
          return back()->with('success','Actualización de los folios correcta.');
        }
      }
      return back()->with('error','Por Favor revise el documento, Algo salio mal.');
    }

    public function DataCenevalC(Request $request)
     {
      if($request->hasFile('import_file')) {
        $path = $request->file('import_file')->getRealPath();

        $data = Excel::load($path, function($reader) {})->get();
          $i = 0;
        if(!empty($data) && $data->count()) {

          foreach ($data->toArray() as $key => $value) {
            if(!empty($value)) {

                  if(isset($value['matricula']) && isset($value['promedio'])) {
                      $matricula = $value['matricula'];
                      $promedio = $value['promedio'];
                      // if(strlen($matricula) < 6) {
                      //     $matricula = str_pad($matricula, 6, '0', STR_PAD_LEFT);
                      // }

                      Aspirantes::where('alufic','=', $matricula)->update(['calificacionCeneval' => $promedio]);
                  }
                  else return back()->with('error','Por Favor revise el documento, Algo salio mal.');


            }

          }
          return back()->with('success','Actualización de los promedios correcta.');

        }
      }
      return back()->with('error','Por Favor revise el documento, Algo salio mal.');
    }
}
