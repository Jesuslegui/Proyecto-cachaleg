@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h1 class="h2 mb-1"><i class="bi bi-truck text-primary"></i> Proveedores</h1><p class="text-muted mb-0">Contactos y datos de abastecimiento</p></div>
    <a href="{{ route('providers.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Agregar proveedor</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Nombre</th><th>Teléfono</th><th>Correo</th><th>Dirección</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                @forelse($providers as $provider)
                    <tr>
                        <td class="fw-semibold">{{ $provider->name }}</td>
                        <td>{{ $provider->phone ? $provider->country_code . ' ' . $provider->phone : '-' }}</td>
                        <td>{{ $provider->email ?: '-' }}</td>
                        <td>
                            @if($provider->address)
                                {{ $provider->address }}<br><a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($provider->address) }}" target="_blank" rel="noopener" class="small text-decoration-none"><i class="bi bi-geo-alt"></i> Abrir en Maps</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('providers.show', $provider) }}" class="btn btn-outline-secondary btn-sm" title="Ver proveedor" aria-label="Ver proveedor"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('providers.edit', $provider) }}" class="btn btn-outline-primary btn-sm" title="Editar proveedor" aria-label="Editar proveedor"><i class="bi bi-pencil"></i></a>
                            @if($provider->phone)
                                <a href="https://wa.me/{{ $provider->country_code }}{{ $provider->phone }}?text={{ urlencode('Hola ' . $provider->name . ', soy de la zapatería. Me gustaría preguntarte sobre tus productos o para coordinar pedidos. ¿Podemos hablar?') }}" class="btn btn-outline-success btn-sm" title="Contactar por WhatsApp" aria-label="Contactar por WhatsApp" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i></a>
                            @endif
                            <form action="{{ route('providers.destroy', $provider) }}" method="POST" class="d-inline" data-confirm="¿Deseas desactivar este proveedor?">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Desactivar proveedor" aria-label="Desactivar proveedor"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-5 text-center text-muted">No hay proveedores registrados aún.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($providers->hasPages())<div class="card-footer bg-white">{{ $providers->links() }}</div>@endif
</div>
@endsection