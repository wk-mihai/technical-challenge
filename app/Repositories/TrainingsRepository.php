<?php

namespace App\Repositories;

use App\Models\Training as Model;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class TrainingsRepository extends BaseRepository
{

    /** @var Filesystem */
    protected Filesystem $filesystem;

    public function __construct(Model $model, Filesystem $filesystem)
    {
        $this->model = $model;
        $this->filesystem = $filesystem;
    }

    /**
     * @param Request $request
     * @param string|null $type
     * @param int $records
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function filter(Request $request, ?string $type, int $records = 12)
    {
        return $this->model
            ->search($request->input('search'))
            ->whereHas(
                'type',
                fn(Builder $query) => $query->when(
                    $type !== null, fn(Builder $query) => $query->where('slug', $type)
                )
            )
            ->with('type')
            ->withCount([
                'files as images_count' => fn($query) => $query->where('type', 'image'),
                'files as videos_count' => fn($query) => $query->where('type', 'video')
            ])
            ->orderBy('name', 'asc')
            ->paginate($records);
    }

    /**
     * @param int $trainingId
     * @param int $fileId
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function getFileById(int $trainingId, int $fileId)
    {
        $training = $this->findOrFail($trainingId);

        return $training->files()->findOrFail($fileId);
    }

    /**
     * @param Model $training
     * @return mixed
     */
    public function getVideoFiles($training)
    {
        return $training->files()
            ->where('type', 'video')
            ->get();
    }

    /**
     * @param Model $training
     * @return mixed
     */
    public function getImageFiles($training)
    {
        return $training->files()
            ->where('type', 'image')
            ->get();
    }

    /**
     * @return mixed
     */
    public function trainingsWithFiles()
    {
        return $this->model
            ->select('id')
            ->withCount('files')
            ->get();
    }

    /**
     * @param $data
     * @throws ValidationException
     */
    public function store($data)
    {
        $record = new $this->model($data);

        if (!$record->save()) {
            throw new ValidationException($record->getErrors());
        }

        $this->saveFiles($data, $record);
    }

    /**
     * @param Model $record
     * @param array $data
     * @throws ValidationException
     */
    public function update($record, array $data)
    {
        $record->fill($data);

        if (!$record->save()) {
            throw new ValidationException($record->getErrors());
        }

        $this->saveFiles($data, $record);
    }

    /**
     * @param $data
     * @param $record
     */
    protected function saveFiles($data, $record)
    {
        $files = array_merge(
            $this->storeFilesOnDisk($record->id, $data['images'] ?? [], 'images'),
            $this->storeFilesOnDisk($record->id, $data['videos'] ?? [], 'videos')
        );

        foreach ($files as $file) {
            $record->files()->updateOrCreate(
                [
                    'type' => $file['type'],
                    'url'  => $file['url']
                ], [
                    'name' => $file['name']
                ]
            );
        }

        if (isset($data['_delete_files']) && is_array($data['_delete_files']) && !empty($data['_delete_files'])) {
            $this->deleteFiles($record, $data['_delete_files']);
        }
    }

    /**
     * @param int $id
     * @param array $files
     * @param string $fileType
     * @return array
     */
    protected function storeFilesOnDisk(int $id, array $files, string $fileType): array
    {
        $infoFiles = [];

        if (empty($files)) {
            return $infoFiles;
        }

        foreach ($files as $file) {
            if (!($file instanceof UploadedFile)) {
                continue;
            }

            $uniqueName = md5($id);
            $fileName = $file->getClientOriginalName();
            $path = "trainings/{$fileType}/{$uniqueName}/{$fileName}";

            $this->filesystem->put($path, file_get_contents($file->getRealPath()));

            $infoFiles[] = [
                'name' => pathinfo($fileName, PATHINFO_FILENAME),
                'type' => $fileType == 'images' ? 'image' : 'video',
                'url'  => $path
            ];
        }

        return $infoFiles;
    }

    /**
     * @param Model $record
     * @param array $filesIds
     */
    public function deleteFiles(Model $record, array $filesIds = array())
    {
        $record->files()
            ->when(!empty($filesIds), fn(Builder $query) => $query->whereIn('id', $filesIds))
            ->each(function ($file) {
                $this->filesystem->delete($file->url);
                $file->delete();
            });
    }
}
