<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Calif;
use App\Materia_Grupo;
use App\Grupos;
use Illuminate\Database\QueryException;

class CalificacionesController extends Controller
{
    public function getViewCrear() {
      return view('calificaciones/registrarCalif');
    }
    public function getViewCalif() {
      return view('calificaciones/verCalif');
    }
    public function getViewModif() {
      return view('calificaciones/modifCalif');
    }

    public function registro(Request $request) {
      $datos = $request->all();
      foreach ($datos as $fila) {

        $alufic = $fila['alufic'];
        $calif = $fila['calif'];
        $idMateriaG = $fila['idMateriaG'];

        $insert[] = ['id_MateriaG' => $idMateriaG,'alufic' => $alufic, 'calif' =>$calif];
      }
      if(!empty($insert)) {
        try{
          Calif::insert($insert);
          return json_encode(array('accion' => 0));
        }
        catch (QueryException $e) {
          return json_encode(array('accion' => 1,'message' =>'calificaciones ya capturadas!!'));
        }
      }
      return json_encode(array('accion' => 2));
    }

    public function getCalif(Request $request) {
      $input = $request->all();
      if(isset($input['carrera']) && isset($input['materia'])
        && isset($input['letra'])) {
          $carrera = $input['carrera'];
          $materia = $input['materia'];
          $letra = $input['letra'];
          $anio = date('y');

          $idGrupo = $anio. '-'. $carrera. $letra;

          
          $mg = Materia_Grupo::where('idMateria','=',$materia)->where('idGrupo','=',$idGrupo)->get();

          if(count($mg) > 0) {
            $data = Grupos::join('DFICHA','DFICHA.alufic','=','grupos.alufic')->where('idGrupo','=',$idGrupo)->select('DFICHA.alufic','DFICHA.aluapp','DFICHA.aluapm','DFICHA.alunom')->get();

            if(count($data) > 0) {
              foreach ($data as $key) {
                  $idMateriaG = $mg[0]->id_MateriaG;
                  $alufic = $key->alufic;

                  $calif = Calif::where('id_MateriaG','=',$idMateriaG)->where('alufic','=',$alufic)->select('calif')->get()->first();
                  if(count($calif) == 0) {
                      return json_encode(array('accion' => 2,'message'=>'no hay registros !!'));
                  }
                  $aluapp = $key->aluapp;
                  $aluapm = $key->aluapm;
                  $alunom = $key->alunom;

                  $datos[] = ['alufic'=>$alufic, 'aluapp' =>$aluapp, 'aluapm'=>$aluapm, 'alunom'=>$alunom, 'calif'=>$calif->calif, 'idMateriaG' => $idMateriaG];
              }
              return $datos;
            }

          }
          else return json_encode(array('accion' => 2,'message'=>'el grupo no tiene asignado esta materia!!'));

      }
      return json_encode(['accion' => 2, 'message'=>'ha ocurrido un error!!']);
    }

    public function modificar(Request $request) {
      $datos = $request->all();
      try{
          foreach ($datos as $fila) {
            $alufic = $fila['alufic'];
            $calif = $fila['calif'];
            $idMateriaG = $fila['idMateriaG'];
            Calif::where('alufic','=', $alufic)->where('id_MateriaG','=',$idMateriaG)->update(['calif' => $calif]);

          }
          return json_encode(array('accion' => 0));
        }
        catch (QueryException $e) {
          return json_encode(array('accion' => 2,'message' =>'ha ocurrido un error!!'));
        }

      return json_encode(array('accion' => 2));
    }
}
