<?php namespace App\Services;

use App\User;
use Validator;
use App\Maestros;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
            'usuario' => 'required|max:255|unique:users',
            'nombre' => 'required|max:255',
            'apellidos' => 'required|max:255',
            'role' => 'required',
            'password' => 'required|min:2|confirmed',
        ]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		$nombre =  $data['nombre'];
		$apellidos = $data['apellidos'];
		$nombre_completo = $nombre . " " . $apellidos;


		$user = User::create([
	            'usuario' => $data['usuario'],
	            'role' => $data['role'],
	            'password' => bcrypt($data['password']),
	        ]);

		$idMaestro = $user->id;
		$insert[] = ['idMaestro' => $idMaestro, 'nombre_Maestro' => $nombre_completo];
		Maestros::insert($insert);


		return $user;

	}

}
