@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
        <div class="card">
            <div class="card-header text-center">
                <h3><i class="bi bi-shield-check"></i> Verificación en dos pasos</h3>
            </div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="status">{{ session('status') }}</div>
                @endif
                <p>Hemos enviado un código de verificación a tu correo electrónico.</p>

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if ($maxAttemptsReached)
                    <div class="alert alert-warning" role="status">
                        Superaste el número de intentos permitidos. Solicita un código nuevo.
                    </div>
                @else
                    <form method="POST" action="{{ route('two-factor.verify') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label">Código de 5 dígitos</label>
                            <input
                                type="text"
                                class="form-control form-control-lg text-center @error('code') is-invalid @enderror"
                                id="code"
                                name="code"
                                inputmode="numeric"
                                pattern="[0-9]{5}"
                                maxlength="5"
                                autocomplete="one-time-code"
                                aria-describedby="code-help"
                                required
                                autofocus
                            >
                            <div id="code-help" class="form-text">
                                El código vence en <span id="code-countdown">{{ gmdate('i:s', $secondsRemaining) }}</span>.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">Verificar código</button>
                    </form>
                @endif

                <form method="POST" action="{{ route('two-factor.resend') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary w-100">Reenviar código</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    (() => {
        let seconds = {{ $secondsRemaining }};
        const countdown = document.getElementById('code-countdown');
        if (!countdown) return;

        const updateCountdown = () => {
            countdown.textContent = `${String(Math.floor(seconds / 60)).padStart(2, '0')}:${String(seconds % 60).padStart(2, '0')}`;
            if (seconds > 0) seconds -= 1;
        };

        updateCountdown();
        window.setInterval(updateCountdown, 1000);
    })();
</script>
@endsection
