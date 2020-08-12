<?php

namespace Tests\Feature\Types;

use App\Models\Type;
use Faker\Generator as Faker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    use DatabaseTransactions;

    /** @var string */
    protected string $typeName = 'Type name';

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
            ->post(route('admin.types.store'), $data);
    }

    /**
     * @param Type $record
     * @param array $data
     * @return TestResponse
     */
    protected function update(Type $record, array $data = []): TestResponse
    {
        static $admin;

        if ($admin === null) {
            $admin = $this->createAdministrator();
        }

        return $this
            ->actingAs($admin)
            ->patch(route('admin.types.update', $record), $data);
    }

    /** @test */
    public function is_requires_name_and_slug_on_store()
    {
        $this->store()->assertSessionHasErrors(['name', 'slug']);
    }

    /** @test */
    public function is_requires_name_and_slug_on_update()
    {
        $type = factory(Type::class)->create();

        $this->update($type)->assertSessionHasErrors(['name', 'slug']);
    }

    /** @test */
    public function is_requires_unique_slug_on_store()
    {
        $slug = 'test-type';

        factory(Type::class)->create([
            'slug' => $slug
        ]);

        $this->store(['slug' => $slug, 'name' => $this->typeName])
            ->assertSessionDoesntHaveErrors(['name'])
            ->assertSessionHasErrors(['slug']);
    }

    /** @test */
    public function is_requires_unique_slug_on_update()
    {
        $slug = 'test-type';

        factory(Type::class)->create([
            'slug' => $slug
        ]);

        $type = factory(Type::class)->create();

        $this->update($type, ['slug' => $slug, 'name' => $this->typeName])
            ->assertSessionDoesntHaveErrors(['name'])
            ->assertSessionHasErrors(['slug']);
    }

    /** @test */
    public function is_requires_name_and_slug_to_be_string()
    {
        $this->store([
            'name' => [],
            'slug' => []
        ])->assertSessionHasErrors(['name', 'slug']);

        $this->update(
            factory(Type::class)->create(),
            [
                'name' => [],
                'slug' => []
            ]
        )->assertSessionHasErrors(['name', 'slug']);
    }

    /** @test */
    public function is_requires_name_and_slug_to_have_admissible_length()
    {
        $name = resolve(Faker::class)->words(100, true);
        $slug = Str::slug($name);

        $this->store([
            'name' => $name,
            'slug' => $slug
        ])->assertSessionHasErrors(['name', 'slug']);

        $this->update(
            factory(Type::class)->create(),
            [
                'name' => $name,
                'slug' => $slug
            ]
        )->assertSessionHasErrors(['name', 'slug']);
    }
}
