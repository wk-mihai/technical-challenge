<?php

namespace Auth;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function is_viewing_forgot_password_page()
    {
        $this->get(route('password.request'))
            ->assertSuccessful()
            ->assertViewIs('auth.passwords.email');
    }

    /** @test */
    public function is_not_viewing_forgot_password_page_after_authentication()
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->get(route('password.request'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function is_sending_reset_link_email_with_not_found_email()
    {
        $this->post(route('password.email'), [
            'email' => 'invalid-email@test.local'
        ])->assertSessionHasErrors(['email']);

        $this->assertTrue(session()->hasOldInput('email'));
    }

    /** @test */
    public function is_sending_reset_link_email_with_found_email()
    {
        Notification::fake();

        $user = $this->createUser();

        $this->post(route('password.email'), [
            'email' => $user->email
        ])->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }
}
