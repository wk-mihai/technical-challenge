<?php

namespace App\Repositories;

use App\Models\Role as Model;
use Illuminate\Validation\ValidationException;

class RolesRepository extends BaseRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return array
     */
    public function dropdown(): array
    {
        $records = $this->all([], ['name', 'asc'])
            ->pluck('name', 'id')
            ->toArray();

        return ['' => 'Select...'] + $records;
    }

    /**
     * @param $data
     * @throws ValidationException
     */
    public function store($data)
    {
        $data['can_view_trainings'] = array_key_exists('can_view_trainings', $data);

        $record = new $this->model($data);

        if (!$record->save()) {
            throw new ValidationException($record->getErrors());
        }
    }

    /**
     * @param $record
     * @param array $data
     * @throws ValidationException
     */
    public function update($record, array $data)
    {
        $isAdmin = $record->isAdmin();

        $data['can_view_trainings'] = $isAdmin || array_key_exists('can_view_trainings', $data);

        if ($isAdmin && array_key_exists('slug', $data)) {
            unset($data['slug']);
        }

        $record->fill($data);

        if (!$record->save()) {
            throw new ValidationException($record->getErrors());
        }
    }
}
