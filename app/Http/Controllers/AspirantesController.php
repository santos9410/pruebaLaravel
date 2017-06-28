<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aspirantes;
use App\Grupos;
use Excel;
use Illuminate\Database\QueryException;

class AspirantesController extends Controller
{


  public function ViewModificar() {

    return view('aspirantes/modificarDatos');
  }
  public function ViewModificarPromedio() {

    return view('aspirantes/modificarPromedio');
  }

  public function ViewPromedio_Archivo() {

    return view('aspirantes/modifPromedio');
  }

  public function one_autocomplete(Request $request) {
    $input = $request->only('id');
    if(isset($input['id'])) {
      $id = $input['id'];
      $data = Aspirantes::where('alufic','like','%'.$id .'%')->select('alufic as label')->limit(5)->get();
      return json_encode($data);
    }
    $data[0] = null;
    return json_encode($data);
  }

  public function one_carrera(Request $request) {
    $input = $request->only('id');
    if(isset($input['id'])) {
      $id = $input['id'];
      $data = Aspirantes::join('carreras','carreras.idCar','=','DFICHA.carcve1')
      ->where('alufic','=', $id)->select('alufic','DFICHA.carcve1','nombcar')
      ->get()->first();

      $carreras = new CarrerasController();
      $dataCar = $carreras->getCarrerasAll();


      return json_encode(['datos'=>$data,'carreras' =>$dataCar]);
    }
    $data[0] = null;
    return json_encode($data);
  }

  public function one_promedio(Request $request) {
    $input = $request->only('id');
    if(isset($input['id'])) {
      $id = $input['id'];
      $data = Aspirantes::where('alufic','=', $id)->select('alufic','alupro')->get()->first();

      return json_encode($data);
    }
    $data[0] = null;
    return json_encode($data);
  }

  public function Modificar(Request $request) {
    $input = $request->all();
    $id = $input['id'];
    $idCar = $input['idCarreraActual'];
    $idCarCambio = $input['idCarreraModificado'];

    $idGrupoMin;
    $min = 0;
    $aux = 0;

    $data = Grupos::where('alufic','=',$id)->get()->first();

    if(count($data) > 0) {

      $anio = date('y');
      $idG = $anio. '-'. $idCarCambio;
      $data2 = Grupos::where('idGrupo','like',$idG.'%')->get();
      if(count($data2) > 0) {
        $grupos = $this->CrearGrupos($data2);
        $minGrupo = $this->menorGrupo($grupos);
        $idGrupoActual = $data['idGrupo'];
        $idGrupo = $minGrupo[0]->idGrupo;
        $letra = $minGrupo[0]->letraGrupo;
        try {
          Grupos::where('idGrupo','=',$idGrupoActual)->where('alufic','=',$id)->delete();
          Grupos::insert(['idGrupo'=>$idGrupo,'alufic'=>$id,'letraGrupo'=>$letra]);
        } catch(QueryException $e) {
            return json_encode(['accion'=>2,'message'=>'ha ocurrido un error']);
          }

        $aula = $this->cambio($id, $idCarCambio);
        return json_encode(['accion'=>0,'message'=>'los datos se han modificado correctamente!!',
                'aula'=>$aula]);
      }
        return json_encode(['accion'=>1,'message'=>'no se puede cambiar al grupo especificado']);
    }
    else {

      $aula = $this->cambio($id, $idCarCambio);
      return json_encode(['accion'=>0,'message'=>'los datos se han modificado correctamente!!',
      'aula'=> $aula]);

    }
    return json_encode(['accion'=>2,'message'=>'ha ocurrido un error']);
  }

  public function modificarPromedio(Request $request) {
    $input = $request->all();
    if(isset($input['id']) && isset($input['promedio'])) {
      $id = $input['id'];
      $promedio = $input['promedio'];
      Aspirantes::where('alufic','=',$id)->update(['alupro'=>$promedio]);
      return json_encode(['accion'=>0,'message'=>'El promedio se ha actualizado correctamente!!']);
    }
    return json_encode(['accion'=>1,'message'=>'Ha ocurrido un error!!']);
  }

  public function Promedio_Archivo(Request $request)
   {
    if($request->hasFile('import_file')) {
      $path = $request->file('import_file')->getRealPath();

      $data = Excel::load($path, function($reader) {})->get();
        $i = 0;
      if(!empty($data) && $data->count()) {

        foreach ($data->toArray() as $key => $value) {
          if(!empty($value)) {

              if(isset($value['alufic']) && isset($value['alupro'])) {
                $alufic = $value['alufic'];
                $alupro = $value['alupro'];

                Aspirantes::where('alufic','=', $alufic)->update(['alupro' => $alupro]);
              }
              else return back()->with('error','Por Favor revise el documento, Algo salio mal.');

          }
        }
        return back()->with('success','Actualización de los promedios correcta.');
      }
    }
    return back()->with('error','Por Favor revise el documento, Algo salio mal.');
  }


  private function CrearGrupos($data) {
      $idAux = '';

      foreach ($data as $key) {
        $id = $key->idGrupo;
        $aux[] = $key;
        if($id != $idAux && $idAux !='') {
          $grupos[] = $aux;
          unset($aux);
        }
        $idAux = $id;

      }
      if(isset($aux)) {
        $grupos[] = $aux;
        unset($aux);
      }

      return $grupos;

    }

  private function menorGrupo($grupos) {
    $min = 0;
    $aux = 0;
    $idGrupo = $grupos[0];
    for ($i = 0; $i < count($grupos) -1; $i++) {
      $min = count($grupos[$i]);
      $aux = count($grupos[$i+1]);
      if($aux <= $min) {
        $min = $aux;
        $idGrupo = $grupos[$i+1];
      }
    }
    return $idGrupo;
  }

  private function cambio($id, $idCarCambio) {
    $data1 = Aspirantes::where('carcve1','=',$idCarCambio)->get()->last();
    $aula = $data1->alucve;
    Aspirantes::where('alufic','=',$id)->update(['alucve'=>$aula,'carcve1'=>$idCarCambio]);
    return $aula;
  }
}
