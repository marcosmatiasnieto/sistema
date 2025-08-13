formulario de creacion de empleado
<form action="{{url('/empleado')}}" method="post" enctype="multipart/form-data">
    {{-- control de seguridad @csrf --}}
    @csrf
@include('empleado.form')



</form>
