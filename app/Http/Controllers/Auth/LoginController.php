<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TwoFactorLoginCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request, TwoFactorLoginCode $twoFactorLoginCode)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $provider = Auth::getProvider();
        $user = $provider->retrieveByCredentials($credentials);

        if (! $user || ! $provider->validateCredentials($user, $credentials)) {
            return back()->withErrors([
                'email' => 'Las credenciales no coinciden.',
            ]);
        }

        if ($user instanceof User && $user->two_factor_enabled) {
            $challengeToken = Str::random(64);
            $result = $twoFactorLoginCode->send($user, $request, $challengeToken);

            if ($result === TwoFactorLoginCode::RATE_LIMITED) {
                $seconds = $twoFactorLoginCode->secondsUntilNextRequest($user, $request);

                return redirect()->route('login')->withErrors([
                    'email' => "Se solicitaron demasiados códigos. Espera {$seconds} segundos antes de intentarlo de nuevo.",
                ]);
            }

            if ($result === TwoFactorLoginCode::FAILED) {
                $request->session()->forget('two_factor_challenge');

                return redirect()->route('login')->withErrors([
                    'email' => 'No se pudo enviar el código de verificación. Inténtalo de nuevo más tarde.',
                ]);
            }

            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->put('two_factor_challenge', $challengeToken);

            return redirect()->route('two-factor.challenge');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
