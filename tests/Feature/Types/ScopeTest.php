<?php

namespace Tests\Feature\Types;

use App\Models\RoleType;
use App\Models\Training;
use App\Models\Type;
use App\Repositories\TypesRepository;
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

    /** @test */
    public function is_showing_types_in_right_order()
    {
        [$firstType, $secondType] = factory(Type::class, 2)->create();

        $this->get(route('admin.types.index'))
            ->assertSuccessful()
            ->assertSeeInOrder([$secondType->id, $firstType->id])
            ->assertSeeInOrder([$secondType->name, $firstType->name])
            ->assertSee($firstType->id)
            ->assertSee($secondType->id);
    }

    /** @test */
    public function is_showing_types_paginated()
    {
        factory(Type::class, 101)->create();

        $this->get(route('admin.types.index'))
            ->assertSuccessful()
            ->assertSee('pagination');
    }

    /** @test */
    public function is_showing_type_details_on_view_page()
    {
        $type = factory(Type::class)->create();

        $this->get(route('admin.types.show', $type->id))
            ->assertSuccessful()
            ->assertSee($type->name)
            ->assertSee($type->slug);
    }

    /** @test */
    public function is_showing_create_page()
    {
        $this->get(route('admin.types.create'))
            ->assertSuccessful();
    }

    /** @test */
    public function is_showing_type_details_on_edit_page()
    {
        $type = factory(Type::class)->create();

        $this->get(route('admin.types.edit', $type))
            ->assertSuccessful()
            ->assertSee($type->name)
            ->assertSee($type->slug);
    }

    /** @test */
    public function is_deleting_type()
    {
        [$type] = $this->createTypeAttachedToRole();

        $this->delete(route('admin.types.destroy', $type))
            ->isRedirection();

        $this->assertEmpty(RoleType::where('type_id', $type->id)->get());

        $this->expectException(ModelNotFoundException::class);

        resolve(TypesRepository::class)->findOrFail($type->id);
    }

    /** @test */
    public function is_not_showing_type_details_on_view_page_for_invalid_type()
    {
        $this->get(route('admin.types.show', -1))
            ->assertNotFound();
    }

    /** @test */
    public function is_not_showing_type_details_on_edit_page_for_invalid_type()
    {
        $this->get(route('admin.types.edit', -1))
            ->assertNotFound();
    }

    /** @test */
    public function is_not_deleting_invalid_type()
    {
        $this->get(route('admin.types.destroy', -1))
            ->assertNotFound();
    }

    /** @test */
    public function is_not_deleting_if_type_has_trainings()
    {
        $type = factory(Type::class)->create();

        factory(Training::class, 2)->create([
            'type_id' => $type->id
        ]);

        $this->delete(route('admin.types.destroy', $type))
            ->assertSessionHas('warning', __('app.record_has_relations'))
            ->isRedirection();
    }

    /** @test */
    public function is_storing_type_with_validated_details()
    {
        $expectedName = 'Test type name';
        $expectedSlug = Str::slug($expectedName);

        $this->post(
            route('admin.types.store'),
            [
                'name' => $expectedName,
                'slug' => $expectedSlug,
            ]
        )->assertSessionHas('success', __('app.the_record_has_been_created'))
            ->isRedirect(route('admin.types.index'));

        $type = resolve(TypesRepository::class)
            ->getModel()
            ->where('slug', $expectedSlug)
            ->firstOrFail();

        $this->assertEquals($expectedName, $type->name);
        $this->assertEquals($expectedSlug, $type->slug);
    }

    /** @test */
    public function is_updating_type_with_validated_details()
    {
        $type = factory(Type::class)->create();

        $expectedName = 'Test type name';
        $expectedSlug = Str::slug($expectedName);

        $this->patch(
            route('admin.types.update', $type),
            [
                'name' => $expectedName,
                'slug' => $expectedSlug,
            ]
        )->assertSessionHas('success', __('app.the_record_has_been_updated'))
            ->isRedirect(route('admin.types.index'));

        $updatedType = resolve(TypesRepository::class)->findOrFail($type->id);

        $this->assertEquals($expectedName, $updatedType->name);
        $this->assertEquals($expectedSlug, $updatedType->slug);
    }
}
