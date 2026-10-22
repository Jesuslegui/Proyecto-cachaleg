@extends('layouts.app')
@section('content')
<div class="mb-4"><a href="{{ route('users.index') }}" class="text-decoration-none"><i class="bi bi-arrow-left"></i> Volver a usuarios</a><h1 class="mt-2">Editar usuario</h1></div>
<div class="card border-0 shadow-sm"><div class="card-body p-4"><form method="POST" action="{{ route('users.update', $user) }}">@method('PUT')@include('users._form')<div class="d-flex gap-2 mt-4"><button class="btn btn-primary"><i class="bi bi-save me-1"></i> Guardar cambios</button><a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancelar</a></div></form></div></div>
@endsection