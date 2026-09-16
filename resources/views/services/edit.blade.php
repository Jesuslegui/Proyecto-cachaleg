@extends('layouts.app')

@section('content')
<h1><i class="bi bi-pencil-square"></i> Editar Servicio</h1>
<form action="{{ route('services.update', $service) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Nombre del Servicio</label>
        <input type="text" class="form-control form-control-lg" id="name" name="name" value="{{ $service->name }}" required>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Precio</label>
        <input type="text" class="form-control form-control-lg" id="price" name="price" value="{{ $service->price }}" required placeholder="Ej: 18 mil pesos col 18000">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Descripción</label>
        <textarea class="form-control form-control-lg" id="description" name="description" rows="3">{{ $service->description }}</textarea>
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Imagen</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
        @if($service->image)
            <img src="{{ asset('storage/' . $service->image) }}" class="img-thumbnail mt-2" width="150">
        @endif
    </div>
    <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle"></i> Actualizar</button>
    <a href="{{ route('services.index') }}" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left"></i> Volver</a>
</form>
@endsection