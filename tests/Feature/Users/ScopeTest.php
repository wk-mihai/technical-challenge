<?php

namespace Tests\Feature\Users;

use App\Models\Role;
use App\Models\User;
use App\Repositories\UsersRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ScopeTest extends TestCase
{
    use DatabaseTransactions;

    /** @var User */
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = $this->createAdministrator();

        $this->actingAs($this->adminUser);
    }

    /** @test */
    public function is_showing_users_in_right_order()
    {
        [$firstUser, $secondUser] = factory(User::class, 2)->create();

        $this->get(route('admin.users.index'))
            ->assertSuccessful()
            ->assertSeeInOrder([$secondUser->id, $firstUser->id])
            ->assertSeeInOrder([$secondUser->name, $firstUser->name])
            ->assertSee($firstUser->id)
            ->assertSee($secondUser->id);
    }

    /** @test */
    public function is_showing_users_paginated()
    {
        factory(User::class, 101)->create();

        $this->get(route('admin.users.index'))
            ->assertSuccessful()
            ->assertSee('pagination');
    }

    /** @test */
    public function is_showing_user_details_on_view_page()
    {
        $user = factory(User::class)->create();

        $this->get(route('admin.users.show', $user->id))
            ->assertSuccessful()
            ->assertSee($user->name)
            ->assertSee($user->role->name)
            ->assertSee($user->email);
    }

    /** @test */
    public function is_showing_create_page()
    {
        $this->get(route('admin.users.create'))
            ->assertSuccessful();
    }

    /** @test */
    public function is_showing_user_details_on_edit_page()
    {
        $user = factory(User::class)->create();

        $this->get(route('admin.users.edit', $user))
            ->assertSuccessful()
            ->assertSee($user->name)
            ->assertSee($user->role->name)
            ->assertSee($user->email);
    }

    /** @test */
    public function is_deleting_user()
    {
        $user = factory(User::class)->create();

        $this->delete(route('admin.users.destroy', $user))
            ->isRedirection();

        $this->expectException(ModelNotFoundException::class);

        resolve(UsersRepository::class)->findOrFail($user->id);
    }

    /** @test */
    public function is_not_showing_user_details_on_view_page_for_invalid_user()
    {
        $this->get(route('admin.users.show', -1))
            ->assertNotFound();
    }

    /** @test */
    public function is_not_showing_user_details_on_edit_page_for_invalid_user()
    {
        $this->get(route('admin.users.edit', -1))
            ->assertNotFound();
    }

    /** @test */
    public function is_not_deleting_invalid_user()
    {
        $this->get(route('admin.users.destroy', -1))
            ->assertNotFound();
    }

    /** @test */
    public function is_not_deleting_own_user()
    {
        $this->delete(route('admin.users.destroy', $this->adminUser))
            ->assertSessionHas('warning', __('app.cant_delete_own_user'))
            ->isRedirection();
    }

    /** @test */
    public function is_storing_user_with_validated_details()
    {
        $expectedName = 'Test user name';
        $expectedEmail = 'test-user-email@test.local';
        $password = 'test-password';

        $role = factory(Role::class)->create();

        $this->post(
            route('admin.users.store'),
            [
                'role_id'         => $role->id,
                'name'            => $expectedName,
                'email'           => $expectedEmail,
                'password'        => $password,
                'repeat_password' => $password
            ]
        )->assertSessionHas('success', __('app.the_record_has_been_created'))
            ->isRedirect(route('admin.users.index'));

        $user = resolve(UsersRepository::class)
            ->getModel()
            ->where('email', $expectedEmail)
            ->firstOrFail();

        $this->assertEquals($expectedName, $user->name);
        $this->assertEquals($expectedEmail, $user->email);
        $this->assertEquals($role->id, $user->role_id);
    }

    /** @test */
    public function is_updating_user_with_validated_details()
    {
        $user = factory(User::class)->create();

        $expectedName = 'Test user name';
        $expectedEmail = 'test-user-email@test.local';
        $password = 'test-password';

        $role = factory(Role::class)->create();

        $this->patch(
            route('admin.users.update', $user),
            [
                'role_id'         => $role->id,
                'name'            => $expectedName,
                'email'           => $expectedEmail,
                'password'        => $password,
                'repeat_password' => $password
            ]
        )->assertSessionHas('success', __('app.the_record_has_been_updated'))
            ->isRedirect(route('admin.users.index'));

        $updatedUser = resolve(UsersRepository::class)->findOrFail($user->id);

        $this->assertEquals($expectedName, $updatedUser->name);
        $this->assertEquals($expectedEmail, $updatedUser->email);
        $this->assertEquals($role->id, $updatedUser->role_id);
    }


    /** @test */
    public function is_updating_user_details_without_password()
    {
        $user = factory(User::class)->create();

        $expectedName = 'Test user name';
        $expectedEmail = 'test-user-email@test.local';

        $role = factory(Role::class)->create();

        $this->patch(
            route('admin.users.update', $user),
            [
                'role_id' => $role->id,
                'name'    => $expectedName,
                'email'   => $expectedEmail
            ]
        )->assertSessionHas('success', __('app.the_record_has_been_updated'))
            ->isRedirect(route('admin.users.index'));

        $updatedUser = resolve(UsersRepository::class)->findOrFail($user->id);

        $this->assertEquals($expectedName, $updatedUser->name);
        $this->assertEquals($expectedEmail, $updatedUser->email);
        $this->assertEquals($role->id, $updatedUser->role_id);
    }
}
