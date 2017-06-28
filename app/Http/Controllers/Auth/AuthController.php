<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller {


	protected $loginPath = '/auth/login';
	protected $redirectTo = '/';
	protected $redirectAfterLogout = '/auth/login';
	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		// $this->middleware('guest', ['except' => 'getLogout']);
		// $this->middleware('guest', ['except' => ['getLogout', 'getRegister']]);
		if($auth->guest()) {
			$this->middleware('auth',['except' =>['getLogin','postLogin']]);
		}
		$this->middleware('guest', ['except' => ['getLogout', 'getRegister', 'postRegister']]);
	}

	public function username()
	{
		return 'usuario';
	}

}
