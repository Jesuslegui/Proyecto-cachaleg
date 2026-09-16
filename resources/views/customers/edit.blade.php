@extends('layouts.app')

@section('content')
<h1><i class="bi bi-pencil-square"></i> Editar Cliente</h1>
<form action="{{ route('customers.update', $customer) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" class="form-control form-control-lg" id="name" name="name" value="{{ $customer->name }}" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control form-control-lg" id="email" name="email" value="{{ $customer->email }}">
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Teléfono</label>
        <div class="input-group">
            <select class="form-select" id="country_code" name="country_code" style="max-width: 120px;">
                <option value="+57" {{ $customer->country_code == '+57' ? 'selected' : '' }}>🇨🇴 +57</option>
                <option value="+1" {{ $customer->country_code == '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                <option value="+34" {{ $customer->country_code == '+34' ? 'selected' : '' }}>🇪🇸 +34</option>
                <option value="+52" {{ $customer->country_code == '+52' ? 'selected' : '' }}>🇲🇽 +52</option>
                <option value="+54" {{ $customer->country_code == '+54' ? 'selected' : '' }}>🇦🇷 +54</option>
            </select>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ $customer->phone }}" placeholder="Número sin código">
            <button type="button" class="btn btn-outline-primary" id="voice-customer-button" title="Completar cliente por voz"><i class="bi bi-mic"></i><span class="visually-hidden">Usar voz</span></button>
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle"></i> Actualizar</button>
    <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-lg"><i class="bi bi-arrow-left"></i> Volver</a>
</form>
<div id="voice-customer-status" class="form-text mt-2" aria-live="polite"></div>
@endsection