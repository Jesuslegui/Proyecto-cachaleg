@extends('layouts.app')

@section('content')
<div class="text-center">
    <h1 class="mb-4"><i class="bi bi-house-door"></i> Cachalegui</h1>
    <p class="lead mb-5">Administra tus productos, clientes y servicios de manera fácil y rápida.</p>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Inventario</h5>
                    <p class="card-text">Gestiona el inventario de zapatos.</p>
                    <a href="{{ route('inventory.index') }}" class="btn btn-primary btn-lg"><i class="bi bi-eye"></i> Ver Inventario</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people display-4 text-success mb-3"></i>
                    <h5 class="card-title">Clientes</h5>
                    <p class="card-text">Mantén la información de tus clientes.</p>
                    <a href="{{ route('customers.index') }}" class="btn btn-success btn-lg"><i class="bi bi-eye"></i> Ver Clientes</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-tools display-4 text-info mb-3"></i>
                    <h5 class="card-title">Servicios</h5>
                    <p class="card-text">Consulta precios de cosida y pegada.</p>
                    <a href="{{ route('services.index') }}" class="btn btn-info btn-lg"><i class="bi bi-eye"></i> Ver Servicios</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection