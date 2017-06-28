<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Config;
use App\D_Config;
use Illuminate\Database\QueryException;

class ConfiguracionesController extends Controller
{
  public function VerConfig_View() {
    return view('configuraciones/verConfiguraciones');
  }

  public function getConfig() {
    $data =  Config::get();
    return $data;

  }
  public function get_oneConfig(Request $request) {
    $input = $request->all();

    if(isset($input['nombre']) && isset($input['id'])) {
      $nombre = $input['nombre'];
      $id = $input['id'];

      $data = Config::join('detalles_config as dc','dc.idConfig','=','config.idConfig')
      ->join('carreras as car','car.idCar','=','dc.idCar')
      ->where('nombre','=',$nombre)->where('dc.idCar','=',$id)
      ->select('cant_Grupos','nombcar')
      ->get()->first();
      return json_encode($data);
    }
    return json_encode(null);
  }


  public function registrarView() {
    return view('configuraciones/registrarConfiguracion');
  }
  public function modificarView() {
    return view('configuraciones/modifConfiguracion');
  }

  public function getDetalles($id = null) {
    $data = D_Config::where('idConfig','=',$id)
    ->join('carreras', 'carreras.idCar', '=', 'detalles_config.idCar')
    ->get();

    return $data;
  }

  public function crearConfig(Request $request) {

    $input = $request->only(['config', 'detalles']);
    $config = $input['config'];
    $detalles = $input['detalles'];

    if(count($config) > 0 && count($detalles) > 0) {


      if(isset($config[0]['nombreConfig']) && isset($config[0]['descripcion'])) {
        $nombre = $config[0]['nombreConfig'];
        $descripcion = $config[0]['descripcion'];

        $idConfig = Config::max('idConfig');
        if($idConfig != null) {
          $idConfig ++;
        } else $idConfig = 0;

        try {


            $insert[] = ['idConfig' => $idConfig, 'nombre' => $nombre, 'descripcion' =>$descripcion ];
            if(!empty($insert)) {
                Config::insert($insert);
            }


            foreach ($detalles as $valores ) {
              $insert2[] = ['idConfig' => $idConfig, 'idCar' => $valores['idCar']
              , 'cant_Grupos' => $valores['grupos'], 'cant_Elem_Grupo' => $valores['elementos']];
            }
            if(!empty($insert2)) {
                D_Config::insert($insert2);
            }

            return json_encode(array('accion' => 0));
      }
      catch(\Exception $e) {

          return json_encode(array('accion' => 1,'message' =>'algo salio mal'));
      }
    }


    }
    return json_encode(array('accion' => 1,'message' =>'algo salio mal'));
  }

  public function getConfig_autocomplete(Request $request) {
    $input = $request->only('nombre');

    if($input != null || $input != "") {
      $nombre = $input['nombre'];

      $config = Config::where('nombre','like','%'.$nombre. '%')->select('idConfig','nombre')->get();
      if (count($config) > 0) {

      foreach ($config as $key) {
        $data[] = ['label'=>$key['nombre'], 'value' =>$key['idConfig']];
      }
      return json_encode($data);
    }
  }
  $data[0] = null;
  return json_encode($data);
}

public function getDatos_modificar(Request $request) {
  $input = $request->only('id');

  if($input != null || $input != "") {
    $id = $input['id'];

    $config = Config::where('idConfig','=',$id)->get();

    $detalles = $this->getDetalles($id);

    $data[] = ['config'=>$config, 'detalles'=>$detalles];
    return json_encode($data);

  }
  $data[0] = null;
  return json_encode($data);
}

  public function modificarConfig(Request $request) {
    try{
      $input = $request->all();

      if(isset($input['config']) && isset($input['detalles'])) {
          $config = $input['config'];
          $detalles = $input['detalles'];
          if(isset($config[0]['idConfig'])) {
            $idConfig = $config[0]['idConfig'];
            $nombre = $config[0]['nombre'];
            $desc = $config[0]['desc'];

            Config::where('idConfig','=', $idConfig)->update(['nombre' => $nombre, 'descripcion' =>$desc]);

            foreach ($detalles as $key) {
              $idCar = $key['idCar'];
              $grupos = $key['grupos'];
              $elem = $key['elem'];

              D_Config::where('idConfig','=', $idConfig)->where('idCar','=',$idCar)->update(['cant_Grupos'=>$grupos, 'cant_Elem_Grupo'=>$elem]);
            }
            return json_encode(array('accion' => 0,'message' =>'exito'));
          }

      }
    }
    catch (QueryException $e) {
      return json_encode(array('accion' => 2,'message' =>'ha ocurrido un error!!' ));
    }
}
}
