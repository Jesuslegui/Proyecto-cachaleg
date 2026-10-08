@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h1 class="h2 mb-1"><i class="bi bi-box-seam text-primary"></i> Inventario</h1><p class="text-muted mb-0">Productos y existencias</p></div>
    @if(auth()->user()->isAdmin())<a href="{{ route('inventory.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Registrar producto</a>@endif
</div>

<form method="GET" action="{{ route('inventory.index') }}" class="row g-2 align-items-end mb-4">
    <input type="hidden" name="category" value="{{ request('category', 'all') }}">
    <div class="col-md-6"><label for="search" class="form-label">Buscar producto</label><input id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Nombre, categoría o referencia"></div>
    <div class="col-md-4"><label for="availability" class="form-label">Disponibilidad</label><select id="availability" name="availability" class="form-select"><option value="">Cualquier disponibilidad</option><option value="available" @selected(request('availability') === 'available')>Disponible</option><option value="low" @selected(request('availability') === 'low')>Inventario bajo</option><option value="out" @selected(request('availability') === 'out')>Agotado</option></select></div>
    <div class="col-md-2 d-grid"><button class="btn btn-outline-primary"><i class="bi bi-search me-1"></i> Buscar</button></div>
</form>

@php($activeCategory = request('category', 'all'))
<ul class="nav nav-tabs mb-3" aria-label="Filtrar inventario por categoría">
    @foreach(['all' => 'Todos', 'cueros' => 'Cueros', 'zuelas' => 'Zuelas', 'hormas' => 'Hormas'] as $categoryKey => $categoryLabel)
        <li class="nav-item"><a class="nav-link {{ $activeCategory === $categoryKey ? 'active' : '' }}" href="{{ route('inventory.index', array_merge(request()->except('category', 'page'), ['category' => $categoryKey])) }}">{{ $categoryLabel }}</a></li>
    @endforeach
</ul>

@include('products._table', ['products' => $products])
@endsection