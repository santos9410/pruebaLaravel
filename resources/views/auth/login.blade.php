<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SISENI</title>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

		<link rel="stylesheet" href="{!!URL::asset('/css/bootstrap.css')!!}" >

		<script src="{!!URL::asset('/js/jquery.min.js')!!}" charset="utf-8"></script>

    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    <style type='text/css'>
    body {
    /*background:#84DCC6;*/
    background: #a5273f;
  }

.form_bg {
    background-color:#eee;
    /*background-color:#ABC8C7;*/
		/*background-color: #136F63;*/

    color:#666;
    padding:20px;
    border-radius:10px;
    position: absolute;
    border:1px solid #fff;
    margin: auto;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    width: 320px;
    height: 320px;
    max-height: 400px;
}


</style>
</head>
<body>
  <div class="container">
    <div class="row" >

        <div class="form_bg">
          <div  id="mensajesLogin" class="text-center">

          </div>

            {{-- <form method="POST" action="return false" onsubmit="return false" id="form-login" autocomplete="off" accept-charset="utf-8"> --}}
             <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
                {{-- {{ csrf_field() }} --}}
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
                 <h2 class="text-center">Iniciar Sesión</h2>
                <br/>
                <div class="form-group text-center {{ $errors->has('name') ? ' has-error' : '' }}">
                    <input type="text" class="form-control" id="usuario" placeholder="Usuario" name="usuario" required value="{{ old('usuario') }}">
										@if ($errors->has('usuario'))
												<span class="help-block" style="color:red;">
														<strong>{{ $errors->first('usuario') }}</strong>
												</span>
										@endif
                </div>
                <div class="form-group text-center {{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" class="form-control" id="pwd" name="password" placeholder="Contraseña" required>
										@if ($errors->has('password'))
												<span class="help-block">
														<strong>{{ $errors->first('password') }}</strong>
												</span>
										@endif
                </div>
                <br/>
                <div class="form-group">
                  <div class="text-center">
                    <button type="submit" class="btn btn-default" name="btn-login" id="btn-login"><span class="glyphicon glyphicon-log-in"></span> &nbsp; Iniciar Sesión</button>
                  </div>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
