@extends('layout')
@section('content')

	<div class="container">



		<div class="panel panel-info">
		  <div class="panel-heading">
		    <h3 class="panel-title text-center" style="padding:12px 0px;font-size:20px;"><strong>Importar datos sustentantes registrados</strong></h3>
		  </div>
		  <div class="panel-body">

		  		@if ($message = Session::get('success'))
					<div class="alert alert-success" role="alert">
						{{ Session::get('success') }}
					</div>
				@endif

				@if ($message = Session::get('error'))
					<div class="alert alert-danger" role="alert">
						{{ Session::get('error') }}
					</div>
				@endif

				<h4>Importar Archivo:</h4>
				<form class ="form" style="border: 4px solid #5e30a1;margin-top: 15px;padding: 20px;" action="{{ URL::to('/importar/datos/DataRegistrados') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="file" name="import_file" />
					{{-- {{ csrf_field() }} --}}
					<br/>

					<button class="btn btn-primary">Importar Datos</button>

				</form>



		  </div>
		</div>
	</div>

@stop
