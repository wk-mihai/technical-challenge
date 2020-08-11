<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function is_viewing_login_page()
    {
        $this->get(route('login'))
            ->assertSuccessful()
            ->assertViewIs('auth.login');
    }

    /** @test */
    public function is_not_viewing_login_page_after_authentication()
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->get(route('login'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function is_login_with_right_credentials()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => $password,
        ])->assertRedirect(route('home'))
            ->assertSessionHas('message');

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function is_not_login_with_wrong_credentials()
    {
        $user = $this->createUser();

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ])->assertSessionHasErrors(['email']);

        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function is_remembering_me()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'password'),
        ]);

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => $password,
            'remember' => 'on'
        ])->assertRedirect(route('home'))
            ->assertSessionHas('message');

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function is_logout_user()
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->get(route('logout'))
            ->isRedirection();

        $this->assertGuest();

    }
}
