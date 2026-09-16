<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Zapatería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { font-size: 1.1rem; }
        .btn { font-size: 1.2rem; padding: 10px 20px; }
        .table th, .table td { font-size: 1.1rem; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/"><i class="bi bi-shop"></i> Cachalegui</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('inventory.index') }}"><i class="bi bi-box-seam"></i> Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customers.index') }}"><i class="bi bi-people"></i> Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('repairs.index') }}"><i class="bi bi-tools"></i> Reparaciones</a>
                    </li>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('providers.index') }}"><i class="bi bi-truck"></i> Proveedores</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('services.index') }}"><i class="bi bi-tools"></i> Servicios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index') }}"><i class="bi bi-person-gear"></i> Usuarios</a>
                            </li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right"></i> Salir</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-person-circle"></i> Iniciar Sesión</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> Revisa los datos indicados antes de continuar.<ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        @yield('content')
    </div>
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header"><h2 class="modal-title h5" id="confirmationModalLabel">Confirmar acción</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
                <div class="modal-body" id="confirmationModalMessage">Esta acción no se puede deshacer.</div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-danger" id="confirmationModalSubmit">Sí, continuar</button></div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            let pendingForm = null;
            const confirmationElement = document.getElementById('confirmationModal');
            if (confirmationElement) {
                const confirmationModal = new bootstrap.Modal(confirmationElement);
                document.querySelectorAll('form[data-confirm]').forEach((form) => {
                    form.addEventListener('submit', (event) => {
                        event.preventDefault();
                        pendingForm = form;
                        document.getElementById('confirmationModalMessage').textContent = form.dataset.confirm;
                        confirmationModal.show();
                    });
                });
                document.querySelectorAll('form[data-confirm-on-status]').forEach((form) => {
                    form.addEventListener('submit', (event) => {
                        const status = form.querySelector('[name="status"]');
                        if (!status || status.value !== form.dataset.confirmOnStatus || form.dataset.confirmed === 'true') return;
                        event.preventDefault();
                        pendingForm = form;
                        document.getElementById('confirmationModalMessage').textContent = 'La reparación se marcará como cancelada. ¿Deseas continuar?';
                        confirmationModal.show();
                    });
                });
                document.getElementById('confirmationModalSubmit').addEventListener('click', () => {
                    if (pendingForm) {
                        pendingForm.dataset.confirmed = 'true';
                        pendingForm.submit();
                    }
                    pendingForm = null;
                    confirmationModal.hide();
                });
            }

            const button = document.getElementById('voice-customer-button');
            if (!button) return;

            const Recognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            const status = document.getElementById('voice-customer-status');
            if (!Recognition) {
                button.disabled = true;
                status.textContent = 'El dictado por voz no está disponible en este navegador.';
                return;
            }

            button.addEventListener('click', () => {
                const recognition = new Recognition();
                recognition.lang = 'es-CO';
                recognition.interimResults = false;
                status.textContent = 'Escuchando nombre y teléfono...';
                recognition.start();

                recognition.onresult = (event) => {
                    const transcript = event.results[0][0].transcript.trim();
                    const phone = transcript.match(/[+]?\d[\d\s-]{6,}/)?.[0]?.replace(/\D/g, '') || '';
                    const name = transcript.replace(/[+]?\d[\d\s-]{6,}/, '').replace(/teléfono|telefono|número|numero/gi, '').trim();
                    if (name) document.getElementById('name').value = name;
                    if (phone) document.getElementById('phone').value = phone;
                    status.textContent = phone ? 'Datos completados. Revísalos antes de guardar.' : 'No encontré un teléfono; revisa el nombre y completa el teléfono.';
                };
                recognition.onerror = () => { status.textContent = 'No pude escuchar la información. Inténtalo nuevamente.'; };
                recognition.onend = () => { if (status.textContent === 'Escuchando nombre y teléfono...') status.textContent = 'Dictado finalizado.'; };
            });
        })();
    </script>
</body>
</html>