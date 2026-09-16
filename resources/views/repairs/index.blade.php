@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="mb-1"><i class="bi bi-tools"></i> Reparaciones</h1>
        <p class="text-muted mb-0">Consulta qué zapatos están pendientes o ya fueron entregados.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('repairs.assistant') }}" class="btn btn-outline-primary"><i class="bi bi-chat-dots"></i> Asistente</a>
        <a href="{{ route('repairs.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nueva reparación</a>
    </div>
</div>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-7"><label class="visually-hidden" for="search">Buscar cliente</label><input id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar por nombre o teléfono"></div>
    <div class="col-md-3"><label class="visually-hidden" for="status">Estado</label><select id="status" name="status" class="form-select"><option value="">Todos los estados</option>@foreach(['Recibido', 'En reparación', 'Listo para entregar', 'Entregado', 'Cancelado'] as $option)<option value="{{ $option }}" @selected(request('status') === $option)>{{ $option }}</option>@endforeach</select></div>
    <div class="col-md-2 d-grid"><button class="btn btn-outline-secondary"><i class="bi bi-search"></i> Buscar</button></div>
</form>

<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
    <thead><tr><th>Cliente</th><th>Zapato</th><th>Estado</th><th>Entrega estimada</th><th>Precio</th><th class="text-end">Acciones</th></tr></thead>
    <tbody>
    @forelse($repairs as $repair)
        <tr><td><strong>{{ $repair->customer?->name ?? $repair->customer_name }}</strong><br><small class="text-muted">{{ $repair->customer?->phone ?? $repair->customer_phone ?? 'Sin teléfono' }}</small></td><td>{{ $repair->product_description }}</td><td><span class="badge text-bg-{{ $repair->status === 'Entregado' ? 'success' : ($repair->status === 'Cancelado' ? 'secondary' : 'warning') }}">{{ $repair->status }}</span></td><td>{{ $repair->estimated_delivery_at?->format('d/m/Y') ?? 'Por definir' }}</td><td>{{ $repair->price !== null ? '$'.number_format($repair->price, 2, ',', '.') : 'Por definir' }}</td><td class="text-end"><a href="{{ route('repairs.show', $repair) }}" class="btn btn-outline-primary btn-sm" title="Ver reparación"><i class="bi bi-eye"></i></a> <a href="{{ route('repairs.edit', $repair) }}" class="btn btn-outline-secondary btn-sm" title="Editar reparación"><i class="bi bi-pencil"></i></a></td></tr>
    @empty
        <tr><td colspan="6" class="text-center text-muted py-5">No hay reparaciones con esos filtros.</td></tr>
    @endforelse
    </tbody>
</table></div></div>
@endsection