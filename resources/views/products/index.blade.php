@extends('layouts.app')

@section('content')
<h1><i class="bi bi-box-seam"></i> Inventario</h1>
<a href="{{ route('inventory.create') }}" class="btn btn-success btn-lg mb-3"><i class="bi bi-plus-circle"></i> Agregar Producto</a>

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