@extends('layouts.app')

@section('content')
<h1>Agregar Proveedor</h1>
<form action="{{ route('providers.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" required>
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
        </div>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="mb-3">
        <label for="maps_url" class="form-label">URL de Google Maps (opcional)</label>
        <input type="url" class="form-control" id="maps_url" placeholder="https://www.google.com/maps/place/...">
        <div class="mt-2">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="urlToAddressBtn">Usar dirección desde Maps</button>
        </div>
        <small class="text-muted">Ej: abre Google Maps, marca punto rojo y copia la URL aquí.</small>
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Dirección / Lugar (ej. Estación, Av. 41 #6-76)</label>
        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
        <div class="mt-2">
            <button type="button" class="btn btn-outline-primary btn-sm" id="previewAddressBtn">Previsualizar en Google Maps</button>
        </div>
        <div class="mt-2" id="mapPreview" style="display:none;">
            <div class="ratio ratio-16x9">
                <iframe id="mapFrame" src="" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>

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
            alert('No se pudo extraer dirección de la URL. Usa una URL de Google Maps con q= o un punto marcado.');
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