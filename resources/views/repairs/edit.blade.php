@extends('layouts.app')
@section('content')
<div class="mb-4"><a href="{{ route('repairs.index') }}" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Volver a reparaciones</a><h1 class="mt-2">Editar reparación</h1></div>
<form action="{{ route('repairs.update', $repair) }}" method="POST" class="card border-0 shadow-sm p-4" data-confirm-on-status="Cancelado">@method('PUT')@include('repairs._form')<div class="mt-4"><button class="btn btn-primary btn-lg"><i class="bi bi-save"></i> Guardar cambios</button></div></form>
@endsection