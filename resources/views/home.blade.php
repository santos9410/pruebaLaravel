@if (Auth::check())

  @if(Auth::user()->role == "admin")
   @include('homes.homeAdmin')
  @else
    @include('homes.homeMaestro')
  @endif

@else
  <h3>Bienvenido Guest!!</h3>
  <h4>No deberias estas aquí</h4>
@endif

{{-- @include('homes.homeAdmin') --}}
