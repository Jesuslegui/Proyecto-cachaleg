@extends('layouts.app')

@section('content')
<h1><i class="bi bi-tools"></i> Servicios de Reparación</h1>
<p class="lead">Aquí puedes ver los precios de nuestros servicios de cosida y pegada.</p>
<a href="{{ route('services.create') }}" class="btn btn-success btn-lg mb-3"><i class="bi bi-plus-circle"></i> Agregar Servicio</a>
@if($services->isEmpty())
    <div class="alert alert-info"><i class="bi bi-info-circle"></i> No hay servicios registrados aún.</div>
@else
<div class="row">
    @foreach($services as $service)
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                @if($service->image)
                    <img src="{{ asset('storage/' . $service->image) }}" class="card-img-top mb-2" style="height: 150px; object-fit: cover;">
                @else
                    <i class="bi bi-wrench display-4 text-primary mb-3"></i>
                @endif
                <h5 class="card-title">{{ $service->name }}</h5>
                <p class="card-text">{{ $service->description ?: 'Servicio de reparación' }}</p>
                <h4 class="text-success">{{ $service->price }}</h4>
                <div class="mt-3">
                    <a href="{{ route('services.show', $service) }}" class="btn btn-info btn-sm"><i class="bi bi-eye"></i> Ver</a>
                    <a href="{{ route('services.edit', $service) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Editar</a>
                    <form action="{{ route('services.destroy', $service) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection