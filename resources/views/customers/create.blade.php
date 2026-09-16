@extends('layouts.app')

@section('content')
<h1>Agregar Cliente</h1>
<form action="{{ route('customers.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Teléfono</label>
        <div class="input-group">
            <select class="form-select" id="country_code" name="country_code" style="max-width: 120px;">
                <option value="+57" selected>🇨🇴 +57</option>
                <option value="+1">🇺🇸 +1</option>
                <option value="+34">🇪🇸 +34</option>
                <option value="+52">🇲🇽 +52</option>
                <option value="+54">🇦🇷 +54</option>
            </select>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Número sin código">
            <button type="button" class="btn btn-outline-primary" id="voice-customer-button" title="Registrar cliente por voz"><i class="bi bi-mic"></i><span class="visually-hidden">Usar voz</span></button>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>
<div id="voice-customer-status" class="form-text mt-2" aria-live="polite"></div>
@endsection