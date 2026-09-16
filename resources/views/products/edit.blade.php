@extends('layouts.app')

@section('content')
<h1>Editar Inventario</h1>
<form action="{{ route('inventory.update', ['product' => $product->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
    </div>
    <div class="mb-3">
        <label for="size" class="form-label">Talla</label>
        <input type="text" class="form-control" id="size" name="size" value="{{ $product->size }}" required>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Precio</label>
        <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
    </div>
    <div class="mb-3">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
    </div>
    <div class="mb-3">
        <label for="colors" class="form-label">Colores</label>
        <input type="text" class="form-control" id="colors" name="colors" value="{{ $product->colors }}" placeholder="Ej: negro, blanco">
    </div>
    <div class="mb-3">
        <label for="category" class="form-label">Categoría</label>
        <select class="form-control" id="category" name="category">
            <option value="">Seleccionar categoría</option>
            <option value="cueros" {{ $product->category == 'cueros' ? 'selected' : '' }}>Cueros</option>
            <option value="zuelas" {{ $product->category == 'zuelas' ? 'selected' : '' }}>Tipos de Zuelas</option>
            <option value="hormas" {{ $product->category == 'hormas' ? 'selected' : '' }}>Hormas de Zapatos</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Imagen</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" class="img-thumbnail mt-2" width="150">
        @endif
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
</form>
@endsection