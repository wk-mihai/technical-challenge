<?php

namespace Tests\Feature\Roles;

use App\Models\Role;
use App\Models\RoleType;
use App\Models\Type;
use App\Models\User;
use App\Repositories\RolesRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class ScopeTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->createAdministrator());
    }

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

    /** @test */
    public function is_showing_roles_in_right_order()
    {
        [$firstRole, $secondRole] = factory(Role::class, 2)->create();

        $this->get(route('admin.roles.index'))
            ->assertSuccessful()
            ->assertSeeInOrder([$secondRole->id, $firstRole->id])
            ->assertSeeInOrder([$secondRole->name, $firstRole->name])
            ->assertSee($firstRole->id)
            ->assertSee($secondRole->id);
    }

    /** @test */
    public function is_showing_roles_paginated()
    {
        factory(Role::class, 101)->create();

        $this->get(route('admin.roles.index'))
            ->assertSuccessful()
            ->assertSee('pagination');
    }

    /** @test */
    public function is_showing_role_details_on_view_page()
    {
        $role = factory(Role::class)->create();

        $this->get(route('admin.roles.show', $role->id))
            ->assertSuccessful()
            ->assertSee($role->name)
            ->assertSee($role->slug);

        $this->assertEmpty($role->humanizeTypeNames());
    }

    /** @test */
    public function is_showing_role_details_with_types_on_view_page()
    {
        $role = $this->createRoleWithTypes();

        $this->get(route('admin.roles.show', $role->id))
            ->assertSuccessful()
            ->assertSee($role->name)
            ->assertSee($role->slug)
            ->assertSee($role->humanizeTypeNames());
    }

    /** @test */
    public function is_showing_admin_role_details_on_view_page()
    {
        $role = $this->getAdminRole();

        $this->get(route('admin.roles.show', $role->id))
            ->assertSuccessful()
            ->assertSee($role->name)
            ->assertSee($role->slug);

        $this->assertEmpty($role->humanizeTypeNames());
    }

    /** @test */
    public function is_showing_create_page()
    {
        $this->get(route('admin.roles.create'))
            ->assertSuccessful();
    }

    /** @test */
    public function is_showing_role_details_on_edit_page()
    {
        $role = $this->createRoleWithTypes();

        $response = $this->get(route('admin.roles.edit', $role))
            ->assertSuccessful()
            ->assertSee($role->name)
            ->assertSee($role->slug);

        foreach ($role->types as $type) {
            $response->assertSee($type->name);
        }
    }

    /** @test */
    public function is_showing_admin_role_details_on_edit_page()
    {
        $role = $this->getAdminRole();

        $this->get(route('admin.roles.edit', $role))
            ->assertSuccessful()
            ->assertSee($role->name)
            ->assertSee($role->slug);
    }

    /** @test */
    public function is_deleting_role()
    {
        $role = $this->createRoleWithTypes();

        $this->delete(route('admin.roles.destroy', $role))
            ->isRedirection();

        $this->assertEmpty(RoleType::where('role_id', $role->id)->get());

        $this->expectException(ModelNotFoundException::class);

        resolve(RolesRepository::class)->findOrFail($role->id);
    }

    /** @test */
    public function is_not_showing_role_details_on_view_page_for_invalid_role()
    {
        $this->get(route('admin.roles.show', -1))
            ->assertNotFound();
    }

    /** @test */
    public function is_not_showing_role_details_on_edit_page_for_invalid_role()
    {
        $this->get(route('admin.roles.edit', -1))
            ->assertNotFound();
    }

    /** @test */
    public function is_not_deleting_invalid_role()
    {
        $this->get(route('admin.roles.destroy', -1))
            ->assertNotFound();
    }

    /** @test */
    public function is_not_deleting_admin_role()
    {
        $role = $this->getAdminRole();

        $this->delete(route('admin.roles.destroy', $role))
            ->assertSessionHas('warning', __('app.cant_delete_admin_role'))
            ->isRedirection();
    }

    /** @test */
    public function is_not_deleting_if_role_has_users()
    {
        $role = factory(Role::class)->create();

        factory(User::class)->create([
            'role_id' => $role->id
        ]);

        $this->delete(route('admin.roles.destroy', $role))
            ->assertSessionHas('warning', __('app.record_has_relations'))
            ->isRedirection();
    }

    /** @test */
    public function is_storing_role_with_validated_details()
    {
        $expectedName = 'Test role name';
        $expectedSlug = Str::slug($expectedName);

        $typesIds = factory(Type::class, 3)
            ->create()
            ->pluck('id')
            ->toArray();

        $this->post(
            route('admin.roles.store'),
            [
                'name'  => $expectedName,
                'slug'  => $expectedSlug,
                'types' => $typesIds
            ]
        )->assertSessionHas('success', __('app.the_record_has_been_created'))
            ->isRedirect(route('admin.roles.index'));

        $role = resolve(RolesRepository::class)
            ->getModel()
            ->where('slug', $expectedSlug)
            ->firstOrFail();

        $this->assertEquals($expectedName, $role->name);
        $this->assertEquals($expectedSlug, $role->slug);

        $createdTypesIds = $role->roleTypes->pluck('type_id')->toArray();

        sort($createdTypesIds);
        sort($typesIds);

        $this->assertTrue($createdTypesIds === $typesIds);
    }

    /** @test */
    public function is_updating_role_with_validated_details()
    {
        $expectedName = 'Test role name';
        $expectedSlug = Str::slug($expectedName);

        $role = $this->createRoleWithTypes();

        $oldTypesIds = $role->roleTypes->pluck('type_id')->toArray();

        $untouchedTypesIds = array_slice($oldTypesIds, 0, 1);
        $removedTypesIds = array_diff($oldTypesIds, $untouchedTypesIds);

        $typesIds = array_merge(
            factory(Type::class, 3)
                ->create()
                ->pluck('id')
                ->toArray(),
            $untouchedTypesIds
        );

        $this->patch(
            route('admin.roles.update', $role),
            [
                'name'  => $expectedName,
                'slug'  => $expectedSlug,
                'types' => $typesIds
            ]
        )->assertSessionHas('success', __('app.the_record_has_been_updated'))
            ->isRedirect(route('admin.roles.index'));

        $updatedRole = resolve(RolesRepository::class)->findOrFail($role->id);

        $this->assertEquals($expectedName, $updatedRole->name);
        $this->assertEquals($expectedSlug, $updatedRole->slug);

        $updatedTypesIds = $updatedRole->roleTypes->pluck('type_id')->toArray();

        sort($updatedTypesIds);
        sort($typesIds);

        $this->assertTrue($updatedTypesIds === $typesIds);

        $deletedTypesCount = RoleType::whereIn('type_id', $removedTypesIds)->count();

        $this->assertEquals(0, $deletedTypesCount);
    }
}
