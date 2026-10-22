<?php

namespace App\Services;

use App\Mail\TwoFactorLoginMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Throwable;

class TwoFactorLoginCode
{
    public const SENT = 'sent';

    public const RATE_LIMITED = 'rate_limited';

    public const FAILED = 'failed';

    public function send(User $user, Request $request, string $challengeToken): string
    {
        $maxRequests = (int) config('two_factor_login.max_emails', 3);

        if (RateLimiter::tooManyAttempts($this->rateLimitKey($user, $request), $maxRequests)) {
            return self::RATE_LIMITED;
        }

        RateLimiter::hit(
            $this->rateLimitKey($user, $request),
            (int) config('two_factor_login.rate_limit_seconds', 600),
        );

        $challengeHash = hash('sha256', $challengeToken);

        $code = DB::transaction(function () use ($user, $challengeHash) {
            User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();

            $previousCodeHash = DB::table('two_factor_login_tokens')
                ->where('user_id', $user->id)
                ->value('code_hash');

            do {
                $code = str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            } while (is_string($previousCodeHash) && Hash::check($code, $previousCodeHash));

            $now = now();
            DB::table('two_factor_login_tokens')->where('user_id', $user->id)->delete();
            DB::table('two_factor_login_tokens')->insert([
                'user_id' => $user->id,
                'token_hash' => $challengeHash,
                'code_hash' => Hash::make($code),
                'attempts' => 0,
                'expires_at' => $now->copy()->addMinutes(5),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            return $code;
        });

        try {
            Mail::to($user->email)->send(new TwoFactorLoginMail($code));
        } catch (Throwable $exception) {
            DB::table('two_factor_login_tokens')
                ->where('token_hash', $challengeHash)
                ->delete();

            Log::error('Unable to send a two-factor login code.', [
                'user_id' => $user->id,
                'exception_type' => get_class($exception),
            ]);

            return self::FAILED;
        }

        return self::SENT;
    }

    public function secondsUntilNextRequest(User $user, Request $request): int
    {
        return RateLimiter::availableIn($this->rateLimitKey($user, $request));
    }

    private function rateLimitKey(User $user, Request $request): string
    {
        return 'two-factor-login:'.$user->id.':'.$request->ip();
    }
}
