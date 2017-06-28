<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Grupos;
use App\Materia_Grupo;
use App\Calif;

class Grupos3Controller extends Controller
{
    public function letrasGrupo($carrera = null) {
      if($carrera != null) {
        //select distinct(letraGrupo) from grupos where idGrupo like '17-16%';
        $anio = date('y');
        $data = Grupos::distinct()->select('letraGrupo')->where('idGrupo','like',$anio . '-'. $carrera. '%')->get();
        if(count($data) > 0) {
        return $data;
        }
        else {
          $data[0] = null;
          return $data;
        }
      }
    }
    public function oneGrupo(Request $request) {
      $input = $request->all();
      if(isset($input['carrera']) && isset($input['materia'])
        && isset($input['letra'])) {
          $carrera = $input['carrera'];
          $materia = $input['materia'];
          $letra = $input['letra'];
          $anio = date('y');

          $idGrupo = $anio. '-'. $carrera. $letra;

          //select * from materia_Grupo where idMateria = 1 and idGrupo = '17-16A';
          $mg = Materia_Grupo::where('idMateria','=',$materia)->where('idGrupo','=',$idGrupo)->get();

          if(count($mg) > 0) {
            $data = Grupos::join('DFICHA','DFICHA.alufic','=','grupos.alufic')->where('idGrupo','=',$idGrupo)->select('DFICHA.alufic','DFICHA.aluapp','DFICHA.aluapm','DFICHA.alunom')->get();

            $idMateriaG = $mg[0]->id_MateriaG;
            $alufic = $data[0]->alufic;

            $calif = Calif::where('id_MateriaG','=',$idMateriaG)->where('alufic','=',$alufic)->get();
            if(count($calif) > 0) {
              return json_encode(array('accion' => 2,'message'=>'ya estan registradas las calificaciones!!'));
            }
            $datos = ['materia_grupo' => $mg, 'grupo' => $data];
            return $datos;
          }
          else return json_encode(array('accion' => 2,'message'=>'el grupo no tiene asignado esta materia!!'));

      }
      return json_encode(array('accion' => 2));
    }
}
