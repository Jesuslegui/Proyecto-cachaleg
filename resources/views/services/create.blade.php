@extends('layouts.app')

@section('content')
<h1><i class="bi bi-plus-circle"></i> Agregar Servicio</h1>
<form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Nombre del Servicio</label>
        <input type="text" class="form-control form-control-lg" id="name" name="name" required placeholder="Ej: Cosida">
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Precio</label>
        <input type="text" class="form-control form-control-lg" id="price" name="price" required placeholder="Ej: 18 mil pesos col 18000">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Descripción</label>
        <textarea class="form-control form-control-lg" id="description" name="description" rows="3" placeholder="Descripción del servicio"></textarea>
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Imagen</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle"></i> Guardar</button>
    <a href="{{ route('services.index') }}" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left"></i> Volver</a>
</form>
@endsection