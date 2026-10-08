@if($products->isEmpty())
    <div class="alert alert-info"><i class="bi bi-info-circle me-1"></i> No hay productos que coincidan con los filtros seleccionados.</div>
@else
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Producto</th><th>Categoría</th><th>Colores</th><th>Forma</th><th>Imagen</th><th>Talla</th><th>Precio</th><th>Stock</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td class="fw-semibold">{{ $product->name }}</td><td>{{ ucfirst($product->category) ?: '-' }}</td><td>{{ $product->colors ?: '-' }}</td><td>{{ $product->shape ?: '-' }}</td>
                        <td>@if($product->image)<img src="{{ asset('storage/' . $product->image) }}" width="52" height="52" class="rounded object-fit-cover" alt="{{ $product->name }}">@else<span class="text-muted">-</span>@endif</td>
                        <td>{{ $product->size }}</td><td>${{ number_format($product->price, 2) }}</td>
                        <td><span class="badge {{ $product->stock > 5 ? 'text-bg-success' : ($product->stock > 0 ? 'text-bg-warning' : 'text-bg-secondary') }}">{{ $product->stock }}</span></td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('inventory.show', $product) }}" class="btn btn-outline-secondary btn-sm" title="Ver producto" aria-label="Ver producto"><i class="bi bi-eye"></i></a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('inventory.edit', $product) }}" class="btn btn-outline-primary btn-sm" title="Editar producto" aria-label="Editar producto"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('inventory.destroy', $product) }}" method="POST" class="d-inline" data-confirm="¿Deseas desactivar este producto?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Desactivar producto" aria-label="Desactivar producto"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($products->hasPages())<div class="card-footer bg-white">{{ $products->links() }}</div>@endif
</div>
@endif