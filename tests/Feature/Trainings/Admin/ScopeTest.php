<?php

namespace Tests\Feature\Trainings\Admin;

use App\Models\Training;
use App\Repositories\TrainingsRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
    public function is_showing_trainings_in_right_order()
    {
        [$firstTraining, $secondTraining] = factory(Training::class, 2)->create();

        $this->get(route('admin.trainings.index'))
            ->assertSuccessful()
            ->assertSeeInOrder([$secondTraining->id, $firstTraining->id])
            ->assertSeeInOrder([$secondTraining->name, $firstTraining->name])
            ->assertSee($firstTraining->id)
            ->assertSee($secondTraining->id);
    }

    /** @test */
    public function is_showing_trainings_paginated()
    {
        factory(Training::class, 101)->create();

        $this->get(route('admin.trainings.index'))
            ->assertSuccessful()
            ->assertSee('pagination');
    }

    /** @test */
    public function is_showing_training_details_on_view_page()
    {
        $training = factory(Training::class)->create();

        [$image, $video] = $this->attachTrainingFiles($training);

        $this->assertTrue(Storage::exists($image->url));
        $this->assertTrue(Storage::exists($video->url));

        $this->get(route('admin.trainings.show', $training->id))
            ->assertSuccessful()
            ->assertSee($training->name)
            ->assertSee($training->type->name)
            ->assertSeeText($training->content, false)
            ->assertSee(__('app.view_all_images', ['count' => 1]))
            ->assertSee(__('app.view_all_videos', ['count' => 1]));
    }

    /** @test */
    public function is_showing_create_page()
    {
        $this->get(route('admin.trainings.create'))
            ->assertSuccessful();
    }

    /** @test */
    public function is_showing_training_details_on_edit_page()
    {
        $training = factory(Training::class)->create();

        [$image, $video] = $this->attachTrainingFiles($training);

        $this->assertTrue(Storage::exists($image->url));
        $this->assertTrue(Storage::exists($video->url));

        $this->get(route('admin.trainings.edit', $training))
            ->assertSuccessful()
            ->assertSee($training->name)
            ->assertSee($training->type->name)
            ->assertSeeText($training->content)
            ->assertSee(__('app.view_all_images', ['count' => 1]))
            ->assertSee(__('app.view_all_videos', ['count' => 1]));
    }

    /** @test */
    public function is_deleting_training_with_files()
    {
        $training = factory(Training::class)->create();

        [$image, $video] = $this->attachTrainingFiles($training);

        $this->assertTrue(Storage::exists($image->url));
        $this->assertTrue(Storage::exists($video->url));

        $this->delete(route('admin.trainings.destroy', $training))
            ->isRedirection();

        $this->assertFalse(Storage::exists($image->url));
        $this->assertFalse(Storage::exists($video->url));

        $this->expectException(ModelNotFoundException::class);

        resolve(TrainingsRepository::class)->findOrFail($training->id);
    }

    /** @test */
    public function is_not_showing_training_details_on_view_page_for_invalid_training()
    {
        $this->get(route('admin.trainings.show', -1))
            ->assertNotFound();
    }

    /** @test */
    public function is_not_showing_training_details_on_edit_page_for_invalid_training()
    {
        $this->get(route('admin.trainings.edit', -1))
            ->assertNotFound();
    }

    /** @test */
    public function is_not_deleting_invalid_training()
    {
        $this->get(route('admin.trainings.destroy', -1))
            ->assertNotFound();
    }

    /** @test */
    public function is_storing_training_with_validated_details()
    {
        Storage::fake();

        $expectedName = 'Test training name';
        $expectedContent = 'Test training content';
        $expectedType = $this->getDefaultTypes('health');

        $this->post(route('admin.trainings.store'),
            [
                'name'    => $expectedName,
                'type_id' => $expectedType->id,
                'content' => $expectedContent,
                'images'  => [UploadedFile::fake()->image('image.jpeg')],
                'videos'  => [UploadedFile::fake()->create('video.mp4')],
            ])->assertSessionHas('success', __('app.the_record_has_been_created'))
            ->isRedirect(route('admin.trainings.index'));

        $training = resolve(TrainingsRepository::class)
            ->getModel()
            ->where('name', $expectedName)
            ->where('type_id', $expectedType->id)
            ->where('content', $expectedContent)
            ->firstOrFail();

        $this->assertEquals($expectedName, $training->name);
        $this->assertEquals($expectedContent, $training->content);
        $this->assertEquals($training->type->id, $training->type->id);
        $this->assertEquals(2, $training->files()->count());
    }

    /** @test */
    public function is_updating_training_with_validated_details()
    {
        Storage::fake();

        $training = factory(Training::class)->create();

        $expectedName = 'Test training name';
        $expectedContent = 'Test training content';

        $this->patch(
            route('admin.trainings.update', $training),
            [
                'name'    => $expectedName,
                'type_id' => $training->type_id,
                'content' => $expectedContent,
                'images'  => [UploadedFile::fake()->image('image.jpeg')],
                'videos'  => [UploadedFile::fake()->create('video.mp4')],
            ]
        )->assertSessionHas('success', __('app.the_record_has_been_updated'))
            ->isRedirect(route('admin.trainings.index'));

        $updatedTraining = resolve(TrainingsRepository::class)->findOrFail($training->id);

        $this->assertEquals($expectedName, $updatedTraining->name);
        $this->assertEquals($expectedContent, $updatedTraining->content);
        $this->assertEquals($training->type->id, $updatedTraining->type->id);
        $this->assertEquals(2, $updatedTraining->files()->count());
    }

    /** @test */
    public function is_deleting_files_on_update()
    {
        $training = factory(Training::class)->create();
        [$image] = $this->attachTrainingFiles($training);

        $this->assertTrue(Storage::exists($image->url));

        $this->patch(route('admin.trainings.update', [
            'training'      => $training,
            'name'          => $training->name,
            'type_id'       => $training->type_id,
            '_delete_files' => [
                $image->id
            ]
        ]))->assertSessionHas('success', __('app.the_record_has_been_updated'))
            ->isRedirect(route('admin.trainings.index'));

        $this->assertFalse(Storage::exists($image->url));
        $this->assertEquals(1, $training->files()->count());
    }


    /** @test */
    public function is_not_deleting_invalid_files_on_update()
    {
        $training = factory(Training::class)->create();
        [$image] = $this->attachTrainingFiles($training);

        $this->assertTrue(Storage::exists($image->url));

        $this->patch(route('admin.trainings.update', [
            'training'      => $training,
            'name'          => $training->name,
            'type_id'       => $training->type_id,
            '_delete_files' => [
                -1
            ]
        ]))->assertSessionHas('success', __('app.the_record_has_been_updated'))
            ->isRedirect(route('admin.trainings.index'));

        $this->assertTrue(Storage::exists($image->url));
        $this->assertEquals(2, $training->files()->count());
    }
}
