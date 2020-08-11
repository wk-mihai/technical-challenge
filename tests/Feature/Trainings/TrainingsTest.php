<?php

namespace Tests\Feature\Trainings;

use App\Models\Training;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TrainingsTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Type */
    protected Type $type;

    /** @var User */
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        [$this->type, $this->user] = $this->createTypeAttachedToRole();
    }

    /** @test */
    public function is_showing_trainings_in_right_order()
    {
        $firstTraining = factory(Training::class)->create([
            'name'    => '000AAA',
            'type_id' => $this->type->id
        ]);

        $secondTraining = factory(Training::class)->create([
            'type_id' => $this->type->id
        ]);

        $this->actingAs($this->user)
            ->get(route('trainings.index'))
            ->assertSuccessful()
            ->assertSeeInOrder([$firstTraining->name, $secondTraining->name]);
    }

    /** @test */
    public function is_showing_trainings_paginated()
    {
        factory(Training::class, 20)->create([
            'type_id' => $this->type->id
        ]);

        $this->actingAs($this->user)
            ->get(route('trainings.index'))
            ->assertSuccessful()
            ->assertSee('pagination');
    }

    /** @test */
    public function is_showing_title_on_training_page()
    {
        $training = factory(Training::class)->create([
            'type_id' => $this->type->id
        ]);

        $this->actingAs($this->user)
            ->get(route('trainings.show', $training->id))
            ->assertSuccessful()
            ->assertSee($training->name);
    }

    /** @test */
    public function is_showing_content_on_training_page()
    {
        $training = factory(Training::class)->create([
            'type_id' => $this->type->id
        ]);

        $this->actingAs($this->user)
            ->get(route('trainings.show', $training->id))
            ->assertSuccessful()
            ->assertSeeText($training->content, false);
    }

    /** @test */
    public function is_showing_files_on_training_page()
    {
        $training = factory(Training::class)->create([
            'type_id' => $this->type->id
        ]);

        [$image, $video] = $this->attachTrainingFiles($training);

        $this->assertTrue(Storage::exists($image->url));
        $this->assertTrue(Storage::exists($video->url));

        $this->actingAs($this->user)
            ->get(route('trainings.show', $training->id))
            ->assertSuccessful()
            ->assertSee('<img src="' . $image->fullFileUrl . '" alt="' . $image->name . '">', false)
            ->assertSee('<video src="' . $video->fullFileUrl . '" height="500" controls>', false);
    }
}
