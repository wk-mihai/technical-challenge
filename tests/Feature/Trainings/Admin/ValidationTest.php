<?php

namespace Tests\Feature\Trainings\Admin;

use App\Models\Training;
use Faker\Generator as Faker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    use DatabaseTransactions;

    /** @var string */
    protected string $trainingName = 'Training name';

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
            ->post(route('admin.trainings.store'), $data);
    }

    /**
     * @param Training $record
     * @param array $data
     * @return TestResponse
     */
    protected function update(Training $record, array $data = []): TestResponse
    {
        static $admin;

        if ($admin === null) {
            $admin = $this->createAdministrator();
        }

        return $this
            ->actingAs($admin)
            ->patch(route('admin.trainings.update', $record), $data);
    }

    /** @test */
    public function is_requires_name_and_type()
    {
        $this->store()->assertSessionHasErrors(['name', 'type_id']);

        $this->update(factory(Training::class)->create())->assertSessionHasErrors(['name', 'type_id']);
    }

    /** @test */
    public function is_requires_name_and_content_to_be_string()
    {
        $this->store([
            'name'    => [],
            'content' => []
        ])->assertSessionHasErrors(['name', 'content']);

        $this->update(
            factory(Training::class)->create(),
            [
                'name'    => [],
                'content' => []
            ]
        )->assertSessionHasErrors(['name', 'content']);
    }


    /** @test */
    public function is_requires_name_to_have_admissible_length()
    {
        $name = resolve(Faker::class)->words(100, true);

        $this->store([
            'name' => $name
        ])->assertSessionHasErrors(['name']);

        $this->update(
            factory(Training::class)->create(),
            [
                'name' => $name
            ]
        )->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function is_requires_integer_type()
    {
        $this->store(['type_id' => 'string'])->assertSessionHasErrors(['type_id']);

        $this->update(
            factory(Training::class)->create(),
            ['type_id' => 'string']
        )->assertSessionHasErrors(['type_id']);
    }

    /** @test */
    public function is_requires_existent_type()
    {
        $this->store(['type_id' => -1, 'name' => $this->trainingName])
            ->assertSessionDoesntHaveErrors(['name'])
            ->assertSessionHasErrors(['type_id']);

        $this->update(
            factory(Training::class)->create(),
            ['type_id' => -1, 'name' => $this->trainingName]
        )->assertSessionDoesntHaveErrors(['name'])
            ->assertSessionHasErrors(['type_id']);
    }

    /** @test */
    public function is_requires_a_valid_image()
    {
        $this->store(['images' => [UploadedFile::fake()->create('video.mp4')]])
            ->assertSessionHasErrors(['images.0']);

        $this->store(['images' => ['string']])
            ->assertSessionHasErrors(['images.0']);

        $this->store(['images' => [UploadedFile::fake()->create('image.svg')]])
            ->assertSessionHasErrors(['images.0']);

        $training = factory(Training::class)->create();

        $this->update($training, ['images' => [UploadedFile::fake()->create('video.mp4')]])
            ->assertSessionHasErrors(['images.0']);

        $this->update($training, ['images' => ['string']])
            ->assertSessionHasErrors(['images.0']);

        $this->update($training, ['images' => [UploadedFile::fake()->create('image.svg')]])
            ->assertSessionHasErrors(['images.0']);
    }


    /** @test */
    public function is_requires_a_valid_video()
    {
        $this->store(['videos' => [UploadedFile::fake()->create('image.jpeg')]])
            ->assertSessionHasErrors(['videos.0']);

        $this->store(['videos' => ['string']])
            ->assertSessionHasErrors(['videos.0']);

        $this->store(['videos' => [UploadedFile::fake()->create('video.avi')]])
            ->assertSessionHasErrors(['videos.0']);

        $training = factory(Training::class)->create();

        $this->update($training, ['videos' => [UploadedFile::fake()->create('image.jpeg')]])
            ->assertSessionHasErrors(['videos.0']);

        $this->update($training, ['videos' => ['string']])
            ->assertSessionHasErrors(['videos.0']);

        $this->update($training, ['videos' => [UploadedFile::fake()->create('video.avi')]])
            ->assertSessionHasErrors(['videos.0']);
    }
}
