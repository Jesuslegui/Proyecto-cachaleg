@extends('layouts.app')

@section('content')
<div class="mb-4"><h1 class="h2 mb-1"><i class="bi bi-pencil-square text-primary"></i> Editar producto</h1><p class="text-muted mb-0">Actualiza los datos del inventario</p></div>
<div class="card border-0 shadow-sm"><div class="card-body p-4">
    <form action="{{ route('inventory.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label for="name" class="form-label">Nombre</label><input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-3"><label for="category" class="form-label">Categoría</label><select class="form-select @error('category') is-invalid @enderror" id="category" name="category"><option value="">Seleccionar categoría</option>@foreach(['cueros' => 'Cueros', 'zuelas' => 'Tipos de zuelas', 'hormas' => 'Hormas de zapatos'] as $value => $label)<option value="{{ $value }}" @selected(old('category', $product->category) === $value)>{{ $label }}</option>@endforeach</select>@error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-3"><label for="size" class="form-label">Talla</label><input type="text" class="form-control @error('size') is-invalid @enderror" id="size" name="size" value="{{ old('size', $product->size) }}" required>@error('size')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-3"><label for="price" class="form-label">Precio</label><input type="number" min="0" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>@error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-3"><label for="stock" class="form-label">Stock</label><input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>@error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-3"><label for="colors" class="form-label">Colores</label><input type="text" class="form-control @error('colors') is-invalid @enderror" id="colors" name="colors" value="{{ old('colors', $product->colors) }}" placeholder="Ej.: negro, blanco">@error('colors')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-3"><label for="shape" class="form-label">Forma</label><input type="text" class="form-control @error('shape') is-invalid @enderror" id="shape" name="shape" value="{{ old('shape', $product->shape) }}">@error('shape')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-6"><label for="image" class="form-label">Imagen</label><input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">@error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if($product->image)<img src="{{ asset('storage/' . $product->image) }}" class="img-thumbnail mt-2" width="120" alt="Imagen de {{ $product->name }}">@endif
            </div>
        </div>
        <div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Guardar cambios</button><a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">Cancelar</a></div>
    </form>
</div></div>
@endsection