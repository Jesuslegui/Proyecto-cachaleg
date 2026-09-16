@extends('layouts.app')
@section('content')
<div class="mb-4"><a href="{{ route('repairs.index') }}" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Volver a reparaciones</a><h1 class="mt-2">Registrar reparación</h1><p class="text-muted">Completa los datos del cliente y del trabajo solicitado.</p></div>
<form action="{{ route('repairs.store') }}" method="POST" class="card border-0 shadow-sm p-4">@include('repairs._form')<div class="mt-4"><button class="btn btn-primary btn-lg"><i class="bi bi-check-circle"></i> Guardar reparación</button></div></form>
@endsection