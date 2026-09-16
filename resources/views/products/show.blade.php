@extends('layouts.app')

@section('content')
<h1>{{ $product->name }}</h1>
<div class="card mb-3">
    <div class="row g-0">
        <div class="col-md-4">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded-start" style="max-height: 280px; object-fit: cover; width:100%;" alt="{{ $product->name }}">
            @else
                <div class="border rounded p-4 text-center text-muted" style="height: 280px; display:flex; align-items:center; justify-content:center; background:#f8f9fa;">Sin imagen</div>
            @endif
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <p><strong>Talla:</strong> {{ $product->size }}</p>
                <p><strong>Precio:</strong> ${{ number_format($product->price, 2) }}</p>
                <p><strong>Stock:</strong> {{ $product->stock }}</p>
                <p><strong>Colores:</strong> {{ $product->colors ?: '-' }}</p>
                <p><strong>Categoría:</strong> {{ ucfirst($product->category) ?: '-' }}</p>
            </div>
        </div>
    </div>
</div>
<a href="{{ route('inventory.index') }}" class="btn btn-secondary">Volver</a>
@endsection