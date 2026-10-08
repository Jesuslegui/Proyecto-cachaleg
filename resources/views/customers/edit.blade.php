@extends('layouts.app')

@section('content')
<div class="mb-4"><h1 class="h2 mb-1"><i class="bi bi-pencil-square text-primary"></i> Editar cliente</h1><p class="text-muted mb-0">Actualiza los datos de contacto</p></div>
<div class="card border-0 shadow-sm"><div class="card-body p-4">
    <form action="{{ route('customers.update', $customer) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label for="name" class="form-label">Nombre</label><input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $customer->name) }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-6"><label for="email" class="form-label">Correo electrónico</label><input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $customer->email) }}">@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="col-md-8"><label for="phone" class="form-label">Teléfono</label><div class="input-group">
                <select class="form-select flex-grow-0 @error('country_code') is-invalid @enderror" id="country_code" name="country_code" style="max-width: 130px;">
                    @foreach(['+57' => '🇨🇴 +57', '+1' => '🇺🇸 +1', '+34' => '🇪🇸 +34', '+52' => '🇲🇽 +52', '+54' => '🇦🇷 +54'] as $code => $label)<option value="{{ $code }}" @selected(old('country_code', $customer->country_code) === $code)>{{ $label }}</option>@endforeach
                </select>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" placeholder="Número sin código">
                <button type="button" class="btn btn-outline-primary" id="voice-customer-button" title="Completar cliente por voz" aria-label="Completar cliente por voz"><i class="bi bi-mic"></i><span class="visually-hidden">Usar voz</span></button>
            </div>@error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
        </div>
        <div id="voice-customer-status" class="form-text mt-2" aria-live="polite"></div>
        <div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Guardar cambios</button><a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Cancelar</a></div>
    </form>
</div></div>
@endsection