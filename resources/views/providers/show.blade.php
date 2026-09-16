@extends('layouts.app')

@section('content')
<h1>{{ $provider->name }}</h1>
<p><strong>Teléfono:</strong> {{ $provider->phone ? $provider->country_code . ' ' . $provider->phone : '-' }}</p>
<p><strong>Email:</strong> {{ $provider->email ?: '-' }}</p>
<p><strong>Dirección:</strong> {{ $provider->address ?: '-' }}</p>
@if($provider->address)
    @php
        $mapQuery = urlencode($provider->address);
    @endphp
    <div class="mb-3">
        <a href="https://www.google.com/maps/search/?api=1&query={{ $mapQuery }}" target="_blank" class="btn btn-primary mb-2"><i class="bi bi-geo-alt"></i> Ver en Google Maps</a>
        <div class="ratio ratio-16x9">
            <iframe src="https://www.google.com/maps?q={{ $mapQuery }}&output=embed" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
@endif
@if($provider->phone)
    <a href="https://wa.me/{{ $provider->country_code }}{{ $provider->phone }}?text={{ urlencode('Hola ' . $provider->name . ', soy de la zapatería. Me gustaría preguntarte sobre tus productos o para coordinar pedidos. ¿Podemos hablar?') }}" class="btn btn-success" target="_blank"><i class="bi bi-whatsapp"></i> Contactar por WhatsApp</a>
@endif
<a href="{{ route('providers.index') }}" class="btn btn-secondary">Volver</a>
@endsection