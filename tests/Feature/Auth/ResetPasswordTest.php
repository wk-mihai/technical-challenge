<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function is_viewing_password_reset_page()
    {
        $user = $this->createUser();

        $this->get(route('password.reset', [
            'token' => Password::broker()->createToken($user),
        ]))
            ->assertSuccessful()
            ->assertViewIs('auth.passwords.reset');
    }

    /** @test */
    public function is_submitting_password_reset_form_with_not_found_email()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($originalPassword = 'password'),
        ]);

        $password = 'test-password';

        $this->post(route('password.update'), [
            'token'                 => Password::broker()->createToken($user),
            'email'                 => 'invalid-email@test.local',
            'password'              => $password,
            'password_confirmation' => $password,
        ])->assertSessionHasErrors(['email']);

        $user->refresh();

        $this->assertFalse(Hash::check($password, $user->password));
        $this->assertTrue(Hash::check($originalPassword, $user->password));
    }

    /** @test */
    public function is_submitting_password_reset_form_with_password_mismatch()
    {
        $user = $this->createUser();

        $this->post(route('password.update'), [
            'token'                 => Password::broker()->createToken($user),
            'email'                 => $user->email,
            'password'              => 'test-password',
            'password_confirmation' => 'test-password-1',
        ])->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function is_submitting_password_reset_form_with_found_email()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($originalPassword = 'password'),
        ]);

        $password = 'test-password';

        $this->post(route('password.update'), [
            'token'                 => Password::broker()->createToken($user),
            'email'                 => $user->email,
            'password'              => $password,
            'password_confirmation' => $password,
        ])->assertSessionHas('status')
            ->assertRedirect(route('home'));

        $user->refresh();

        $this->assertTrue(Hash::check($password, $user->password));
        $this->assertFalse(Hash::check($originalPassword, $user->password));
    }
}
