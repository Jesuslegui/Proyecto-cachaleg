@extends('layouts.app')

@section('content')
<h1><i class="bi bi-person-circle"></i> {{ $customer->name }}</h1>
<div class="card">
    <div class="card-body">
        <p><strong>Email:</strong> {{ $customer->email }}</p>
        <p><strong>Teléfono:</strong> {{ $customer->phone ? $customer->country_code . ' ' . $customer->phone : 'No proporcionado' }}</p>
        <p><strong>Registrado:</strong> {{ $customer->created_at->format('d/m/Y') }}</p>
        @if($customer->phone)
            <a href="https://wa.me/{{ $customer->country_code }}{{ $customer->phone }}?text={{ urlencode('Hola ' . $customer->name . ', gracias por registrarte en nuestra zapatería. Me gustaría preguntarte sobre nuestros servicios o para comunicarme más fácil. ¿Cómo podemos ayudarte?') }}" class="btn btn-success" target="_blank"><i class="bi bi-whatsapp"></i> Contactar por WhatsApp</a>
        @endif
    </div>
</div>
<a href="{{ route('customers.index') }}" class="btn btn-secondary btn-lg mt-3"><i class="bi bi-arrow-left"></i> Volver</a>
@endsection