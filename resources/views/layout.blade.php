<!doctype html>
<html>
<head lang="es">
  <title>SISENI</title>
  <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   <link rel="stylesheet" href="{!!URL::asset('/fonts/glyphicons-halflings-regular.woff2')!!}" >
  <link rel="stylesheet" href="{!!URL::asset('/css/bootstrap.css')!!}" >
  <!-- Optional theme -->
  {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> --}}

  <script src="{!!URL::asset('/js/jquery.js')!!}" charset="utf-8"></script>
  <script src="{!!URL::asset('/js/bootstrap.min.js')!!}" charset="utf-8"></script>
  <script src="{!!URL::asset('/js/app/helpers.js')!!}" charset="utf-8"></script>
</head>
<body>
  @if (Auth::check())

   @if(Auth::user()->role == "admin")
    @include('menus.Admin')

  @else
    @include('menus.Maestro')
  @endif
@endif

  <!-- main content -->
  <div id="content">
    @yield('content')
  </div>






  <!-- Latest compiled and minified JavaScript -->
{{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> --}}

</body>
</html>
