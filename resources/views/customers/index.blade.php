@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h1 class="h2 mb-1"><i class="bi bi-people text-primary"></i> Clientes</h1><p class="text-muted mb-0">Directorio de clientes de la zapatería</p></div>
    <a href="{{ route('customers.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Agregar cliente</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Nombre</th><th>Correo</th><th>Teléfono</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td class="fw-semibold">{{ $customer->name }}</td>
                        <td>{{ $customer->email ?: '-' }}</td>
                        <td>{{ $customer->phone ? $customer->country_code . ' ' . $customer->phone : '-' }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-outline-secondary btn-sm" title="Ver cliente" aria-label="Ver cliente"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-outline-primary btn-sm" title="Editar cliente" aria-label="Editar cliente"><i class="bi bi-pencil"></i></a>
                            @if($customer->phone)
                                <a href="https://wa.me/{{ $customer->country_code }}{{ $customer->phone }}?text={{ urlencode('Hola ' . $customer->name . ', gracias por registrarte en nuestra zapatería. Me gustaría preguntarte sobre nuestros servicios o para comunicarme más fácil. ¿Cómo podemos ayudarte?') }}" class="btn btn-outline-success btn-sm" title="Contactar por WhatsApp" aria-label="Contactar por WhatsApp" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i></a>
                            @endif
                            @if(auth()->user()->isAdmin())
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline" data-confirm="¿Deseas desactivar este cliente?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Desactivar cliente" aria-label="Desactivar cliente"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-5 text-center text-muted">No hay clientes registrados aún.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customers->hasPages())<div class="card-footer bg-white">{{ $customers->links() }}</div>@endif
</div>
@endsection