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
@if(auth()->user()->isAdmin())<div class="card border-0 shadow-sm mb-3"><div class="card-body"><h2 class="h5">Registrar movimiento</h2><form method="POST" action="{{ route('inventory.movements.store', ['product' => $product->id]) }}" class="row g-2 align-items-end">@csrf<div class="col-md-3"><label for="type" class="form-label">Tipo</label><select id="type" name="type" class="form-select"><option value="in">Entrada</option><option value="out">Salida</option></select></div><div class="col-md-3"><label for="quantity" class="form-label">Cantidad</label><input id="quantity" name="quantity" type="number" min="1" class="form-control" required></div><div class="col-md-3"><label for="customer_id" class="form-label">Cliente</label><select id="customer_id" name="customer_id" class="form-select"><option value="">No aplica</option>@foreach($customers as $customer)<option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone ?: 'sin teléfono' }}</option>@endforeach</select></div><div class="col-md-3"><label for="reason" class="form-label">Motivo</label><input id="reason" name="reason" class="form-control" placeholder="Ej.: compra o venta"></div><div class="col-12 d-grid d-md-flex justify-content-md-end"><button class="btn btn-primary">Registrar movimiento</button></div></form></div></div>@endif
<a href="{{ route('inventory.index') }}" class="btn btn-secondary">Volver</a>
@endsection