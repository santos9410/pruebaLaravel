<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aspirantes;
use App\Carreras;
use App\Materias;
use App\Grupos;
use App\Materia_Grupo;
// use Illuminate\Support\Facades\Crypt;
// use Illuminate\Contracts\Encryption\DecryptException;

class Grupos2Controller extends Controller
{

  public function getViewAsignar() {

    $aux = $this->mergeMaterias();
    $materias = array();
    foreach ($aux as $key) {
      $row = Materias::where('idMateria','=',$key)->get()->first();

      $materias[] = ['idMateria'=>$row['idMateria'],'nombre_Mat'=> $row['nombre_Mat']];

    }

    return view('grupos/asignarMaestros',compact('materias'));
  }

  public function getViewModificar() {
    $materias = Materias::get();
    return view('grupos/modificarMaestros',compact('materias'));
  }

  public function getGrupos()
  {
    $grupos = Grupos::distinct()->select('idGrupo','letraGrupo')->orderBy('idGrupo')->get();

    if(count($grupos) > 0) {
    foreach ($grupos as $grupo) {
      $idGrupo = $grupo->idGrupo;

      $dataGrupo = Grupos::select('alufic')->where('idGrupo','=',$idGrupo)->first();
      $dataAlu = Aspirantes::select('carcve1','nombcar')->join('carreras','DFICHA.carcve1','=','carreras.idCar')
      ->where('alufic','=',$dataGrupo->alufic)->get()->first();
      // echo json_encode($dataAlu);
      $carrera = $dataAlu->carcve1;
      // $idGrupo = Crypt::encryptString($idGrupo);

      // $decrypted = Crypt::decryptString($encrypted);
      $data[$carrera][] = array('idCar'=> $carrera, 'nombcar'=>$dataAlu->nombcar
        , 'letraGrupo'=>$grupo->letraGrupo, 'idGrupo'=>$idGrupo );

    }
    return $data;
  }
    return json_encode(array('accion' => 2,'message' => 'no existen datos!!'));
  }

  public function asignar(Request $request)
  {

    $input = $request->all();


    if(isset($input['datos'])) {

        $datos = $input["datos"];
        $materia = $input['materia'];

        if(count($datos) > 0 && $materia !="") {
          $keys = array_keys($datos);

          $idMateriaG = Materia_Grupo::max('id_MateriaG');

          if($idMateriaG != null) {
            $idMateriaG ++;
          }
          else $idMateriaG = 0;

          for ($i=0; $i <count($keys); $i++) {
            $idMateriaG ++;
            $index = $keys[$i];
            $idGrupo = $index;
            
            $idMaestro = $datos[$index];
            if($idMaestro == null) {
              return json_encode(array('accion' => 1));
            }
            $row = Materia_Grupo::where('idGrupo','=',$idGrupo)->where('idMateria','=',$materia)->get()->first();
            if($row == null)
              $insert[] = ['id_MateriaG'=> $idMateriaG, 'idMateria'=>$materia, 'idMaestro' => $idMaestro,'idGrupo'=>$idGrupo];
          }

          if(!empty($insert)) {
              Materia_Grupo::insert($insert);
              return json_encode(array('accion' => 0));
          }
        }

    }
    return json_encode(array('accion' => 1));
  }

  public function getData_Modificar(Request $request) {
      $input = $request->only('id');
      if(isset($input['id'])) {
        $id = $input['id']; // id de materia


        $gruposM = Materia_Grupo::join('maestros','maestros.idMaestro','=','materia_Grupo.idMaestro')
        ->where('idMateria','=',$id)->get();
        if(count($gruposM) > 0) {
        foreach ($gruposM as $key) {
          $idGrupo = $key['idGrupo'];
          $grupos = Grupos::join('DFICHA','DFICHA.alufic','=','grupos.alufic')
          ->join('carreras','carreras.idCar','=','DFICHA.carcve1')->where('idGrupo','=',$idGrupo)
          ->select('carreras.idCar','carreras.nombcar','grupos.letraGrupo','grupos.idGrupo')->get()->first();

          $idCar = $grupos['idCar'];

          $nombcar = $grupos['nombcar'];

          $letra = $grupos['letraGrupo'];
          $idMaestro = $key['idMaestro'];
          $idMateriaG = $key['id_MateriaG'];
          // $idMaestro = Crypt::encryptString($key['idMaestro']);
          $maestro = $key['nombre_Maestro'];
          // $idMateriaG = Crypt::encryptString($key['id_MateriaG']);

          $data[$idCar][] = array('idCar' => $idCar, 'nombcar'=>$nombcar, 'letraGrupo'=>$letra
          , 'idMaestro'=>$idMaestro, 'maestro'=>$maestro, 'idGrupo'=>$idGrupo, 'idMateriaG'=>$idMateriaG );

        }
        echo json_encode($data);
        // echo json_encode($gruposM);
        return;
      }
    }
      return json_encode(array('accion' => 2));
  }

  public function modificar(Request $request) {
      $input = $request->all();

      // echo json_encode($input);
      if(isset($input['idMateria']) && isset($input['datos'])) {
        $idMateria = $input['idMateria'];
        $datos = $input['datos'];


        foreach ($datos as $key) {
          // $idMateriaG = Crypt::decryptString($key['idMateriaG']);
          // $idMaestro = Crypt::decryptString($key['idMaestro']);
          $idMateriaG = $key['idMateriaG'];
          $idMaestro = $key['idMaestro'];
          Materia_Grupo::where('id_MateriaG','=',$idMateriaG)->update(['idMaestro' => $idMaestro]);

        }
        return json_encode(array('accion' => 0));

      }
      return json_encode(array('accion' => 2));
  }

  private function mergeMaterias() {
    $materias = Materias::select('idMateria')->get();
    $materiasGr = Materia_Grupo::distinct()->select('idMateria')->get();

    $array = array();
    foreach ($materias as $key) {
      $array[] = $key['idMateria'];
    }

    $array2 = array();
    foreach ($materiasGr as $key) {
      $array2[] = $key['idMateria'];
    }
    $intersect = array_intersect($array, $array2);
        return array_merge(array_diff($array, $intersect), array_diff($array2, $intersect));
  }
}
