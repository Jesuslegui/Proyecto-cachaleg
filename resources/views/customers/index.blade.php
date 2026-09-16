@extends('layouts.app')

@section('content')
<h1><i class="bi bi-people"></i> Clientes</h1>
<a href="{{ route('customers.create') }}" class="btn btn-success btn-lg mb-3"><i class="bi bi-plus-circle"></i> Agregar Cliente</a>
@if($customers->isEmpty())
    <div class="alert alert-info"><i class="bi bi-info-circle"></i> No hay clientes registrados aún.</div>
@else
<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->phone ? $customer->country_code . ' ' . $customer->phone : 'N/A' }}</td>
            <td>
                <a href="{{ route('customers.show', $customer) }}" class="btn btn-info btn-sm" title="Ver"><i class="bi bi-eye"></i></a>
                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning btn-sm" title="Editar"><i class="bi bi-pencil"></i></a>
                @if($customer->phone)
                    <a href="https://wa.me/{{ $customer->country_code }}{{ $customer->phone }}?text={{ urlencode('Hola ' . $customer->name . ', gracias por registrarte en nuestra zapatería. Me gustaría preguntarte sobre nuestros servicios o para comunicarme más fácil. ¿Cómo podemos ayudarte?') }}" class="btn btn-success btn-sm" title="WhatsApp" target="_blank"><i class="bi bi-whatsapp"></i></a>
                @endif
                <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display:inline;">
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