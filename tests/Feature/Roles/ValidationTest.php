<?php

namespace Tests\Feature\Roles;

use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    use DatabaseTransactions;

    /** @var string */
    protected string $roleName = 'Role name';

    /**
     * @return Role
     */
    protected function getAdminRole(): Role
    {
        static $role;

        if ($role === null) {
            $role = Role::firstWhere('slug', 'admin');
        }

        return $role;
    }

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
            ->post(route('admin.roles.store'), $data);
    }

    /**
     * @param Role $record
     * @param array $data
     * @return TestResponse
     */
    protected function update(Role $record, array $data = []): TestResponse
    {
        static $admin;

        if ($admin === null) {
            $admin = $this->createAdministrator();
        }

        return $this
            ->actingAs($admin)
            ->patch(route('admin.roles.update', $record), $data);
    }

    /** @test */
    public function is_requires_all_fields_on_store()
    {
        $this->store()->assertSessionHasErrors(['name', 'slug', 'types']);
    }

    /** @test */
    public function is_requires_all_fields_on_update()
    {
        $role = factory(Role::class)->create();

        $this->update($role)->assertSessionHasErrors(['name', 'slug', 'types']);
    }

    /** @test */
    public function is_requires_unique_slug_on_store()
    {
        $slug = 'test-role';

        factory(Role::class)->create([
            'slug' => $slug
        ]);

        $this->store(['slug' => $slug, 'name' => $this->roleName])
            ->assertSessionDoesntHaveErrors(['name'])
            ->assertSessionHasErrors(['slug']);
    }

    /** @test */
    public function is_requires_unique_slug_on_update()
    {
        $slug = 'test-role';

        factory(Role::class)->create([
            'slug' => $slug
        ]);

        $role = factory(Role::class)->create();

        $this->update($role, ['slug' => $slug, 'name' => $this->roleName])
            ->assertSessionDoesntHaveErrors(['name'])
            ->assertSessionHasErrors(['slug']);
    }

    /** @test */
    public function is_requires_exits_types()
    {
        $this->store(['types' => [-1]])
            ->assertSessionHasErrors(['types']);

        $role = factory(Role::class)->create();

        $this->update($role, ['types' => [-1]])
            ->assertSessionHasErrors(['types']);
    }

    /** @test */
    public function is_not_requires_types_for_admin_role_on_update()
    {
        $role = $this->getAdminRole();

        $this->update($role)
            ->assertSessionDoesntHaveErrors(['types']);
    }
}
