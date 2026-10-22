<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\TwoFactorLoginCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TwoFactorAuthenticationController extends Controller
{
    public function settings()
    {
        return view('account.security');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'enabled' => ['required', 'boolean'],
            'password' => ['required', 'string'],
        ]);

        $user = $request->user();

        if (! Hash::check($validated['password'], $user->password)) {
            return redirect()->route('account.security')->withErrors([
                'password' => 'La contraseña actual es incorrecta.',
            ]);
        }

        $enabled = (bool) $validated['enabled'];

        if ($enabled && Validator::make(
            ['email' => $user->email],
            ['email' => ['required', 'email']],
        )->fails()) {
            return redirect()->route('account.security')->withErrors([
                'email' => 'Agrega una dirección de correo válida antes de activar la verificación en dos pasos.',
            ]);
        }

        $user->two_factor_enabled = $enabled;
        $user->save();

        if (! $enabled) {
            DB::table('two_factor_login_tokens')->where('user_id', $user->id)->delete();
        }

        return redirect()->route('account.security')->with(
            'status',
            $enabled
                ? 'La verificación en dos pasos quedó activada.'
                : 'La verificación en dos pasos quedó desactivada.'
        );
    }

    public function challenge(Request $request)
    {
        $challenge = $this->findChallenge($request);

        if (! $challenge) {
            return $this->returnToLogin($request);
        }

        return view('auth.two-factor-verify', [
            'secondsRemaining' => max(0, (int) now()->diffInSeconds($challenge->expires_at, false)),
            'maxAttemptsReached' => $challenge->attempts >= (int) config('two_factor_login.max_attempts', 5),
        ]);
    }

    public function complete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => ['required', 'digits:5'],
        ], [
            'code.digits' => 'El código debe contener exactamente 5 dígitos numéricos.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except('code'));
        }

        $validated = $validator->validated();
        $challengeToken = $request->session()->get('two_factor_challenge');

        if (! is_string($challengeToken)) {
            return $this->returnToLogin($request);
        }

        $result = DB::transaction(function () use ($challengeToken, $validated) {
            $challenge = DB::table('two_factor_login_tokens')
                ->where('token_hash', hash('sha256', $challengeToken))
                ->lockForUpdate()
                ->first();

            if (! $challenge || $challenge->used_at !== null) {
                return ['status' => 'used'];
            }

            if (now()->greaterThanOrEqualTo($challenge->expires_at)) {
                return ['status' => 'expired'];
            }

            $maxAttempts = (int) config('two_factor_login.max_attempts', 5);

            if ($challenge->attempts >= $maxAttempts) {
                return ['status' => 'attempts'];
            }

            if (! is_string($challenge->code_hash) || ! Hash::check($validated['code'], $challenge->code_hash)) {
                $attempts = $challenge->attempts + 1;
                DB::table('two_factor_login_tokens')
                    ->where('id', $challenge->id)
                    ->update(['attempts' => $attempts, 'updated_at' => now()]);

                return ['status' => $attempts >= $maxAttempts ? 'attempts' : 'incorrect'];
            }

            $user = User::query()->find($challenge->user_id);

            if (! $user || ! $user->two_factor_enabled) {
                return ['status' => 'used'];
            }

            DB::table('two_factor_login_tokens')
                ->where('id', $challenge->id)
                ->update(['used_at' => now(), 'updated_at' => now()]);

            return ['status' => 'verified', 'user' => $user];
        });

        if ($result['status'] === 'verified') {
            Auth::login($result['user']);
            $request->session()->regenerate();
            $request->session()->forget('two_factor_challenge');

            return redirect()->route('dashboard');
        }

        if ($result['status'] === 'used') {
            return $this->returnToLogin($request, 'Este código ya fue utilizado. Inicia sesión nuevamente para solicitar otro.');
        }

        $message = match ($result['status']) {
            'expired' => 'El código venció. Solicita uno nuevo para continuar.',
            'attempts' => 'Superaste el número de intentos permitidos. Solicita un código nuevo.',
            default => 'El código es incorrecto.',
        };

        return back()->withErrors(['code' => $message]);
    }

    public function resend(Request $request, TwoFactorLoginCode $twoFactorLoginCode)
    {
        $challenge = $this->findChallenge($request);

        if (! $challenge) {
            return $this->returnToLogin($request);
        }

        $user = User::query()->find($challenge->user_id);
        $challengeToken = $request->session()->get('two_factor_challenge');

        if (! $user || ! is_string($challengeToken)) {
            return $this->returnToLogin($request);
        }

        $result = $twoFactorLoginCode->send($user, $request, $challengeToken);

        if ($result === TwoFactorLoginCode::RATE_LIMITED) {
            $seconds = $twoFactorLoginCode->secondsUntilNextRequest($user, $request);

            return back()->withErrors([
                'code' => "Se solicitaron demasiados códigos. Espera {$seconds} segundos antes de volver a solicitar uno.",
            ]);
        }

        if ($result === TwoFactorLoginCode::FAILED) {
            $request->session()->forget('two_factor_challenge');

            return $this->returnToLogin($request, 'No se pudo enviar el código. Inicia sesión para intentarlo de nuevo.');
        }

        return redirect()->route('two-factor.challenge')
            ->with('status', 'Enviamos un nuevo código a tu correo electrónico.');
    }

    private function findChallenge(Request $request): ?object
    {
        $challengeToken = $request->session()->get('two_factor_challenge');

        if (! is_string($challengeToken)) {
            return null;
        }

        $challenge = DB::table('two_factor_login_tokens')
            ->where('token_hash', hash('sha256', $challengeToken))
            ->first();

        if (! $challenge || $challenge->used_at !== null) {
            return null;
        }

        $user = User::query()->find($challenge->user_id);

        return $user && $user->two_factor_enabled ? $challenge : null;
    }

    private function returnToLogin(Request $request, ?string $message = null)
    {
        $request->session()->forget('two_factor_challenge');

        return redirect()->route('login')->withErrors([
            'email' => $message ?? 'El código ya fue utilizado o la sesión de verificación ya no está activa. Inicia sesión para solicitar otro.',
        ]);
    }
}
