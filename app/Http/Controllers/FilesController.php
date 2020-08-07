<?php

namespace App\Http\Controllers;

use App\Repositories\TrainingsRepository;
use Illuminate\Contracts\Filesystem\Filesystem;
use League\Flysystem\FileNotFoundException;

class FilesController extends Controller
{
    /** @var Filesystem */
    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param int $trainingId
     * @param int $fileId
     * @param TrainingsRepository $trainingsRepository
     * @return mixed|void
     */
    public function getFile(int $trainingId, int $fileId, TrainingsRepository $trainingsRepository)
    {
        $trainingFile = $trainingsRepository->getFileById($trainingId, $fileId);

        try {
            $file = $this->filesystem->read($trainingFile->url);
            $mimetype = $this->filesystem->mimeType($trainingFile->url);

            return response()->make($file)->header('Content-type', $mimetype);
        } catch (FileNotFoundException $e) {
            abort(404);
        }
    }


}
