@extends('layouts.app')

@section('content')
<h1><i class="bi bi-truck"></i> Proveedores</h1>
<a href="{{ route('providers.create') }}" class="btn btn-success btn-lg mb-3"><i class="bi bi-plus-circle"></i> Agregar Proveedor</a>
@if($providers->isEmpty())
    <div class="alert alert-info"><i class="bi bi-info-circle"></i> No hay proveedores registrados aún.</div>
@else
<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Dirección</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($providers as $provider)
        <tr>
            <td>{{ $provider->name }}</td>
            <td>{{ $provider->phone ? $provider->country_code . ' ' . $provider->phone : '-' }}</td>
            <td>{{ $provider->email ?: '-' }}</td>
            <td>
                @if($provider->address)
                    {{ $provider->address }}<br>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($provider->address) }}" target="_blank" class="text-decoration-none" title="Ver dirección en Maps"><i class="bi bi-geo-alt"></i> Abrir en Maps</a>
                @else
                    -
                @endif
            </td>
            <td>
                <a href="{{ route('providers.show', $provider) }}" class="btn btn-info btn-sm" title="Ver"><i class="bi bi-eye"></i></a>
                <a href="{{ route('providers.edit', $provider) }}" class="btn btn-warning btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                @if($provider->phone)
                    <a href="https://wa.me/{{ $provider->country_code }}{{ $provider->phone }}?text={{ urlencode('Hola ' . $provider->name . ', soy de la zapatería. Me gustaría preguntarte sobre tus productos o para coordinar pedidos. ¿Podemos hablar?') }}" class="btn btn-success btn-sm" title="WhatsApp" target="_blank"><i class="bi bi-whatsapp"></i></a>
                @endif
                <form action="{{ route('providers.destroy', $provider) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" onclick="return confirm('¿Estás seguro?')"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection