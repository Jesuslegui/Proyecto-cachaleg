@extends('layouts.app')

@section('content')
<div class="mb-4"><h1 class="h2 mb-1"><i class="bi bi-pencil-square text-primary"></i> Editar proveedor</h1><p class="text-muted mb-0">Actualiza los datos de contacto y ubicación</p></div>
<div class="card border-0 shadow-sm"><div class="card-body p-4">
<form action="{{ route('providers.update', $provider) }}" method="POST">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-md-6"><label for="name" class="form-label">Nombre</label><input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $provider->name) }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-6"><label for="email" class="form-label">Correo electrónico</label><input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $provider->email) }}">@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-6"><label for="phone" class="form-label">Teléfono</label><div class="input-group">
            <select class="form-select flex-grow-0 @error('country_code') is-invalid @enderror" id="country_code" name="country_code" style="max-width: 130px;">
                @foreach(['+57' => '🇨🇴 +57', '+1' => '🇺🇸 +1', '+34' => '🇪🇸 +34', '+52' => '🇲🇽 +52', '+54' => '🇦🇷 +54'] as $code => $label)<option value="{{ $code }}" @selected(old('country_code', $provider->country_code) === $code)>{{ $label }}</option>@endforeach
            </select>
            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $provider->phone) }}" placeholder="Número sin código">
        </div>@error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror</div>
        <div class="col-md-6"><label for="maps_url" class="form-label">URL de Google Maps <span class="text-muted">(opcional)</span></label><input type="url" class="form-control" id="maps_url" placeholder="https://www.google.com/maps/place/...">
            <div class="form-text">Pega una URL de Google Maps para completar la ubicación.</div><button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="urlToAddressBtn">Usar dirección desde Maps</button>
        </div>
        <div class="col-12"><label for="address" class="form-label">Dirección o lugar</label><textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $provider->address) }}</textarea>@error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="previewAddressBtn">Previsualizar en Google Maps</button>
            <div class="mt-3" id="mapPreview" style="display:none;"><div class="ratio ratio-16x9"><iframe id="mapFrame" src="" style="border:0;" allowfullscreen="" loading="lazy" title="Vista previa de Google Maps"></iframe></div></div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Guardar cambios</button><a href="{{ route('providers.index') }}" class="btn btn-outline-secondary">Cancelar</a></div>
</form>
</div></div>

<script>
    const previewBtn = document.getElementById('previewAddressBtn');
    const mapsUrl = document.getElementById('maps_url');
    const urlToAddressBtn = document.getElementById('urlToAddressBtn');
    const addressField = document.getElementById('address');
    const mapPreview = document.getElementById('mapPreview');
    const mapFrame = document.getElementById('mapFrame');

    function parseGoogleMapsAddress(url) {
        try {
            const u = new URL(url);
            const q = u.searchParams.get('q');
            if (q) return decodeURIComponent(q);
            const path = u.pathname;
            const match = path.match(/@(-?\d+\.\d+),(-?\d+\.\d+),/);
            if (match) return `${match[1]},${match[2]}`;
            return null;
        } catch (e) {
            return null;
        }
    }

    urlToAddressBtn?.addEventListener('click', function() {
        const url = mapsUrl.value.trim();
        if (!url) {
            alert('Ingresa la URL de Google Maps.');
            return;
        }
        const address = parseGoogleMapsAddress(url);
        if (!address) {
            alert('No se pudo extraer dirección de la URL. Usa una URL válida de Google Maps.');
            return;
        }
        addressField.value = address;
        alert('Dirección cargada. Puedes previsualizar si quieres.');
    });

    previewBtn?.addEventListener('click', function() {
        const address = addressField.value.trim();
        if (!address) {
            alert('Escribe un lugar o dirección para previsualizar en Google Maps.');
            return;
        }
        const query = encodeURIComponent(address);
        mapFrame.src = `https://www.google.com/maps?q=${query}&output=embed`;
        mapPreview.style.display = 'block';
    });
</script>
@endsection