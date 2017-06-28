<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class NuevoProceso extends Controller {


	public function getView() {
		return view('procesos/confirmarProceso');
	}

	public function Reiniciar() {
		try {
			DB::statement("SET foreign_key_checks=0");
			DB::table('calificaciones')->delete();
			DB::table('materia_Grupo')->delete();
			DB::table('grupos')->delete();
			DB::table('DFICHA')->delete();
			DB::statement("SET foreign_key_checks=1");
			return redirect()->back()->with('exito', 'Datos eliminados correctamente');

		} catch(QueryException $e) {
				return redirect()->back()->with('error', 'Ha ocurrido un error, contacte al administrador!');
			}

	}

}
