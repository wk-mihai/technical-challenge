<?php

namespace Tests\Feature;

use App\Models\Training;
use App\Models\Type;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RolesTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var Router $router */
        $router = $this->app->get('router');

        // Register a fake web route
        $router->middleware('admin')->attribute('as', 'admin.test-route')->get('admin/_', function () {
            return response()->noContent();
        });
    }

    /** @test */
    public function is_viewing_admin_user_the_trainings_on_trainings_lists_page()
    {
        $type = factory(Type::class)->create();

        $training = factory(Training::class)->create([
            'type_id' => $type->id
        ]);

        $this->actingAs($this->createAdministrator())
            ->get(route('trainings.index'))
            ->assertSuccessful()
            ->assertViewIs('pages.trainings.index')
            ->assertSee($type->name)
            ->assertSee($training->name);
    }

    /** @test */
    public function is_having_admin_user_access_to_the_training_page()
    {
        $training = factory(Training::class)->create();

        $this->actingAs($this->createAdministrator())
            ->get(route('trainings.show', $training->id))
            ->assertSuccessful()
            ->assertViewIs('pages.trainings.show')
            ->assertSee($training->name);
    }

    /** @test */
    public function is_viewing_results_on_trainings_lists_page()
    {
        [$type, $user] = $this->createTypeAttachedToRole();

        $training = factory(Training::class)->create([
            'type_id' => $type->id
        ]);

        $this->actingAs($user)
            ->get(route('trainings.index'))
            ->assertSuccessful()
            ->assertViewIs('pages.trainings.index')
            ->assertSee($type->name)
            ->assertSee($training->name);
    }

    /** @test */
    public function is_having_access_to_the_training_page()
    {
        [$type, $user] = $this->createTypeAttachedToRole();

        $training = factory(Training::class)->create([
            'type_id' => $type->id
        ]);

        $this->actingAs($user)
            ->get(route('trainings.show', $training->id))
            ->assertSuccessful()
            ->assertViewIs('pages.trainings.show')
            ->assertSee($training->name);
    }

    /** @test */
    public function is_not_viewing_any_results_on_trainings_lists_page()
    {
        $userWithoutAccess = $this->createUser();

        $type = $this->createTypeAttachedToRole(false);

        $training = factory(Training::class)->create([
            'type_id' => $type->id
        ]);

        $this->actingAs($userWithoutAccess)
            ->get(route('trainings.index'))
            ->assertSuccessful()
            ->assertViewIs('pages.trainings.index')
            ->assertDontSee($type->name)
            ->assertDontSee($training->name)
            ->assertSee(__('app.there_are_currently_no_records'));
    }

    /** @test */
    public function is_not_having_access_to_the_training_page()
    {
        $userWithoutAccess = $this->createUser();

        [$type] = $this->createTypeAttachedToRole();

        $training = factory(Training::class)->create([
            'type_id' => $type->id
        ]);

        $this->actingAs($userWithoutAccess)
            ->get(route('trainings.show', $training->id))
            ->assertNotFound();
    }

    /** @test */
    public function is_not_viewing_specific_type_on_trainings_lists_page()
    {
        [$type, $user] = $this->createTypeAttachedToRole();

        $healthType = $this->getDefaultTypes('health');

        $this->actingAs($user)
            ->get(route('trainings.index'))
            ->assertSuccessful()
            ->assertViewIs('pages.trainings.index')
            ->assertDontSee($healthType->name);
    }

    /** @test */
    public function is_not_having_access_to_specific_type_page()
    {
        [$type, $user] = $this->createTypeAttachedToRole();

        $healthType = $this->getDefaultTypes('health');

        $this->actingAs($user)
            ->get(route('trainings.index', $healthType->slug))
            ->assertNotFound();
    }

    /** @test */
    public function is_having_access_to_training_files()
    {
        [$type, $user] = $this->createTypeAttachedToRole();

        $training = factory(Training::class)->create([
            'type_id' => $type->id
        ]);

        [$image, $video] = $this->attachTrainingFiles($training);

        $this->assertTrue(Storage::exists($image->url));
        $this->assertTrue(Storage::exists($video->url));

        $this->actingAs($user)
            ->get(route('training.files', [
                'id'     => $training->id,
                'fileId' => $image->id
            ]))
            ->assertSuccessful();

        $this->actingAs($user)
            ->get(route('training.files', [
                'id'     => $training->id,
                'fileId' => $video->id
            ]))
            ->assertSuccessful();
    }

    /** @test */
    public function is_not_having_access_to_training_files()
    {
        $userWithoutAccess = $this->createUser();

        [$type] = $this->createTypeAttachedToRole();

        $training = factory(Training::class)->create([
            'type_id' => $type->id
        ]);

        [$image, $video] = $this->attachTrainingFiles($training);

        $this->assertTrue(Storage::exists($image->url));
        $this->assertTrue(Storage::exists($video->url));

        $this->actingAs($userWithoutAccess)
            ->get(route('training.files', [
                'id'     => $training->id,
                'fileId' => $image->id
            ]))
            ->assertNotFound();

        $this->actingAs($userWithoutAccess)
            ->get(route('training.files', [
                'id'     => $training->id,
                'fileId' => $video->id
            ]))
            ->assertNotFound();
    }

    /** @test */
    public function is_having_access_to_admin_routes()
    {
        $this->actingAs($this->createAdministrator())
            ->get(route('admin.test-route'))
            ->assertSuccessful();
    }

    /** @test */
    public function is_not_having_access_to_admin_routes()
    {
        $this->actingAs($this->createUser())
            ->get(route('admin.test-route'))
            ->assertForbidden();
    }

    /** @test */
    public function is_throwing_exception_non_existing_file()
    {
        $training = factory(Training::class)->create();

        $image = $training->files()->create([
            'name' => 'test',
            'url' => 'wrong-url'
        ]);

        $this->actingAs($this->createAdministrator())
            ->get(route('training.files', [
                'id'     => $training->id,
                'fileId' => $image->id
            ]))
            ->assertNotFound();
    }
}
