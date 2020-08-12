<?php

namespace Tests\Unit\Repositories;

use App\Exceptions\ValidationFilesException;
use App\Models\Training;
use App\Repositories\TrainingsRepository;
use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TrainingsRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return TrainingsRepository
     */
    protected function getRepository(): TrainingsRepository
    {
        return new TrainingsRepository(resolve(Training::class), resolve(Filesystem::class));
    }

    /** @test
     * @throws Exception
     */
    public function is_throwing_exception_save_files_method_with_wrong_format_images()
    {
        $training = factory(Training::class);

        $trainingRepo = $this->getRepository();

        $this->expectException(ValidationFilesException::class);
        $this->invokeMethod($trainingRepo, 'saveFiles', [['images' => 'string'], $training]);
    }

    /** @test
     * @throws Exception
     */
    public function is_throwing_exception_save_files_method_with_wrong_format_videos()
    {
        $training = factory(Training::class);

        $trainingRepo = $this->getRepository();

        $this->expectException(ValidationFilesException::class);
        $this->invokeMethod($trainingRepo, 'saveFiles', [['videos' => 'string'], $training]);
    }

    /** @test
     * @throws Exception
     */
    public function is_continuing_files_foreach_on_stores_files_on_disk_for_wrong_files_format()
    {
        $trainingRepo = $this->getRepository();

        $response = $this->invokeMethod($trainingRepo, 'storeFilesOnDisk', [1, [1, 2], 'image']);

        $this->assertTrue(empty($response));
    }
}
