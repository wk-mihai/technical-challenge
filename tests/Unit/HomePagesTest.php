<?php

namespace Tests\Unit;

use App\Models\Training;
use App\Repositories\TrainingsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomePagesTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function is_showing_page_title()
    {
        $this->actingAs($this->createUser())
            ->get(route('home'))
            ->assertSuccessful()
            ->assertViewIs('home')
            ->assertSee(__('Airline Client Trainings'));
    }

    /** @test */
    public function is_showing_trainings_count_for_admin_user()
    {
        $trainings = factory(Training::class, 10)->create();

        foreach ($trainings as $training) {
            [$image, $video] = $this->attachTrainingFiles($training);

            $this->assertTrue(Storage::exists($image->url));
            $this->assertTrue(Storage::exists($video->url));
        }

        $response = $this->actingAs($this->createAdministrator())
            ->get(route('home'))
            ->assertSuccessful()
            ->assertViewIs('home');

        $trainingsWithFiles = resolve(TrainingsRepository::class)->trainingsWithFiles();

        $response->assertSeeInOrder(['<div class="count">' . $trainingsWithFiles->count() . '</div>', '<div class="name">' . __('Trainings') . '</div>'], false)
            ->assertSeeInOrder(['<div class="count">' . $trainingsWithFiles->sum('files_count') . '</div>', '<div class="name">' . __('Image/Video files') . '</div>'], false);
    }
}
