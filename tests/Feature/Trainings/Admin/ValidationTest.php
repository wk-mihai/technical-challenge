<?php

namespace Tests\Feature\Trainings\Admin;

use App\Models\Training;
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

        $this->store(['images' => [UploadedFile::fake()->create('image.svg')]])
            ->assertSessionHasErrors(['images.0']);

        $training = factory(Training::class)->create();

        $this->update($training, ['images' => [UploadedFile::fake()->create('video.mp4')]])
            ->assertSessionHasErrors(['images.0']);

        $this->update($training, ['images' => [UploadedFile::fake()->create('image.svg')]])
            ->assertSessionHasErrors(['images.0']);
    }


    /** @test */
    public function is_requires_a_valid_video()
    {
        $this->store(['videos' => [UploadedFile::fake()->create('image.jpeg')]])
            ->assertSessionHasErrors(['videos.0']);

        $this->store(['videos' => [UploadedFile::fake()->create('video.avi')]])
            ->assertSessionHasErrors(['videos.0']);

        $training = factory(Training::class)->create();

        $this->update($training, ['videos' => [UploadedFile::fake()->create('image.jpeg')]])
            ->assertSessionHasErrors(['videos.0']);

        $this->update($training, ['videos' => [UploadedFile::fake()->create('video.avi')]])
            ->assertSessionHasErrors(['videos.0']);
    }
}
