@extends('layouts.app')

@section('content')
<h1><i class="bi bi-box-seam"></i> Inventario</h1>
<div class="d-flex flex-wrap gap-2 mb-3">@if(auth()->user()->isAdmin())<a href="{{ route('inventory.create') }}" class="btn btn-success btn-lg"><i class="bi bi-plus-circle"></i> Registrar zapato</a>@endif<a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg"><i class="bi bi-arrow-left"></i> Panel</a></div>
<form method="GET" class="row g-2 mb-4"><div class="col-md-6"><label for="search" class="visually-hidden">Buscar producto</label><input id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar por nombre, categoría o referencia"></div><div class="col-md-4"><label for="availability" class="visually-hidden">Disponibilidad</label><select id="availability" name="availability" class="form-select"><option value="">Cualquier disponibilidad</option><option value="available" @selected(request('availability') === 'available')>Disponible</option><option value="low" @selected(request('availability') === 'low')>Inventario bajo</option><option value="out" @selected(request('availability') === 'out')>Agotado</option></select></div><div class="col-md-2 d-grid"><button class="btn btn-outline-primary"><i class="bi bi-search"></i> Buscar</button></div></form>

<ul class="nav nav-tabs" id="inventoryTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">Todos</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="cueros-tab" data-bs-toggle="tab" data-bs-target="#cueros" type="button" role="tab">Cueros</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="zuelas-tab" data-bs-toggle="tab" data-bs-target="#zuelas" type="button" role="tab">Zuelas</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="hormas-tab" data-bs-toggle="tab" data-bs-target="#hormas" type="button" role="tab">Hormas</button>
    </li>
</ul>

<div class="tab-content" id="inventoryTabContent">
    <div class="tab-pane fade show active" id="all" role="tabpanel">
        @include('products._table', ['products' => $products])
    </div>
    <div class="tab-pane fade" id="cueros" role="tabpanel">
        @include('products._table', ['products' => $products->where('category', 'cueros')])
    </div>
    <div class="tab-pane fade" id="zuelas" role="tabpanel">
        @include('products._table', ['products' => $products->where('category', 'zuelas')])
    </div>
    <div class="tab-pane fade" id="hormas" role="tabpanel">
        @include('products._table', ['products' => $products->where('category', 'hormas')])
    </div>
</div>
@endsection