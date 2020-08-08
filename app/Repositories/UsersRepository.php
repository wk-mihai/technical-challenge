<?php

namespace App\Repositories;

use App\Models\User as Model;
use Illuminate\Validation\ValidationException;

class UsersRepository extends BaseRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param $data
     * @throws ValidationException
     */
    public function store($data)
    {
        $data['password'] = bcrypt($data['password']);

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
        if (array_key_exists('password', $data) && $data['password'] === null) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $record->fill($data);

        if (!$record->save()) {
            throw new ValidationException($record->getErrors());
        }
    }
}
