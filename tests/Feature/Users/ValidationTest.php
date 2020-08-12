<?php

namespace Tests\Feature\Users;

use App\Models\Role;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    use DatabaseTransactions;

    /** @var string */
    protected string $userName = 'User name';

    /** @var string */
    protected string $userEmail = 'test-user-email@test.local';

    /** @var string */
    protected string $userPassword = 'test-password';

    /**
     * @param array $data
     * @return TestResponse
     */
    protected function store(array $data = []): TestResponse
    {
        static $admin;

        if ($admin === null) {
            $admin = $this->createAdministrator();
        }

        return $this
            ->actingAs($admin)
            ->post(route('admin.users.store'), $data);
    }

    /**
     * @param User $record
     * @param array $data
     * @return TestResponse
     */
    protected function update(User $record, array $data = []): TestResponse
    {
        static $admin;

        if ($admin === null) {
            $admin = $this->createAdministrator();
        }

        return $this
            ->actingAs($admin)
            ->patch(route('admin.users.update', $record), $data);
    }

    /** @test */
    public function is_requires_all_fields_on_store()
    {
        $this->store()->assertSessionHasErrors(['name', 'role_id', 'email', 'password', 'repeat_password']);
    }

    /** @test */
    public function is_requires_all_fields_on_update()
    {
        $user = factory(User::class)->create();

        $this->update($user)->assertSessionHasErrors(['name', 'role_id', 'email']);
    }

    /** @test */
    public function is_requires_repeat_password_on_update()
    {
        $user = factory(User::class)->create();

        $this->update($user, [
            'password' => $this->userPassword,
        ])->assertSessionHasErrors(['name', 'role_id', 'email', 'repeat_password']);
    }

    /** @test */
    public function is_requires_unique_email_on_store()
    {
        factory(User::class)->create([
            'email' => $this->userEmail
        ]);

        $role = factory(Role::class)->create();

        $this->store([
            'name'            => $this->userName,
            'role_id'         => $role->id,
            'email'           => $this->userEmail,
            'password'        => $this->userPassword,
            'repeat_password' => $this->userPassword
        ])
            ->assertSessionDoesntHaveErrors(['name', 'role_id', 'password', 'repeat_password'])
            ->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function is_requires_unique_email_on_update()
    {
        factory(User::class)->create([
            'email' => $this->userEmail
        ]);

        $user = factory(User::class)->create();

        $role = factory(Role::class)->create();

        $this->update(
            $user,
            [
                'name'    => $this->userName,
                'role_id' => $role->id,
                'email'   => $this->userEmail
            ]
        )
            ->assertSessionDoesntHaveErrors(['name', 'role_id', 'password'])
            ->assertSessionHasErrors(['email']);
    }


    /** @test */
    public function is_requires_password_and_repeat_password_matches()
    {
        $this->store([
            'password'        => $this->userPassword,
            'repeat_password' => 'wrong-password'
        ])->assertSessionDoesntHaveErrors(['password'])
            ->assertSessionHasErrors(['repeat_password']);

        $user = factory(User::class)->create();

        $this->update($user, [
            'password'        => $this->userPassword,
            'repeat_password' => 'wrong-password'
        ])->assertSessionDoesntHaveErrors(['password'])
            ->assertSessionHasErrors(['repeat_password']);

        $this->update($user, [
            'password' => $this->userPassword,
        ])->assertSessionDoesntHaveErrors(['password'])
            ->assertSessionHasErrors(['repeat_password']);

        $this->update($user, [
            'repeat_password' => 'wrong-password'
        ])->assertSessionDoesntHaveErrors(['password'])
            ->assertSessionHasErrors(['repeat_password']);
    }


    /** @test */
    public function is_requires_name_and_password_to_be_string()
    {
        $this->store([
            'name'     => [],
            'password' => []
        ])->assertSessionHasErrors(['name', 'password']);

        $this->update(
            factory(User::class)->create(),
            [
                'name'     => [],
                'password' => []
            ]
        )->assertSessionHasErrors(['name', 'password']);
    }

    /** @test */
    public function is_requires_name_and_password_to_have_admissible_length()
    {
        $name = $password = resolve(Faker::class)->words(100, true);

        $this->store([
            'name'     => $name,
            'password' => $password
        ])->assertSessionHasErrors(['name', 'password']);

        $this->update(
            factory(User::class)->create(),
            [
                'name'     => $name,
                'password' => $password
            ]
        )->assertSessionHasErrors(['name', 'password']);
    }
}
