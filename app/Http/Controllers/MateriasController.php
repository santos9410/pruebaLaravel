<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Materias;
use Illuminate\Database\QueryException;

class MateriasController extends Controller
{
  public function getMaterias() {
    $data = Materias::get();
    return $data;
  }

  public function ViewNuevo() {
    return view('materias/registrarMateria');
  }

  public function crear(Request $request) {

    try {
      $input = $request->all();


      if(isset($input['materia'])) {
        $nombre = $input['materia'];

        $id = Materias::max('idMateria');
        $id ++;

        $insert[] = ['idMateria' => $id, 'nombre_Mat' => $nombre];

        Materias::insert($insert);
        return json_encode(array('accion' => 0,'message' =>'exito'));
      }
    }
    catch(QueryException $e) {

      return json_encode(array('accion' => 2,'message' =>'algo salio mal'));
    }
    return json_encode(array('accion' => 2,'message' =>'algo salio mal'));
  }


  public function ViewModificar() {
    return view('materias/modificarMateria');
  }

  public function one_autocomplete(Request $request) {
    $input = $request->only(['nombre']);
    $nombre = $input['nombre'];

    $data = Materias::where('nombre_Mat','like','%'. $nombre. '%')->select('idMateria as value','nombre_Mat as label')->get();
    if(count($data) > 0) {

      return json_encode($data);
    }
    $data[0] = null;
    return json_encode($data);
  }

  public function modificar(Request $request) {

      $input = $request->only(['idMateria', 'nombre']);
      if(isset($input['idMateria']) && isset($input['nombre'])) {

        $id = $input['idMateria'];
        $nombre = $input['nombre'];

        // return json_encode($input);
        Materias::where('idMateria','=', $id)->update(['nombre_Mat' => $nombre ]);
        return json_encode(array('accion' => 0,'message' =>'exito'));
      }
      return json_encode(array('accion' => 2,'message' =>'algo salio mal'));
  }
}
