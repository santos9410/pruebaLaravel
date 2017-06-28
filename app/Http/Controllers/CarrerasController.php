<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Carreras;

class CarrerasController extends Controller
{
    public function getCarrerasAll() {
      $data = Carreras::where('idCar','!=',0)->get();
      return $data;
    }

    public function getCarreras() {
      $data = Carreras::where('idCar','!=',0)->where('idCar','!=',17)->where('idCar','!=',18)->get();
      return $data;
    }

    public function getCarrera($id = null) {
      if($id != null) {
        $data = Carreras::where('idCar','=',$id)->get();
        return $data;
    }
    return null;
    }


}
