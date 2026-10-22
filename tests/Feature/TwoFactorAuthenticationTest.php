<?php

namespace Tests\Feature;

use App\Mail\TwoFactorLoginMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class TwoFactorAuthenticationTest extends FeatureTestCase
{
    public function test_user_without_two_factor_keeps_the_existing_login_flow(): void
    {
        $user = $this->createUser();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ])->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
        Mail::assertNothingSent();
    }

    public function test_two_factor_login_requires_the_email_code_before_authentication(): void
    {
        Mail::fake();
        $user = $this->createUser(twoFactorEnabled: true);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ])->assertRedirect(route('two-factor.challenge'));

        $this->assertGuest();
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('two-factor.challenge'))
            ->assertOk()
            ->assertSee('Verificación en dos pasos')
            ->assertSee('Hemos enviado un código de verificación a tu correo electrónico');

        $code = null;
        Mail::assertSent(TwoFactorLoginMail::class, function (TwoFactorLoginMail $mail) use (&$code) {
            $code = $mail->code;

            return preg_match('/^\d{5}$/', $mail->code) === 1;
        });

        $challenge = DB::table('two_factor_login_tokens')->first();
        $this->assertTrue(Hash::check($code, $challenge->code_hash));
        $this->assertNotSame($code, $challenge->code_hash);
        $this->assertSame(300, strtotime($challenge->expires_at) - strtotime($challenge->created_at));

        $this->post(route('two-factor.verify'), ['code' => $code])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->assertNotNull(DB::table('two_factor_login_tokens')->value('used_at'));
    }

    public function test_two_factor_challenge_cannot_be_opened_without_a_pending_login(): void
    {
        $this->get(route('two-factor.challenge'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
    }

    public function test_code_must_contain_exactly_five_numeric_digits(): void
    {
        Mail::fake();
        $user = $this->createUser(twoFactorEnabled: true);
        $this->startTwoFactorLogin($user);

        $this->post(route('two-factor.verify'), ['code' => '12a'])
            ->assertSessionHasErrors('code');

        $this->assertGuest();
        $this->assertDatabaseHas('two_factor_login_tokens', ['attempts' => 0]);
    }

    public function test_wrong_codes_are_limited_and_never_authenticate_the_user(): void
    {
        Mail::fake();
        config()->set('two_factor_login.max_attempts', 2);
        $user = $this->createUser(twoFactorEnabled: true);
        $this->startTwoFactorLogin($user);

        $correctCode = Mail::sent(TwoFactorLoginMail::class)->first()->code;
        $wrongCode = $correctCode === '00000' ? '00001' : '00000';
        $this->post(route('two-factor.verify'), ['code' => $wrongCode])
            ->assertSessionHasErrors('code');
        $this->assertGuest();

        $this->post(route('two-factor.verify'), ['code' => $wrongCode])
            ->assertSessionHasErrors('code');
        $this->assertGuest();
        $this->assertDatabaseHas('two_factor_login_tokens', ['attempts' => 2]);

        $this->post(route('two-factor.verify'), ['code' => $correctCode])
            ->assertSessionHasErrors('code');
        $this->assertGuest();
    }

    public function test_expired_code_cannot_be_used(): void
    {
        Mail::fake();
        $user = $this->createUser(twoFactorEnabled: true);
        $this->startTwoFactorLogin($user);

        DB::table('two_factor_login_tokens')->update(['expires_at' => now()->subMinute()]);

        $this->post(route('two-factor.verify'), ['code' => Mail::sent(TwoFactorLoginMail::class)->first()->code])
            ->assertSessionHasErrors('code');

        $this->assertGuest();
    }

    public function test_a_verified_code_cannot_be_reused(): void
    {
        Mail::fake();
        $user = $this->createUser(twoFactorEnabled: true);
        $this->startTwoFactorLogin($user);
        $code = Mail::sent(TwoFactorLoginMail::class)->first()->code;

        $this->post(route('two-factor.verify'), ['code' => $code])
            ->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'));
        $this->post(route('two-factor.verify'), ['code' => $code])
            ->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_resending_a_code_invalidates_the_previous_code(): void
    {
        Mail::fake();
        $user = $this->createUser(twoFactorEnabled: true);
        $this->startTwoFactorLogin($user);

        $oldCode = Mail::sent(TwoFactorLoginMail::class)->first()->code;
        $this->post(route('two-factor.resend'))->assertRedirect(route('two-factor.challenge'));

        $this->assertCount(2, Mail::sent(TwoFactorLoginMail::class));
        $newCode = Mail::sent(TwoFactorLoginMail::class)->last()->code;
        $this->assertNotSame($oldCode, $newCode);

        $this->post(route('two-factor.verify'), ['code' => $oldCode])
            ->assertSessionHasErrors('code');
        $this->assertGuest();

        $this->post(route('two-factor.verify'), ['code' => $newCode])
            ->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_enabling_two_factor_requires_current_password_and_valid_email(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->patch(route('account.security.two-factor.update'), ['enabled' => 1, 'password' => 'wrong-password'])
            ->assertSessionHasErrors('password');
        $this->assertFalse($user->fresh()->two_factor_enabled);

        $user->email = 'invalid-email';
        $user->save();

        $this->actingAs($user)
            ->patch(route('account.security.two-factor.update'), ['enabled' => 1, 'password' => 'correct-password'])
            ->assertSessionHasErrors('email');
        $this->assertFalse($user->fresh()->two_factor_enabled);
    }

    public function test_user_can_enable_and_disable_two_factor_with_current_password(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->patch(route('account.security.two-factor.update'), ['enabled' => 1, 'password' => 'correct-password'])
            ->assertRedirect(route('account.security'));
        $this->assertTrue($user->fresh()->two_factor_enabled);

        DB::table('two_factor_login_tokens')->insert([
            'user_id' => $user->id,
            'token_hash' => hash('sha256', 'old-token'),
            'code_hash' => Hash::make('11111'),
            'attempts' => 0,
            'expires_at' => now()->addMinutes(5),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($user->fresh())
            ->patch(route('account.security.two-factor.update'), ['enabled' => 0, 'password' => 'correct-password'])
            ->assertRedirect(route('account.security'));

        $this->assertFalse($user->fresh()->two_factor_enabled);
        $this->assertDatabaseMissing('two_factor_login_tokens', ['user_id' => $user->id]);
    }

    public function test_two_factor_code_requests_are_rate_limited(): void
    {
        Mail::fake();
        $user = $this->createUser(twoFactorEnabled: true);
        $key = 'two-factor-login:'.$user->id.':127.0.0.1';
        RateLimiter::clear($key);

        foreach (range(1, 4) as $attempt) {
            $this->post(route('login'), ['email' => $user->email, 'password' => 'correct-password']);
        }

        Mail::assertSent(TwoFactorLoginMail::class, 3);
        $this->assertGuest();
        $this->assertSessionHas('two_factor_challenge');
        $this->get(route('two-factor.challenge'))->assertOk();
    }

    private function startTwoFactorLogin(User $user): void
    {
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ])->assertRedirect(route('two-factor.challenge'));

        $this->assertGuest();
    }

    private function createUser(bool $twoFactorEnabled = false): User
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.test',
            'password' => 'correct-password',
            'role' => 'user',
        ]);

        $user->two_factor_enabled = $twoFactorEnabled;
        $user->save();

        return $user;
    }
}
