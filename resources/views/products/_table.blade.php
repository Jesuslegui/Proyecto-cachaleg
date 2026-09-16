@if($products->isEmpty())
    <div class="alert alert-info"><i class="bi bi-info-circle"></i> No hay productos en esta categoría.</div>
@else
<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Colores</th>
            <th>Forma</th>
            <th>Imagen</th>
            <th>Talla</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ ucfirst($product->category) }}</td>
            <td>{{ $product->colors }}</td>
            <td>{{ $product->shape }}</td>
            <td>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" width="60" class="img-thumbnail">
                @else
                    -
                @endif
            </td>
            <td>{{ $product->size }}</td>
            <td>${{ number_format($product->price, 2) }}</td>
            <td>{{ $product->stock }}</td>
            <td>
                <a href="{{ route('inventory.show', $product) }}" class="btn btn-info btn-sm" title="Ver"><i class="bi bi-eye"></i></a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('inventory.edit', $product) }}" class="btn btn-warning btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('inventory.destroy', $product) }}" method="POST" style="display:inline;" data-confirm="¿Deseas desactivar este producto?">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar"><i class="bi bi-trash"></i></button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif