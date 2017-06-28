<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TokenEntrustAbility
{
  public function handle($request, Closure $next)
  {
    $role = 'admin';

    if (Auth::check()) {
      $Brole = Auth::user()->role;

      if($role == $Brole) {
        return $next($request);
      }
      else {
        return back();
        // return response('usuario no permitido', 403)->header('Content-Type', 'text/plain');

    }
  }

  return $next($request);

  }
}
