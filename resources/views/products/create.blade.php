@extends('layouts.app')

@section('content')
<h1>Agregar Inventario</h1>
<form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="size" class="form-label">Talla</label>
        <input type="text" class="form-control" id="size" name="size" required>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Precio</label>
        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
    </div>
    <div class="mb-3">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" class="form-control" id="stock" name="stock" required>
    </div>
    <div class="mb-3">
        <label for="colors" class="form-label">Colores</label>
        <input type="text" class="form-control" id="colors" name="colors" placeholder="Ej: negro, blanco">
    </div>
    <div class="mb-3">
        <label for="category" class="form-label">Categoría</label>
        <select class="form-control" id="category" name="category">
            <option value="">Seleccionar categoría</option>
            <option value="cueros">Cueros</option>
            <option value="zuelas">Tipos de Zuelas</option>
            <option value="hormas">Hormas de Zapatos</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Imagen</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>
@endsection