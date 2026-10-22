@csrf
<div class="row g-3">
    <div class="col-md-6"><label for="customer_name" class="form-label">Nombre del cliente</label><input id="customer_name" name="customer_name" class="form-control" value="{{ old('customer_name', $repair->customer_name ?? '') }}" required>@error('customer_name')<div class="text-danger small">{{ $message }}</div>@enderror</div>
    <div class="col-md-6"><label for="customer_phone" class="form-label">Teléfono</label><input id="customer_phone" name="customer_phone" class="form-control" value="{{ old('customer_phone', $repair->customer_phone ?? '') }}" inputmode="tel">@error('customer_phone')<div class="text-danger small">{{ $message }}</div>@enderror</div>
    <div class="col-12"><label for="product_description" class="form-label">¿Qué zapato dejó?</label><input id="product_description" name="product_description" class="form-control" value="{{ old('product_description', $repair->product_description ?? '') }}" placeholder="Ej.: zapatos deportivos negros" required></div>
    <div class="col-12">
        <label for="repair-image" class="form-label">Fotografía del zapato</label>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <input id="repair-image" name="image" type="file" accept="image/jpeg,image/png,image/webp" class="form-control @error('image') is-invalid @enderror">
            <button id="repair-camera" type="button" class="btn btn-outline-secondary"><i class="bi bi-camera me-1"></i> Tomar foto</button>
            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div id="repair-image-empty" class="small text-muted mt-2" @if(isset($repair) && $repair->image) hidden @endif>Sin fotografía seleccionada.</div>
        @if(isset($repair) && $repair->image)
            <img id="repair-image-current" src="{{ asset('storage/' . $repair->image) }}" alt="Zapato asociado a la reparación" class="img-thumbnail mt-2" style="max-width: 240px; max-height: 200px; object-fit: contain">
        @endif
        <img id="repair-image-preview" alt="Vista previa de la fotografía seleccionada" class="img-thumbnail mt-2" style="max-width: 240px; max-height: 200px; object-fit: contain" hidden>
    </div>
    <div class="col-md-6"><label for="service_id" class="form-label">Tipo de reparación</label><select id="service_id" name="service_id" class="form-select"><option value="">Seleccionar servicio</option>@foreach($services as $service)<option value="{{ $service->id }}" @selected(old('service_id', $repair->service_id ?? '') == $service->id)>{{ $service->name }}</option>@endforeach</select></div>
    <div class="col-md-6"><label for="status" class="form-label">Estado</label><select id="status" name="status" class="form-select">@foreach(['Recibido', 'En reparación', 'Listo para entregar', 'Entregado', 'Cancelado'] as $status)<option @selected(old('status', $repair->status ?? 'Recibido') === $status)>{{ $status }}</option>@endforeach</select></div>
    <div class="col-md-4"><label for="received_at" class="form-label">Fecha de recepción</label><input id="received_at" name="received_at" type="datetime-local" class="form-control" value="{{ old('received_at', isset($repair) && $repair->received_at ? $repair->received_at->format('Y-m-d\\TH:i') : now()->format('Y-m-d\\TH:i')) }}"></div>
    <div class="col-md-4"><label for="estimated_delivery_at" class="form-label">Entrega estimada</label><input id="estimated_delivery_at" name="estimated_delivery_at" type="datetime-local" class="form-control" value="{{ old('estimated_delivery_at', isset($repair) && $repair->estimated_delivery_at ? $repair->estimated_delivery_at->format('Y-m-d\\TH:i') : '') }}"></div>
    <div class="col-md-4"><label for="price" class="form-label">Precio</label><input id="price" name="price" type="number" min="0" step="0.01" class="form-control" value="{{ old('price', $repair->price ?? '') }}"></div>
    <div class="col-12"><label for="description" class="form-label">Descripción del trabajo</label><textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $repair->description ?? '') }}</textarea></div>
    <div class="col-12"><label for="observations" class="form-label">Observaciones</label><textarea id="observations" name="observations" class="form-control" rows="2">{{ old('observations', $repair->observations ?? '') }}</textarea></div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('repair-image');
    const cameraButton = document.getElementById('repair-camera');
    const preview = document.getElementById('repair-image-preview');
    const currentImage = document.getElementById('repair-image-current');
    const emptyMessage = document.getElementById('repair-image-empty');
    let previewUrl;

    cameraButton.addEventListener('click', () => {
        input.setAttribute('capture', 'environment');
        input.click();
    });

    input.addEventListener('change', () => {
        input.removeAttribute('capture');
        const file = input.files[0];
        if (!file) return;

        if (previewUrl) URL.revokeObjectURL(previewUrl);
        previewUrl = URL.createObjectURL(file);
        preview.src = previewUrl;
        preview.hidden = false;
        if (currentImage) currentImage.hidden = true;
        emptyMessage.hidden = true;
    });
});
</script>