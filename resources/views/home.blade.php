@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <p class="text-uppercase text-muted small fw-semibold mb-1">Resumen de la zapatería</p>
        <h1 class="mb-1"><i class="bi bi-speedometer2"></i> Panel principal</h1>
        <p class="text-muted mb-0">Consulta lo importante y accede rápidamente a las tareas del día.</p>
    </div>
    <a href="{{ route('repairs.create') }}" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle"></i> Registrar reparación
    </a>
</div>

<div class="row g-3 mb-4">
    @foreach([
        ['products', 'Zapatos registrados', 'box-seam', 'primary', route('inventory.index')],
        ['lowStock', 'Inventario bajo', 'exclamation-triangle', 'warning', route('inventory.index')],
        ['outOfStock', 'Agotados', 'x-octagon', 'danger', route('inventory.index')],
        ['customers', 'Clientes', 'people', 'success', route('customers.index')],
        ['pendingRepairs', 'Reparaciones pendientes', 'tools', 'info', route('repairs.index')],
    ] as [$key, $label, $icon, $color, $url])
        <div class="col-6 col-xl">
            <a href="{{ $url }}" class="card h-100 text-decoration-none border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="text-muted">{{ $label }}</span>
                        <i class="bi bi-{{ $icon }} text-{{ $color }} fs-4"></i>
                    </div>
                    <strong class="display-6 text-dark">{{ $stats[$key] }}</strong>
                </div>
            </a>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h2 class="h5 mb-0"><i class="bi bi-arrow-left-right"></i> Últimos movimientos</h2>
                <a href="{{ route('inventory.index') }}" class="btn btn-outline-primary btn-sm">Ver inventario</a>
            </div>
            <div class="card-body p-0">
                @if($latestMovements->isEmpty())
                    <p class="text-muted p-4 mb-0">Todavía no hay movimientos registrados.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead><tr><th>Producto</th><th>Tipo</th><th>Cantidad</th><th>Fecha</th></tr></thead>
                            <tbody>
                                @foreach($latestMovements as $movement)
                                    <tr>
                                        <td>{{ $movement->product?->name ?? 'Producto eliminado' }}</td>
                                        <td>{{ ucfirst($movement->type) }}</td>
                                        <td>{{ $movement->quantity }}</td>
                                        <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white"><h2 class="h5 mb-0"><i class="bi bi-lightning-charge"></i> Accesos rápidos</h2></div>
            <div class="card-body d-grid gap-2">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('inventory.create') }}" class="btn btn-outline-primary btn-lg text-start"><i class="bi bi-box-seam"></i> Registrar zapato</a>
                @endif
                <a href="{{ route('customers.create') }}" class="btn btn-outline-success btn-lg text-start"><i class="bi bi-person-plus"></i> Registrar cliente</a>
                <a href="{{ route('repairs.index') }}" class="btn btn-outline-info btn-lg text-start"><i class="bi bi-tools"></i> Consultar reparaciones</a>
            </div>
        </div>
    </div>
</div>
@endsection