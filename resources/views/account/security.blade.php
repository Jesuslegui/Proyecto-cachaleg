@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0"><i class="bi bi-shield-lock"></i> Seguridad de la cuenta</h3>
            </div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="status">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <p>
                    Estado actual:
                    <strong>{{ auth()->user()->two_factor_enabled ? 'Activada' : 'Desactivada' }}</strong>
                </p>
                <p>Al activarla, enviaremos un código temporal a <strong>{{ auth()->user()->email }}</strong> cada vez que inicies sesión.</p>

                <form method="POST" action="{{ route('account.security.two-factor.update') }}"
                      onsubmit="return confirm('¿Confirmas que deseas {{ auth()->user()->two_factor_enabled ? 'desactivar' : 'activar' }} la verificación en dos pasos?');">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="enabled" value="{{ auth()->user()->two_factor_enabled ? '0' : '1' }}">

                    <div class="mb-3">
                        <label for="password" class="form-label">Confirma tu contraseña actual</label>
                        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
                    </div>

                    <button type="submit" class="btn {{ auth()->user()->two_factor_enabled ? 'btn-outline-danger' : 'btn-primary' }}">
                        {{ auth()->user()->two_factor_enabled ? 'Desactivar verificación en dos pasos' : 'Activar verificación en dos pasos' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
