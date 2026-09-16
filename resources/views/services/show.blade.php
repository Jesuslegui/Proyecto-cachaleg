@extends('layouts.app')

@section('content')
<h1><i class="bi bi-wrench"></i> {{ $service->name }}</h1>
<div class="card mb-3">
    <div class="row g-0">
        <div class="col-md-4">
            @if($service->image)
                <img src="{{ asset('storage/' . $service->image) }}" class="img-fluid rounded-start" style="max-height:260px; object-fit:cover; width:100%;" alt="{{ $service->name }}">
            @else
                <div class="border rounded p-4 text-center text-muted" style="height:260px; display:flex; align-items:center; justify-content:center; background:#f8f9fa;">Sin imagen</div>
            @endif
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <p><strong>Precio:</strong> ${{ number_format($service->price, 2) }}</p>
                <p><strong>Descripción:</strong> {{ $service->description ?: 'Sin descripción' }}</p>
            </div>
        </div>
    </div>
</div>
<a href="{{ route('services.index') }}" class="btn btn-secondary btn-lg mt-3"><i class="bi bi-arrow-left"></i> Volver</a>
@endsection