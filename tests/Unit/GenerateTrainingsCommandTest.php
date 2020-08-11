<?php

namespace Tests\Unit;

use App\Repositories\TrainingsRepository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GenerateTrainingsCommandTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->createAdministrator());
    }

    protected function copyRealFilesToFakeStorage()
    {
        $realStorage = resolve(Filesystem::class);
        $files = [];

        foreach ($realStorage->allFiles(storage_path('app/public/default-trainings-images')) as $file) {
            $files[] = [
                'name' => $file->getFilename(),
                'file' => $file
            ];
        }

        Storage::fake();

        array_map(fn($file) => Storage::put("public/default-trainings-images/{$file['name']}", $file['file']), $files);
    }

    /** @test */
    public function is_generating_automatic_trainings_with_images_without_count()
    {
        $this->copyRealFilesToFakeStorage();

        $this->artisan('generate:trainings')
            ->expectsOutput('20 trainings were successfully generated.')
            ->assertExitCode(0);

        $trainings = resolve(TrainingsRepository::class)->all(['files']);

        $filesCount = 0;
        foreach ($trainings as $training) {
            $filesCount += $training->files->count();

            if ($training->files->isNotEmpty()) {
                $training->files->each(fn($file) => $this->assertTrue(Storage::exists($file->url)));
            }
        }

        $this->assertEquals(20, $trainings->count());
        $this->assertGreaterThan(0, $filesCount);
    }

    /** @test */
    public function is_generating_automatic_trainings_with_images_with_count()
    {
        $this->copyRealFilesToFakeStorage();

        $this->artisan('generate:trainings 2')
            ->expectsOutput('2 trainings were successfully generated.')
            ->assertExitCode(0);

        $trainings = resolve(TrainingsRepository::class)->all(['files']);

        $filesCount = 0;
        foreach ($trainings as $training) {
            $filesCount += $training->files->count();

            if ($training->files->isNotEmpty()) {
                $training->files->each(fn($file) => $this->assertTrue(Storage::exists($file->url)));
            }
        }

        $this->assertEquals(2, $trainings->count());
        $this->assertGreaterThan(0, $filesCount);
    }

    /** @test */
    public function is_not_generating_automatic_trainings_with_images()
    {
        $this->artisan('generate:trainings invalid-option')
            ->expectsOutput('No trainings were generated.')
            ->assertExitCode(0);
    }
}
