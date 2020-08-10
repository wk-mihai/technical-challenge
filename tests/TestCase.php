<?php

namespace Tests;

use App\Models\Role;
use App\Models\RoleType;
use App\Models\Training;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Storage;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /** @var bool $initialised */
    public static bool $initialised = false;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (!static::$initialised) {
            $this->artisan('migrate', ['--env' => 'testing']);

            $this->seedRequiredData();

            static::$initialised = true;
        }
    }

    /**
     * Creates a new Administrator
     *
     * @return User
     */
    public function createAdministrator(): User
    {
        /** @var User $user */
        $user = factory(User::class)->create([
            'role_id' => Role::firstWhere('slug', 'admin')->id
        ]);

        return $user;
    }

    /**
     * @param int|null $roleId
     * @return User
     */
    public function createUser(int $roleId = null): User
    {
        $attributes = !is_null($roleId) ? ['role_id' => $roleId] : [];

        return factory(User::class)->create($attributes);
    }

    /**
     * @return \Closure[][]
     */
    public function userProvider(): array
    {
        return [
            'admin' => [
                fn() => $this->createAdministrator()
            ],
            'user'  => [
                fn() => $this->createUser()
            ]
        ];
    }

    /**
     * @param bool $withUser
     * @return mixed
     */
    public function createTypeAttachedToRole(bool $withUser = true)
    {
        $type = factory(Type::class)->create();

        if ($withUser) {
            $role = factory(Role::class)->create();

            $user = $this->createUser($role->id);

            RoleType::firstOrCreate([
                'role_id' => $role->id,
                'type_id' => $type->id
            ]);
        }

        return $withUser ? [$type, $user] : $type;
    }

    /**
     * @param Training $training
     * @return Collection
     */
    public function attachTrainingFiles(Training $training): Collection
    {
        Storage::fake();

        $uniqueName = md5(time());

        $files = [
            [
                'name' => 'image',
                'type' => 'image',
                'url'  => UploadedFile::fake()->create('image.jpg')
                    ->storeAs("trainings/images/{$uniqueName}", 'image.jpg')
            ],
            [
                'name' => 'video',
                'type' => 'video',
                'url'  => UploadedFile::fake()->create('video.mp4')
                    ->storeAs("trainings/videos/{$uniqueName}", 'video.mp4')
            ]
        ];

        return $training->files()->createMany($files);
    }

    /**
     * @param string|null $slug
     * @return mixed
     */
    public function getDefaultTypes(string $slug = null)
    {
        if ($slug !== null) {
            return Type::firstWhere('slug', $slug);
        }

        return Type::all();
    }

    /**
     * Run all the factories needed for an asset to be created successfully.
     *
     * @return void
     */
    protected function seedRequiredData(): void
    {
        if (Role::count() === 0) {
            $this->seed(\RolesSeeder::class);
        }

        if (Type::count() === 0) {
            $this->seed(\TypesSeeder::class);
            $this->seed(\RoleTypesSeeder::class);
        }
    }

    /**
     * @return string
     */
    public function getStubDirectory(): string
    {
        return __DIR__ . '/stubs';
    }
}
