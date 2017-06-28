<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Maestros;
use Illuminate\Database\QueryException;

class MaestrosController extends Controller
{
    public function getMaestro_Autocomplete($maestro)
    {
      // echo $maestro;
        $maestros = Maestros::select('nombre_Maestro','idMaestro')->where('nombre_Maestro','like','%'. $maestro. '%')->limit(5)->get();
        if (count($maestros) > 0) {

        foreach ($maestros as $key) {
          $data[] = ['label'=>$key['nombre_Maestro'], 'value' =>$key['idMaestro']];
        }
        return $data;
      }
      $data[0] = null;
      return $data;


    }

    public function ViewNuevo() {
      return view('maestros/registrarMaestro');
    }

    public function crear(Request $request) {
      try {
        $input = $request->all();


        if(isset($input['maestro'])) {
          $nombre = $input['maestro'];
          if($nombre != null || $nombre !="") {
            // $maestro = new Maestros;

            for ($i=0; $i < 1; $i++) {

                $idMaestro = rand(1,10000);
                $consulta = Maestros::where('idMaestro','=',$idMaestro)->get()->first();
                if(count($consulta) == 0) {

                  $insert[] = ['idMaestro' => $idMaestro, 'nombre_Maestro' => $nombre];
              //  $maestro->idMaestro = $idMaestro;
              //  $maestro->nombre_Maestro = $maestro;
               //
               Maestros::insert($insert);
               return json_encode(array('accion' => 0,'message' =>'exito','idGenerado' =>$idMaestro));
            }
            else {
              $i = 0;
            }
          }
         }
       }
     }
     catch(QueryException $e) {

        return json_encode(array('accion' => 2,'message' =>'algo salio mal'));
    }
    }

    
}
